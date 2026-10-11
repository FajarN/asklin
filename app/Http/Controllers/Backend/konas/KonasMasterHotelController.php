<?php

namespace App\Http\Controllers\Backend\konas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KonasMasterHotel;
use DataTables;
use Illuminate\Support\Str;

class KonasMasterHotelController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:konas-master-hotel-list', ['only' => ['index', 'list']]);
        $this->middleware('permission:konas-master-hotel-create', ['only' => ['store']]);
        $this->middleware('permission:konas-master-hotel-edit', ['only' => ['edit']]);
        $this->middleware('permission:konas-master-hotel-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('backend.konas.hotel');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = KonasMasterHotel::query();
            if ($request->filled('id_periode')) {
                $query->where('id_periode', $request->id_periode);
            }
            $data = $query->latest()->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    $search = is_array($request->get('search')) ? ($request->input('search.value') ?? '') : $request->get('search');
                    if (!empty($search)) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($search) {
                            if (Str::contains(Str::lower($data['hotel'] ?? ''), Str::lower($search))){
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
                            <button type="button" class="btn btn-info btn-sm font-semibold" title="Edit Hotel" onclick="edit('.$data["id"].')">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" title="Hapus Hotel" onclick="deleteu('.$data["id"].')">
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
        $periodeId = $request->id_periode ?: (\App\Models\KonasPeriode::where('status', '1')->value('id') ?? 1);
        $payload = [
            'hotel' => $request->hotel,
            'status' => $request->status,
            'alamat' => $request->alamat,
        ];
        if ($request->filled('id_periode') || empty($id)) {
            $payload['id_periode'] = $periodeId;
        }

        $data = KonasMasterHotel::updateOrCreate(
            ['id' => $id],
            $payload
        );

        return Response()->json($data);
    }

    public function edit(Request $request)
    {
        $data = KonasMasterHotel::find($request->id);

        return Response()->json($data);
    }

    public function destroy(Request $request)
    {
        $data = KonasMasterHotel::where('id',$request->id)->delete();
        return Response()->json($data);
    }
}