<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\{Anggota, SDM, RumahSakit, Asuransi, FotoKlinik, Sertifikat, Pembayaran};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;
use Auth;
use DataTables;

class AnggotaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:data-anggota', ['only' => ['index', 'list']]);
    }

    public function index()
    {
        return view('backend.anggota.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Anggota::select(
                'anggota.*',
                'indonesia_cities.name',
                'indonesia_villages.name as kelurahan',
                'indonesia_districts.name as kecamatan',
                'indonesia_provinces.name as provinsi',
                \DB::raw('GROUP_CONCAT(DISTINCT fasilitas_klinik.nama SEPARATOR ", ") as kriteria'),
                \DB::raw('
                CASE
                    WHEN anggota.verifikasi_pusat IS NULL OR anggota.verifikasi_pusat = "0000-00-00 00:00:00" THEN
                        FROM_UNIXTIME(anggota.created_on)
                    ELSE
                        anggota.verifikasi_pusat
                END as tanggal_approve
            '),
                \DB::raw('
                CASE
                    WHEN anggota.created_at IS NULL OR anggota.created_at = "0000-00-00 00:00:00" THEN
                        FROM_UNIXTIME(anggota.created_on)
                    ELSE
                        anggota.created_at
                END as tanggal_daftar
            ')
            )
                ->leftjoin('fasilitas_klinik', \DB::raw('FIND_IN_SET(fasilitas_klinik.id, anggota.fasilitas_klinik)'), '>', \DB::raw("'0'"))
                ->leftjoin('indonesia_cities', 'indonesia_cities.code', '=', 'anggota.id_kota')
                ->leftjoin('indonesia_villages', 'indonesia_villages.code', '=', 'anggota.id_kelurahan')
                ->leftjoin('indonesia_districts', 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
                ->leftjoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
                ->groupBy('anggota.id')
                ->where('anggota.status', 'approved')
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })
                ->when(Auth::user()->hasRole('Sekjen'), function ($query) use ($request) {
                    $query->where('anggota.status', 'Verifikasi Sekjen');
                })
                ->when(Auth::user()->hasRole('Ketua Umum'), function ($query) use ($request) {
                    $query->where('anggota.status_pembayaran', '1');
                })
                ->when(Auth::user()->hasRole('Bendahara'), function ($query) use ($request) {
                    $query->where('anggota.status', 'Verifikasi Bendahara');
                })
                ->when($request->get('filter_tahun_approve'), function ($query) use ($request) {
                    $tahunFilter = $request->get('filter_tahun_approve');
                    $query->whereRaw('YEAR(CASE
                    WHEN anggota.verifikasi_pusat IS NULL OR anggota.verifikasi_pusat = "0000-00-00 00:00:00" THEN
                        FROM_UNIXTIME(anggota.created_on)
                    ELSE
                        anggota.verifikasi_pusat
                END) = ?', [$tahunFilter]);
                })
                ->when($request->get('filter_bulan_approve'), function ($query) use ($request) {
                    $bulanFilter = $request->get('filter_bulan_approve');
                    $query->whereRaw('MONTH(CASE
                    WHEN anggota.verifikasi_pusat IS NULL OR anggota.verifikasi_pusat = "0000-00-00 00:00:00" THEN
                        FROM_UNIXTIME(anggota.created_on)
                    ELSE
                        anggota.verifikasi_pusat
                END) = ?', [$bulanFilter]);
                })
                ->when($request->get('filter_jenis'), function ($query) use ($request) {
                    $query->where('anggota.jenis_klinik', $request->get('filter_jenis'));
                })
                ->get();

            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $searchQuery = Str::lower($request->get('search'));
                        $instance->collection = $instance->collection->filter(function ($data) use ($searchQuery) {
                            if (Str::contains(Str::lower($data['nama_klinik']), $searchQuery)) {
                                return true;
                            }
                            if (Str::contains(Str::lower($data['no_anggota']), $searchQuery)) {
                                return true;
                            }
                            if (Str::contains(Str::lower($data['name']), $searchQuery)) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addIndexColumn()
                // Format tanggal daftar
                ->addColumn('tanggal_daftar_formatted', function ($row) {
                    if ($row->tanggal_daftar && $row->tanggal_daftar != '0000-00-00 00:00:00') {
                        $tanggal = \Carbon\Carbon::parse($row->tanggal_daftar);
                        return '<span style="color: #666; font-size: 12px;">' . $tanggal->format('d-m-Y') . '</span>';
                    }
                    return '<span style="color: #999;">-</span>';
                })
                ->addColumn('tanggal_approve_formatted', function ($row) {
                    if ($row->tanggal_approve && $row->tanggal_approve != '0000-00-00 00:00:00') {
                        $tanggal = \Carbon\Carbon::parse($row->tanggal_approve);
                        $formatTanggal = $tanggal->format('d-m-Y');

                        $isDataLama = ($row->verifikasi_pusat == '0000-00-00 00:00:00' || $row->verifikasi_pusat == null);

                        if ($isDataLama) {
                            return '<span class="badge badge-info" style="font-size: 11px;">' . $formatTanggal . '</span><br>
                                <small style="color: #17a2b8;">Data Migrasi</small>';
                        } else {
                            return '<span class="badge badge-success" style="font-size: 11px;">' . $formatTanggal . '</span><br>
                                <small style="color: #28a745;">Verifikasi Resmi</small>';
                        }
                    }
                    return '<span style="color: #999;">-</span>';
                })
                ->addColumn('durasi_verifikasi', function ($row) {
                    if (
                        $row->tanggal_daftar &&
                        $row->tanggal_approve &&
                        $row->tanggal_daftar != '0000-00-00 00:00:00' &&
                        $row->tanggal_approve != '0000-00-00 00:00:00'
                    ) {
                        $daftar = \Carbon\Carbon::parse($row->tanggal_daftar);
                        $approve = \Carbon\Carbon::parse($row->tanggal_approve);

                        $durasi = $daftar->diffInDays($approve);

                        // Warna berdasarkan kecepatan verifikasi
                        $warna = '#28a745';  // Hijau untuk cepat
                        if ($durasi > 365) {
                            $warna = '#dc3545';  // Merah untuk sangat lama
                        } elseif ($durasi > 180) {
                            $warna = '#fd7e14';  // Orange untuk lama
                        } elseif ($durasi > 90) {
                            $warna = '#ffc107';  // Kuning untuk agak lama
                        }

                        return '<span style="color: ' . $warna . '; font-weight: bold; font-size: 12px;">' . $durasi . ' hari</span>';
                    }
                    return '<span style="color: #999;">-</span>';
                })
                ->addColumn('action', 'backend.anggota.action')
                ->rawColumns(['action', 'tanggal_daftar_formatted', 'tanggal_approve_formatted', 'durasi_verifikasi'])
                ->make(true);
        }
    }

    public function detail_anggota($id)
    {
        $anggota = Anggota::select('anggota.*', 'indonesia_cities.name as kota', 'indonesia_districts.name as kecamatan', 'indonesia_villages.name as kelurahan', 'indonesia_provinces.name as provinsi')
            ->leftjoin('indonesia_cities', 'indonesia_cities.code', '=', 'anggota.id_kota')
            ->leftjoin('indonesia_districts', 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
            ->leftjoin('indonesia_villages', 'indonesia_villages.code', '=', 'anggota.id_kelurahan')
            ->leftjoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
            ->where('anggota.id', $id)
            ->first();

        return view('backend.anggota.detail_anggota', compact('anggota'));
    }

    public function verifyBendahara(Request $request)
    {
        $id = $request->id;
        $data = Anggota::where('id', $id)->update([
            'status_pembayaran' => $request->status_pembayaran
        ]);

        return Response()->json($data);
    }

    public function editBendahara(Request $request)
    {
        $data = Anggota::find($request->id);

        return Response()->json($data);
    }

    public function sdm_pjk(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = SDM::where(['id_klinik' => $id, 'id_kategori_sdm' => '1'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_dp(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = SDM::where(['id_klinik' => $id, 'id_kategori_sdm' => '2'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_tk(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = SDM::where(['id_klinik' => $id, 'id_kategori_sdm' => '3'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_tkl(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = SDM::where(['id_klinik' => $id, 'id_kategori_sdm' => '4'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_lain(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = SDM::where(['id_klinik' => $id, 'id_kategori_sdm' => '5'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_rumah_sakit(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = RumahSakit::where('id_klinik', $id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_asuransi(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = Asuransi::where('id_klinik', $id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function sdm_foto(Request $request, $id)
    {
        if ($request->ajax()) {
            $data = FotoKlinik::where('id_klinik', $id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }


    /**
     * Delete anggota dan semua data terkait
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $anggota = Anggota::findOrFail($id);

            // 1. Hapus semua SDM yang terkait dengan klinik ini
            $sdmList = SDM::where('id_klinik', $anggota->id)->get();
            foreach ($sdmList as $sdm) {
                // Hapus file STR jika ada
                if ($sdm->file_str && file_exists(public_path('images/file/' . $sdm->file_str))) {
                    unlink(public_path('images/file/' . $sdm->file_str));
                }
                // Hapus file SIP jika ada
                if ($sdm->file_sip && file_exists(public_path('images/file/' . $sdm->file_sip))) {
                    unlink(public_path('images/file/' . $sdm->file_sip));
                }
                $sdm->delete();
            }

            // 2. Hapus semua data Rumah Sakit terkait
            RumahSakit::where('id_klinik', $anggota->id)->delete();

            // 3. Hapus semua data Asuransi terkait beserta file bukti
            $asuransiList = Asuransi::where('id_klinik', $anggota->id)->get();
            foreach ($asuransiList as $asuransi) {
                if ($asuransi->bukti && file_exists(public_path('images/file/' . $asuransi->bukti))) {
                    unlink(public_path('images/file/' . $asuransi->bukti));
                }
                $asuransi->delete();
            }

            // 4. Hapus semua foto klinik beserta file foto
            $fotoList = FotoKlinik::where('id_klinik', $anggota->id)->get();
            foreach ($fotoList as $foto) {
                if ($foto->foto && file_exists(public_path('images/file/' . $foto->foto))) {
                    unlink(public_path('images/file/' . $foto->foto));
                }
                $foto->delete();
            }

            // 5. Hapus semua pembayaran terkait beserta bukti pembayaran
            $pembayaranList = Pembayaran::where('id_anggota', $anggota->id)->get();
            foreach ($pembayaranList as $pembayaran) {
                if ($pembayaran->bukti && file_exists(public_path('images/file/' . $pembayaran->bukti))) {
                    unlink(public_path('images/file/' . $pembayaran->bukti));
                }
                $pembayaran->delete();
            }

            // 6. Hapus semua sertifikat terkait
            Sertifikat::where('id_anggota', $anggota->id)->delete();

            // 7. Hapus file logo anggota jika ada
            if ($anggota->logo && file_exists(public_path('images/file/' . $anggota->logo))) {
                unlink(public_path('images/file/' . $anggota->logo));
            }

            // 8. Hapus file SIO anggota jika ada
            if ($anggota->sio && file_exists(public_path('images/file/' . $anggota->sio))) {
                unlink(public_path('images/file/' . $anggota->sio));
            }

            // 9. Terakhir hapus data anggota
            $anggota->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data anggota dan semua data terkait berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            \Log::error('Error saat menghapus anggota: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Konfirmasi delete dengan menampilkan detail yang akan dihapus
     */
    public function confirmDelete($id)
    {
        try {
            $anggota = Anggota::findOrFail($id);

            $sdmCount = SDM::where('id_klinik', $anggota->id)->count();
            $rsCount = RumahSakit::where('id_klinik', $anggota->id)->count();
            $asuransiCount = Asuransi::where('id_klinik', $anggota->id)->count();
            $fotoCount = FotoKlinik::where('id_klinik', $anggota->id)->count();
            $pembayaranCount = Pembayaran::where('id_anggota', $anggota->id)->count();
            $sertifikatCount = Sertifikat::where('id_anggota', $anggota->id)->count();

            return response()->json([
                'anggota' => $anggota,
                'related_data' => [
                    'sdm' => $sdmCount,
                    'rumah_sakit' => $rsCount,
                    'asuransi' => $asuransiCount,
                    'foto_klinik' => $fotoCount,
                    'pembayaran' => $pembayaranCount,
                    'sertifikat' => $sertifikatCount
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saat konfirmasi delete: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }



    public function print($id)
    {
        $anggota = Sertifikat::select(
            'sertifikat.*',
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
            ->leftJoin('anggota as a', 'a.id', '=', 'sertifikat.id_anggota')
            ->leftJoin('indonesia_villages as b', 'b.code', '=', 'a.id_kelurahan')
            ->leftJoin('indonesia_districts as c', 'c.code', '=', 'a.id_kecamatan')
            ->leftJoin('indonesia_cities as d', 'd.code', '=', 'a.id_kota')
            ->leftJoin('indonesia_provinces as e', 'e.code', '=', 'a.id_provinsi')
            ->where('sertifikat.id', $id)
            ->first();

        $id = $anggota->id;

        try {
            ob_start();
            $content = view('backend.anggota.print', compact('anggota'));

            $html2pdf = new Html2Pdf('L', 'A4', 'fr', true, 'UTF-8', 1);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->pdf->SetTitle('Sertifikat Anggota ASKLIN');
            $html2pdf->writeHTML($content);
            $html2pdf->output('sertifikat_anggota.pdf');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();

            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
        exit();
    }

    public function printsk($id)
    {
        $anggota = Anggota::select(
            'anggota.*',
            \DB::raw('GROUP_CONCAT(DISTINCT a.nama SEPARATOR ", ") as nama_fasilitas_klinik'),
            'b.name as kota',
            'c.name as provinsi'
        )
            ->leftJoin('fasilitas_klinik as a', \DB::raw('FIND_IN_SET(a.id, anggota.fasilitas_klinik)'), '>', \DB::raw("'0'"))
            ->leftJoin('indonesia_cities as b', 'b.code', '=', 'anggota.id_kota')
            ->leftJoin('indonesia_provinces as c', 'c.code', '=', 'anggota.id_provinsi')
            ->groupBy('anggota.id')
            ->where('anggota.id', $id)
            ->first();

        $id = $anggota->id;

        $created_on = Carbon::createFromTimestamp($anggota->created_on)->format('Y');

        try {
            ob_start();
            $content = view('backend.anggota.printsk', compact('anggota', 'created_on'));

            $html2pdf = new Html2Pdf('P', 'F4', 'fr', true, 'UTF-8', 1);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->pdf->SetTitle('SK Anggota ASKLIN');
            $html2pdf->writeHTML($content);
            $html2pdf->output('sk_anggota.pdf');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();

            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
        exit();
    }
}
