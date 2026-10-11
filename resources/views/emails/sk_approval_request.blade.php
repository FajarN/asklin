@extends('emails.master')

@section('content')
<div class="greeting">
    Yth. Bapak/Ibu {{ $recipientTitle ?? 'Pengurus Pusat ASKLIN' }},
</div>

<p>
    Pemberitahuan resmi dari Sistem Organisasi ASKLIN bahwa terdapat dokumen susunan kepengurusan baru yang memerlukan tinjauan dan tindakan persetujuan Anda:
</p>

<div class="info-box">
    <table style="width: 100%; font-size: 13px;">
        <tr>
            <td style="width: 140px; padding: 4px 0; color: #64748b; font-weight: 600;">Nama Struktur</td>
            <td style="padding: 4px 0; font-weight: 700; color: #0f172a;">: {{ $sk->strukturOrganisasi->nama_struktur ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Tingkatan Organisasi</td>
            <td style="padding: 4px 0; font-weight: 600; text-transform: uppercase; color: #dc2626;">: {{ $sk->tingkatan ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Periode Masa Bakti</td>
            <td style="padding: 4px 0; font-weight: 600;">: {{ $sk->strukturOrganisasi->periode ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Nomor SK Draf</td>
            <td style="padding: 4px 0; font-weight: 600; font-family: monospace;">: {{ $sk->no_sk ?: 'Draft (Belum Dinomori)' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Tahap Verifikasi</td>
            <td style="padding: 4px 0;">
                : <span class="badge-pill badge-warning">{{ $stepTitle ?? 'Pemeriksaan Persetujuan' }}</span>
            </td>
        </tr>
    </table>
</div>

<p>
    Anda dapat menyetujui pengajuan ini secara cepat langsung melalui email dengan menekan tombol verifikasi di bawah ini:
</p>

<div style="text-align: center; margin: 25px 0;">
    <a href="{{ $approvalUrl }}" class="btn-cta" target="_blank">
        &check; Setujui & Proses SK Sekarang
    </a>
    <div style="margin-top: 8px;">
        <a href="{{ $timelineUrl }}" style="font-size: 12px; color: #64748b; text-decoration: underline;">
            Atau Buka Riwayat & Timeline Dokumen di Dashboard
        </a>
    </div>
</div>

<div class="token-warning">
    <strong>&excl; Masa Berlaku Tautan:</strong><br>
    Tautan persetujuan via email di atas berlaku selama <strong>7 (tujuh) hari kalender</strong> hingga <strong>{{ $tokenExpiresAt->isoFormat('D MMMM Y, HH:mm') }} WIB</strong>.<br>
    Apabila telah melewati batas waktu 7 hari, tautan akan otomatis hangus (expired) demi keamanan sistem, dan persetujuan harus dilakukan secara manual melalui Dashboard Pengurus ASKLIN.
</div>
@endsection

