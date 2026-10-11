<?php

namespace App\Mail;

use App\Models\SkPengurus;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SkApprovalRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sk;
    public $recipientTitle;
    public $stepTitle;
    public $approvalUrl;
    public $timelineUrl;
    public $tokenExpiresAt;

    public function __construct(SkPengurus $sk, string $recipientTitle, string $stepTitle, string $approvalUrl, string $timelineUrl, $tokenExpiresAt)
    {
        $this->sk = $sk;
        $this->recipientTitle = $recipientTitle;
        $this->stepTitle = $stepTitle;
        $this->approvalUrl = $approvalUrl;
        $this->timelineUrl = $timelineUrl;
        $this->tokenExpiresAt = $tokenExpiresAt;
    }

    public function build()
    {
        $strukturNama = $this->sk->strukturOrganisasi->nama_struktur ?? 'Struktur Organisasi';

        return $this->from(config('mail.from.address', 'pp_asklin@asklin.org'), 'Pengurus Pusat ASKLIN')
            ->subject("[Persetujuan SK] Permohonan Pengesahan SK: {$strukturNama}")
            ->view('emails.sk_approval_request');
    }
}
