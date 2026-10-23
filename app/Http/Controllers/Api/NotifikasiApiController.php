<?php

namespace App\Http\Controllers\Api;

use App\Models\Anggota;
use App\Models\SystemNotification;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotifikasiApiController extends BaseApiController
{
    /**
     * Mendapatkan daftar notifikasi untuk pengguna yang sedang aktif (GET /api/v1/notifikasi).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = min((int) $request->get('per_page', 25), 50);

        $query = SystemNotification::forCurrentUser($user)
            ->orderBy('id', 'desc');

        if ($request->get('filter') === 'unread') {
            $query->unread();
        }

        $notifications = $query->paginate($perPage);

        $unreadCount = SystemNotification::forCurrentUser($user)->unread()->count();

        $formatted = $notifications->getCollection()->map(function ($item) {
            return [
                'id'           => $item->id,
                'title'        => $item->title,
                'message'      => $item->message,
                'type'         => $item->type,
                'role_target'  => $item->role_target,
                'reference_id' => $item->reference_id,
                'url'          => $item->url,
                'data'         => $item->data,
                'is_read'      => (bool) $item->is_read,
                'read_at'      => $item->read_at ? $item->read_at->toIso8601String() : null,
                'created_at'   => $item->created_at ? $item->created_at->toIso8601String() : null,
                'time_ago'     => $item->created_at ? $item->created_at->diffForHumans() : '-',
            ];
        });

        return $this->successResponse([
            'unread_count' => $unreadCount,
            'items'        => $formatted,
            'meta'         => [
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'per_page'     => $notifications->perPage(),
                'total'        => $notifications->total(),
            ],
        ], 'Daftar notifikasi berhasil diambil.');
    }

    /**
     * Mendapatkan jumlah notifikasi yang belum dibaca (GET /api/v1/notifikasi/unread-count).
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = $request->user();
        $unreadCount = SystemNotification::forCurrentUser($user)->unread()->count();

        return $this->successResponse([
            'unread_count' => $unreadCount,
        ], 'Jumlah notifikasi unread berhasil diambil.');
    }

    /**
     * Menandai satu notifikasi sudah dibaca (POST /api/v1/notifikasi/{id}/read).
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $notif = SystemNotification::forCurrentUser($user)->find($id);

        if (!$notif) {
            return $this->errorResponse('Notifikasi tidak ditemukan atau tidak berhak.', 404);
        }

        $notif->update([
            'is_read' => true,
            'read_at' => Carbon::now(),
        ]);

        $unreadCount = SystemNotification::forCurrentUser($user)->unread()->count();

        return $this->successResponse([
            'id'           => $notif->id,
            'is_read'      => true,
            'unread_count' => $unreadCount,
        ], 'Notifikasi telah ditandai sudah dibaca.');
    }

    /**
     * Menandai semua notifikasi pengguna sudah dibaca (POST /api/v1/notifikasi/read-all).
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();
        SystemNotification::forCurrentUser($user)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => Carbon::now(),
            ]);

        return $this->successResponse([
            'unread_count' => 0,
        ], 'Semua notifikasi telah ditandai sudah dibaca.');
    }

    /**
     * Mendaftarkan / memperbarui FCM device token dari aplikasi Flutter mobile (POST /api/v1/auth/fcm-token).
     */
    public function updateFcmToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $user = $request->user();
        $user->update([
            'fcm_token' => $request->fcm_token,
        ]);

        return $this->successResponse([
            'user_id'   => $user->id,
            'fcm_token' => $user->fcm_token,
        ], 'FCM Token perangkat berhasil diperbarui.');
    }

    /**
     * Tombol Permintaan Verifikasi di Menu Klinik (POST /api/v1/anggota/request-verifikasi).
     * Digunakan oleh ~1000 klinik yang belum diverifikasi agar segera diverifikasi oleh Pengurus Cabang.
     */
    public function requestVerifikasiKlinik(Request $request): JsonResponse
    {
        $user = $request->user();
        $anggota = $user->anggota;

        if (!$anggota) {
            // Coba cari anggota berdasarkan user id atau email
            $anggota = Anggota::where('id_user', $user->id)
                ->orWhere('email', $user->email)
                ->first();
        }

        if (!$anggota) {
            return $this->errorResponse('Data faskes klinik Anda tidak ditemukan.', 404);
        }

        // Jika status sudah approved, tidak perlu minta verifikasi lagi
        if (strtolower($anggota->status) === 'approved') {
            return $this->errorResponse('Klinik Anda sudah terverifikasi dan berstatus Anggota Resmi.', 400);
        }

        $catatan = $request->input('catatan', 'Permintaan verifikasi berkas diajukan melalui aplikasi ASKLIN Mobile.');

        // Update status pendaftaran menjadi 'waiting' (Menunggu Verifikasi)
        $anggota->update([
            'status'     => 'waiting',
            'keterangan' => $catatan,
            'updated_by' => $user->id,
        ]);

        // Kirim Notifikasi Firebase & Simpan ke System Notification untuk Pengurus Cabang
        FirebaseNotificationService::notifyClinicRequestedVerification($anggota, $catatan);

        return $this->successResponse([
            'id'             => $anggota->id,
            'nama_klinik'    => $anggota->nama_klinik,
            'status'         => $anggota->status,
            'keterangan'     => $anggota->keterangan,
            'requested_at'   => Carbon::now()->toIso8601String(),
        ], 'Permintaan verifikasi telah dikirimkan ke Pengurus Cabang. Anda akan menerima notifikasi setelah Cabang memeriksa berkas.');
    }

    /**
     * Tombol Permintaan Verifikasi di Login Cabang untuk diverifikasi Ketum atau Sekjen (POST /api/v1/pengurus/request-verifikasi-pusat/{id}).
     */
    public function requestVerifikasiPusat(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        // Validasi hak akses (Pengurus Cabang / Daerah / Admin)
        if (!$user->hasAnyRole(['Admin Cabang', 'Admin Daerah', 'Superadmin', 'Admin Pusat'])) {
            return $this->errorResponse('Hanya Pengurus Cabang/Daerah yang dapat mengajukan permintaan verifikasi ke Ketum atau Sekjen.', 403);
        }

        $anggota = Anggota::find($id);
        if (!$anggota) {
            return $this->errorResponse('Data anggota faskes tidak ditemukan.', 404);
        }

        // Cek batasan wilayah jika admin cabang
        if ($user->hasRole('Admin Cabang') && !empty($user->kota) && (string)$anggota->id_kota !== (string)$user->kota) {
            return $this->errorResponse('Klinik ini di luar wilayah kewenangan Cabang Anda.', 403);
        }

        $catatan = $request->input('catatan', 'Pengurus Cabang merekomendasikan dan mengajukan verifikasi pengesahan ke Ketum & Sekjen.');

        // Perbarui status menjadi Verifikasi Sekjen
        $anggota->update([
            'status'            => 'Verifikasi Sekjen',
            'keterangan'        => $catatan,
            'verifikasi_cabang' => Carbon::now(),
            'updated_by'        => $user->id,
        ]);

        // Kirim Notifikasi Firebase & Simpan Notifikasi untuk Ketum & Sekjen
        FirebaseNotificationService::notifyCabangRequestedPusatVerification($anggota, $user, $catatan);

        return $this->successResponse([
            'id'          => $anggota->id,
            'nama_klinik' => $anggota->nama_klinik,
            'status'      => $anggota->status,
            'keterangan'  => $anggota->keterangan,
            'pengaju'     => $user->name,
        ], 'Permintaan verifikasi pengesahan telah diteruskan ke Ketua Umum & Sekjen Pusat.');
    }
}
