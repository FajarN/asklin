<?php

namespace App\Http\Controllers\Backend\konas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KonasPartner;
use DataTables;
use Illuminate\Support\Str;

class KonasPartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:konas-partner-list', ['only' => ['index', 'list']]);
        $this->middleware('permission:konas-partner-create', ['only' => ['store']]);
        $this->middleware('permission:konas-partner-edit', ['only' => ['edit']]);
        $this->middleware('permission:konas-partner-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('backend.partner.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = KonasPartner::query();
            if ($request->filled('id_periode')) {
                $query->where('id_periode', $request->id_periode);
            }
            $data = $query->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    $search = is_array($request->get('search')) ? ($request->input('search.value') ?? '') : $request->get('search');
                    if (!empty($search)) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($search) {
                            if (Str::contains(Str::lower($data['nama']), Str::lower($search))){
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
                            <button type="button" class="btn btn-info btn-sm font-semibold" title="Edit Sponsor" onclick="edit('.$data["id"].')">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" title="Hapus Sponsor" onclick="deleteu('.$data["id"].')">
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
        if($request->hasFile('photo')) {
            $destinationPath = 'images/partner/';
            $img_ext = $request->file('photo')->getClientOriginalExtension();
            $partner = 'partner-' . time() . '.' . $img_ext;
            $path = $request->file('photo')->move($destinationPath, $partner);
        }else{
            $partner = NULL;
        }

        $payload = [
            'nama' => $request->nama,
            'link' => $request->link
        ];

        if ($partner !== NULL) {
            $payload['photo'] = $partner;
        }

        if ($request->filled('id_periode') || empty($id)) {
            $payload['id_periode'] = $periodeId;
        }

        $data = KonasPartner::updateOrCreate(
            ['id' => $id],
            $payload
        );

        return Response()->json($data);
    }

    public function edit(Request $request)
    {
        $data = KonasPartner::find($request->id);

        return Response()->json($data);
    }

    public function destroy(Request $request)
    {
        $data = KonasPartner::where('id',$request->id);
        if(file_exists(public_path() .  '/images/partner/' . $data->photo)) {
            unlink(public_path() .  '/images/partner/' . $data->photo);
        }
        $data->delete();
        return Response()->json($data);
    }
}