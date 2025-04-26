<?php

namespace App\Http\Controllers\Backend\Secret;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles-list', ['only' => ['index', 'list']]);
        $this->middleware('permission:roles-create', ['only' => ['store']]);
        $this->middleware('permission:roles-edit', ['only' => ['edit']]);
        $this->middleware('permission:roles-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $permissions = Permission::get();
        
        $groupedPermissions = $permissions->groupBy(function ($perm) {
            return explode('-', $perm->name, 2)[0];
        });

        return view('backend.secret.roles.index', compact('permissions', 'groupedPermissions'));
    }

    public function list(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'Bad request'], 400);
        }

        try {
            $query = Role::query();

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($data) {
                    return '
                          <div class="btn-group">
                            <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="edit(${row.id})">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteu(${row.id})">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </a>
                            </div>
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error in RoleController list method: '.$e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data.'], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'permission' => 'nullable|array',
            'permission.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $role = Role::updateOrCreate(
                ['name' => $request->name, 'guard_name' => 'web'],
                ['name' => $request->name]
            );

            if ($request->has('permission')) {
                $permissionNames = Permission::whereIn('id', $request->permission)
                    ->pluck('name')
                    ->toArray();
                $role->syncPermissions($permissionNames);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing role: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $role = Role::findOrFail($id);
            $rolePermissions = DB::table('role_has_permissions')
                ->where('role_id', $id)
                ->pluck('permission_id')
                ->toArray();

            return response()->json([
                'success' => true,
                'role' => $role,
                'permissions' => $rolePermissions,
            ]);
        } catch (\Exception $e) {
            Log::error('Error editing role: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Role tidak ditemukan.',
            ], 404);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $role = Role::find($request->id);
            
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak ditemukan.',
                ], 404);
            }

            $role->permissions()->detach();
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting role: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus role.',
            ], 500);
        }
    }
}