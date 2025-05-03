<!DOCTYPE html>
<html>

<head>
    <title>Cetak Surat Keluar - {{ $suratKeluar->no_surat }}</title>
    <style>
        .surat_tugas {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding-left: 40px;
            padding-right: 40px;
            font-size:14px;
        }

        .surat_undangan {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding-left: 30px;
            padding-right: 60px;
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
        @if (stripos($suratKeluar->jenisSurat->nama_jenis, 'tugas') !== false && $suratKeluar->suratTugas)
            <!-- Template Surat Tugas -->
          <div class="surat_tugas">
            <h3 style="text-align: center; text-decoration:underline">SURAT TUGAS</h3>
            <p style="text-align: center; margin-top:-20px;">Nomor : {{ $suratKeluar->no_surat }}</p>
            <p>Bersama dengan surat ini kami selaku Pengurus Pusat Asosiasi Klinik Indonesia (ASKLIN) memberikan tugas
                kepada:</p>
            @php
                $withNumber = $suratKeluar->suratTugas->details->count() > 1;
            @endphp
            <table width="300" style="margin-left:50px; border-collapse: collapse;">
                @foreach ($suratKeluar->suratTugas->details as $index => $detail)
                    <tr>
                        <td width="10" style="padding: 2px 4px;">
                            {{ $withNumber ? $index + 1 . '.' : '' }}
                        </td>
                        <th width="50" style="padding: 2px 4px;">Nama</th>
                        <td style="padding: 2px 4px;">: {{ $detail->nama_pengurus }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 4px;">&nbsp;</td>
                        <th style="padding: 2px 4px;">Jabatan</th>
                        <td style="padding: 2px 4px;">: {{ $detail->jabatan }}</td>
                    </tr>
                @endforeach
            </table>
            <p>Dalam rangka menghadiri undangan dari {{ $suratKeluar->suratTugas->asal_surat }}
                Nomor : {{ $suratKeluar->suratTugas->nomor_asal_surat }}, perihal {{ $suratKeluar->perihal }}, yang
                diselenggarakan pada:</p>
            <table width="300" style="margin-left:40px;">
                <tr>
                    <th width="50" style="padding: 2px 4px;">Hari/Tanggal</th>
                    <td style="padding: 2px 4px;">:
                        {{ $suratKeluar->suratTugas->hari }}
                    </td>
                </tr>
                <tr>
                    <th style="padding: 2px 4px;">Tanggal</th>
                    <td style="padding: 2px 4px;">: {{ $suratKeluar->suratTugas->tgl_agenda_formatted }}</td>
                </tr>
                <tr>
                    <th style="padding: 2px 4px;">Waktu</th>
                    <td style="padding: 2px 4px;">: {{ $suratKeluar->suratTugas->waktu_agenda }}</td>
                </tr>
                <tr>
                    <th style="padding: 2px 4px;">Tempat</th>
                    <td style="padding: 2px 4px;">: {{ $suratKeluar->suratTugas->tempat_agenda }}</td>
                </tr>
            </table>
            <p>Demikian surat ini dibuat agar dapat melaksanakan tugas dengan sebaik-baiknya. Atas perhatian
                dan Kerjasama kami sampaikan terima kasih.
            </p>
        </div>

        @elseif(stripos($suratKeluar->jenisSurat->nama_jenis, 'undangan') !== false && $suratKeluar->suratUndangan)
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

            <p style="font-size: 14px; font-weight: normal">{{ $suratKeluar->suratUndangan->isi_surat}}</p>

           <table style="border-collapse: collapse; width: 100%; font-size: 14px;">
            <tr>
                <td style="padding: 2px 4px;">Hari,&nbsp;Tanggal</td>
                <td style="padding: 2px 4px;">:</td>
                <td style="padding: 2px 4px;"> {{ $suratKeluar->suratUndangan->hari }},
                {{ \Carbon\Carbon::parse($suratKeluar->suratUndangan->tgl_acara)->format('d-m-Y') }}</td>
            </tr>
            <tr>
              <td style="padding: 2px 4px;">Waktu</td>
              <td style="padding: 2px 4px;">:</td>
              <td style="padding: 2px 4px;"></td>
            </tr>
            <tr>
                <td style="padding: 2px 4px;">Tempat</td>
                <td style="padding: 2px 4px;">:</td>
                <td style="padding: 2px 4px;">{{ $suratKeluar->suratUndangan->lokasi_acara}}</td>
            </tr>
        </table>

          <p style="font-size: 14px; font-weight: normal">{{ $suratKeluar->suratUndangan->salam_penutup}}</p>
          <p style="font-size: 14px; font-weight: normal">{{ $suratKeluar->suratUndangan->informasi_tambahan}}</p>
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
                    <td colspan="2" style="padding: 2px; text-align:center;">Jakarta, {{ $suratKeluar->tgl_surat_formatted }}</td>
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