<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Cetak Laporan ASKLIN</title>
    <!-- Google Fonts & FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        * {
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            font-size: 11px;
        }
        .print-container {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .no-print-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn-primary {
            background: #b91c1c;
            color: white;
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #b91c1c;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 16px;
            color: #991b1b;
            margin: 0 0 5px 0;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .header h2 {
            font-size: 13px;
            color: #334155;
            margin: 0;
            font-weight: 600;
        }
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .meta-item {
            background: #f8fafc;
            padding: 6px 10px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .meta-label {
            font-weight: 600;
            color: #64748b;
        }
        .kpi-row {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        .kpi-box {
            flex: 1;
            padding: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            text-align: center;
        }
        .kpi-box-title {
            font-size: 10px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
        }
        .kpi-box-val {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }
        th {
            background-color: #991b1b;
            color: white;
            font-weight: 600;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #7f1d1d;
        }
        td {
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
        }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .print-container {
                box-shadow: none;
                padding: 0;
            }
            .no-print-bar {
                display: none !important;
            }
            th {
                background-color: #991b1b !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<div class="print-container">
    <!-- Non-printable top action bar -->
    <div class="no-print-bar">
        <div>
            <strong>Mode Cetak Laporan ASKLIN</strong> &bull; Silakan gunakan tombol cetak atau pintasan browser (Ctrl+P).
        </div>
        <div style="display: flex; gap: 8px;">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
            <button class="btn btn-secondary" onclick="window.close()">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>
    </div>

    <!-- Header Dokumen -->
    <div class="header">
        <h1>ASOSIASI KLINIK INDONESIA (ASKLIN)</h1>
        <h2>{{ strtoupper($scope['title']) }}</h2>
    </div>

    <!-- Metadata Grid -->
    <div class="meta-grid">
        <div class="meta-item">
            <span class="meta-label">Jenis Dokumen:</span> {{ $title }}
        </div>
        <div class="meta-item">
            <span class="meta-label">Wilayah Kewenangan:</span> {{ $scope['level'] === 'daerah' ? $scope['provinsi_name'] : $scope['kota_name'] }}
        </div>
        <div class="meta-item">
            <span class="meta-label">Tanggal Cetak:</span> {{ $today->format('d-m-Y H:i:s') }} WIB
        </div>
        <div class="meta-item">
            <span class="meta-label">Petugas:</span> {{ Auth::user()->name ?? 'Pengurus' }}
        </div>
    </div>

    <!-- Executive KPI Row -->
    <div class="kpi-row">
        <div class="kpi-box">
            <div class="kpi-box-title">Total Anggota</div>
            <div class="kpi-box-val">{{ number_format($stats['total_anggota']) }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-box-title">Anggota Aktif</div>
            <div class="kpi-box-val" style="color: #15803d;">{{ number_format($stats['anggota_aktif']) }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-box-title">Anggota Inaktif</div>
            <div class="kpi-box-val" style="color: #b45309;">{{ number_format($stats['anggota_inaktif']) }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-box-title">Sertifikat Aktif</div>
            <div class="kpi-box-val" style="color: #0369a1;">{{ number_format($stats['sertifikat_aktif']) }}</div>
        </div>
        <div class="kpi-box">
            <div class="kpi-box-title">Pembayaran Lunas (Trx)</div>
            <div class="kpi-box-val" style="color: #15803d;">{{ number_format($stats['transaksi_bayar_lunas']) }}</div>
        </div>
    </div>

    <!-- Tabel Data Sesuai Jenis Laporan -->
    @if ($type === 'anggota')
        <table>
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th>No. Anggota</th>
                    <th>Nama Klinik</th>
                    <th>Jenis</th>
                    <th>Pemilik / Kontak</th>
                    <th>Kab/Kota</th>
                    <th>Kecamatan</th>
                    <th>Tgl Daftar</th>
                    <th>Status</th>
                    <th>Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->no_anggota ?: '-' }}</td>
                        <td><strong>{{ $item->nama_klinik }}</strong></td>
                        <td>{{ $item->jenis_klinik ?: '-' }}</td>
                        <td>{{ $item->nama_pemilik_klinik ?: ($item->nama_kontak ?: '-') }}</td>
                        <td>{{ $item->kota_name ?: '-' }}</td>
                        <td>{{ $item->kecamatan_name ?: '-' }}</td>
                        <td class="text-center">{{ $item->tgl_daftar_raw ? \Carbon\Carbon::parse($item->tgl_daftar_raw)->format('d-m-Y') : '-' }}</td>
                        <td class="text-center">
                            @if(strtolower($item->status) === 'approved')
                                <span class="badge badge-success">Disetujui</span>
                            @else
                                <span class="badge badge-warning">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->status_pembayaran == '1')
                                <span class="badge badge-success">Lunas</span>
                            @else
                                <span class="badge badge-warning">Belum Lunas</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data anggota.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @elseif ($type === 'sertifikat')
        <table>
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th>No. Anggota</th>
                    <th>Nama Klinik</th>
                    <th>Wilayah</th>
                    <th>Nomor Sertifikat (Kode)</th>
                    <th>Jenis Sertifikat</th>
                    <th>Tanggal Terbit</th>
                    <th>Masa Berlaku</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->no_anggota ?: '-' }}</td>
                        <td><strong>{{ $item->nama_klinik }}</strong></td>
                        <td>{{ $item->kota_name ?: '-' }}</td>
                        <td>{{ $item->kode ?: '-' }}</td>
                        <td>{{ $item->sertifikat ? ucwords(str_replace('_', ' ', $item->sertifikat)) : 'Sertifikat Anggota' }}</td>
                        <td class="text-center">{{ $item->dari && $item->dari != '0000-00-00' ? \Carbon\Carbon::parse($item->dari)->format('d-m-Y') : '-' }}</td>
                        <td class="text-center">{{ $item->sampai && $item->sampai != '0000-00-00' ? \Carbon\Carbon::parse($item->sampai)->format('d-m-Y') : '-' }}</td>
                        <td class="text-center">
                            @if($item->status_masa_berlaku === 'Aktif')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Kedaluwarsa</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data sertifikat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @elseif ($type === 'pembayaran')
        <table>
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th>No. Anggota</th>
                    <th>Nama Klinik</th>
                    <th>Wilayah</th>
                    <th>Kategori Pembayaran</th>
                    <th>Tanggal Bayar</th>
                    <th>Periode</th>
                    <th>Nominal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->no_anggota ?: '-' }}</td>
                        <td><strong>{{ $item->nama_klinik }}</strong></td>
                        <td>{{ $item->kota_name ?: '-' }}</td>
                        <td>{{ $item->nama_kategori ?: 'Pembayaran Iuran' }}</td>
                        <td class="text-center">{{ $item->tanggal_bayar && $item->tanggal_bayar != '0000-00-00' ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d-m-Y') : '-' }}</td>
                        <td class="text-center">
                            {{ $item->dari && $item->dari != '0000-00-00' ? \Carbon\Carbon::parse($item->dari)->format('d-m-Y') : '-' }} s/d {{ $item->sampai && $item->sampai != '0000-00-00' ? \Carbon\Carbon::parse($item->sampai)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="text-right">Rp {{ number_format((float) $item->total, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($item->status == '1')
                                <span class="badge badge-success">Berhasil / Lunas</span>
                            @else
                                <span class="badge badge-warning">Menunggu / Tertunda</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data transaksi pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @elseif ($type === 'rekapitulasi' || $type === 'matriks')
        <h3 style="font-size: 12px; margin: 15px 0 6px 0; color: #991b1b;">1. Rekapitulasi Wilayah</h3>
        <table>
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
                        <td class="text-center" style="color: #15803d; font-weight: bold;">{{ number_format($r->aktif) }}</td>
                        <td class="text-center" style="color: #b45309;">{{ number_format($r->inaktif) }}</td>
                        <td class="text-center">{{ number_format($r->lunas) }}</td>
                        <td class="text-center">{{ number_format($r->belum_lunas) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3 style="font-size: 12px; margin: 15px 0 6px 0; color: #991b1b;">2. Rekapitulasi Tahun Pendaftaran</h3>
        <table>
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
                        <td class="text-center" style="color: #15803d; font-weight: bold;">{{ number_format($t->disetujui) }}</td>
                        <td class="text-center">{{ number_format($t->lunas) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Dicetak secara otomatis dari Sistem Informasi Manajemen Keanggotaan ASKLIN pada {{ $today->format('d-m-Y H:i:s') }} WIB
    </div>
</div>

<script>
    // Auto prompt print on open if parameter auto=1
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('auto') === '1') {
        window.addEventListener('load', () => window.print());
    }
</script>
</body>
</html>

