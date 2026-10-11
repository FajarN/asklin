<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestSmtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $smtpDetails;

    /**
     * Create a new message instance.
     *
     * @param array $smtpDetails
     */
    public function __construct(array $smtpDetails = [])
    {
        $this->smtpDetails = $smtpDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fromAddress = $this->smtpDetails['from_address'] ?? config('mail.from.address', 'pp_asklin@asklin.org');
        $fromName = $this->smtpDetails['from_name'] ?? config('mail.from.name', 'Pengurus Pusat ASKLIN');

        return $this->from($fromAddress, $fromName)
            ->subject('Pengujian Konfigurasi Email SMTP SIMAK ASKLIN [BERHASIL]')
            ->view('emails.test_smtp');
    }
}

