<?php

namespace App\Http\Controllers\Backend\ruang_pengurus;

use App\Http\Controllers\Controller;
use App\Models\MasterKetuaUmum;
use App\Models\SkPengurus;
use App\Models\StrukturOrganisasi;
use App\Models\StrukturPengurus;
use App\Models\StrukturKelompokPengurus;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use TCPDF2DBarcode;

class SkDocumentController extends Controller
{
    /**
     * Format tanggal Indonesia
     */
    private function formatTanggalIndonesia($date)
    {
        if (!$date) return '-';

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $carbon = Carbon::parse($date);
        return $carbon->format('d') . ' ' . $bulan[(int)$carbon->format('m')] . ' ' . $carbon->format('Y');
    }

    /**
     * Cetak dokumen Surat Keputusan (SK) resmi berformat PDF
     */
    public function print($id)
    {
        $sk = SkPengurus::with([
            'strukturOrganisasi.provinsi',
            'strukturOrganisasi.kota',
            'ketuaUmum'
        ])->findOrFail($id);

        $struktur = $sk->strukturOrganisasi;

        if (!$struktur || !$struktur->isAccessibleByCurrentUser()) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang pada dokumen SK ini.');
        }

        // Ambil personil pengurus dikelompokkan
        $kelompokIds = StrukturPengurus::where('id_struktur_organisasi', $struktur->id)
            ->whereNotNull('id_kelompok')
            ->distinct()
            ->pluck('id_kelompok');

        $kelompokList = StrukturKelompokPengurus::whereIn('id', $kelompokIds)
            ->orderBy('urutan', 'asc')
            ->get();

        $pengurusSemua = StrukturPengurus::where('id_struktur_organisasi', $struktur->id)
            ->orderBy('urutan', 'asc')
            ->get();

        // Ambil Ketua Umum pengesah
        $ketum = $sk->ketuaUmum ?: MasterKetuaUmum::getAktif();
        if (!$ketum) {
            $ketum = (object)[
                'nama_lengkap' => 'dr. Eddi Junaidi, SpOG., SH., MKes',
                'file_ttd' => null,
            ];
        }

        // Tanda tangan Ketum (Base64)
        $ttdBase64 = null;
        if ($ketum->file_ttd && Storage::exists('public/ttd_ketum/' . $ketum->file_ttd)) {
            $ttdBytes = Storage::get('public/ttd_ketum/' . $ketum->file_ttd);
            $mime = Storage::mimeType('public/ttd_ketum/' . $ketum->file_ttd) ?: 'image/png';
            $ttdBase64 = 'data:' . $mime . ';base64,' . base64_encode($ttdBytes);
        } elseif (file_exists(public_path('assets/images/ttd.png'))) {
            $ttdBytes = file_get_contents(public_path('assets/images/ttd.png'));
            $ttdBase64 = 'data:image/png;base64,' . base64_encode($ttdBytes);
        }

        // Kop Surat resmi (Base64)
        $kopBase64 = null;
        if (file_exists(public_path('assets/images/kop_asklin.jpg'))) {
            $kopBytes = file_get_contents(public_path('assets/images/kop_asklin.jpg'));
            $kopBase64 = 'data:image/jpeg;base64,' . base64_encode($kopBytes);
        }

        // Generate QR Code PNG (Base64) tanpa echo/output headers
        $qrCodeSvg = null;
        if ($sk->show_qr) {
            $verifyUrl = url('/cek-sk/' . ($sk->qr_token ?: $sk->id));
            try {
                $barcode = new TCPDF2DBarcode($verifyUrl, 'QRCODE,H');
                $pngRaw = $barcode->getBarcodePngData(4, 4);
                $qrCodeSvg = 'data:image/png;base64,' . base64_encode($pngRaw);
            } catch (\Exception $e) {
                $qrCodeSvg = null;
            }
        }

        // Format tanggal-tanggal penting
        $tglPenetapanFormatted = $this->formatTanggalIndonesia($sk->tgl_penetapan ?: now());
        $tglMusyawarahFormatted = $this->formatTanggalIndonesia($sk->tgl_musyawarah ?: $struktur->tgl_muscab);
        $tglPelantikanFormatted = $sk->tgl_pelantikan ? $this->formatTanggalIndonesia($sk->tgl_pelantikan) : null;
        $tglRekomendasiFormatted = $sk->tgl_rekomendasi_daerah ? $this->formatTanggalIndonesia($sk->tgl_rekomendasi_daerah) : null;

        // Tingkatan dan wilayah hukum
        $tingkatan = $sk->tingkatan ?: ($struktur->id_tingkatan_pengurus == 1 ? 'pusat' : ($struktur->id_tingkatan_pengurus == 2 ? 'daerah' : 'cabang'));

        $namaWilayah = '';
        if ($tingkatan === 'cabang') {
            $namaWilayah = $struktur->kota ? $struktur->kota->name : ($struktur->nama_struktur ?: 'KABUPATEN/KOTA');
        } elseif ($tingkatan === 'daerah') {
            $namaWilayah = $struktur->provinsi ? $struktur->provinsi->name : ($struktur->nama_struktur ?: 'PROVINSI');
        } else {
            $namaWilayah = 'INDONESIA';
        }

