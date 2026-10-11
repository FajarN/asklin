@extends('emails.master')

@section('content')
<div class="greeting">
    Selamat Datang di ASKLIN,
</div>

<p>
    Terima kasih telah melakukan pendaftaran klinik Anda di portal <strong>Asosiasi Klinik Indonesia (ASKLIN)</strong>. Permohonan keanggotaan klinik Anda telah berhasil kami terima ke dalam sistem.
</p>

<div class="info-box">
    <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px;">
        Ringkasan Data Permohonan:
    </h4>
    <table style="width: 100%; font-size: 13px;">
        <tr>
            <td style="width: 150px; padding: 4px 0; color: #64748b; font-weight: 600;">Nama Klinik</td>
            <td style="padding: 4px 0; font-weight: 700; color: #0f172a;">: {{ $anggota->nama_klinik }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Badan Hukum / Pemilik</td>
            <td style="padding: 4px 0;">: {{ $anggota->status_kepemilikan == 'Perorangan' ? $anggota->nama_pemilik_klinik : $anggota->nama_badan_usaha }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Wilayah</td>
            <td style="padding: 4px 0;">: {{ $anggota->kota ? $anggota->kota->name : '-' }}, {{ $anggota->provinsi ? $anggota->provinsi->name : '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Status Permohonan</td>
            <td style="padding: 4px 0;">
                : <span class="badge-pill badge-info">Dalam Antrean Verifikasi</span>
            </td>
        </tr>
    </table>
</div>

<h4 style="margin: 20px 0 10px 0; font-size: 14px; color: #0f172a;">Status Kelengkapan Berkas & Data:</h4>
<div style="background-color: #f8fafc; border-radius: 10px; padding: 12px 18px; font-size: 13px;">
    <p style="margin: 5px 0;">
        @if(!empty($anggota->nama_klinik) && !empty($anggota->alamat))
            <span style="color: #dc2626; font-weight: bold;">&#10004; Profil Klinik:</span> Sudah Lengkap
        @else
            <span style="color: #e11d48; font-weight: bold;">&#10008; Profil Klinik:</span> Perlu Dilengkapi
        @endif
    </p>
    <p style="margin: 5px 0;">
        @if(!empty($anggota->sio) || !empty($anggota->file_sio))
            <span style="color: #dc2626; font-weight: bold;">&#10004; Dokumen Legalitas SIO:</span> Terunggah
        @else
            <span style="color: #d97706; font-weight: bold;">&#9888; Dokumen Legalitas SIO:</span> Masih Menunggu Unggahan Berkas
        @endif
    </p>
    <p style="margin: 5px 0;">
        @if(!empty($anggota->sdmKlinik) && $anggota->sdmKlinik->count() > 0)
            <span style="color: #dc2626; font-weight: bold;">&#10004; SDM Medis & Dokter Penanggung Jawab:</span> Terisi ({{ $anggota->sdmKlinik->count() }} Personil)
        @else
            <span style="color: #d97706; font-weight: bold;">&#9888; SDM Medis & Dokter:</span> Belum Ada Data Personil Medis Terdaftar
        @endif
    </p>
</div>

<p style="font-size: 13px; color: #64748b; margin-top: 20px;">
    Pengurus Cabang & Daerah ASKLIN akan segera meninjau keabsahan berkas permohonan klinik Anda. Anda akan menerima pemberitahuan email lanjutan saat klinik Anda telah lolos verifikasi resmi.
</p>
@endsection

