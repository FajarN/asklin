<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Galery;
use DataTables;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\Province;
use Auth;

class GaleryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:galery', ['only' => ['index','store', 'create', 'edit', 'destroy']]);
    }

    public function index()
    {
        $provinsi = Province::get();
        return view('backend.galery.index', compact('provinsi'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Galery::select('galery.*', 'a.name as provinsi', 'b.name as kota')
                ->leftJoin('indonesia_provinces as a', 'a.code', '=', 'galery.id_provinsi')
                ->leftJoin('indonesia_cities as b', 'b.code', '=', 'galery.id_kota')
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('galery.id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('galery.id_provinsi', Auth::user()->provinsi);
                })
                ->latest()->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['judul']), Str::lower($request->get('search')))){
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $actionBtn = '
                        <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a href="javascript:void(0)" onclick="edit('.$data["id"].')" class="dropdown-item has-icon"><i class="fas fa-edit"></i> Edit</a>
                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0)" onclick="deleteu('.$data["id"].')" class="dropdown-item has-icon text-danger" ><i class="fas fa-trash-alt"></i>Hapus</a>
                        </div>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        if($request->hasFile('foto')) {
            $destinationPath = 'assets/images/galery/';
            $img_ext = $request->file('foto')->getClientOriginalExtension();
            $filename = 'galery-' . time() . '.' . $img_ext;
            $path = $request->file('foto')->move($destinationPath, $filename);
        }else{
            $galery = Galery::where('id', $request->id)->first();
            $filename = $galery->foto;
        }
        
        if(Auth::user()->hasRole('Admin Cabang')){
            $status = "0";
        }elseif(Auth::user()->hasRole('Admin Daerah')){
            $status = "0";
        }else{
            $status = $request->status;
        }

        $id = $request->id;
        $data = Galery::updateOrCreate(
            ['id' => $id],
            [
                'judul' => $request->judul,
                'keterangan' => $request->keterangan,
                'status' => $status,
                'foto' => $filename,
                'id_provinsi' => $request->id_provinsi,
                'id_kota' => $request->id_kota
            ]
        );

        return Response()->json($data);
    }

    public function edit(Request $request)
    {
        $data = Galery::find($request->id);

        return Response()->json($data);
    }

    public function destroy(Request $request)
    {
        $data = Galery::where('id', $request->id)->first();
        if(file_exists(public_path() .  '/assets/images/galery/' . $data->foto)) {
            unlink(public_path() .  '/assets/images/galery/' . $data->foto);
        }
        $data->delete();
        return Response()->json($data);
    }
}