        $tingkatanText = strtoupper($tingkatan);
        $pasalAd = match($tingkatan) {
            'cabang' => 'Pasal 14 ayat (1) huruf c',
            'daerah' => 'Pasal 14 ayat (1) huruf b',
            default => 'Pasal 14 ayat (1) huruf a',
        };
        $pasalArt = match($tingkatan) {
            'cabang' => 'Pasal 17 ayat (2)',
            'daerah' => 'Pasal 16 ayat (2)',
            default => 'Pasal 15 ayat (2)',
        };
        $jenisMusyawarah = match($tingkatan) {
            'cabang' => 'Musyawarah Cabang (Muscab)',
            'daerah' => 'Musyawarah Daerah (Musda)',
            default => 'Kongres Nasional',
        };

        // Parse susunan pengurus ke struktur hierarki resmi organisasi
        $pembina = [];
        $penasehat = [];
        $pimpinan = [];
        $koordinator_daerah = [];
        $bidangList = [];
        $lainnya = [];

        foreach ($pengurusSemua as $p) {
            $jabatanLower = strtolower(trim($p->jabatan));
            $keteranganTrim = trim($p->keterangan ?? '');

            if (strpos($jabatanLower, 'pembina') !== false) {
                $pembina[] = $p->nama_lengkap;
            } elseif (strpos($jabatanLower, 'penasehat') !== false || strpos($jabatanLower, 'penasihat') !== false) {
                $penasehat[] = $p->nama_lengkap;
            } elseif (strpos($jabatanLower, 'koordinator daerah') !== false) {
                $koordinator_daerah[] = [
                    'wilayah' => $keteranganTrim ?: 'Wilayah',
                    'nama' => $p->nama_lengkap
                ];
            } elseif (strpos($jabatanLower, 'koordinator bidang') !== false || strpos($jabatanLower, 'anggota bidang') !== false) {
                $namaBidang = $keteranganTrim ?: 'Bidang Organisasi';
                if (!isset($bidangList[$namaBidang])) {
                    $bidangList[$namaBidang] = ['koordinator' => null, 'anggota' => []];
                }
                if (strpos($jabatanLower, 'koordinator') !== false || strpos($jabatanLower, 'ketua') !== false) {
                    $bidangList[$namaBidang]['koordinator'] = $p->nama_lengkap;
                } else {
                    $bidangList[$namaBidang]['anggota'][] = $p->nama_lengkap;
                }
            } elseif (strpos($jabatanLower, 'bidang') !== false && !in_array($jabatanLower, ['koordinator bidang', 'anggota bidang'])) {
                $namaBidang = $p->jabatan;
                if (!isset($bidangList[$namaBidang])) {
                    $bidangList[$namaBidang] = ['koordinator' => $p->nama_lengkap, 'anggota' => []];
                } else {
                    if (!$bidangList[$namaBidang]['koordinator']) {
                        $bidangList[$namaBidang]['koordinator'] = $p->nama_lengkap;
                    } else {
                        $bidangList[$namaBidang]['anggota'][] = $p->nama_lengkap;
                    }
                }
            } else {
                $pimpinan[] = [
                    'jabatan' => ucwords($p->jabatan),
                    'nama' => $p->nama_lengkap
                ];
            }
        }

        $isDraft = ($sk->status !== 'terbit');

        $data = [
            'sk' => $sk,
            'struktur' => $struktur,
            'kelompokList' => $kelompokList,
            'pengurusSemua' => $pengurusSemua,
            'isDraft' => $isDraft,
            'pembina' => $pembina,
            'penasehat' => $penasehat,
            'pimpinan' => $pimpinan,
            'koordinator_daerah' => $koordinator_daerah,
            'bidangList' => $bidangList,
            'lainnya' => $lainnya,
            'ketum' => $ketum,
            'ttdBase64' => $ttdBase64,
            'kopBase64' => $kopBase64,
            'qrCodeSvg' => $qrCodeSvg,
            'verifyUrl' => url('/cek-sk/' . ($sk->qr_token ?: $sk->id)),
            'tglPenetapanFormatted' => $tglPenetapanFormatted,
            'tglMusyawarahFormatted' => $tglMusyawarahFormatted,
            'tglPelantikanFormatted' => $tglPelantikanFormatted,
            'tglRekomendasiFormatted' => $tglRekomendasiFormatted,
            'tingkatanText' => $tingkatanText,
            'namaWilayah' => $namaWilayah,
            'pasalAd' => $pasalAd,
            'pasalArt' => $pasalArt,
            'jenisMusyawarah' => $jenisMusyawarah,
        ];

        $pdf = Pdf::loadView('backend.sk_pengurus.pdf_template', $data)
            ->setPaper('a4', 'portrait')
            ->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);

        $safeFilename = 'SK_ASKLIN_' . Str::slug($sk->no_sk ?: ('pengurus_' . $struktur->nama_struktur)) . '.pdf';

        if (ob_get_length()) {
            ob_end_clean();
        }

        return $pdf->stream($safeFilename);
    }
}
