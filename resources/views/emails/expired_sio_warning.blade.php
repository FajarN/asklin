@extends('emails.master')

@section('content')
<div class="greeting">
    Pemberitahuan Masa Berlaku Surat Izin Operasional (SIO) Klinik
</div>

<p>
    Yth. Pimpinan Klinik <strong>{{ $anggota->nama_klinik }}</strong>,
</p>

<p>
    Berdasarkan data sistem monitoring keanggotaan <strong>Asosiasi Klinik Indonesia (ASKLIN)</strong>, kami menginformasikan status legalitas Surat Izin Operasional (SIO) klinik Anda:
</p>

<div class="info-box" style="border-left: 4px solid #f43f5e;">
    <table style="width: 100%; font-size: 13px;">
        <tr>
            <td style="width: 150px; padding: 4px 0; color: #64748b; font-weight: 600;">Nama Klinik</td>
            <td style="padding: 4px 0; font-weight: 700; color: #0f172a;">: {{ $anggota->nama_klinik }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Nomor Anggota ASKLIN</td>
            <td style="padding: 4px 0; font-weight: 600;">: {{ $anggota->no_anggota }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Nomor Izin (SIO)</td>
            <td style="padding: 4px 0;">: {{ $anggota->sio ?: '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 0; color: #64748b; font-weight: 600;">Tanggal Kadaluwarsa SIO</td>
            <td style="padding: 4px 0; font-weight: 700; color: #e11d48;">
                : {{ $anggota->tgl_habis_sio ? \Carbon\Carbon::parse($anggota->tgl_habis_sio)->isoFormat('D MMMM Y') : 'Mendekati / Telah Berakhir' }}
            </td>
        </tr>
    </table>
</div>

<p>
    Demi menjaga legalitas operasional faskes dan kelangsungan kerjasama layanan (termasuk BPJS Kesehatan dan Provider Asuransi), kami mengimbau agar segera melakukan perpanjangan izin operasional ke Dinas Kesehatan / PTSP terkait dan memperbarui berkas izin klinik Anda di sistem keanggotaan ASKLIN.
</p>

<p style="font-size: 13px; color: #64748b;">
    Apabila Anda memerlukan surat pengantar atau bantuan advokasi perpanjangan izin, silakan menghubungi Pengurus Cabang atau Pengurus Pusat ASKLIN melalui email <a href="mailto:pp_asklin@asklin.org">pp_asklin@asklin.org</a>.
</p>
@endsection
