<?php

namespace App\Http\Controllers\Api;

use App\Models\Anggota;
use App\Models\SDM;
use App\Models\Sertifikat;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnggotaProfileApiController extends BaseApiController
{
    /**
     * Mengambil profil lengkap klinik milik pengguna yang sedang login.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProfil(Request $request): JsonResponse
    {
        $user = $request->user();

        $anggota = Anggota::select(
            'anggota.*',
            'p.name as provinsi_name',
            'c.name as kota_name',
            'd.name as kecamatan_name',
            'v.name as kelurahan_name'
        )
            ->leftJoin('indonesia_provinces as p', 'p.code', '=', 'anggota.id_provinsi')
            ->leftJoin('indonesia_cities as c', 'c.code', '=', 'anggota.id_kota')
            ->leftJoin('indonesia_districts as d', 'd.code', '=', 'anggota.id_kecamatan')
            ->leftJoin('indonesia_villages as v', 'v.code', '=', 'anggota.id_kelurahan')
            ->where('anggota.id_user', $user->id)
            ->first();

        if (!$anggota) {
            return $this->errorResponse('Data profil klinik anggota belum ditemukan.', 404);
        }

        // Ambil rekap SDM klinik
        $sdmSummary = SDM::where('id_klinik', $anggota->id)
            ->select('id_kategori_sdm', DB::raw('count(*) as total'))
            ->groupBy('id_kategori_sdm')
            ->pluck('total', 'id_kategori_sdm')
            ->toArray();

        $sdmDokterList = SDM::where('id_klinik', $anggota->id)
            ->whereIn('id_kategori_sdm', ['1', '2'])
            ->get(['id', 'nama', 'nama_dokter', 'peranan', 'no_str', 'no_sip', 'tgl_akhir_sip', 'id_kategori_sdm']);

        $profilData = [
            'id'                  => $anggota->id,
            'no_anggota'          => $anggota->no_anggota,
            'nama_klinik'         => $anggota->nama_klinik,
            'status'              => $anggota->status,
            'jenis_klinik'        => $anggota->jenis_klinik,
            'status_pembayaran'   => (string) $anggota->status_pembayaran,
            'status_kepemilikan'  => $anggota->status_kepemilikan,
            'nama_pemilik_klinik' => $anggota->nama_pemilik_klinik,
            'bentuk_badan_hukum'  => $anggota->bentuk_badan_hukum,
            'nama_badan_usaha'    => $anggota->nama_badan_usaha,
            'bentuk_badan_usaha'  => $anggota->bentuk_badan_usaha,
            'alamat_lengkap'      => [
                'alamat_klinik' => $anggota->alamat_klinik,
                'rt'            => $anggota->rt,
                'rw'            => $anggota->rw,
                'kode_pos'      => $anggota->kode_pos,
                'kelurahan'     => $anggota->kelurahan_name,
                'kecamatan'     => $anggota->kecamatan_name,
                'kota'          => $anggota->kota_name,
                'provinsi'      => $anggota->provinsi_name,
                'id_provinsi'   => $anggota->id_provinsi,
                'id_kota'       => $anggota->id_kota,
                'id_kecamatan'  => $anggota->id_kecamatan,
                'id_kelurahan'  => $anggota->id_kelurahan,
            ],
            'kontak'              => [
                'nama_kontak' => $anggota->nama_kontak,
                'email'       => $anggota->email,
                'tlf'         => $anggota->tlf,
                'tlf_klinik'  => $anggota->tlf_klinik,
            ],
            'perijinan'           => [
                'no_ijin'        => $anggota->no_ijin,
                'tgl_ijin'       => $anggota->tgl_ijin,
                'tgl_akhir_ijin' => $anggota->tgl_akhir_ijin,
                'has_sio_file'   => !empty($anggota->sio),
            ],
            'sdm'                 => [
                'dokter_penanggungjawab_count' => (int) ($sdmSummary['1'] ?? 0),
                'dokter_pendamping_count'       => (int) ($sdmSummary['2'] ?? 0),
                'tenaga_kesehatan_count'        => (int) ($sdmSummary['3'] ?? 0),
                'tenaga_lainnya_count'          => (int) ($sdmSummary['4'] ?? 0) + (int) ($sdmSummary['5'] ?? 0),
                'dokter_list'                   => $sdmDokterList,
            ],
            'tanggal_verifikasi'  => [
                'verifikasi_cabang' => $anggota->verifikasi_cabang,
                'verifikasi_pusat'  => $anggota->verifikasi_pusat,
            ],
            'keterangan'          => $anggota->keterangan,
        ];

        return $this->successResponse($profilData, 'Profil klinik berhasil dimuat.');
    }

    /**
     * Mengambil status keanggotaan dan alur verifikasi.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getStatusKeanggotaan(Request $request): JsonResponse
    {
        $user = $request->user();

        $anggota = Anggota::where('id_user', $user->id)->first();

        if (!$anggota) {
            return $this->errorResponse('Data keanggotaan belum ditemukan.', 404);
        }

        $statusRaw = strtolower($anggota->status ?? 'waiting');
        $isApproved = ($statusRaw === 'approved');
        $isLunas = ((string) $anggota->status_pembayaran === '1');

        // Menentukan label status yang mudah dipahami
        $statusLabel = 'Pendaftaran Sedang Diproses';
        if ($isApproved) {
            $statusLabel = 'Anggota Resmi ASKLIN';
        } elseif (in_array($statusRaw, ['diverifikasi cabang', 'terverifikasi cabang'])) {
            $statusLabel = 'Menunggu Verifikasi Pengurus Daerah/Pusat';
        } elseif (in_array($statusRaw, ['verifikasi sekjen', 'verifikasi bendahara'])) {
            $statusLabel = 'Verifikasi Dokumen & Administrasi Pusat';
        } elseif ($statusRaw === 'perlu perbaikan') {
            $statusLabel = 'Perlu Perbaikan Dokumen';
        } elseif (str_contains($statusRaw, 'ditolak')) {
            $statusLabel = 'Pendaftaran Ditolak';
        }

        $timeline = [
            [
                'step'        => 1,
                'title'       => 'Pendaftaran & Pengisian Data',
                'is_done'     => true,
                'date'        => $anggota->created_at ? $anggota->created_at->format('d-m-Y H:i') : null,
            ],
            [
                'step'        => 2,
                'title'       => 'Verifikasi Pengurus Cabang',
                'is_done'     => !empty($anggota->verifikasi_cabang) || $isApproved,
                'date'        => $anggota->verifikasi_cabang ? Carbon::parse($anggota->verifikasi_cabang)->format('d-m-Y H:i') : null,
            ],
            [
                'step'        => 3,
                'title'       => 'Verifikasi & Iuran Pembayaran Pusat',
                'is_done'     => $isLunas || $isApproved,
                'date'        => null,
            ],
            [
                'step'        => 4,
                'title'       => 'Persetujuan Final & Penerbitan SK/Sertifikat',
                'is_done'     => $isApproved,
                'date'        => $anggota->verifikasi_pusat ? Carbon::parse($anggota->verifikasi_pusat)->format('d-m-Y H:i') : null,
            ],
        ];

        return $this->successResponse([
            'id'                   => $anggota->id,
            'no_anggota'           => $anggota->no_anggota,
            'status'               => $anggota->status,
            'status_label'         => $statusLabel,
            'status_pembayaran'    => (string) $anggota->status_pembayaran,
            'is_approved'          => $isApproved,
            'is_lunas'             => $isLunas,
            'keterangan_perbaikan' => $anggota->keterangan,
            'tgl_akhir_ijin'       => $anggota->tgl_akhir_ijin,
            'timeline'             => $timeline,
        ], 'Status keanggotaan berhasil diambil.');
    }

    /**
     * Mengambil daftar dokumen yang berhak diakses oleh klinik anggota.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getDokumenSaya(Request $request): JsonResponse
    {
        $user = $request->user();
        $anggota = Anggota::where('id_user', $user->id)->first();

        if (!$anggota) {
            return $this->errorResponse('Data keanggotaan belum ditemukan.', 404);
        }

        $isApproved = (strtolower($anggota->status ?? '') === 'approved');
        $isLunas = ((string) $anggota->status_pembayaran === '1');

        $dokumen = [];

        // 1. Dokumen SK Anggota
        $dokumen[] = [
            'type'         => 'sk_anggota',
            'title'        => 'Surat Keputusan (SK) Anggota ASKLIN',
            'nomor'        => $anggota->no_anggota ?: '-',
            'is_available' => $isApproved,
            'notes'        => $isApproved ? 'Tersedia untuk diunduh' : 'Tersedia setelah pendaftaran disetujui Pengurus Pusat',
            'download_url' => $isApproved ? url('/api/v1/dokumen/sk-anggota/download') : null,
        ];

        // 2. Dokumen Sertifikat Anggota
        $sertifikat = Sertifikat::where('id_anggota', $anggota->id)->latest()->first();
        $dokumen[] = [
            'type'         => 'sertifikat',
            'title'        => 'Sertifikat Keanggotaan Resmi ASKLIN',
            'nomor'        => $anggota->no_anggota ?: '-',
            'is_available' => $isApproved && $isLunas,
            'masa_berlaku' => [
                'dari'   => $sertifikat ? $sertifikat->dari : null,
                'sampai' => $sertifikat ? $sertifikat->sampai : null,
            ],
            'notes'        => ($isApproved && $isLunas) 
                ? 'Tersedia untuk diunduh' 
                : 'Tersedia setelah klinik disetujui dan iuran terverifikasi lunas',
            'download_url' => ($isApproved && $isLunas) ? url('/api/v1/dokumen/sertifikat/download') : null,
        ];

        return $this->successResponse($dokumen, 'Daftar dokumen keanggotaan berhasil diambil.');
    }
}

