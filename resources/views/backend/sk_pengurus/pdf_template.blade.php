<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Keputusan - {{ $sk->no_sk ?: $struktur->nama_struktur }}</title>
    <style>
        @page {
            margin: 15mm 20mm 15mm 20mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.35;
            color: #000;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-justify { text-align: justify; text-justify: inter-word; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        .kop-container {
            width: 100%;
            text-align: center;
            margin-bottom: 10px;
        }

        .kop-image {
            width: 100%;
            max-height: 110px;
        }

        .judul-sk {
            margin-top: 5px;
            margin-bottom: 12px;
            text-align: center;
        }

        .judul-sk h2 {
            font-size: 12pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
            letter-spacing: 0.5px;
        }

        .judul-sk p {
            margin: 2px 0;
            font-size: 11pt;
        }

        .table-konsiderans {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 10.5pt;
        }

        .table-konsiderans td {
            vertical-align: top;
            padding: 2.5px 0;
        }

        .col-label {
            width: 125px;
            font-weight: bold;
        }

        .col-bullet {
            width: 20px;
            text-align: left;
        }

        .col-text {
            text-align: justify;
            text-justify: inter-word;
        }

        .memutuskan-block {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin: 10px 0 6px 0;
            letter-spacing: 1px;
        }

        .table-diktum {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10.5pt;
        }

        .table-diktum td {
            vertical-align: top;
            padding: 3px 0;
        }

        .col-diktum-label {
            width: 115px;
            font-weight: bold;
        }

        .table-ttd {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 10.5pt;
        }

        .table-ttd td {
            vertical-align: top;
        }

        .qr-box {
            border: 1px solid #777;
            padding: 6px;
            display: inline-block;
            text-align: center;
            background: #fff;
            border-radius: 4px;
        }

        .page-break {
            page-break-before: always;
        }

        /* Lampiran Table Styles */
        .header-lampiran {
            font-size: 9.5pt;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .table-lampiran-meta {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-bottom: 10px;
        }

        .table-lampiran-meta td {
            padding: 1.5px 0;
            vertical-align: top;
        }

        .table-pengurus {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-top: 8px;
            margin-bottom: 15px;
        }

        .table-pengurus th, .table-pengurus td {
            border: 1px solid #444;
            padding: 5px 6px;
        }

        .table-pengurus th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .group-header-row td {
            background-color: #eaeaea;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 8px;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT RESMI -->
    <div class="kop-container">
        @if($kopBase64)
            <img src="{{ $kopBase64 }}" class="kop-image" alt="Kop Surat PP ASKLIN">
        @else
            <div style="border-bottom: 3px double #000; padding-bottom: 5px;">
                <h1 style="font-size: 16pt; margin: 0; font-weight: bold; color: #dc2626;">ASOSIASI KLINIK INDONESIA</h1>
                <h3 style="font-size: 12pt; margin: 0; font-weight: bold;">PENGURUS PUSAT</h3>
                <p style="font-size: 9pt; margin: 0;">Sekretariat: Gedung Pengurus Pusat ASKLIN - Jakarta</p>
            </div>
        @endif
    </div>

    <!-- JUDUL SURAT KEPUTUSAN -->
    <div class="judul-sk">
        <h2>SURAT KEPUTUSAN PENGURUS PUSAT ASOSIASI KLINIK INDONESIA</h2>
        <p><strong>Nomor : {{ $sk->no_sk ?: '.../SK-' . ($tingkatanText === 'CABANG' ? 'PC' : ($tingkatanText === 'DAERAH' ? 'PD' : 'PP')) . '/PP-ASKLIN/.../' . date('Y') }}</strong></p>
        <p style="margin-top: 6px; font-weight: bold;">TENTANG</p>
        <h2 style="font-size: 11pt; margin-top: 3px;">
            PENGESAHAN SUSUNAN PENGURUS ASKLIN {{ $tingkatanText }} {{ strtoupper($namaWilayah) }}<br>
            MASA BAKTI {{ $struktur->periode }}
        </h2>
        <p style="margin-top: 8px; font-weight: bold; font-size: 10pt; letter-spacing: 0.5px;">
            PENGURUS PUSAT ASOSIASI KLINIK INDONESIA,
        </p>
    </div>

    <!-- KONSIDERANS MENIMBANG -->
    <table class="table-konsiderans">
        <tr>
            <td class="col-label">Menimbang</td>
            <td class="col-bullet">: a.</td>
            <td class="col-text">
                bahwa {{ $jenisMusyawarah }} ASKLIN {{ $namaWilayah }} yang dilaksanakan pada tanggal {{ $tglMusyawarahFormatted }} telah memilih dan menyusun Pengurus ASKLIN {{ $tingkatanText }} {{ $namaWilayah }} Masa Bakti {{ $struktur->periode }};
            </td>
        </tr>
        <tr>
            <td class="col-label"></td>
            <td class="col-bullet">b.</td>
            <td class="col-text">
                bahwa untuk kelancaran roda organisasi dipandang perlu mengesahkan Susunan Pengurus ASKLIN {{ $tingkatanText }} {{ $namaWilayah }} Masa Bakti {{ $struktur->periode }};
            </td>
        </tr>
        <tr>
            <td class="col-label"></td>
            <td class="col-bullet">c.</td>
            <td class="col-text">
                bahwa nama-nama yang tercantum dalam Lampiran Surat Keputusan ini dipandang mampu dan cakap untuk melaksanakan tugas sebagai Pengurus ASKLIN {{ $tingkatanText }} {{ $namaWilayah }} Masa Bakti {{ $struktur->periode }};
            </td>
        </tr>
    </table>

    <!-- KONSIDERANS MENGINGAT -->
    <table class="table-konsiderans">
        <tr>
            <td class="col-label">Mengingat</td>
            <td class="col-bullet">: 1.</td>
            <td class="col-text">Anggaran Dasar ASKLIN {{ $pasalAd }};</td>
        </tr>
        <tr>
            <td class="col-label"></td>
            <td class="col-bullet">2.</td>
            <td class="col-text">Anggaran Rumah Tangga ASKLIN {{ $pasalArt }};</td>
        </tr>
        <tr>
            <td class="col-label"></td>
            <td class="col-bullet">3.</td>
            <td class="col-text">
                Surat Keputusan Pengurus Pusat ASKLIN Nomor: 03/SK-PP/PP-ASKLIN/VII/2018 tentang Pedoman Penyelenggaraan Musyawarah Daerah dan Musyawarah Cabang Asosiasi Klinik Indonesia;
            </td>
        </tr>
        <tr>
            <td class="col-label"></td>
            <td class="col-bullet">4.</td>
            <td class="col-text">
                Surat Keputusan Pengurus Pusat ASKLIN Nomor: 04/SK-PP/PP-ASKLIN/VII/2018 tentang Pedoman Tata Cara Pembentukan dan Pengesahan Pengurus Daerah dan Pengurus Cabang Asosiasi Klinik Indonesia;
            </td>
        </tr>
    </table>

    <!-- KONSIDERANS MEMPERHATIKAN -->
    <table class="table-konsiderans">
        <tr>
            <td class="col-label">Memperhatikan</td>
            <td class="col-bullet">:</td>
            <td class="col-text">
                @if($sk->no_rekomendasi_daerah)
                    Surat Permohonan / Rekomendasi Pengurus ASKLIN Daerah {{ $struktur->provinsi ? $struktur->provinsi->name : '' }} Nomor: {{ $sk->no_rekomendasi_daerah }} {{ $tglRekomendasiFormatted ? 'tanggal ' . $tglRekomendasiFormatted : '' }} perihal Permohonan Pengesahan Susunan Pengurus ASKLIN {{ $tingkatanText }} {{ $namaWilayah }};
                @else
                    Hasil Musyawarah dan Berita Acara Formatur Pembentukan Pengurus ASKLIN {{ $tingkatanText }} {{ $namaWilayah }} tanggal {{ $tglMusyawarahFormatted }};
                @endif
            </td>
        </tr>
    </table>

    <!-- MEMUTUSKAN -->
    <div class="memutuskan-block">
        MEMUTUSKAN :
    </div>

    <!-- DIKTUM -->
    <table class="table-diktum">
        <tr>
            <td class="col-diktum-label">Menetapkan</td>
            <td style="width: 15px; font-weight: bold;">:</td>
            <td></td>
        </tr>
        <tr>
            <td class="col-diktum-label">PERTAMA</td>
            <td style="width: 15px;">:</td>
            <td class="col-text">
                Mengesahkan Susunan Pengurus ASKLIN {{ $tingkatanText }} {{ strtoupper($namaWilayah) }} Masa Bakti {{ $struktur->periode }} sebagaimana tercantum dalam Lampiran yang merupakan bagian tidak terpisahkan dari Surat Keputusan ini.
            </td>
        </tr>
        <tr>
            <td class="col-diktum-label">KEDUA</td>
            <td style="width: 15px;">:</td>
            <td class="col-text">
                Masa bakti Pengurus ASKLIN {{ $tingkatanText }} {{ $namaWilayah }} terhitung sejak tanggal pelantikan {{ $tglPelantikanFormatted ? '(' . $tglPelantikanFormatted . ')' : '' }} sampai dengan berakhirnya masa bakti periode {{ $struktur->periode }}.
            </td>
        </tr>
        <tr>
            <td class="col-diktum-label">KETIGA</td>
            <td style="width: 15px;">:</td>
            <td class="col-text">
                Mengamanatkan kepada Pengurus ASKLIN {{ $tingkatanText }} {{ $namaWilayah }} Masa Bakti {{ $struktur->periode }} untuk menjalankan tugas organisasi dengan sebaik-baiknya dengan senantiasa berpedoman pada Anggaran Dasar dan Anggaran Rumah Tangga ASKLIN.
            </td>
        </tr>
        <tr>
            <td class="col-diktum-label">KEEMPAT</td>
            <td style="width: 15px;">:</td>
            <td class="col-text">
                Surat Keputusan ini berlaku sejak tanggal ditetapkan, dan apabila di kemudian hari terdapat kekeliruan dalam penetapan ini akan diadakan perbaikan sebagaimana mestinya.
            </td>
        </tr>
    </table>

    <!-- TANDA TANGAN & QR CODE -->
    <table class="table-ttd">
        <tr>
            <!-- Kolom Kiri: QR Code (Jika diaktifkan) -->
            <td style="width: 48%; vertical-align: bottom;">
                @if($sk->show_qr && $qrCodeSvg)
                    <div class="qr-box">
                        <img src="{{ $qrCodeSvg }}" style="width: 90px; height: 90px;" alt="QR Code Keabsahan"><br>
                        <span style="font-size: 7.5pt; color: #444; line-height: 1.1; display: inline-block; margin-top: 3px;">
                            Pindai QR Code untuk verifikasi keabsahan:<br>
                            <span style="font-family: monospace; font-size: 7pt; color: #111;">{{ $sk->qr_token }}</span>
                        </span>
                    </div>
                @endif
            </td>

            <!-- Kolom Kanan: Tanda Tangan Ketua Umum -->
            <td style="width: 52%; padding-left: 20px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 10.5pt;">
                    <tr>
                        <td style="width: 95px;">Ditetapkan di</td>
                        <td style="width: 10px;">:</td>
                        <td>Jakarta</td>
                    </tr>
                    <tr>
                        <td>Pada tanggal</td>
                        <td>:</td>
                        <td>{{ $tglPenetapanFormatted }}</td>
                    </tr>
                </table>
                <div style="border-top: 1px solid #000; margin: 4px 0 6px 0;"></div>
                <div style="font-weight: bold; line-height: 1.2;">
                    PENGURUS PUSAT<br>
                    ASOSIASI KLINIK INDONESIA
                </div>
                <div style="height: 68px; margin: 4px 0;">
                    @if($ttdBase64)
                        <img src="{{ $ttdBase64 }}" style="height: 68px; max-width: 180px;" alt="Tanda Tangan">
                    @else
                        <br><br><br>
                    @endif
                </div>
                <div style="font-weight: bold; text-decoration: underline; font-size: 11pt;">
                    {{ $ketum->nama_lengkap }}
                </div>
                <div style="font-size: 10.5pt;">
                    Ketua Umum
                </div>
            </td>
        </tr>
    </table>

    <!-- ========================================== -->
    <!-- HALAMAN 2: LAMPIRAN SUSUNAN PENGURUS       -->
    <!-- ========================================== -->
    <div class="page-break"></div>

    <!-- Header Lampiran -->
    <div style="width: 100%; margin-bottom: 8px;">
        <table style="width: 55%; margin-left: 45%; border-collapse: collapse; font-size: 9pt; line-height: 1.25;">
            <tr>
                <td colspan="3" style="font-weight: bold;">LAMPIRAN SURAT KEPUTUSAN PENGURUS PUSAT ASKLIN</td>
            </tr>
            <tr>
                <td style="width: 60px;">Nomor</td>
                <td style="width: 8px;">:</td>
                <td>{{ $sk->no_sk ?: '.../SK-' . ($tingkatanText === 'CABANG' ? 'PC' : ($tingkatanText === 'DAERAH' ? 'PD' : 'PP')) . '/PP-ASKLIN/.../' . date('Y') }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>:</td>
                <td>{{ $tglPenetapanFormatted }}</td>
            </tr>
            <tr>
                <td>Tentang</td>
                <td>:</td>
                <td>PENGESAHAN SUSUNAN PENGURUS ASKLIN {{ $tingkatanText }} {{ strtoupper($namaWilayah) }} MASA BAKTI {{ $struktur->periode }}</td>
            </tr>
        </table>
        <div style="border-top: 1.5px solid #000; margin-top: 8px; margin-bottom: 12px;"></div>
    </div>

    <!-- Judul Lampiran -->
    <div class="text-center" style="margin-bottom: 12px;">
        <h3 style="font-size: 11pt; font-weight: bold; margin: 0; text-transform: uppercase;">
            SUSUNAN PERSONALIA PENGURUS ASKLIN {{ $tingkatanText }} {{ strtoupper($namaWilayah) }}<br>
            MASA BAKTI {{ $struktur->periode }}
        </h3>
    </div>

    <!-- Tabel Susunan Pengurus -->
    <table class="table-pengurus">
        <thead>
            <tr>
                <th style="width: 32px;">NO</th>
                <th style="width: 210px;">JABATAN</th>
                <th>NAMA LENGKAP & GELAR</th>
                <th style="width: 170px;">ASAL KLINIK / FASKES</th>
            </tr>
        </thead>
        <tbody>
            @php $globalNo = 1; @endphp
            @forelse($kelompokList as $kelompok)
                @php
                    $pengurusInGroup = $pengurusSemua->where('id_kelompok', $kelompok->id);
                @endphp
                @if($pengurusInGroup->count() > 0)
                    <tr class="group-header-row">
                        <td colspan="4">{{ $kelompok->nama_kelompok }}</td>
                    </tr>
                    @foreach($pengurusInGroup as $person)
                        <tr>
                            <td class="text-center">{{ $globalNo++ }}</td>
                            <td style="font-weight: 600;">{{ $person->jabatan }}</td>
                            <td style="font-weight: bold;">{{ $person->nama_lengkap }}</td>
                            <td>{{ $person->keterangan ?: '-' }}</td>
                        </tr>
                    @endforeach
                @endif
            @empty
                @foreach($pengurusSemua as $person)
                    <tr>
                        <td class="text-center">{{ $globalNo++ }}</td>
                        <td style="font-weight: 600;">{{ $person->jabatan }}</td>
                        <td style="font-weight: bold;">{{ $person->nama_lengkap }}</td>
                        <td>{{ $person->keterangan ?: '-' }}</td>
                    </tr>
                @endforeach
            @endforelse

            {{-- Pengurus tanpa kelompok jika ada --}}
            @php
                $unassigned = $pengurusSemua->whereNull('id_kelompok');
            @endphp
            @if($unassigned->count() > 0)
                <tr class="group-header-row">
                    <td colspan="4">PENGURUS LAINNYA</td>
                </tr>
                @foreach($unassigned as $person)
                    <tr>
                        <td class="text-center">{{ $globalNo++ }}</td>
                        <td style="font-weight: 600;">{{ $person->jabatan }}</td>
                        <td style="font-weight: bold;">{{ $person->nama_lengkap }}</td>
                        <td>{{ $person->keterangan ?: '-' }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- Tanda Tangan Lampiran -->
    <table class="table-ttd" style="margin-top: 20px;">
        <tr>
            <td style="width: 48%; vertical-align: bottom;">
                @if($sk->show_qr && $qrCodeSvg)
                    <div class="qr-box">
                        <img src="{{ $qrCodeSvg }}" style="width: 80px; height: 80px;" alt="QR Code Keabsahan"><br>
                        <span style="font-size: 7pt; color: #444; line-height: 1.1; display: inline-block; margin-top: 2px;">
                            Validasi Lampiran Resmi ASKLIN
                        </span>
                    </div>
                @endif
            </td>
            <td style="width: 52%; padding-left: 20px;">
                <div style="font-weight: bold; line-height: 1.2;">
                    PENGURUS PUSAT<br>
                    ASOSIASI KLINIK INDONESIA
                </div>
                <div style="height: 60px; margin: 3px 0;">
                    @if($ttdBase64)
                        <img src="{{ $ttdBase64 }}" style="height: 60px; max-width: 160px;" alt="Tanda Tangan">
                    @else
                        <br><br><br>
                    @endif
                </div>
                <div style="font-weight: bold; text-decoration: underline; font-size: 11pt;">
                    {{ $ketum->nama_lengkap }}
                </div>
                <div style="font-size: 10.5pt;">
                    Ketua Umum
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
