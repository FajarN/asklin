<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;

class AuthController extends BaseApiController
{
    /**
     * Registrasi pengguna baru (Klinik) & penerbitan bearer token Sanctum.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'provinsi'  => $request->provinsi,
                'kota'      => $request->kota,
            ]);

            $user->assignRole('Klinik');

            // Jika informasi klinik atau kontak disertakan, inisialisasi record Anggota
            if ($request->filled('nama_klinik') || $request->filled('phone')) {
                Anggota::create([
                    'nama_klinik'       => $request->nama_klinik ?: ('Klinik ' . $request->name),
                    'nama_kontak'       => $request->name,
                    'email'             => $request->email,
                    'tlf'               => $request->phone,
                    'id_provinsi'       => $request->provinsi,
                    'id_kota'           => $request->kota,
                    'status'            => 'Draft',
                    'status_pembayaran' => '0',
                    'id_user'           => $user->id,
                    'created_by'        => $user->id,
                    'updated_by'        => $user->id,
                ]);
            }

            $deviceName = $request->device_name ?: 'asklin-mobile-app';
            $token = $user->createToken($deviceName)->plainTextToken;
            $userData = $this->formatUserData($user);

            return $this->successResponse([
                'token'      => $token,
                'token_type' => 'Bearer',
                'user'       => $userData,
            ], 'Registrasi berhasil. Akun Anda telah siap digunakan.', 201);
        });
    }

    /**
     * Login kredensial & penerbitan bearer token Sanctum.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Kombinasi email dan password tidak sesuai.', 401);
        }

        $deviceName = $request->device_name ?: 'asklin-mobile-app';
        $token = $user->createToken($deviceName)->plainTextToken;

        $userData = $this->formatUserData($user);

        return $this->successResponse([
            'token' => $token,
            'token_type' => 'Bearer',
            'user'  => $userData,
        ], 'Login berhasil.');
    }

    /**
     * Mendapatkan profil pengguna terautentikasi (GET /api/v1/auth/me).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $userData = $this->formatUserData($user);

        return $this->successResponse($userData, 'Data pengguna berhasil diambil.');
    }

    /**
     * Pencabutan token aktif (POST /api/v1/auth/logout).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        return $this->successResponse(null, 'Logout berhasil dan sesi token telah dicabut.');
    }

    /**
     * Memperbarui kata sandi pengguna (POST /api/v1/auth/change-password).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password'      => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required'         => 'Password baru wajib diisi.',
            'password.min'              => 'Password baru minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->errorResponse('Password saat ini tidak sesuai.', 422, [
                'current_password' => ['Password saat ini yang Anda masukkan salah.']
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return $this->successResponse(null, 'Password Anda berhasil diperbarui.');
    }

    /**
     * Format representasi data pengguna untuk mobile app.
     *
     * @param User $user
     * @return array
     */
    private function formatUserData(User $user): array
    {
        $roles = $user->getRoleNames()->values()->toArray();
        $primaryRole = !empty($roles) ? $roles[0] : 'Klinik';

        // Ambil nama wilayah jika kode tersedia
        $provinsiName = null;
        if (!empty($user->provinsi)) {
            $prov = Province::where('code', $user->provinsi)->first();
            $provinsiName = $prov ? $prov->name : null;
        }

        $kotaName = null;
        if (!empty($user->kota)) {
            $city = City::where('code', $user->kota)->first();
            $kotaName = $city ? $city->name : null;
        }

        $formatted = [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'primary_role' => $primaryRole,
            'roles'        => $roles,
            'wilayah'      => [
                'provinsi_code' => $user->provinsi,
                'provinsi_name' => $provinsiName,
                'kota_code'     => $user->kota,
                'kota_name'     => $kotaName,
                'kecamatan_code'=> $user->kecamatan,
                'kelurahan_code'=> $user->kelurahan,
            ],
            'anggota'      => null,
        ];

        // Jika user adalah klinik atau memiliki data anggota terikat
        $anggota = Anggota::where('id_user', $user->id)->first();
        if ($anggota) {
            $formatted['anggota'] = [
                'id'                => $anggota->id,
                'no_anggota'        => $anggota->no_anggota,
                'nama_klinik'       => $anggota->nama_klinik,
                'status'            => $anggota->status,
                'jenis_klinik'      => $anggota->jenis_klinik,
                'status_pembayaran' => (string) $anggota->status_pembayaran,
                'is_approved'       => strtolower($anggota->status) === 'approved',
                'is_lunas'          => (string) $anggota->status_pembayaran === '1',
            ];
        }

        return $formatted;
    }
}

