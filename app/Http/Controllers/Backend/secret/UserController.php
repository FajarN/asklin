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
use Illuminate\Support\Facades\Mail;
use App\Mail\UserPasswordResetMail;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:secret', ['only' => ['index','store', 'create', 'edit', 'destroy', 'resetPassword']]);
    }

    public function index()
    {
        $role = Role::all();

        return view('backend.secret.users.index',compact('role'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = User::select(
                'users.*',
                'b.name as kota',
                'c.name as provinsi',
                'anggota.nama_klinik as relasi_klinik',
                'anggota.no_anggota as relasi_no_anggota',
                DB::raw('(SELECT GROUP_CONCAT(r.name SEPARATOR ", ") FROM model_has_roles mhr JOIN roles r ON r.id = mhr.role_id WHERE mhr.model_id = users.id AND mhr.model_type = "App\\\\Models\\\\User") as role_names')
            )
                ->leftJoin('indonesia_cities as b', 'b.code', '=', 'users.kota')
                ->leftJoin('indonesia_provinces as c', 'c.code', '=', 'users.provinsi')
                ->leftJoin('anggota', 'anggota.id_user', '=', 'users.id');

            return Datatables::of($query)
                ->filter(function ($query) use ($request) {
                    $search = is_array($request->get('search'))
                        ? ($request->input('search.value') ?? '')
                        : (string) $request->get('search');
                    $search = trim($search);

                    if (!empty($search)) {
                        $query->where(function ($q) use ($search) {
                            $q->where('users.name', 'like', "%{$search}%")
                                ->orWhere('users.email', 'like', "%{$search}%")
                                ->orWhere('b.name', 'like', "%{$search}%")
                                ->orWhere('c.name', 'like', "%{$search}%")
                                ->orWhere('anggota.nama_klinik', 'like', "%{$search}%")
                                ->orWhere('anggota.no_anggota', 'like', "%{$search}%");
                        });
                    }

                    if ($roleName = $request->get('role_names')) {
                        $query->whereExists(function ($sub) use ($roleName) {
                            $sub->select(DB::raw(1))
                                ->from('model_has_roles')
                                ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                                ->whereColumn('model_has_roles.model_id', 'users.id')
                                ->where('model_has_roles.model_type', 'App\\Models\\User')
                                ->where('roles.name', $roleName);
                        });
                    }

                    if ($statusRelasi = $request->get('status_relasi')) {
                        if ($statusRelasi === 'linked') {
                            $query->whereNotNull('anggota.id');
                        } elseif ($statusRelasi === 'unlinked') {
                            $query->whereNull('anggota.id');
                        }
                    }
                })
                ->addColumn('status_relasi', function ($row) {
                    if ($row->relasi_klinik) {
                        return '<span class="badge badge-success text-[11px]" title="Terhubung ke: ' . e($row->relasi_klinik) . '"><i class="fas fa-check-circle mr-1"></i> ' . e(Str::limit($row->relasi_klinik, 20)) . '</span>';
                    }
                    return '<span class="badge badge-secondary text-[11px]"><i class="fas fa-unlink mr-1"></i> Tidak Berelasi</span>';
                })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $nameEscaped = htmlspecialchars($data["name"], ENT_QUOTES);
                    $emailEscaped = htmlspecialchars($data["email"], ENT_QUOTES);
                    $klinikEscaped = htmlspecialchars($data["relasi_klinik"] ?? '', ENT_QUOTES);
                    $actionBtn = '
                        <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                            <a href="' . route("users.edit", $data["id"]) . '" class="dropdown-item has-icon"><i class="fas fa-edit"></i> Edit</a>
                            <a href="javascript:void(0)" onclick="openResetPasswordModal(' . $data["id"] . ', \'' . $nameEscaped . '\', \'' . $emailEscaped . '\', \'' . $klinikEscaped . '\')" class="dropdown-item has-icon text-warning"><i class="fas fa-key"></i> Reset Password (8 Digit)</a>
                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0)" onclick="deleteu(' . $data["id"] . ')" class="dropdown-item has-icon text-danger" ><i class="fas fa-trash-alt"></i>Hapus</a>
                        </div>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'status_relasi'])
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

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Ambil custom password dari request atau generate acak 8 karakter
        $newPassword = $request->input('password');
        if (empty($newPassword) || strlen($newPassword) !== 8) {
            // Karakter alfanumerik yang mudah dibaca (tanpa karakter ambigu)
            $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
            $shuffled = str_shuffle(str_repeat($chars, 3));
            $newPassword = substr($shuffled, 0, 8);
        }

        // Simpan hash password baru ke database
        $user->password = Hash::make($newPassword);
        $user->save();

        // Cari relasi klinik untuk disertakan di email
        $anggota = DB::table('anggota')->where('id_user', $user->id)->first();
        $klinikName = $anggota ? $anggota->nama_klinik : null;
        $noAnggota = $anggota ? $anggota->no_anggota : null;

        $emailSent = false;
        $emailError = null;

        try {
            Mail::to($user->email)->send(new UserPasswordResetMail($user, $newPassword, $klinikName, $noAnggota));
            $emailSent = true;
        } catch (\Throwable $e) {
            \Log::error("Gagal mengirim email reset password ke {$user->email}: " . $e->getMessage());
            $emailError = $e->getMessage();
        }

        return response()->json([
            'status' => 'success',
            'email_sent' => $emailSent,
            'email_error' => $emailError,
            'password' => $newPassword,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'klinik' => $klinikName,
                'no_anggota' => $noAnggota,
            ],
            'message' => $emailSent 
                ? 'Password berhasil di-reset menjadi 8 digit dan telah dikirimkan ke email ' . $user->email 
                : 'Password berhasil di-reset, namun pengiriman email mengalami kendala (' . $emailError . ').'
        ]);
    }
}