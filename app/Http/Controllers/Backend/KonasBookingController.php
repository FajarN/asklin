<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KonasBookingHotel;
use DataTables;
use Illuminate\Support\Str;

class KonasBookingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:konas-booking-hotel', ['only' => ['index','store', 'create', 'edit', 'destroy']]);
    }

    public function index()
    {
        return view('backend.konas.booking');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = KonasBookingHotel::select('konas_booking_hotel.*', 'a.name')
                ->join('users as a', 'a.id', '=', 'konas_booking_hotel.id_user')
                ->orderBy('konas_booking_hotel.tanggal', 'DESC')->get();
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
        $data = KonasBookingHotel::where('id', $request->id)->update(
            [
                'hotel' => $request->hotel,
                'jumlah' => $request->jumlah,
                'extrabed' => $request->extrabed,
                'harga_kasur' => $request->harga_kasur,
                'harga_kamar' => $request->total_harga_kamar,
                'total_harga_extrabed' => $request->total_harga_extrabed,
                'total' => $request->total
            ]
        );

        return Response()->json($data);
    }

    public function edit(Request $request)
    {
        $data = Fasilitas::find($request->id);

        return Response()->json($data);
    }
}