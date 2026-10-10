<?php

namespace App\Services;

use App\Models\SystemNotification;
use App\Models\User;

class NotificationService
{
    /**
     * Kirim notifikasi sistem terpusat
     */
    public static function send(array $data)
    {
        return SystemNotification::create([
            'user_id' => $data['user_id'] ?? null,
            'role_target' => $data['role_target'] ?? null,
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'] ?? 'general',
            'reference_id' => $data['reference_id'] ?? null,
            'url' => $data['url'] ?? null,
            'is_read' => false,
        ]);
    }

    /**
     * Notifikasi khusus SK yang butuh tindakan approve
     */
    public static function notifySkActionRequired($sk, $stepName, $targetRole, $message = null)
    {
        $strukturNama = $sk->strukturOrganisasi->nama_struktur ?? 'Struktur Organisasi';
        $defaultMsg = "Struktur kepengurusan {$strukturNama} memerlukan verifikasi & persetujuan pada tahap: {$stepName}.";

        return self::send([
            'role_target' => $targetRole,
            'title' => "Tindakan Diperlukan: Persetujuan SK ({$stepName})",
            'message' => $message ?: $defaultMsg,
            'type' => 'sk_pengurus',
            'reference_id' => $sk->id,
            'url' => route('sk_pengurus.timeline', $sk->id),
        ]);
    }

    /**
     * Notifikasi ketika SK resmi telah diterbitkan
     */
    public static function notifySkIssued($sk)
    {
        $strukturNama = $sk->strukturOrganisasi->nama_struktur ?? 'Struktur Organisasi';

        return self::send([
            'role_target' => null, // Semua pihak terkait
            'title' => "SK Resmi Disahkan & Terbit",
            'message' => "Surat Keputusan (SK) untuk {$strukturNama} telah resmi diterbitkan oleh Ketua Umum ASKLIN.",
            'type' => 'sk_pengurus',
            'reference_id' => $sk->id,
            'url' => route('sk_pengurus.timeline', $sk->id),
        ]);
    }
}
