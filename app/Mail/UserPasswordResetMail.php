<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $newPassword;
    public $klinikName;
    public $noAnggota;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $newPassword
     * @param string|null $klinikName
     * @param string|null $noAnggota
     */
    public function __construct(User $user, string $newPassword, ?string $klinikName = null, ?string $noAnggota = null)
    {
        $this->user = $user;
        $this->newPassword = $newPassword;
        $this->klinikName = $klinikName;
        $this->noAnggota = $noAnggota;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->klinikName 
            ? "Pemberitahuan Pembaruan Akses Akun ASKLIN: {$this->klinikName}" 
            : "Pemberitahuan Pembaruan Akses Akun Sistem ASKLIN";

        return $this->from(config('mail.from.address', 'pp_asklin@asklin.org'), 'Pengurus Pusat ASKLIN')
            ->subject($subject)
            ->view('emails.user_password_reset');
    }
}

