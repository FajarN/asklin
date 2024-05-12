<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Anggota};
use DataTables;
use Illuminate\Support\Str;

class KerjasamaAsuransiController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:kerjasama-asuransi', ['only' => ['index']]);
    }

    public function index()
    {
        return view('backend.kerjasama_asuransi.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Anggota::select('anggota.nama_klinik', 'sdm_asuransi.created_at', \DB::raw('GROUP_CONCAT(sdm_asuransi.nama SEPARATOR ", ") as asuransi'))
                ->leftjoin("sdm_asuransi", 'sdm_asuransi.id_klinik', '=', 'anggota.id')
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