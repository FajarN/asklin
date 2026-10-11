<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }} - ASKLIN</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #b91c1c;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h1 {
            font-size: 14px;
            color: #991b1b;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header h2 {
            font-size: 10px;
            color: #334155;
            margin: 0;
            font-weight: normal;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 8px;
        }
        .meta-table td {
            padding: 3px 6px;
            border: 1px solid #cbd5e1;
        }
        .meta-label {
            background-color: #f1f5f9;
            font-weight: bold;
            width: 15%;
            color: #475569;
        }
        .kpi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 8px;
        }
        .kpi-table td {
            padding: 5px;
            text-align: center;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
        }
        .kpi-val {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5px;
        }
        .data-table th {
            background-color: #991b1b;
            color: #ffffff;
            font-weight: bold;
            padding: 5px 3px;
            text-align: center;
            border: 1px solid #7f1d1d;
        }
        .data-table td {
            padding: 4px 3px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge-success { color: #166534; font-weight: bold; }
        .badge-warning { color: #9a3412; font-weight: bold; }
        .badge-danger { color: #991b1b; font-weight: bold; }
        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 7px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }
        @page {
            margin: 10mm;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>ASOSIASI KLINIK INDONESIA (ASKLIN)</h1>
        <h2>{{ strtoupper($scope['title']) }}</h2>
    </div>

    <!-- Metadata Section -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Jenis Laporan:</td>
            <td><strong>{{ $title }}</strong></td>
            <td class="meta-label">Wilayah:</td>
            <td>{{ $scope['level'] === 'daerah' ? $scope['provinsi_name'] : $scope['kota_name'] }}</td>
        </tr>
        <tr>
            <td class="meta-label">Waktu Cetak:</td>
            <td>{{ $today->format('d-m-Y H:i:s') }} WIB</td>
            <td class="meta-label">Dicetak Oleh:</td>
            <td>{{ Auth::user()->name ?? 'Pengurus ASKLIN' }}</td>
        </tr>
    </table>

    <!-- Ringkasan Eksekutif KPI -->
    <table class="kpi-table">
        <tr>
            <td>
                Total Anggota Terdaftar<br>
                <span class="kpi-val">{{ number_format($stats['total_anggota']) }}</span>
            </td>
            <td>
                Anggota Aktif (Disetujui)<br>
                <span class="kpi-val" style="color: #166534;">{{ number_format($stats['anggota_aktif']) }}</span>
            </td>
            <td>
                Anggota Belum Aktif<br>
                <span class="kpi-val" style="color: #9a3412;">{{ number_format($stats['anggota_inaktif']) }}</span>
            </td>
            <td>
                Sertifikat Aktif / Expired<br>
                <span class="kpi-val">{{ number_format($stats['sertifikat_aktif']) }} / {{ number_format($stats['sertifikat_expired']) }}</span>
            </td>
            <td>
                Pembayaran Lunas (Trx)<br>
                <span class="kpi-val" style="color: #166534;">{{ number_format($stats['transaksi_bayar_lunas']) }}</span>
            </td>
        </tr>
    </table>

    <!-- Content Table Based on Type -->
    @if ($type === 'anggota')
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 20px;">No</th>
                    <th>No. Anggota</th>
                    <th>Nama Klinik</th>
                    <th>Jenis</th>
                    <th>Pemilik / Kontak</th>
                    <th>Kab/Kota</th>
                    <th>Kecamatan</th>
                    <th>Tgl Daftar</th>
                    <th>Status</th>
                    <th>Status Bayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $item->no_anggota ?: '-' }}</td>
                        <td><strong>{{ $item->nama_klinik }}</strong></td>
                        <td>{{ $item->jenis_klinik ?: '-' }}</td>
                        <td>{{ $item->nama_pemilik_klinik ?: ($item->nama_kontak ?: '-') }}</td>
                        <td>{{ $item->kota_name ?: '-' }}</td>
                        <td>{{ $item->kecamatan_name ?: '-' }}</td>
                        <td class="text-center">{{ $item->tgl_daftar_raw ? \Carbon\Carbon::parse($item->tgl_daftar_raw)->format('d-m-Y') : '-' }}</td>
                        <td class="text-center">
                            @if(strtolower($item->status) === 'approved')
                                <span class="badge-success">Disetujui</span>
                            @else
                                <span class="badge-warning">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->status_pembayaran == '1')
                                <span class="badge-success">Lunas</span>
                            @else
                                <span class="badge-warning">Belum Lunas</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 10px;">Tidak ada data anggota sesuai filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @elseif ($type === 'sertifikat')
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 20px;">No</th>
                    <th>No. Anggota</th>
                    <th>Nama Klinik</th>
                    <th>Wilayah</th>
                    <th>Nomor Sertifikat</th>
                    <th>Jenis Sertifikat</th>
                    <th>Tgl Terbit</th>
                    <th>Masa Berlaku</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $item->no_anggota ?: '-' }}</td>
                        <td><strong>{{ $item->nama_klinik }}</strong></td>
                        <td>{{ $item->kota_name ?: '-' }}</td>
                        <td class="text-center">{{ $item->kode ?: '-' }}</td>
                        <td>{{ $item->sertifikat ? ucwords(str_replace('_', ' ', $item->sertifikat)) : 'Sertifikat Anggota' }}</td>
                        <td class="text-center">{{ $item->dari && $item->dari != '0000-00-00' ? \Carbon\Carbon::parse($item->dari)->format('d-m-Y') : '-' }}</td>
                        <td class="text-center">{{ $item->sampai && $item->sampai != '0000-00-00' ? \Carbon\Carbon::parse($item->sampai)->format('d-m-Y') : '-' }}</td>
                        <td class="text-center">
                            @if($item->status_masa_berlaku === 'Aktif')
                                <span class="badge-success">Aktif</span>
                            @else
                                <span class="badge-danger">Kedaluwarsa</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 10px;">Tidak ada data sertifikat sesuai filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @elseif ($type === 'pembayaran')
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 20px;">No</th>
                    <th>No. Anggota</th>
                    <th>Nama Klinik</th>
                    <th>Wilayah</th>
                    <th>Kategori Pembayaran</th>
                    <th>Tgl Bayar</th>
                    <th>Periode</th>
                    <th>Nominal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $item->no_anggota ?: '-' }}</td>
                        <td><strong>{{ $item->nama_klinik }}</strong></td>
                        <td>{{ $item->kota_name ?: '-' }}</td>
                        <td>{{ $item->nama_kategori ?: 'Uang Iuran' }}</td>
                        <td class="text-center">{{ $item->tanggal_bayar && $item->tanggal_bayar != '0000-00-00' ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d-m-Y') : '-' }}</td>
                        <td class="text-center">
                            {{ $item->dari && $item->dari != '0000-00-00' ? \Carbon\Carbon::parse($item->dari)->format('d-m-Y') : '-' }} s/d {{ $item->sampai && $item->sampai != '0000-00-00' ? \Carbon\Carbon::parse($item->sampai)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="text-right">Rp {{ number_format((float) $item->total, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($item->status == '1')
                                <span class="badge-success">Berhasil / Lunas</span>
                            @else
                                <span class="badge-warning">Menunggu / Tertunda</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 10px;">Tidak ada data pembayaran sesuai filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @elseif ($type === 'rekapitulasi' || $type === 'matriks')
        <h3 style="font-size: 9px; margin: 10px 0 5px 0;">1. Rekapitulasi Wilayah</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Wilayah</th>
                    <th>Total Anggota</th>
                    <th>Aktif (Approved)</th>
                    <th>Inaktif</th>
                    <th>Bayar Lunas</th>
                    <th>Bayar Tertunda</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['regional_data'] as $r)
                    <tr>
                        <td><strong>{{ $r->name }}</strong></td>
                        <td class="text-center">{{ number_format($r->total_anggota) }}</td>
                        <td class="text-center badge-success">{{ number_format($r->aktif) }}</td>
                        <td class="text-center badge-warning">{{ number_format($r->inaktif) }}</td>
                        <td class="text-center">{{ number_format($r->lunas) }}</td>
                        <td class="text-center">{{ number_format($r->belum_lunas) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3 style="font-size: 9px; margin: 10px 0 5px 0;">2. Rekapitulasi Tahun Pendaftaran</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th>Total Pendaftar</th>
                    <th>Disetujui Aktif</th>
                    <th>Status Lunas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['tahun_breakdown'] as $t)
                    <tr>
                        <td class="text-center"><strong>{{ $t->tahun }}</strong></td>
                        <td class="text-center">{{ number_format($t->total) }}</td>
                        <td class="text-center badge-success">{{ number_format($t->disetujui) }}</td>
                        <td class="text-center">{{ number_format($t->lunas) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Dicetak secara otomatis dari Sistem Informasi Manajemen Keanggotaan ASKLIN pada {{ $today->format('d-m-Y H:i:s') }}
    </div>

</body>
</html>

