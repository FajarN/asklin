<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Event, EventKategori};
use DataTables;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\Province;
use Auth;

class EventController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:events-list|events-create|events-edit|events-delete', ['only' => ['index','show']]);
        $this->middleware('permission:events-create', ['only' => ['create','store']]);
        $this->middleware('permission:events-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:events-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('backend.events.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Event::select('events.*', 'event_kategori.nama')
                ->join('event_kategori', 'event_kategori.id', '=', 'events.id_kategori')
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('events.id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('events.id_provinsi', Auth::user()->provinsi);
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
                ->addColumn('action', 'backend.events.action')
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        $kategori = EventKategori::get();
        $provinsi = Province::get();
        return view('backend.events.create', compact('kategori', 'provinsi'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'judul' => 'required',
            'konten' => 'required',
            'path' => 'required',
            'id_kategori' => 'required',
            'gambar' => 'mimes:jpg,jpeg,png|max:2000',
            'mulai' => 'required',
            'selesai' => 'required'
        ]);

        if($request->hasFile('gambar')) {
            $destinationPath = 'assets/images/events/';
            $img_ext = $request->file('gambar')->getClientOriginalExtension();
            $filename = 'events-' . time() . '.' . $img_ext;
            $path = $request->file('gambar')->move($destinationPath, $filename);
        }else{
            $filename = NULL;
        }
        
        if(Auth::user()->hasRole('Admin Cabang')){
            $kategori = "Cabang";
            $status = "0";
        }elseif(Auth::user()->hasRole('Admin Daerah')){
            $kategori = "Daerah";
            $status = "0";
        }else{
            $kategori = "Pusat";
            $status = $request->status;
        }

        $data = Event::create([
            'id_kategori' => $request->id_kategori,
            'judul' => $request->judul,
            'konten' => $request->konten,
            'path' => $request->path,
            'status' => $status,
            'gambar' => $filename,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
            'kategori' => $request->kategori,
            'id_provinsi' => $request->id_provinsi,
            'id_kota' => $request->id_kota,
            'kategori' => $kategori
        ]);

        return redirect()->route('events.edit', $data->id)->with('success','Event berhasil dibuat');
    }

    public function edit(Request $request)
    {
        $data = Event::find($request->id);
        $kategori = EventKategori::all();
        $provinsi = Province::get();

        return view('backend.events.edit', compact('data', 'kategori', 'provinsi'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'judul' => 'required',
            'konten' => 'required',
            'path' => 'required',
            'id_kategori' => 'required',
            'gambar' => 'mimes:jpg,jpeg,png|max:2000'
        ]);

        if($request->hasFile('gambar')) {
            $destinationPath = 'assets/images/events/';
            $img_ext = $request->file('gambar')->getClientOriginalExtension();
            $filename = 'events-' . time() . '.' . $img_ext;
            $path = $request->file('gambar')->move($destinationPath, $filename);
        }else{
            $event = Event::find($id);
            $filename = $event->gambar;
        }
        
        $event = Event::where('id', $id)->first();
        
        if(Auth::user()->hasRole('Admin Cabang')){
            $kategori = "Cabang";
            $status = $event->status;
        }elseif(Auth::user()->hasRole('Admin Daerah')){
            $kategori = "Daerah";
            $status = $event->status;
        }else{
            $kategori = "Pusat";
            $status = $request->status;
        }

        $data = Event::where('id', $id)->update([
            'id_kategori' => $request->id_kategori,
            'judul' => $request->judul,
            'konten' => $request->konten,
            'path' => $request->path,
            'status' => $request->status,
            'gambar' => $filename,
            'mulai' => $request->mulai,
            'selesai' => $request->selesai,
            'kategori' => $request->kategori,
            'id_provinsi' => $request->id_provinsi,
            'id_kota' => $request->id_kota
        ]);

        return redirect()->route('events.edit', $id)->with('success','Event berhasil dibuat');
    }

    public function destroy(Request $request)
    {
        $data = Event::where('id', $request->id)->first();
        if(file_exists(public_path() .  '/assets/images/events/' . $data->gambar)) {
            unlink(public_path() .  '/assets/images/events/' . $data->gambar);
        }
        $data->delete();
        return Response()->json($data);
    }
}