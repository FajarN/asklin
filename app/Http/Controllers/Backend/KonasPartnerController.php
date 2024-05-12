<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KonasPartner;
use DataTables;
use Illuminate\Support\Str;

class KonasPartnerController extends Controller
{
    public function index()
    {
        return view('backend.partner.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = KonasPartner::get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['nama']), Str::lower($request->get('search')))){
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
        if($request->hasFile('photo')) {
            $destinationPath = 'images/partner/';
            $img_ext = $request->file('photo')->getClientOriginalExtension();
            $partner = 'partner-' . time() . '.' . $img_ext;
            $path = $request->file('photo')->move($destinationPath, $partner);
        }else{
            $partner = NULL;
        }

        $data = KonasPartner::updateOrCreate(
            ['id' => $id],
            [
                'nama' => $request->nama,
                'photo' => $partner,
                'link' => $request->link
            ]
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