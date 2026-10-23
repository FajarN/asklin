<?php

namespace App\Services;

use App\Models\Anggota;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    /**
     * URL FCM HTTP Legacy Endpoint
     */
    protected const FCM_LEGACY_URL = 'https://fcm.googleapis.com/fcm/send';

    /**
     * Mendapatkan Server Key Firebase dari .env atau config
     */
    protected static function getServerKey(): ?string
    {
        return config('services.firebase.server_key') ?: env('FIREBASE_SERVER_KEY');
    }

    /**
     * Kirim notifikasi push FCM ke satu atau beberapa device token.
     *
     * @param string|array $tokens Token FCM perangkat
     * @param string $title Judul notifikasi
     * @param string $body Isi pesan notifikasi
     * @param array $data Payload data tambahan (misal: screen, anggota_id)
     * @return array Hasil pengiriman
     */
    public static function sendToTokens($tokens, string $title, string $body, array $data = []): array
    {
        $serverKey = self::getServerKey();
        $targetTokens = is_array($tokens) ? array_values(array_filter($tokens)) : [$tokens];

        if (empty($targetTokens)) {
            return ['success' => false, 'message' => 'No target device tokens available'];
        }

        if (empty($serverKey)) {
            Log::info("[FCM Simulation] Server Key not set. Simulated notification: '{$title}' - '{$body}' to " . count($targetTokens) . " tokens.");
            return ['success' => true, 'simulated' => true, 'message' => 'Firebase Server Key not configured, simulated'];
        }

        try {
            $payload = [
                'registration_ids' => $targetTokens,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                    'sound' => 'default',
                    'icon'  => 'ic_launcher',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ],
                'data' => array_merge($data, [
                    'title'     => $title,
                    'body'      => $body,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ]),
                'priority' => 'high',
            ];

            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type'  => 'application/json',
            ])->timeout(10)->post(self::FCM_LEGACY_URL, $payload);

            Log::info("[FCM Push Result] HTTP {$response->status()}: " . $response->body());

            return [
                'success' => $response->successful(),
                'status'  => $response->status(),
                'response'=> $response->json(),
            ];
        } catch (\Throwable $e) {
            Log::error("[FCM Push Error] Failed to send push: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Kirim notifikasi push FCM ke Topic (misal: asklin_cabang_3216, asklin_pusat, dll)
     *
     * @param string $topic Nama topik FCM
     * @param string $title Judul
     * @param string $body Pesan
     * @param array $data Payload
     * @return array
     */
    public static function sendToTopic(string $topic, string $title, string $body, array $data = []): array
    {
        $serverKey = self::getServerKey();
        $topicPath = str_starts_with($topic, '/topics/') ? $topic : ('/topics/' . $topic);

        if (empty($serverKey)) {
            Log::info("[FCM Topic Simulation] Topic {$topicPath}: '{$title}' - '{$body}'");
            return ['success' => true, 'simulated' => true, 'message' => 'Topic notification simulated'];
        }

        try {
            $payload = [
                'to' => $topicPath,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                    'sound' => 'default',
                    'icon'  => 'ic_launcher',
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ],
                'data' => array_merge($data, [
                    'title' => $title,
                    'body'  => $body,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ]),
                'priority' => 'high',
            ];

            $response = Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type'  => 'application/json',
            ])->timeout(10)->post(self::FCM_LEGACY_URL, $payload);

            return [
                'success' => $response->successful(),
                'status'  => $response->status(),
                'response'=> $response->json(),
            ];
        } catch (\Throwable $e) {
            Log::error("[FCM Topic Error] Failed to send to topic {$topicPath}: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // =========================================================================
    // SPESIFIK WORKFLOW NOTIFIKASI SESUAI KEBUTUHAN ASKLIN MOBILE
    // =========================================================================

    /**
     * 1. Ketika anggota baru daftar -> Notifikasi ke Pengurus Cabang
     */
    public static function notifyNewMemberRegistration(Anggota $anggota): void
    {
        $title = 'Pendaftaran Anggota Baru';
        $body = "Klinik {$anggota->nama_klinik} baru saja mendaftar di wilayah Anda. Segera lakukan verifikasi.";

        // Catat ke system_notifications untuk Admin Cabang di kota tersebut
        SystemNotification::create([
            'role_target'  => 'Admin Cabang',
            'id_kota'      => $anggota->id_kota,
            'id_provinsi'  => $anggota->id_provinsi,
            'title'        => $title,
            'message'      => $body,
            'type'         => 'anggota_baru',
            'reference_id' => $anggota->id,
            'url'          => '/pengurus/anggota/' . $anggota->id,
            'data'         => [
                'screen'     => 'verifikasi_detail',
                'anggota_id' => $anggota->id,
                'event'      => 'register_anggota_baru',
            ],
            'is_read'      => false,
        ]);

        // Cari token FCM pengurus cabang di kota tersebut
        $tokens = User::role('Admin Cabang')
            ->where(function ($q) use ($anggota) {
                $q->where('kota', $anggota->id_kota)
                  ->orWhereNull('kota');
            })
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();

        self::sendToTokens($tokens, $title, $body, [
            'screen'     => 'verifikasi_detail',
            'anggota_id' => (string) $anggota->id,
            'type'       => 'anggota_baru',
        ]);

        // Kirim juga ke topic wilayah cabang
        if (!empty($anggota->id_kota)) {
            self::sendToTopic('asklin_cabang_' . $anggota->id_kota, $title, $body, [
                'screen'     => 'verifikasi_detail',
                'anggota_id' => (string) $anggota->id,
            ]);
        }
    }

    /**
     * 2. Ketika klinik meminta verifikasi ke Cabang (Tombol di menu klinik)
     */
    public static function notifyClinicRequestedVerification(Anggota $anggota, ?string $catatan = null): void
    {
        $title = 'Permintaan Verifikasi Klinik';
        $body = "Klinik {$anggota->nama_klinik} mengajukan permohonan verifikasi ke Pengurus Cabang." .
            ($catatan ? " Catatan: {$catatan}" : "");

        SystemNotification::create([
            'role_target'  => 'Admin Cabang',
            'id_kota'      => $anggota->id_kota,
            'id_provinsi'  => $anggota->id_provinsi,
            'title'        => $title,
            'message'      => $body,
            'type'         => 'request_verifikasi_cabang',
            'reference_id' => $anggota->id,
            'url'          => '/pengurus/anggota/' . $anggota->id,
            'data'         => [
                'screen'     => 'verifikasi_detail',
                'anggota_id' => $anggota->id,
                'catatan'    => $catatan,
            ],
            'is_read'      => false,
        ]);

        $tokens = User::role('Admin Cabang')
            ->where(function ($q) use ($anggota) {
                $q->where('kota', $anggota->id_kota)
                  ->orWhereNull('kota');
            })
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();

        self::sendToTokens($tokens, $title, $body, [
            'screen'     => 'verifikasi_detail',
            'anggota_id' => (string) $anggota->id,
            'type'       => 'request_verifikasi_cabang',
        ]);

        if (!empty($anggota->id_kota)) {
            self::sendToTopic('asklin_cabang_' . $anggota->id_kota, $title, $body, [
                'screen'     => 'verifikasi_detail',
                'anggota_id' => (string) $anggota->id,
            ]);
        }
    }

    /**
     * 3. Ketika diverifikasi oleh Cabang -> Notifikasi ke Ketum atau Sekjen
     */
    public static function notifyCabangVerificationDone(Anggota $anggota, User $verifikator, ?string $catatan = null): void
    {
        $title = 'Verifikasi Cabang Selesai - Menunggu Pengesahan';
        $body = "Klinik {$anggota->nama_klinik} telah diverifikasi oleh Pengurus Cabang ({$verifikator->name}) dan siap disahkan oleh Ketum / Sekjen." .
            ($catatan ? " Catatan: {$catatan}" : "");

        // Catat notifikasi untuk Sekjen
        SystemNotification::create([
            'role_target'  => 'Sekjen',
            'title'        => $title,
            'message'      => $body,
            'type'         => 'verifikasi_cabang',
            'reference_id' => $anggota->id,
            'url'          => '/pengurus/anggota/' . $anggota->id,
            'data'         => [
                'screen'      => 'verifikasi_detail',
                'anggota_id'  => $anggota->id,
                'verifikator' => $verifikator->name,
            ],
            'is_read'      => false,
        ]);

        // Catat notifikasi untuk Ketua Umum
        SystemNotification::create([
            'role_target'  => 'Ketua Umum',
            'title'        => $title,
            'message'      => $body,
            'type'         => 'verifikasi_cabang',
            'reference_id' => $anggota->id,
            'url'          => '/pengurus/anggota/' . $anggota->id,
            'data'         => [
                'screen'      => 'verifikasi_detail',
                'anggota_id'  => $anggota->id,
                'verifikator' => $verifikator->name,
            ],
            'is_read'      => false,
        ]);

        // Cari token FCM Ketum & Sekjen & Superadmin
        $tokens = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Sekjen', 'Ketua Umum', 'Admin Pusat', 'Superadmin']);
        })
        ->whereNotNull('fcm_token')
        ->pluck('fcm_token')
        ->toArray();

        self::sendToTokens($tokens, $title, $body, [
            'screen'     => 'verifikasi_detail',
            'anggota_id' => (string) $anggota->id,
            'type'       => 'verifikasi_cabang',
        ]);

        // Kirim juga ke topic pusat
        self::sendToTopic('asklin_ketum_sekjen', $title, $body, [
            'screen'     => 'verifikasi_detail',
            'anggota_id' => (string) $anggota->id,
        ]);
    }

    /**
     * 4. Tombol permintaan verifikasi di login cabang untuk diverifikasi Ketum atau Sekjen
     */
    public static function notifyCabangRequestedPusatVerification(Anggota $anggota, User $cabangUser, ?string $catatan = null): void
    {
        $title = 'Permintaan Verifikasi Ketum & Sekjen';
        $body = "Pengurus Cabang ({$cabangUser->name}) meminta verifikasi pengesahan akhir untuk Klinik {$anggota->nama_klinik} oleh Ketum / Sekjen." .
            ($catatan ? " Catatan: {$catatan}" : "");

        SystemNotification::create([
            'role_target'  => 'Sekjen',
            'title'        => $title,
            'message'      => $body,
            'type'         => 'request_verifikasi_pusat',
            'reference_id' => $anggota->id,
            'url'          => '/pengurus/anggota/' . $anggota->id,
            'data'         => [
                'screen'     => 'verifikasi_detail',
                'anggota_id' => $anggota->id,
                'catatan'    => $catatan,
                'pengaju'    => $cabangUser->name,
            ],
            'is_read'      => false,
        ]);

        SystemNotification::create([
            'role_target'  => 'Ketua Umum',
            'title'        => $title,
            'message'      => $body,
            'type'         => 'request_verifikasi_pusat',
            'reference_id' => $anggota->id,
            'url'          => '/pengurus/anggota/' . $anggota->id,
            'data'         => [
                'screen'     => 'verifikasi_detail',
                'anggota_id' => $anggota->id,
                'catatan'    => $catatan,
                'pengaju'    => $cabangUser->name,
            ],
            'is_read'      => false,
        ]);

        $tokens = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Sekjen', 'Ketua Umum', 'Admin Pusat', 'Superadmin']);
        })
        ->whereNotNull('fcm_token')
        ->pluck('fcm_token')
        ->toArray();

        self::sendToTokens($tokens, $title, $body, [
            'screen'     => 'verifikasi_detail',
            'anggota_id' => (string) $anggota->id,
            'type'       => 'request_verifikasi_pusat',
        ]);

        self::sendToTopic('asklin_ketum_sekjen', $title, $body, [
            'screen'     => 'verifikasi_detail',
            'anggota_id' => (string) $anggota->id,
        ]);
    }

    /**
     * 5. Ketika disahkan oleh Ketum / Sekjen -> Notifikasi ke Klinik dan Cabang
     */
    public static function notifyClinicApproved(Anggota $anggota, User $verifikator): void
    {
        $title = 'Selamat! Faskes Anda Telah Disahkan ASKLIN';
        $body = "Klinik {$anggota->nama_klinik} telah resmi disahkan oleh Pengurus Pusat dengan Nomor Anggota: {$anggota->no_anggota}. SK dan Sertifikat dapat diunduh sekarang.";

        // Notifikasi untuk user klinik
        if ($anggota->id_user) {
            SystemNotification::create([
                'user_id'      => $anggota->id_user,
                'role_target'  => 'Klinik',
                'title'        => $title,
                'message'      => $body,
                'type'         => 'approved',
                'reference_id' => $anggota->id,
                'url'          => '/anggota/status-keanggotaan',
                'data'         => [
                    'screen'     => 'keanggotaan',
                    'no_anggota' => $anggota->no_anggota,
                ],
                'is_read'      => false,
            ]);

            $userKlinik = User::find($anggota->id_user);
            if ($userKlinik && !empty($userKlinik->fcm_token)) {
                self::sendToTokens($userKlinik->fcm_token, $title, $body, [
                    'screen'     => 'keanggotaan',
                    'anggota_id' => (string) $anggota->id,
                ]);
            }
        }

        // Notifikasi tembusan ke Admin Cabang
        $cabangMsg = "Klinik {$anggota->nama_klinik} di wilayah Anda telah resmi disahkan oleh Pusat ({$verifikator->name}) dengan No. Anggota: {$anggota->no_anggota}.";
        SystemNotification::create([
            'role_target'  => 'Admin Cabang',
            'id_kota'      => $anggota->id_kota,
            'title'        => 'Klinik Resmi Disahkan Pusat',
            'message'      => $cabangMsg,
            'type'         => 'approved',
            'reference_id' => $anggota->id,
            'url'          => '/pengurus/anggota/' . $anggota->id,
            'is_read'      => false,
        ]);
    }

    /**
     * 6. Ketika diminta perbaikan dokumen -> Notifikasi ke Klinik
     */
    public static function notifyPerbaikanDokumen(Anggota $anggota, User $verifikator, string $catatan): void
    {
        $title = 'Perlu Perbaikan Dokumen Pendaftaran';
        $body = "Pengurus ({$verifikator->name}) mengembalikan formulir pendaftaran klinik Anda untuk perbaikan: {$catatan}";

        if ($anggota->id_user) {
            SystemNotification::create([
                'user_id'      => $anggota->id_user,
                'role_target'  => 'Klinik',
                'title'        => $title,
                'message'      => $body,
                'type'         => 'perlu_perbaikan',
                'reference_id' => $anggota->id,
                'url'          => '/anggota/pendaftaran',
                'data'         => [
                    'screen'  => 'pendaftaran',
                    'catatan' => $catatan,
                ],
                'is_read'      => false,
            ]);

            $userKlinik = User::find($anggota->id_user);
            if ($userKlinik && !empty($userKlinik->fcm_token)) {
                self::sendToTokens($userKlinik->fcm_token, $title, $body, [
                    'screen' => 'pendaftaran',
                ]);
            }
        }
    }
}
