<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Anggota, SDM};
use DataTables;
use Illuminate\Support\Str;

class SDMKlinikController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:verifikasi-anggota', ['only' => ['index']]);
    }

    public function index()
    {
        return view('backend.sdm_klinik.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Anggota::select('anggota.nama_klinik',
                \DB::raw('COUNT(id_kategori_sdm) as total'),
                \DB::raw("SUM(id_kategori_sdm = '2') AS tot1"),
                \DB::raw("SUM(id_kategori_sdm = '3') AS tot2"),
                \DB::raw("SUM(id_kategori_sdm = '4') AS tot3"),
                \DB::raw("SUM(id_kategori_sdm = '5') AS tot4"),)
                ->leftjoin("sdm", 'sdm.id_klinik', '=', 'anggota.id')
                ->groupBy('anggota.id')
                ->whereNotIn('anggota.status', ['waiting', 'create-dokter'])->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['nama']), Str::lower($request->get('search')))){
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addIndexColumn()
                ->make(true);
        }
    }
}