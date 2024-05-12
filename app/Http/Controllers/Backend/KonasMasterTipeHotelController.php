<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{KonasMasterTipeHotel, KonasMasterHotel};
use DataTables;
use Illuminate\Support\Str;

class KonasMasterTipeHotelController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:konas-master-tipe-hotel', ['only' => ['index','store', 'create', 'edit', 'destroy']]);
    }

    public function index()
    {
        $hotel = KonasMasterHotel::where('status', '1')->get();
        return view('backend.konas.tipe_hotel', compact('hotel'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = KonasMasterTipeHotel::select('konas_master_hotel_tipe.*', 'konas_master_hotel.hotel')->join('konas_master_hotel', 'konas_master_hotel.id', '=', 'konas_master_hotel_tipe.id_hotel')->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['hotel']), Str::lower($request->get('search')))){
                                return true;
                            }
                            if (Str::contains(Str::lower($data['tipe']), Str::lower($request->get('search')))){
                                return true;
                            }
                            if (Str::contains(Str::lower($data['harga']), Str::lower($request->get('search')))){
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
        $id = $request->id;
        $data = KonasMasterTipeHotel::updateOrCreate(
            ['id' => $id],
            [
                'id_hotel' => $request->id_hotel,
                'tipe' => $request->tipe,
                'harga' => $request->harga,
                'extrabed' => $request->extrabed,
                'stok' => $request->stok,
                'stok' => $request->stok_extrabed
            ]
        );

        return Response()->json($data);
    }

    public function edit(Request $request)
    {
        $data = KonasMasterTipeHotel::find($request->id);

        return Response()->json($data);
    }

    public function destroy(Request $request)
    {
        $data = KonasMasterTipeHotel::where('id',$request->id)->delete();
        return Response()->json($data);
    }
}