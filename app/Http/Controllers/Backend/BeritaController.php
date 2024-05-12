<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Berita, BeritaKategori};
use DataTables;
use Illuminate\Support\Str;
use Auth;

class BeritaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:berita-list|berita-create|berita-edit|berita-delete', ['only' => ['index','show']]);
        $this->middleware('permission:berita-create', ['only' => ['create','store']]);
        $this->middleware('permission:berita-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:berita-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('backend.berita.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Berita::select('berita.*', 'berita_kategori.nama')
                ->join('berita_kategori', 'berita_kategori.id', '=', 'berita.id_kategori')
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('berita.id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('berita.id_provinsi', Auth::user()->provinsi);
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
                               
                              <a href="'.route("berita.edit", $data["id"]).'" class="dropdown-item has-icon"><i class="fas fa-edit"></i> Edit</a>
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

    public function create()
    {
        $kategori = BeritaKategori::get();
        return view('backend.berita.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'judul' => 'required',
            'konten' => 'required',
            'path' => 'required',
            'id_kategori' => 'required',
            'status' => 'required',
            'gambar' => 'mimes:jpg,jpeg,png|max:2000'
        ]);

        if($request->hasFile('gambar')) {
            $destinationPath = 'assets/images/berita/';
            $img_ext = $request->file('gambar')->getClientOriginalExtension();
            $filename = 'berita-' . time() . '.' . $img_ext;
            $path = $request->file('gambar')->move($destinationPath, $filename);
        }else{
            $filename = NULL;
        }

        $data = Berita::create([
            'id_kategori' => $request->id_kategori,
            'judul' => $request->judul,
            'konten' => $request->konten,
            'path' => $request->path,
            'status' => $request->status,
            'gambar' => $filename
        ]);

        return redirect()->route('berita.edit', $data->id)->with('success','Berita berhasil dibuat');
    }

    public function edit(Request $request)
    {
        $data = Berita::find($request->id);
        $kategori = BeritaKategori::all();

        return view('backend.berita.edit', compact('data', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'judul' => 'required',
            'konten' => 'required',
            'path' => 'required',
            'id_kategori' => 'required',
            'status' => 'required',
            'gambar' => 'mimes:jpg,jpeg,png|max:2000'
        ]);

        if($request->hasFile('gambar')) {
            $destinationPath = 'assets/images/berita/';
            $img_ext = $request->file('gambar')->getClientOriginalExtension();
            $filename = 'berita-' . time() . '.' . $img_ext;
            $path = $request->file('gambar')->move($destinationPath, $filename);
        }else{
            $berita = Berita::find($id);
            $filename = $berita->gambar;
        }

        $data = Berita::where('id', $id)->update([
            'id_kategori' => $request->id_kategori,
            'judul' => $request->judul,
            'konten' => $request->konten,
            'path' => $request->path,
            'status' => $request->status,
            'gambar' => $filename
        ]);

        return redirect()->route('berita.edit', $id)->with('success','Berita berhasil dibuat');
    }

    public function destroy(Request $request)
    {
        $data = Berita::where('id', $request->id)->first();
        if(file_exists(public_path() .  '/assets/images/berita/' . $data->gambar)) {
            unlink(public_path() .  '/assets/images/berita/' . $data->gambar);
        }
        $data->delete();
        return Response()->json($data);
    }
}