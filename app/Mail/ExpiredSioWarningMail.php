<?php

namespace App\Mail;

use App\Models\Anggota;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExpiredSioWarningMail extends Mailable
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
            ->subject("[Penting] Pemberitahuan Masa Berlaku Izin Operasional (SIO): {$this->anggota->nama_klinik}")
            ->view('emails.expired_sio_warning');
    }
}
