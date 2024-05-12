<?php
    
namespace App\Http\Controllers\Backend\secret;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\{Role, Permission};
use Illuminate\Support\Str;
use DB;
use DataTables;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:secret', ['only' => ['index','store', 'edit', 'destroy']]);
    }

    public function index()
    {
        $permission = Permission::get();

        return view('backend.secret.roles.index', compact('permission'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::all();
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
        $role = Role::updateOrCreate(
            ['id' => $id],
            [
                'name' => $request->name
            ]
        );
        $role->syncPermissions($request->input('permission'));

        return Response()->json($role);
    }

    public function edit(Request $request)
    {
        $role1 = Role::find($request->id);
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $request->id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
            return Response()->json([$role1, $rolePermissions]);
    }

    public function destroy(Request $request)
    {
        $role = DB::table("roles")->where('id',$request->id)->delete();
        return Response()->json($role);
    }
}