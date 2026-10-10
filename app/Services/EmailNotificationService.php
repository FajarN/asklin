<?php

namespace App\Services;

use App\Mail\AnggotaApprovedMail;
use App\Mail\AnggotaRegisteredMail;
use App\Mail\ExpiredSioWarningMail;
use App\Mail\SkApprovalRequestMail;
use App\Models\Anggota;
use App\Models\MasterKetuaUmum;
use App\Models\SkPengurus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailNotificationService
{
    /**
     * Dapatkan alamat email Sekjen dari database settings atau user ber-role Sekjen
     */
    public static function getEmailSekjen(): ?string
    {
        $settingEmail = DB::table('settings')->where('name', 'email_sekjen')->value('val');
        if (!empty($settingEmail)) {
            return $settingEmail;
        }

        $userSekjen = User::role('Sekjen')->first();
        if ($userSekjen && !empty($userSekjen->email)) {
            return $userSekjen->email;
        }

        return 'sekjen@asklin.org';
    }

    /**
     * Dapatkan alamat email Ketua Umum dari MasterKetuaUmum aktif, setting, atau user Ketum
     */
    public static function getEmailKetum(): ?string
    {
        $settingEmail = DB::table('settings')->where('name', 'email_ketum')->value('val');
        if (!empty($settingEmail)) {
            return $settingEmail;
        }

        $ketumAktif = MasterKetuaUmum::where('is_aktif', true)->first();
        if ($ketumAktif && !empty($ketumAktif->email)) {
            return $ketumAktif->email;
        }

        $userKetum = User::role('Ketua Umum')->first();
        if ($userKetum && !empty($userKetum->email)) {
            return $userKetum->email;
        }

        return 'ketum@asklin.org';
    }

    /**
     * Kirim email permohonan persetujuan SK dengan Token 7 Hari ke Sekjen atau Ketum
     */
    public static function sendSkApprovalRequest(SkPengurus $sk, string $roleTarget = 'sekjen')
    {
        try {
            // Generate token 7 hari unik
            $token = Str::random(48);
            $expiresAt = now()->addDays(7);

            $sk->update([
                'email_token' => $token,
                'email_token_role' => $roleTarget,
                'email_token_expires_at' => $expiresAt,
                'email_token_used_at' => null,
            ]);

            $recipientEmail = ($roleTarget === 'ketum') ? self::getEmailKetum() : self::getEmailSekjen();
            $recipientTitle = ($roleTarget === 'ketum') ? 'Ketua Umum PP ASKLIN' : 'Sekretaris Jenderal PP ASKLIN';
            $stepTitle = ($roleTarget === 'ketum') ? 'Tahap 4: Pengesahan & Tanda Tangan Ketum' : 'Tahap 3: Pemeriksaan & Penomoran Sekjen';

            $approvalUrl = route('sk_pengurus.approve-email', ['token' => $token]);
            $timelineUrl = route('sk_pengurus.timeline', ['id' => $sk->id]);

            Mail::to($recipientEmail)->send(new SkApprovalRequestMail(
                $sk,
                $recipientTitle,
                $stepTitle,
                $approvalUrl,
                $timelineUrl,
                $expiresAt
            ));

            return true;
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim email approval SK ke {$roleTarget}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim email konfirmasi registrasi anggota baru
     */
    public static function sendAnggotaRegistered(Anggota $anggota)
    {
        if (empty($anggota->email)) {
            return false;
        }

        try {
            Mail::to($anggota->email)->send(new AnggotaRegisteredMail($anggota));
            return true;
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim email registrasi anggota ({$anggota->id}): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim email pemberitahuan keanggotaan telah disetujui (Approved)
     */
    public static function sendAnggotaApproved(Anggota $anggota)
    {
        if (empty($anggota->email)) {
            return false;
        }

        try {
            Mail::to($anggota->email)->send(new AnggotaApprovedMail($anggota));
            return true;
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim email approval anggota ({$anggota->id}): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kirim email peringatan SIO habis ke klinik
     */
    public static function sendExpiredSioWarning(Anggota $anggota)
    {
        if (empty($anggota->email)) {
            return false;
        }

        try {
            Mail::to($anggota->email)->send(new ExpiredSioWarningMail($anggota));
            return true;
        } catch (\Throwable $e) {
            Log::warning("Gagal mengirim email peringatan SIO ({$anggota->id}): " . $e->getMessage());
            return false;
        }
    }
}
