<?php

namespace App\Mail;

use App\Models\Anggota;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnggotaRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $anggota;

    public function __construct(Anggota $anggota)
    {
        $this->anggota = $anggota;
    }

    public function build()
    {
        return $this->from(config('mail.from.address', 'pp_asklin@asklin.org'), 'Pengurus Pusat ASKLIN')
            ->subject("Konfirmasi Pendaftaran Anggota ASKLIN: {$this->anggota->nama_klinik}")
            ->view('emails.anggota_registered');
    }
}
