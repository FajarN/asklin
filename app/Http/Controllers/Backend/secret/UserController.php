<?php

namespace App\Http\Controllers\Backend\secret;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use DataTables;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\Province;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:secret', ['only' => ['index','store', 'create', 'edit', 'destroy']]);
    }

    public function index()
    {
        $role = Role::all();

        return view('backend.secret.users.index',compact('role'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('users.*', 'b.name as kota', 'c.name as provinsi')
                ->leftjoin("indonesia_cities as b", 'b.code', '=', 'users.kota')
                ->leftjoin("indonesia_provinces as c", 'c.code', '=', 'users.provinsi')
                ->with('roles')->get();
            $data = $data->transform(function($item){
                $item->role_names = $item->roles->pluck('name')->implode(' ');
                return $item;
            })->all();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['email']), Str::lower($request->get('search')))){
                                return true;
                            }else if (Str::contains(Str::lower($data['name']), Str::lower($request->get('search')))) {
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
                            <a href="'.route("users.edit", $data["id"]).'" class="dropdown-item has-icon"><i class="fas fa-edit"></i> Edit</a>
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

    public function create()
    {
        $role = Role::all();
        $provinsi = Province::get();
        return view('backend.secret.users.create',compact('role', 'provinsi'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.edit', $user->id)->with('success','User created successfully');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $provinsi = Province::get();

        return view('backend.secret.users.edit',compact('user','roles','userRole','provinsi'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.edit', $user->id)->with('success','User updated successfully');
    }

    public function destroy(Request $request)
    {
        $user = User::find($request->id)->delete();
        return Response()->json($user);
    }
}