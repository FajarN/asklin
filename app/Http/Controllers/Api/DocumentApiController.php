<?php

namespace App\Http\Controllers\Api;

use App\Models\Anggota;
use App\Models\Sertifikat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;

class DocumentApiController extends BaseApiController
{
    /**
     * Download/Stream PDF Surat Keputusan (SK) Anggota secara aman.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function downloadSkAnggota(Request $request)
    {
        $user = $request->user();

        // Resolusi target anggota berbasis hak akses
        $anggota = $this->resolveTargetAnggota($user, $request->get('anggota_id'));

        if (!$anggota) {
            return $this->errorResponse('Data anggota tidak ditemukan atau Anda tidak memiliki hak akses.', 404);
        }

        if (strtolower($anggota->status ?? '') !== 'approved') {
            return $this->errorResponse('SK Anggota belum dapat diterbitkan karena pendaftaran belum disetujui (Status: ' . $anggota->status . ').', 403);
        }

        // Ambil data lengkap untuk cetak SK
        $detailAnggota = Anggota::select(
            'anggota.*',
            DB::raw('GROUP_CONCAT(DISTINCT a.nama SEPARATOR ", ") as nama_fasilitas_klinik'),
            'b.name as kota',
            'c.name as provinsi'
        )
            ->leftJoin('fasilitas_klinik as a', DB::raw("FIND_IN_SET(a.id, anggota.fasilitas_klinik)"), ">", DB::raw("'0'"))
            ->leftJoin('indonesia_cities as b', 'b.code', '=', 'anggota.id_kota')
            ->leftJoin('indonesia_provinces as c', 'c.code', '=', 'anggota.id_provinsi')
            ->groupBy('anggota.id')
            ->where('anggota.id', $anggota->id)
            ->first();

        $created_on = !empty($detailAnggota->created_on)
            ? Carbon::createFromTimestamp($detailAnggota->created_on)->format('d-m-Y H:i:s')
            : ($detailAnggota->created_at
                ? Carbon::parse($detailAnggota->created_at)->format('d-m-Y H:i:s')
                : Carbon::parse($detailAnggota->verifikasi_pusat ?: now())->format('d-m-Y H:i:s'));

        try {
            $viewContent = view('backend.anggota.printsk', [
                'anggota'    => $detailAnggota,
                'created_on' => $created_on,
            ])->render();

            $html2pdf = new Html2Pdf('P', 'F4', 'fr', true, 'UTF-8', 1);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->pdf->SetTitle('SK Anggota ASKLIN - ' . ($detailAnggota->nama_klinik ?: ''));
            $html2pdf->writeHTML($viewContent);

            // Output mode 'S' mengembalikan raw binary string tanpa exit()
            $pdfContent = $html2pdf->output('sk_anggota.pdf', 'S');

            $filename = 'SK_ASKLIN_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $detailAnggota->no_anggota ?: $detailAnggota->id) . '.pdf';

            return response($pdfContent, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Cache-Control'       => 'private, max-age=3600',
            ]);
        } catch (Html2PdfException $e) {
            \Log::error('Error generate SK PDF: ' . $e->getMessage());
            return $this->errorResponse('Gagal menghasilkan dokumen PDF SK: ' . $e->getMessage(), 500);
        } catch (\Throwable $e) {
            \Log::error('Error generate SK PDF: ' . $e->getMessage());
            return $this->errorResponse('Terjadi kesalahan sistem saat memproses dokumen SK.', 500);
        }
    }

    /**
     * Download/Stream PDF Sertifikat Anggota secara aman.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function downloadSertifikatAnggota(Request $request)
    {
        $user = $request->user();

        $anggota = $this->resolveTargetAnggota($user, $request->get('anggota_id'));

        if (!$anggota) {
            return $this->errorResponse('Data anggota tidak ditemukan atau Anda tidak memiliki hak akses.', 404);
        }

        if (strtolower($anggota->status ?? '') !== 'approved') {
            return $this->errorResponse('Sertifikat belum tersedia karena pendaftaran belum disetujui.', 403);
        }

        if ((string) $anggota->status_pembayaran !== '1') {
            return $this->errorResponse('Sertifikat belum dapat diunduh karena iuran anggota belum terverifikasi lunas.', 403);
        }

        // Ambil data sertifikat
        $sertifikat = Sertifikat::select(
            'sertifikat.*',
            'a.id_user',
            'a.nama_klinik',
            'a.no_anggota',
            'a.updated_at',
            'a.nama_kontak',
            'a.alamat_klinik',
            'a.rt',
            'a.rw',
            'b.name as kelurahan',
            'c.name as kecamatan',
            'd.name as kota',
            'e.name as provinsi',
            'a.id_kota',
            'a.id_provinsi'
        )
            ->join('anggota as a', 'a.id', '=', 'sertifikat.id_anggota')
            ->leftJoin('indonesia_villages as b', 'b.code', '=', 'a.id_kelurahan')
            ->leftJoin('indonesia_districts as c', 'c.code', '=', 'a.id_kecamatan')
            ->leftJoin('indonesia_cities as d', 'd.code', '=', 'a.id_kota')
            ->leftJoin('indonesia_provinces as e', 'e.code', '=', 'a.id_provinsi')
            ->where('sertifikat.id_anggota', $anggota->id)
            ->latest('sertifikat.id')
            ->first();

        // Fallback jika record di tabel sertifikat belum di-generate
        if (!$sertifikat) {
            $sertifikat = (object) [
                'id'            => 0,
                'id_anggota'    => $anggota->id,
                'nama_klinik'   => $anggota->nama_klinik,
                'no_anggota'    => $anggota->no_anggota,
                'updated_at'    => $anggota->updated_at,
                'nama_kontak'   => $anggota->nama_kontak,
                'alamat_klinik' => $anggota->alamat_klinik,
                'rt'            => $anggota->rt,
                'rw'            => $anggota->rw,
                'kelurahan'     => '',
                'kecamatan'     => '',
                'kota'          => '',
                'provinsi'      => '',
                'dari'          => $anggota->created_at ? $anggota->created_at->format('Y-m-d') : date('Y-m-d'),
                'sampai'        => date('Y-m-d', strtotime('+3 years')),
            ];
        }

        try {
            $viewContent = view('frontend.print_sertifikat', [
                'anggota' => $sertifikat,
            ])->render();

            $html2pdf = new Html2Pdf('L', 'A4', 'fr', true, 'UTF-8', 1);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->pdf->SetTitle('Sertifikat Anggota ASKLIN - ' . ($anggota->nama_klinik ?: ''));
            $html2pdf->writeHTML($viewContent);

            $pdfContent = $html2pdf->output('sertifikat_anggota.pdf', 'S');

            $filename = 'Sertifikat_ASKLIN_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $anggota->no_anggota ?: $anggota->id) . '.pdf';

            return response($pdfContent, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Cache-Control'       => 'private, max-age=3600',
            ]);
        } catch (Html2PdfException $e) {
            \Log::error('Error generate Sertifikat PDF: ' . $e->getMessage());
            return $this->errorResponse('Gagal menghasilkan dokumen PDF Sertifikat: ' . $e->getMessage(), 500);
        } catch (\Throwable $e) {
            \Log::error('Error generate Sertifikat PDF: ' . $e->getMessage());
            return $this->errorResponse('Terjadi kesalahan sistem saat memproses dokumen Sertifikat.', 500);
        }
    }

    /**
     * Resolusi dan otorisasi target anggota dengan proteksi IDOR lintas wilayah.
     *
     * @param \App\Models\User $user
     * @param int|string|null $requestedAnggotaId
     * @return Anggota|null
     */
    private function resolveTargetAnggota($user, $requestedAnggotaId): ?Anggota
    {
        // 1. Jika role adalah Klinik biasa, selalu paksa mengambil klinik milik sendiri
        if ($user->hasRole('Klinik') || !$user->hasAnyRole(['Superadmin', 'Admin Pusat', 'Admin Cabang', 'Admin Daerah', 'Sekjen', 'Ketua Umum', 'Bendahara'])) {
            return Anggota::where('id_user', $user->id)->first();
        }

        // 2. Jika pengurus meminta dokumen spesifik via parameter anggota_id
        if ($requestedAnggotaId) {
            $query = Anggota::where('id', $requestedAnggotaId);

            // Filter isolasi wilayah pengurus
            if ($user->hasRole('Admin Cabang') && !empty($user->kota)) {
                $query->where('id_kota', $user->kota);
            } elseif ($user->hasRole('Admin Daerah') && !empty($user->provinsi)) {
                $query->where('id_provinsi', $user->provinsi);
            }

            return $query->first();
        }

        // 3. Fallback jika pengurus juga memiliki akun klinik pribadi
        return Anggota::where('id_user', $user->id)->first();
    }
}

