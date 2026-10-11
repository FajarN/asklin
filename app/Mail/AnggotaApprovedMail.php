<?php

namespace App\Mail;

use App\Models\Anggota;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AnggotaApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $anggota;

    public function __construct(Anggota $anggota)
    {
        $this->anggota = $anggota;
    }

    public function build()
    {
        return $this->from(config('mail.from.address', 'pp_asklin@asklin.org'), config('mail.from.name', 'Pengurus Pusat ASKLIN'))
            ->subject("Selamat! Keanggotaan ASKLIN Resmi Disetujui: {$this->anggota->nama_klinik} (#{$this->anggota->no_anggota})")
            ->view('emails.anggota_approved');
    }
}
