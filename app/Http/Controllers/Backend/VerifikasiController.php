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

class VerifikasiController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:verifikasi', ['only' => ['index']]);
    }

    public function index()
    {
        return view('backend.verifikasi.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Anggota::select(
                'anggota.*',
                'indonesia_cities.name',
                \DB::raw('GROUP_CONCAT(DISTINCT fasilitas_klinik.nama SEPARATOR ", ") as kriteria')
            )
                ->leftjoin("fasilitas_klinik", \DB::raw("FIND_IN_SET(fasilitas_klinik.id, anggota.fasilitas_klinik)"), ">", \DB::raw("'0'"))
                ->leftjoin("indonesia_cities", 'indonesia_cities.code', '=', 'anggota.id_kota')
                ->groupBy('anggota.id')
                ->where('anggota.status', 'approved')
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })
                ->get();
            // return Datatables::of($data)
            //     ->filter(function ($instance) use ($request) {
            //         if (!empty($request->get('search'))) {
            //             $instance->collection = $instance->collection->filter(function ($data) use ($request) {
            //                 if (Str::contains(Str::lower($data['nama_klinik']), Str::lower($request->get('search')))){
            //                     return true;
            //                 }
            //                 return false;
            //             });
            //         }
            //     })
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
                ->addColumn('action', 'backend.verifikasi.action')
                ->rawColumns(['action'])
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
            $content = view('backend.verifikasi.print', compact('anggota'));

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

    // Menggunakan Carbon untuk memformat tanggal dari timestamp Unix
    $created_on = Carbon::createFromTimestamp($anggota->created_on)->format('Y');

    try {
        ob_start();
        $content = view('backend.verifikasi.printsk', compact('anggota', 'created_on'));

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
