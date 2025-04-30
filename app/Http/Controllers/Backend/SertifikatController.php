<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Sertifikat, Anggota};
use DataTables;
use Illuminate\Support\Str;
use Auth;

class SertifikatController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:sertifikat', ['only' => ['index', 'store', 'create', 'edit', 'destroy']]);
    }

    public function index(Request $request)
    {
        $anggota = Anggota::when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
            $query->where('id_kota', Auth::user()->kota);
        })
            ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                $query->where('id_provinsi', Auth::user()->provinsi);
            })
            ->where('status', 'approved')->latest()->get();
        return view('backend.sertifikat.index', compact('anggota'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Sertifikat::select(
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
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('a.id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('a.id_provinsi', Auth::user()->provinsi);
                })
                ->when($request->filled('tahun'), function ($query) use ($request) {
                    $query->whereYear('sertifikat.dari', $request->tahun);
                })
                ->get();

            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            $search = Str::lower($request->get('search'));

                            return Str::contains(Str::lower($data['sertifikat']), $search) ||
                                Str::contains(Str::lower($data['nama_klinik']), $search) ||
                                Str::contains(Str::lower($data['kota']), $search);
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return '
                        <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Action</button>
                        <div class="dropdown-menu">
                            <a href="' . route('anggota.print', $data["id"]) . '" class="dropdown-item has-icon"><i class="fas fa-print"></i> Print Sertifikat</a>
                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0)" onclick="edit(' . $data["id"] . ')" class="dropdown-item has-icon"><i class="fas fa-edit"></i> Edit</a>
                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0)" onclick="deleteu(' . $data["id"] . ')" class="dropdown-item has-icon text-danger"><i class="fas fa-trash-alt"></i> Hapus</a>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function store(Request $request)
    {
        $data = Sertifikat::updateOrCreate(
            ['id' => $request->id],
            [
                'id_anggota' => $request->id_anggota,
                'dari' => $request->dari,
                'sampai' => $request->sampai
            ]
        );

        return Response()->json($data);
    }

    public function edit(Request $request)
    {
        $data = Sertifikat::find($request->id);

        return Response()->json($data);
    }

    public function destroy(Request $request)
    {
        $data = Sertifikat::where('id', $request->id)->delete();
        return Response()->json($data);
    }
}
