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

        $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames() : collect(['Klinik']);
        $primaryRole = $roles->first() ?? 'Klinik';

        if (!$anggota) {
            $isPengurus = in_array(strtolower($primaryRole), ['superadmin', 'admin', 'pengurus', 'pengurus cabang', 'pengurus daerah', 'pengurus pusat']) 
                || (method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['superadmin', 'admin', 'pengurus']));

            return response()->json([
                'success' => true,
                'message' => 'Profil pengguna berhasil dimuat.',
                'data'    => [
                    'user' => [
                        'id'           => $user->id,
                        'name'         => $user->name,
                        'email'        => $user->email,
                        'primary_role' => $primaryRole,
                        'roles'        => $roles,
                    ],
                    'klinik'      => null,
                    'is_pengurus' => $isPengurus,
                    'nama_klinik' => $isPengurus ? 'Administrator / ' . $primaryRole : 'Klinik Belum Terdaftar',
                    'no_anggota'  => $isPengurus ? 'Pengurus ASKLIN' : 'Menunggu Pendaftaran',
                    'status'      => $isPengurus ? 'approved' : 'waiting',
                ],
            ], 200);
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

        $userData = [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'primary_role' => $primaryRole,
            'roles'        => $roles,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Profil klinik berhasil dimuat.',
            'data'    => array_merge($profilData, [
                'user'        => $userData,
                'klinik'      => $profilData,
                'is_pengurus' => false,
            ]),
        ], 200);
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
        $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames() : collect(['Klinik']);
        $primaryRole = $roles->first() ?? 'Klinik';
        $isPengurus = in_array(strtolower($primaryRole), ['superadmin', 'admin', 'pengurus', 'pengurus cabang', 'pengurus daerah', 'pengurus pusat']) 
            || (method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['superadmin', 'admin', 'pengurus']));

        $anggota = Anggota::where('id_user', $user->id)->first();

        if (!$anggota) {
            return response()->json([
                'success' => true,
                'message' => 'Status akun berhasil dimuat.',
                'data'    => [
                    'is_pengurus'             => $isPengurus,
                    'is_approved'             => $isPengurus,
                    'status'                  => $isPengurus ? 'approved' : 'waiting',
                    'status_label'            => $isPengurus ? 'Akun ' . $primaryRole . ' ASKLIN' : 'Pendaftaran Anggota Faskes',
                    'bisa_edit'               => !$isPengurus,
                    'can_download_sk'         => false,
                    'can_download_sertifikat' => false,
                    'timeline'                => [],
                    'catatan'                 => $isPengurus ? 'Anda login menggunakan akun Administrator / Pengurus.' : 'Silakan lengkapi formulir pendaftaran anggota.',
                ],
            ], 200);
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

    /**
     * Menyimpan atau memperbarui data formulir pendaftaran anggota faskes lengkap.
     * POST /api/v1/anggota/pendaftaran
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function simpanPendaftaran(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->validate([
            'nama_klinik'         => 'required|string|max:255',
            'nama_kontak'         => 'required|string|max:255',
            'email'               => 'required|email|max:255',
            'tlf'                 => 'required|string|max:30',
            'id_provinsi'         => 'required|string',
            'id_kota'             => 'required|string',
            'id_kecamatan'        => 'nullable|string',
            'id_kelurahan'        => 'nullable|string',
            'alamat_klinik'       => 'required|string',
            'jenis_klinik'        => 'required|string', // Pratama / Utama
            'status_pendaftar'    => 'nullable|string',
            'status_kepemilikan'  => 'nullable|string',
            'nama_pemilik_klinik' => 'nullable|string',
            'bentuk_badan_hukum'  => 'nullable|string',
            'nama_badan_usaha'    => 'nullable|string',
            'no_ijin'             => 'nullable|string',
            'tgl_ijin'            => 'nullable|date',
            'tgl_akhir_ijin'      => 'nullable|date',
        ]);

        $anggota = Anggota::where('id_user', $user->id)->first();

        $data = [
            'nama_klinik'         => $request->nama_klinik,
            'nama_kontak'         => $request->nama_kontak,
            'email'               => $request->email,
            'tlf'                 => $request->tlf,
            'id_provinsi'         => $request->id_provinsi,
            'id_kota'             => $request->id_kota,
            'id_kecamatan'        => $request->id_kecamatan,
            'id_kelurahan'        => $request->id_kelurahan,
            'alamat_klinik'       => $request->alamat_klinik,
            'rt'                  => $request->rt,
            'rw'                  => $request->rw,
            'kode_pos'            => $request->kode_pos,
            'tlf_klinik'          => $request->tlf_klinik,
            'jenis_klinik'        => $request->jenis_klinik,
            'status_pendaftar'    => $request->status_pendaftar,
            'status_kepemilikan'  => $request->status_kepemilikan,
            'nama_pemilik_klinik' => $request->nama_pemilik_klinik,
            'bentuk_badan_hukum'  => $request->bentuk_badan_hukum,
            'nama_badan_usaha'    => $request->nama_badan_usaha,
            'bentuk_badan_usaha'  => $request->bentuk_badan_usaha,
            'no_ijin'             => $request->no_ijin,
            'tgl_ijin'            => $request->tgl_ijin ? date('Y-m-d', strtotime($request->tgl_ijin)) : null,
            'tgl_akhir_ijin'      => $request->tgl_akhir_ijin ? date('Y-m-d', strtotime($request->tgl_akhir_ijin)) : null,
            'status'              => 'create_dokter', // Status transisi sesuai website asklin
        ];

        if ($anggota) {
            $anggota->update($data);
        } else {
            $data['id_user'] = $user->id;
            $data['status_pembayaran'] = '0';
            $anggota = Anggota::create($data);
        }

        return $this->successResponse($anggota, 'Data pendaftaran klinik berhasil disimpan. Silakan lanjutkan input data SDM/Dokter.');
    }

    /**
     * Menyimpan data SDM Dokter (Penanggung Jawab atau Dokter Praktek).
     * POST /api/v1/anggota/sdm
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function simpanSdm(Request $request): JsonResponse
    {
        $user = $request->user();
        $anggota = Anggota::where('id_user', $user->id)->first();

        if (!$anggota) {
            return $this->errorResponse('Data pendaftaran klinik belum dibuat.', 404);
        }

        $request->validate([
            'nama_dokter'     => 'required|string|max:255',
            'id_kategori_sdm' => 'required|in:1,2', // 1: PJ, 2: Dokter Praktek
            'npa_idi'         => 'nullable|string|max:100',
            'no_str'          => 'nullable|string|max:100',
            'no_sip'          => 'nullable|string|max:100',
            'tgl_akhir_sip'   => 'nullable|date',
            'no_tlf'          => 'nullable|string|max:30',
        ]);

        $sdm = SDM::create([
            'id_klinik'       => $anggota->id,
            'nama_dokter'     => $request->nama_dokter,
            'nama'            => $request->nama_dokter,
            'id_kategori_sdm' => $request->id_kategori_sdm,
            'peranan'         => $request->id_kategori_sdm == '1' ? 'penanggung jawab' : 'dokter praktek',
            'npa_idi'         => $request->npa_idi,
            'no_str'          => $request->no_str,
            'no_sip'          => $request->no_sip,
            'tgl_akhir_sip'   => $request->tgl_akhir_sip ? date('Y-m-d', strtotime($request->tgl_akhir_sip)) : null,
            'no_tlf'          => $request->no_tlf,
        ]);

        // Setelah minimal 1 dokter PJ terisi, ubah status klinik menjadi 'waiting' (menunggu verifikasi cabang)
        if ($anggota->status === 'create_dokter' || empty($anggota->status)) {
            $anggota->update(['status' => 'waiting']);
        }

        return $this->successResponse($sdm, 'Data dokter/SDM berhasil ditambahkan. Status pendaftaran: ' . $anggota->status, 201);
    }
}

