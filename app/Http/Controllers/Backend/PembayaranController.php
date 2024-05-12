<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Pembayaran, PembayaranKategori, Anggota};
use DataTables;
use Illuminate\Support\Str;
use Auth;

class PembayaranController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:pembayaran', ['only' => ['index','store', 'create', 'edit', 'destroy']]);
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
        return view('backend.pembayaran.index', compact('anggota'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Pembayaran::select('pembayaran.*', 'a.nama', 'b.nama_klinik')
                ->join('pembayaran_kategori as a', 'a.id', '=', 'pembayaran.id_kategori')
                ->join('anggota as b', 'b.id', '=', 'pembayaran.id_anggota')
                ->where('pembayaran.id_kategori', '1')
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('b.id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('b.id_provinsi', Auth::user()->provinsi);
                })
                ->when(Auth::user()->hasRole('Admin Pusat'), function ($query) use ($request) {
                    $query->where('b.status', 'Terverifikasi Cabang');
                })
                ->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['nama']), Str::lower($request->get('search')))){
                                return true;
                            }elseif (Str::contains(Str::lower($data['nama_klinik']), Str::lower($request->get('search')))){
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
                           <a href="javascript:void(0)" onclick="updateStatus('.$data["id"].')" class="dropdown-item has-icon"><i class="fas fa-edit"></i> Update Status Pembayaran</a>
                            <div class="dropdown-divider"></div>
                        
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
    
    public function pangkal(Request $request)
    {
        // $anggota = Anggota::where('status', 'approved')->latest()->get();

          $anggota = Anggota::when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('id_provinsi', Auth::user()->provinsi);
                })
                ->where('status', 'approved')->latest()->get();
        
        return view('backend.pembayaran.pangkal', compact('anggota'));
    }

    public function list_pangkal(Request $request)
    {
        if ($request->ajax()) {
            $data = Pembayaran::select('pembayaran.*', 'a.nama', 'b.nama_klinik')
                ->join('pembayaran_kategori as a', 'a.id', '=', 'pembayaran.id_kategori')
                ->join('anggota as b', 'b.id', '=', 'pembayaran.id_anggota')
                ->where('pembayaran.id_kategori', '2')
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('b.id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('b.id_provinsi', Auth::user()->provinsi);
                })
                ->when(Auth::user()->hasRole('Admin Pusat'), function ($query) use ($request) {
                    $query->where('b.status', 'Terverifikasi Cabang');
                })
                ->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['nama']), Str::lower($request->get('search')))){
                                return true;
                            }elseif (Str::contains(Str::lower($data['nama_klinik']), Str::lower($request->get('search')))){
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

     public function iuran_anggota()
    {
        $anggota = Anggota::where('status', 'approved')->latest()->get();
        return view('backend.pembayaran.iuran', compact('anggota'));
    }

    public function list_iuran_anggota(Request $request)
    {
        if ($request->ajax()) {
            $data = Pembayaran::select('pembayaran.*', 'a.nama', 'b.nama_klinik')
                ->join('pembayaran_kategori as a', 'a.id', '=', 'pembayaran.id_kategori')
                ->join('anggota as b', 'b.id', '=', 'pembayaran.id_anggota')
                ->where('pembayaran.id_kategori', '3')
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('b.id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('b.id_provinsi', Auth::user()->provinsi);
                })
                ->when(Auth::user()->hasRole('Admin Pusat'), function ($query) use ($request) {
                    $query->where('b.status', 'Terverifikasi Cabang');
                })
                ->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['nama']), Str::lower($request->get('search')))){
                                return true;
                            }elseif (Str::contains(Str::lower($data['nama_klinik']), Str::lower($request->get('search')))){
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
        $pembayaran = Pembayaran::where('id', $request->id)->first();
        $destinationPath = 'images/file/';
        if($request->hasFile('bukti')) {
            $img_ext = $request->file('bukti')->getClientOriginalExtension();
            $bukti = 'bukti-pembayaran-' . time() . '.' . $img_ext;
            $path = $request->file('bukti')->move($destinationPath, $bukti);
        }else{
            $bukti = $pembayaran->bukti;
        }

        $data = Pembayaran::updateOrCreate(
            ['id' => $request->id],
            [
                'id_kategori' => $request->id_kategori,
                'status' => $request->status,
                'id_anggota' => $request->id_anggota,
                'keterangan' => $request->keterangan,
                'bukti' => $bukti,
                'tanggal_bayar' => $request->tanggal_bayar,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'total' => $request->total
            ]
        );

        return Response()->json($data);
    }

    public function edit(Request $request)
    {
        $data = Pembayaran::find($request->id);

        return Response()->json($data);
    }

    public function updateStatus(Request $request)
    {
        $data = Pembayaran::find($request->id);

        return Response()->json($data);
    }

    public function destroy(Request $request)
    {
        $data = Pembayaran::where('id', $request->id)->first();
        if(file_exists(public_path() .  '/images/file/' . $data->bukti)) {
            unlink(public_path() .  '/images/file/' . $data->bukti);
        }
        $data->delete();
        return Response()->json($data);
    }
}