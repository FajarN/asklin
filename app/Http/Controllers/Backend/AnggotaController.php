<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Anggota, SDM, RumahSakit, Asuransi, FotoKlinik, Sertifikat};
use DataTables;
use Illuminate\Support\Str;
use Auth;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Carbon\Carbon;

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
                \DB::raw('GROUP_CONCAT(DISTINCT fasilitas_klinik.nama SEPARATOR ", ") as kriteria')
                )
                ->leftjoin("fasilitas_klinik", \DB::raw("FIND_IN_SET(fasilitas_klinik.id, anggota.fasilitas_klinik)"), ">", \DB::raw("'0'"))
                ->leftjoin("indonesia_cities", 'indonesia_cities.code', '=', 'anggota.id_kota')
                ->leftjoin("indonesia_villages", 'indonesia_villages.code', '=', 'anggota.id_kelurahan')
                ->leftjoin("indonesia_districts", 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
                ->leftjoin("indonesia_provinces", 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
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
                ->get();
                return Datatables::of($data)
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                            $searchQuery = Str::lower($request->get('search'));
                            $instance->collection = $instance->collection->filter(function ($data) use ($searchQuery) {
                                if (Str::contains(Str::lower($data['nama_klinik']), $searchQuery)) {
                                    return true;
                                }
                                if (Str::contains(
                                    Str::lower($data['no_anggota']),
                                    $searchQuery
                                )) {
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
                    ->addColumn('action', 'backend.anggota.action')
                    ->rawColumns(['action'])
                    ->make(true);
            }
    }
    public function detail_anggota($id)
    {
        $anggota = Anggota::select('anggota.*', 'indonesia_cities.name as kota', 'indonesia_districts.name as kecamatan', 'indonesia_villages.name as kelurahan', 'indonesia_provinces.name as provinsi')
            ->leftjoin("indonesia_cities", 'indonesia_cities.code', '=', 'anggota.id_kota')
            ->leftjoin("indonesia_districts", 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
            ->leftjoin("indonesia_villages", 'indonesia_villages.code', '=', 'anggota.id_kelurahan')
            ->leftjoin("indonesia_provinces", 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
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
            ->leftJoin('fasilitas_klinik as a', \DB::raw("FIND_IN_SET(a.id, anggota.fasilitas_klinik)"), ">", \DB::raw("'0'"))
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
