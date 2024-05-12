<?php
    
namespace App\Http\Controllers\Backend\secret;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\{Role, Permission};
use Illuminate\Support\Str;
use DB;
use DataTables;

class PermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:secret', ['only' => ['index','store', 'edit', 'destroy']]);
    }

    public function index()
    {
        $role = Role::get();

        return view('backend.secret.permissions.index', compact('role'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Permission::with('roles')->get();
            $data = $data->transform(function($item){
                $item->role_names = $item->roles->pluck('name')->implode(' ');
                return $item;
            })->all();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['name']), Str::lower($request->get('search')))){
                                return true;
                            }
                            return false;
                        });
                    }

                    if (!empty($request->get('role_names'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            return Str::contains($data['role_names'], $request->get('role_names')) ? true : false;
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
        $ID = $request->id;

        $permission = Permission::updateOrCreate(
            ['id' => $ID],
            [
                'name' => $request->name
            ]
        );

        return Response()->json($permission);
    }

    public function edit(Request $request)
    {
        $data = Permission::find($request->id);

        return Response()->json($data);
    }

    public function destroy(Request $request)
    {
        $permission = Permission::find($request->id)->delete();
        return Response()->json($permission);
    }
}