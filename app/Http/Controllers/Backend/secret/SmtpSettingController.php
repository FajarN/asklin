<?php

namespace App\Http\Controllers\Backend\secret;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestSmtpMail;

class SmtpSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:secret');
    }

    /**
     * Tampilkan halaman pengaturan SMTP
     */
    public function index()
    {
        $smtp = [
            'mail_mailer' => setting('mail_mailer', config('mail.default', 'smtp')),
            'mail_host' => setting('mail_host', config('mail.mailers.smtp.host', '')),
            'mail_port' => setting('mail_port', config('mail.mailers.smtp.port', 587)),
            'mail_encryption' => setting('mail_encryption', config('mail.mailers.smtp.encryption', 'tls')),
            'mail_username' => setting('mail_username', config('mail.mailers.smtp.username', '')),
            'mail_password' => setting('mail_password', config('mail.mailers.smtp.password', '')),
            'mail_from_address' => setting('mail_from_address', config('mail.from.address', 'pp_asklin@asklin.org')),
            'mail_from_name' => setting('mail_from_name', config('mail.from.name', 'Pengurus Pusat ASKLIN')),
        ];

        return view('backend.secret.smtp_settings.index', compact('smtp'));
    }

    /**
     * Simpan pembaruan pengaturan SMTP ke database
     */
    public function update(Request $request)
    {
        $request->validate([
            'mail_host' => 'required|string',
            'mail_port' => 'required|numeric',
            'mail_encryption' => 'nullable|string',
            'mail_username' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        setting()->set('mail_mailer', 'smtp');
        setting()->set('mail_host', trim($request->mail_host));
        setting()->set('mail_port', (int) $request->mail_port);
        
        $encryption = $request->mail_encryption;
        if ($encryption === 'none' || $encryption === 'null' || empty($encryption)) {
            $encryption = '';
        }
        setting()->set('mail_encryption', $encryption);
        setting()->set('mail_username', trim($request->mail_username ?? ''));

        // Hanya ubah password jika diisi baru
        if ($request->filled('mail_password')) {
            setting()->set('mail_password', $request->mail_password);
        }

        setting()->set('mail_from_address', trim($request->mail_from_address));
        setting()->set('mail_from_name', trim($request->mail_from_name));

        return redirect()->route('smtp_settings.index')
            ->with('success', 'Konfigurasi SMTP berhasil disimpan ke sistem! Seluruh pengiriman email kini menggunakan pengaturan ini.');
    }

    /**
     * Uji coba pengiriman email langsung (Live Connection & Send Test)
     */
    public function testSend(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        $host = $request->filled('mail_host') ? trim($request->mail_host) : setting('mail_host', config('mail.mailers.smtp.host'));
        $port = $request->filled('mail_port') ? (int) $request->mail_port : (int) setting('mail_port', config('mail.mailers.smtp.port', 587));
        
        $encryption = $request->filled('mail_encryption') ? $request->mail_encryption : setting('mail_encryption', config('mail.mailers.smtp.encryption', 'tls'));
        if ($encryption === 'none' || $encryption === 'null' || empty($encryption)) {
            $encryption = null;
        }

        $username = $request->filled('mail_username') ? trim($request->mail_username) : setting('mail_username', config('mail.mailers.smtp.username'));
        
        $password = $request->filled('mail_password') 
            ? $request->mail_password 
            : setting('mail_password', config('mail.mailers.smtp.password'));

        $fromAddress = $request->filled('mail_from_address') 
            ? trim($request->mail_from_address) 
            : setting('mail_from_address', config('mail.from.address', 'pp_asklin@asklin.org'));

        $fromName = $request->filled('mail_from_name') 
            ? trim($request->mail_from_name) 
            : setting('mail_from_name', config('mail.from.name', 'Pengurus Pusat ASKLIN'));

        if (empty($host)) {
            return response()->json([
                'status' => 'error',
                'message' => 'SMTP Host belum diisi. Harap lengkapi host server terlebih dahulu.'
            ], 422);
        }

        try {
            // Terapkan konfigurasi runtime untuk sesi test ini
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $host,
                'mail.mailers.smtp.port' => $port,
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.mailers.smtp.username' => $username,
                'mail.mailers.smtp.password' => $password,
                'mail.from.address' => $fromAddress,
                'mail.from.name' => $fromName,
            ]);

            // Bersihkan instance mailer yang ter-cache agar menggunakan parameter baru
            Mail::purge('smtp');

            $smtpDetails = [
                'host' => $host,
                'port' => $port,
                'encryption' => $encryption,
                'from_address' => $fromAddress,
                'from_name' => $fromName,
            ];

            Mail::to($request->test_email)->send(new TestSmtpMail($smtpDetails));

            return response()->json([
                'status' => 'success',
                'message' => 'Koneksi SMTP Berhasil! Email uji coba telah berhasil dikirimkan ke ' . $request->test_email . '.',
                'details' => $smtpDetails
            ]);
        } catch (\Throwable $e) {
            $rawMsg = $e->getMessage();
            $hint = '';

            if (strpos($rawMsg, '535') !== false || stripos($rawMsg, 'authentication') !== false || stripos($rawMsg, 'Username and Password not accepted') !== false) {
                $hint = 'Petunjuk: Autentikasi ditolak oleh server SMTP. Jika menggunakan Gmail, pastikan 2-Step Verification akun Google aktif dan gunakan 16-digit Google App Password (sandi aplikasi khusus), bukan password login biasa.';
            } elseif (stripos($rawMsg, 'Connection refused') !== false || stripos($rawMsg, 'timed out') !== false) {
                $hint = 'Petunjuk: Koneksi ke server ditolak atau timeout. Pastikan kombinasi Host dan Port benar (Port 587 untuk TLS, Port 465 untuk SSL).';
            } elseif (stripos($rawMsg, 'Certificate') !== false) {
                $hint = 'Petunjuk: Masalah sertifikat SSL/TLS. Pastikan jenis enkripsi cocok dengan port yang digunakan.';
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim email uji coba: ' . $rawMsg,
                'hint' => $hint,
            ], 500);
        }
    }
}

