<?php

namespace App\Http\Controllers\Backend\konas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KonasPenerbangan;
use DataTables;
use Illuminate\Support\Str;

class KonasPenerbanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:konas-data-penerbangan-list', ['only' => ['index', 'list']]);
        $this->middleware('permission:konas-data-penerbangan-create', ['only' => ['store']]);
        $this->middleware('permission:konas-data-penerbangan-edit', ['only' => ['edit']]);
        $this->middleware('permission:konas-data-penerbangan-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('backend.konas.penerbangan');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = KonasPenerbangan::select('konas_penerbangan.*', 'b.no_anggota', 'b.nama_peserta', 'b.foto', 'b.no_hp', 'b.nama_klinik', 'b.email')
                ->join('users as a', 'a.id', '=', 'konas_penerbangan.id_user')
                ->join('konas_peserta as b', 'b.email', '=', 'a.email')
                ->orderBy('konas_penerbangan.tanggal', 'DESC')->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['nama_peserta']), Str::lower($request->get('search')))){
                                return true;
                            }
                            if (Str::contains(Str::lower($data['nama_klinik']), Str::lower($request->get('search')))){
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