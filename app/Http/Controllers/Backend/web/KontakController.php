<?php

namespace App\Http\Controllers\Backend\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kontak;
use DataTables;
use Illuminate\Support\Str;

class KontakController extends Controller
{
     function __construct()
    {
       $this->middleware('permission:kontak', ['only' => ['index', 'list']]);
    }


    public function index()
    {
        return view('backend.kontak.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Kontak::latest()->get();
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
                            <a href="javascript:void(0)" onclick="deleteu('.$data["id"].')" class="dropdown-item has-icon text-danger" ><i class="fas fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function destroy(Request $request)
    {
        $data = Kontak::where('id',$request->id)->delete();
        return Response()->json($data);
    }
}