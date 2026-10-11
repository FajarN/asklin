@extends('emails.master')

@section('content')
<div class="greeting" style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">
    Pengujian Koneksi Email SMTP Berhasil!
</div>

<p style="margin: 0 0 16px 0; color: #334155; line-height: 1.6;">
    Halo Administrator SIMAK ASKLIN,
</p>

<p style="margin: 0 0 20px 0; color: #475569; line-height: 1.6;">
    Email ini merupakan pesan uji coba (test email) otomatis dari sistem <strong>SIMAK ASKLIN (Asosiasi Klinik Indonesia)</strong>. Apabila Anda menerima email ini, artinya konfigurasi server email SMTP Anda telah berhasil terhubung dan siap digunakan untuk seluruh modul notifikasi sistem.
</p>

<!-- Rincian Konfigurasi yang Digunakan -->
<div class="info-box" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; margin: 20px 0;">
    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 12px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 6px;">
        Parameter Server SMTP Aktif
    </div>
    <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
        <tr>
            <td style="width: 140px; padding: 6px 0; color: #64748b; font-weight: 600;">Status Koneksi</td>
            <td style="padding: 6px 0;">
                <span style="display: inline-block; background-color: #d1fae5; color: #065f46; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 9999px;">
                    &#10004; TERHUBUNG & TERVERIFIKASI
                </span>
            </td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #64748b; font-weight: 600;">SMTP Host</td>
            <td style="padding: 6px 0; font-weight: 700; color: #0f172a; font-family: monospace;">: {{ $smtpDetails['host'] ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #64748b; font-weight: 600;">SMTP Port & Enkripsi</td>
            <td style="padding: 6px 0; font-weight: 600; color: #0284c7; font-family: monospace;">: {{ $smtpDetails['port'] ?? '-' }} ({{ strtoupper($smtpDetails['encryption'] ?? 'None') }})</td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Email Pengirim (From)</td>
            <td style="padding: 6px 0; font-weight: 600; color: #0f172a;">: {{ $smtpDetails['from_address'] ?? '-' }} ({{ $smtpDetails['from_name'] ?? '-' }})</td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Waktu Pengujian</td>
            <td style="padding: 6px 0; color: #334155;">: {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i:s') }} WIB</td>
        </tr>
    </table>
</div>

<p style="margin: 0 0 16px 0; color: #475569; line-height: 1.6;">
    Seluruh proses pengiriman email otomatis seperti <strong>Pemberitahuan Pendaftaran Anggota</strong>, <strong>Persetujuan SK Pengurus</strong>, <strong>Pemberitahuan Expired SIO</strong>, serta <strong>Reset Password Pengguna</strong> kini akan beroperasi menggunakan server SMTP ini secara penuh tanpa memerlukan perubahan file <code>.env</code>.
</p>

<div style="text-align: center; margin: 28px 0 20px 0;">
    <a href="{{ url('/backend/secret/smtp-settings') }}" class="btn-cta" target="_blank" style="display: inline-block; background-color: #059669; color: #ffffff !important; font-size: 13px; font-weight: 700; text-decoration: none; padding: 12px 30px; border-radius: 10px; text-align: center; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.25);">
        Buka Pengaturan Email SMTP &rarr;
    </a>
</div>

<p style="margin: 20px 0 0 0; font-size: 13px; color: #1e293b; font-weight: 600;">
    Salam Hangat,<br>
    <span style="color: #059669; font-weight: 700;">Pengurus Pusat Asosiasi Klinik Indonesia (ASKLIN)</span>
</p>
@endsection

