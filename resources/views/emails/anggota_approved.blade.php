@extends('emails.master')

@section('content')
<div class="greeting">
    Selamat, Klinik Anda Telah Resmi Terdaftar!
</div>

<p>
    Dengan hormat kami sampaikan bahwa permohonan keanggotaan klinik Anda pada <strong>Asosiasi Klinik Indonesia (ASKLIN)</strong> telah selesai diverifikasi berjenjang dan <strong>DISETUJUI RESMI</strong> oleh Pengurus Pusat ASKLIN.
</p>

<div class="info-box">
    <table style="width: 100%; font-size: 13px;">
        <tr>
            <td style="width: 140px; padding: 4px 0; color: #64748b; font-weight: 600;">Nama Klinik</td>
            <td style="padding: 4px 0; font-weight: 700; color: #0f172a;">: {{ $anggota->nama_klinik }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Nomor Anggota Resmi</td>
            <td style="padding: 4px 0; font-weight: 800; font-size: 15px; color: #059669; font-family: monospace;">: {{ $anggota->no_anggota }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Status Keanggotaan</td>
            <td style="padding: 4px 0;">
                : <span class="badge-pill badge-success">&#10004; AKTIF RESMI</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Wilayah</td>
            <td style="padding: 4px 0;">: {{ $anggota->kota ? $anggota->kota->name : '-' }}, {{ $anggota->provinsi ? $anggota->provinsi->name : '-' }}</td>
        </tr>
    </table>
</div>

<p>
    Anda kini berhak mendapatkan seluruh manfaat advokasi hukum, pendampingan akreditasi, program kerjasama asuransi kesehatan, serta akses pelatihan organisasi ASKLIN.
</p>

<div style="text-align: center; margin: 25px 0;">
    <a href="{{ route('anggota.printsk', $anggota->id) }}" class="btn-cta" target="_blank">
        &DownArrow; Cetak Surat Keputusan Anggota (PDF)
    </a>
</div>
@endsection
