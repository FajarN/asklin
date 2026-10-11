<?php

namespace App\Http\Controllers\Backend\konas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{KonasMasterTipeHotel, KonasMasterHotel};
use DataTables;
use Illuminate\Support\Str;

class KonasMasterTipeHotelController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:konas-tipe-hotel-list', ['only' => ['index', 'list']]);
        $this->middleware('permission:konas-tipe-hotel-create', ['only' => ['store']]);
        $this->middleware('permission:konas-tipe-hotel-edit', ['only' => ['edit']]);
        $this->middleware('permission:konas-tipe-hotel-delete', ['only' => ['destroy']]);
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
                    $search = is_array($request->get('search')) ? ($request->input('search.value') ?? '') : $request->get('search');
                    if (!empty($search)) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($search) {
                            if (Str::contains(Str::lower($data['hotel']), Str::lower($search))){
                                return true;
                            }
                            if (Str::contains(Str::lower($data['tipe']), Str::lower($search))){
                                return true;
                            }
                            if (Str::contains(Str::lower($data['harga']), Str::lower($search))){
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $actionBtn = '
                        <div class="inline-flex items-center gap-1">
                            <button type="button" class="btn btn-info btn-sm font-semibold" title="Edit Tipe Kamar" onclick="edit('.$data["id"].')">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" title="Hapus Tipe Kamar" onclick="deleteu('.$data["id"].')">
                                <i class="fas fa-trash"></i>
                            </button>
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