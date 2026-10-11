@extends('emails.master')

@section('content')
<div class="greeting" style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">
    Pemberitahuan Pembaruan Akses Akun
</div>

<p style="margin: 0 0 16px 0; color: #334155; line-height: 1.6;">
    Yth. <strong>{{ $user->name }}</strong>
    @if(!empty($klinikName))
        <br><span style="color: #dc2626; font-weight: 600;">Pengelola {{ $klinikName }}</span>
    @endif
    ,
</p>

<p style="margin: 0 0 20px 0; color: #475569; line-height: 1.6;">
    Kami informasikan bahwa kata sandi (password) untuk akses akun Anda pada <strong>Sistem Informasi Manajemen Anggota Klinik ( ASKLIN)</strong> telah berhasil diperbarui oleh Administrator Sistem Pengurus Pusat ASKLIN.
</p>

<!-- Rincian Akun -->
<div class="info-box" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; margin: 20px 0;">
    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 12px; border-bottom: 1px dashed #cbd5e1; padding-bottom: 6px;">
        Informasi Akun Pengguna
    </div>
    <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
        <tr>
            <td style="width: 140px; padding: 6px 0; color: #64748b; font-weight: 600;">Nama Akun</td>
            <td style="padding: 6px 0; font-weight: 700; color: #0f172a;">: {{ $user->name }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Email Terdaftar</td>
            <td style="padding: 6px 0; font-weight: 700; color: #0284c7; font-family: monospace; font-size: 13px;">: {{ $user->email }}</td>
        </tr>
        @if(!empty($klinikName))
        <tr>
            <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Klinik Terhubung</td>
            <td style="padding: 6px 0; font-weight: 700; color: #dc2626;">: {{ $klinikName }}</td>
        </tr>
        @endif
        @if(!empty($noAnggota))
        <tr>
            <td style="padding: 6px 0; color: #64748b; font-weight: 600;">No. Anggota (NRA)</td>
            <td style="padding: 6px 0; font-weight: 700; color: #0f172a; font-family: monospace;">: {{ $noAnggota }}</td>
        </tr>
        @endif
        <tr>
            <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Waktu Pembaruan</td>
            <td style="padding: 6px 0; color: #334155;">: {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
    </table>
</div>

<!-- Highlight Box Password Baru (8 Karakter) -->
<div style="background: linear-gradient(135deg, #fef2f2 0%, #fff1f2 100%); border: 2px dashed #dc2626; border-radius: 14px; padding: 22px 20px; text-align: center; margin: 26px 0;">
    <div style="font-size: 12px; font-weight: 700; color: #991b1b; text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 8px;">
        &#128273; Password Baru Anda (8 Digit)
    </div>
    <div style="display: inline-block; background: #ffffff; border: 1px solid #fecaca; padding: 10px 24px; border-radius: 10px; font-family: 'Courier New', Courier, monospace; font-size: 24px; font-weight: 800; letter-spacing: 4px; color: #991b1b; box-shadow: 0 2px 6px rgba(220, 38, 38, 0.15);">
        {{ $newPassword }}
    </div>
    <p style="margin: 10px 0 0 0; font-size: 12px; color: #991b1b;">
        Gunakan password di atas bersama email <strong>{{ $user->email }}</strong> untuk masuk.
    </p>
</div>

<!-- Tombol CTA -->
<div style="text-align: center; margin: 28px 0 24px 0;">
    <a href="{{ url('/login') }}" class="btn-cta" target="_blank" style="display: inline-block; background-color: #dc2626; color: #ffffff !important; font-size: 14px; font-weight: 700; text-decoration: none; padding: 14px 34px; border-radius: 10px; text-align: center; box-shadow: 0 4px 14px rgba(220, 38, 38, 0.3);">
        Masuk ke Portal  ASKLIN &rarr;
    </a>
</div>

<!-- Keamanan & Panduan -->
<div class="token-warning" style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 14px 16px; font-size: 12px; color: #92400e; border-radius: 8px; margin-top: 24px; line-height: 1.6;">
    <strong style="display: block; font-size: 13px; margin-bottom: 4px; color: #b45309;">
        &#9888; Catatan Keamanan Penting:
    </strong>
    <ul style="margin: 6px 0 0 0; padding-left: 18px;">
        <li style="margin-bottom: 4px;">Demi menjaga keamanan akun klinik Anda, disarankan untuk <strong>mengubah kata sandi ini</strong> setelah Anda berhasil masuk ke sistem melalui menu <em>Profil Pengguna</em>.</li>
        <li style="margin-bottom: 4px;">Jangan pernah memberitahukan atau membagikan kata sandi ini kepada pihak mana pun di luar penanggung jawab resmi klinik.</li>
        <li>Pengurus Pusat ASKLIN dan tim teknis tidak pernah meminta kata sandi Anda melalui telepon, SMS, WhatsApp, atau pesan pribadi lainnya.</li>
    </ul>
</div>

<p style="margin: 24px 0 0 0; font-size: 13px; color: #475569; line-height: 1.6;">
    Apabila Anda tidak merasa meminta pembaruan kata sandi ini atau membutuhkan bantuan teknis lebih lanjut, silakan segera menghubungi Layanan Bantuan / Helpdesk Pengurus Pusat ASKLIN melalui Sekretariat Pusat.
</p>

<p style="margin: 18px 0 0 0; font-size: 13px; color: #1e293b; font-weight: 600;">
    Salam Sehat & Sukses Selalu,<br>
    <span style="color: #dc2626; font-weight: 700;">Pengurus Pusat Asosiasi Klinik Indonesia (ASKLIN)</span>
</p>
@endsection

