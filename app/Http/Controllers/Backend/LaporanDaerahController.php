<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Anggota};
use DataTables;
use Illuminate\Support\Str;
use Auth;

class LaporanDaerahController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:laporan-daerah', ['only' => ['index']]);
    }

    public function index()
    {
        return view('backend.laporan.daerah');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Anggota::select('anggota.*', 'indonesia_cities.name',
                    \DB::raw('GROUP_CONCAT(DISTINCT fasilitas_klinik.nama SEPARATOR ", ") as kriteria'))
                ->leftjoin("fasilitas_klinik",\DB::raw("FIND_IN_SET(fasilitas_klinik.id, anggota.fasilitas_klinik)"),">",\DB::raw("'0'"))
                ->leftjoin("indonesia_cities", 'indonesia_cities.code', '=', 'anggota.id_kota')
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })
                ->groupBy('anggota.id')
                ->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['nama_klinik']), Str::lower($request->get('search')))){
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
}