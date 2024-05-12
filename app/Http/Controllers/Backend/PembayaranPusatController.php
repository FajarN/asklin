<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{PembayaranPusat};
use DataTables;
use Illuminate\Support\Str;
use Auth;

class PembayaranPusatController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:pembayaran-pusat', ['only' => ['index','store', 'create', 'edit', 'destroy']]);
    }

    public function index()
    {
        return view('backend.pembayaran_pusat.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = PembayaranPusat::select('pembayaran_pusat.*', 'a.name as kota', 'b.name as provinsi')
                ->join('indonesia_cities as a', 'a.code', '=', 'pembayaran_pusat.id_kota')
                ->join('indonesia_provinces as b', 'b.code', '=', 'pembayaran_pusat.id_provinsi')
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                    $query->where('pembayaran_pusat.id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                    $query->where('pembayaran_pusat.id_provinsi', Auth::user()->provinsi);
                })
                ->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['provinsi']), Str::lower($request->get('search')))){
                                return true;
                            }elseif (Str::contains(Str::lower($data['kota']), Str::lower($request->get('search')))){
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
        $pembayaran = PembayaranPusat::where('id', $request->id)->first();
        $destinationPath = 'images/file/';
        if($request->hasFile('bukti')) {
            $img_ext = $request->file('bukti')->getClientOriginalExtension();
            $bukti = 'bukti-pembayaran-pusat-' . time() . '.' . $img_ext;
            $path = $request->file('bukti')->move($destinationPath, $bukti);
        }else{
            $bukti = $pembayaran->bukti;
        }

        $data = PembayaranPusat::updateOrCreate(
            ['id' => $request->id],
            [
                'id_kota' => Auth()->user()->kota,
                'id_provinsi' => Auth()->user()->provinsi,
                'status' => $request->status,
                'jumlah_klinik' => $request->jumlah_klinik,
                'bukti' => $bukti,
                'total' => $request->total,
                'dari' => $request->dari,
                'sampai' => $request->sampai,
                'jenis_pembayaran' => $request->jenis_pembayaran,
                'keterangan' => $request->keterangan
            ]
        );

        return Response()->json($data);
    }

    public function edit(Request $request)
    {
        $data = PembayaranPusat::find($request->id);

        return Response()->json($data);
    }

    public function destroy(Request $request)
    {
        $data = PembayaranPusat::where('id', $request->id)->first();
        if(file_exists(public_path() .  '/images/file/' . $data->bukti)) {
            unlink(public_path() .  '/images/file/' . $data->bukti);
        }
        $data->delete();
        return Response()->json($data);
    }
}