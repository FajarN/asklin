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
                    // PERBAIKAN: Gunakan $data->id bukan ${row.id}
                    return '
                          <div class="btn-group">
                            <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="edit('.$data->id.')">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteu('.$data->id.')">
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
            // PERBAIKAN: Tambah validasi permission yang ada di database
            if ($request->has('permission') && !empty($request->permission)) {
                $validPermissions = Permission::whereIn('id', $request->permission)->pluck('id')->toArray();
                $invalidPermissions = array_diff($request->permission, $validPermissions);
                
                if (!empty($invalidPermissions)) {
                    Log::warning('Invalid permission IDs detected', [
                        'invalid_permissions' => $invalidPermissions,
                        'requested_permissions' => $request->permission
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Permission dengan ID ' . implode(', ', $invalidPermissions) . ' tidak ditemukan di tabel permissions.',
                    ], 400);
                }
            }

            // Log untuk debugging
            Log::info('Creating/updating role', [
                'name' => $request->name,
                'permissions' => $request->permission ?? []
            ]);

            $role = Role::updateOrCreate(
                ['name' => $request->name, 'guard_name' => 'web'],
                ['name' => $request->name]
            );

            // PERBAIKAN: Sync permissions dengan validasi tambahan
            if ($request->has('permission') && !empty($request->permission)) {
                // Double check permissions masih valid sebelum sync
                $permissionNames = Permission::whereIn('id', $request->permission)
                    ->pluck('name')
                    ->toArray();
                
                if (count($permissionNames) !== count($request->permission)) {
                    Log::warning('Permission count mismatch', [
                        'requested_count' => count($request->permission),
                        'found_count' => count($permissionNames),
                        'requested_ids' => $request->permission,
                        'found_names' => $permissionNames
                    ]);
                }
                
                Log::info('Syncing permissions', [
                    'role_id' => $role->id,
                    'permission_names' => $permissionNames
                ]);

                $role->syncPermissions($permissionNames);
            } else {
                // Jika tidak ada permission, hapus semua permission dari role
                $role->syncPermissions([]);
                Log::info('Cleared all permissions for role', ['role_id' => $role->id]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing role', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $role = Role::findOrFail($id);
            
            // PERBAIKAN: Gunakan relasi Laravel untuk lebih aman
            $rolePermissions = $role->permissions()->pluck('permissions.id')->toArray();

            return response()->json([
                'success' => true,
                'role' => $role,
                'permissions' => $rolePermissions,
            ]);
        } catch (\Exception $e) {
            Log::error('Error editing role', [
                'role_id' => $id,
                'message' => $e->getMessage()
            ]);
            
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

            Log::info('Deleting role', [
                'role_id' => $role->id,
                'role_name' => $role->name
            ]);

            // PERBAIKAN: Gunakan transaction untuk keamanan
            DB::transaction(function () use ($role) {
                $role->permissions()->detach();
                $role->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Role berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting role', [
                'role_id' => $request->id,
                'message' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus role: ' . $e->getMessage(),
            ], 500);
        }
    }
}