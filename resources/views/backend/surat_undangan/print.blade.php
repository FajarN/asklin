<!DOCTYPE html>
<html>

<head>
    <title>Cetak Surat Keluar - {{ $suratKeluar->no_surat }}</title>
    <style>
        .surat_undangan {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding-left: 30px;
            padding-right: 40px;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            margin: 20px 0;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
        }

        .signature {}

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {}

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        p {
            text-align: justify;
            text-justify: inter-word;
        }
    </style>

</head>

<body>

    <div class="header">
        @php
            $imagePath = public_path('assets/images/kop_asklin.jpg');
            $imageData = base64_encode(file_get_contents($imagePath));
        @endphp
        <img src="data:image/jpeg;base64,{{ $imageData }}" alt="Kop surat"
            style="width:100%; height:120px; margin-top:-30px">
    </div>

    <div class="content">
        @if (stripos($suratKeluar->jenisSurat->nama_jenis, 'undangan') !== false && $suratKeluar->suratUndangan)
            <!-- Template Surat Undangan -->
            <div class="surat_undangan">
                <table style="border-collapse: collapse; width: 100%; font-size: 14px;">
                    <tr>
                        <td style="padding: 2px 4px;">Nomor</td>
                        <td style="padding: 2px 4px;">:</td>
                        <td style="padding: 2px 4px;">{{ $suratKeluar->no_surat }}</td>
                        <td style="padding: 2px 4px; text-align:right;">{{ $suratKeluar->tgl_surat_formatted }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 4px;">Perihal</td>
                        <td style="padding: 2px 4px;">:</td>
                        <td style="padding: 2px 4px;">{{ $suratKeluar->perihal }}</td>
                        <td style="padding: 2px 4px;"></td>
                    </tr>
                </table>

                <p style="font-size: 14px; font-weight: normal">
                    Kepada Yth,<br>
                    <strong>{{ $suratKeluar->suratUndangan->nama_penerima }}</strong><br>
                    Di-<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tempat<br><br>
                    {{ $suratKeluar->suratUndangan->salam_pembuka }}
                </p>

                <p style="font-size: 14px; font-weight: normal">{{ $suratKeluar->suratUndangan->isi_surat }}</p>

                <table style="border-collapse: collapse; width: 100%; font-size: 14px;">
                    <tr>
                        <td style="padding: 2px 4px;">Hari,&nbsp;Tanggal</td>
                        <td style="padding: 2px 4px;">:</td>
                        <td style="padding: 2px 4px;"> {{ $suratKeluar->suratUndangan->hari }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 4px;">Tanggal</td>
                        <td style="padding: 2px 4px;">:</td>
                        <td style="padding: 2px 4px;">
                            {{ $suratKeluar->suratUndangan->tgl_acara_formatted }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 4px;">Waktu</td>
                        <td style="padding: 2px 4px;">:</td>
                        <td style="padding: 2px 4px;">{{ $suratKeluar->suratUndangan->waktu_acara }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 4px;">Tempat</td>
                        <td style="padding: 2px 4px;">:</td>
                        <td style="padding: 2px 4px;">{{ $suratKeluar->suratUndangan->lokasi_acara }}</td>
                    </tr>
                </table>

                <p style="font-size: 14px; font-weight: normal">{{ $suratKeluar->suratUndangan->salam_penutup }}</p>
                <p style="font-size: 14px; font-weight: normal">{{ $suratKeluar->suratUndangan->informasi_tambahan }}
                </p>
            </div>
        @else
            no content
        @endif
    </div>

    <div class="footer">
        <div class="signature">
            <table width="500" align="center"
                style="border-collapse: collapse; text-align: center; margin-left:200px; font-size: 13px; line-height: 1.2;">
                <tr>
                    <td colspan="2" style="padding: 2px; text-align:center;">Jakarta,
                        {{ $suratKeluar->tgl_surat_formatted }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 2px; text-align:center;">Asosiasi Klinik Indonesia</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 2px 0 0 0; text-align:center;"><strong>Ketua Umum</strong></td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 4px; text-align:center;">
                        @php
                            $imagePath = public_path('assets/images/ttd.png');
                            $imageData = base64_encode(file_get_contents($imagePath));
                        @endphp
                        <img src="data:image/png;base64,{{ $imageData }}" alt="TTD" style="width:200px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 2px; text-align:center;"><strong>dr. Eddi Junaidi, SpOG., SH.,
                            MKes</strong></td>
                </tr>
            </table>

        </div>
    </div>
</body>

</html>
