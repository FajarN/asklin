<?php

namespace App\Http\Controllers;

use App\Models\SkPengurus;
use App\Models\StrukturKelompokPengurus;
use App\Models\StrukturPengurus;
use Illuminate\Http\Request;

class PublicSkVerificationController extends Controller
{
    /**
     * Halaman verifikasi publik keabsahan Surat Keputusan (SK) ASKLIN
     * Dapat diakses tanpa autentikasi oleh publik/instansi luar yang memindai QR Code resmi.
     */
    public function verify($token)
    {
        $sk = SkPengurus::with([
            'strukturOrganisasi.provinsi',
            'strukturOrganisasi.kota',
            'strukturOrganisasi.tingkatanPengurus',
            'ketuaUmum'
        ])
        ->where('qr_token', $token)
        ->orWhere('id', $token)
        ->first();

        if (!$sk) {
            return view('public_sk_verify', [
                'isValid' => false,
                'sk' => null,
                'message' => 'Surat Keputusan (SK) tidak ditemukan atau kode verifikasi QR tidak valid.'
            ]);
        }

        $struktur = $sk->strukturOrganisasi;

        // Ambil personil pengurus dikelompokkan
        $kelompokIds = StrukturPengurus::where('id_struktur_organisasi', $struktur->id)
            ->whereNotNull('id_kelompok')
            ->distinct()
            ->pluck('id_kelompok');

        $kelompokList = StrukturKelompokPengurus::whereIn('id', $kelompokIds)
            ->with(['strukturPengurus' => function ($q) use ($struktur) {
                $q->where('id_struktur_organisasi', $struktur->id)->orderBy('urutan', 'asc');
            }])
            ->orderBy('urutan', 'asc')
            ->get();

        $pengurusSemua = StrukturPengurus::where('id_struktur_organisasi', $struktur->id)
            ->orderBy('urutan', 'asc')
            ->get();

        return view('public_sk_verify', [
            'isValid' => true,
            'sk' => $sk,
            'struktur' => $struktur,
            'kelompokList' => $kelompokList,
            'pengurusSemua' => $pengurusSemua,
            'ketum' => $sk->ketuaUmum,
        ]);
    }
}
