<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Anggota};
use DataTables;
use Illuminate\Support\Str;
use Auth;
use Laravolt\Indonesia\Models\Province;
use Carbon\Carbon;

class LaporanPusatController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:laporan-pusat', ['only' => ['index']]);
    }

    public function index()
    {
        $provinsi = Province::get();
        return view('backend.laporan.pusat', compact('provinsi'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Anggota::select(
                'anggota.*',
                'indonesia_cities.name',
                'indonesia_provinces.name as provinsi',
                \DB::raw('GROUP_CONCAT(DISTINCT fasilitas_klinik.nama SEPARATOR ", ") as kriteria')
            )
                ->leftjoin("fasilitas_klinik", \DB::raw("FIND_IN_SET(fasilitas_klinik.id, anggota.fasilitas_klinik)"), ">", \DB::raw("'0'"))
                ->leftjoin("indonesia_cities", 'indonesia_cities.code', '=', 'anggota.id_kota')
                ->leftjoin("indonesia_provinces", 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
                ->groupBy('anggota.id')
                ->get();

               foreach ($data as $item) {
                    $item->formatted_created_on = Carbon::createFromTimestamp($item->created_on)->format('d-m-Y');
                }

            return Datatables::of($data)
               ->addColumn('created_on', '{{$formatted_created_on}}')
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['nama_klinik']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            if (Str::contains(Str::lower($data['no_anggota']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            if (Str::contains(Str::lower($data['name']), Str::lower($request->get('search')))) {
                                return true;
                            }
                            return false;
                        });
                    }

                    if (!empty($request->get('provinsi'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            return Str::contains($data['id_provinsi'], $request->get('provinsi')) ? true : false;
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', 'backend.verifikasi.action')
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
