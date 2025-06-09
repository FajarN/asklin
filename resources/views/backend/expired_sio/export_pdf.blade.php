<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Expired SIO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 15px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #4472C4;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #4472C4;
            font-weight: bold;
        }
        .header h2 {
            font-size: 14px;
            margin: 5px 0;
            color: #666;
        }
        .info-section {
            margin-bottom: 15px;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
        }
        .info-section table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-section td {
            padding: 3px 8px;
            font-size: 9px;
        }
        .info-section .label {
            font-weight: bold;
            width: 120px;
        }
        .statistics {
            margin-bottom: 15px;
        }
        .stat-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
            background-color: #f0f7ff;
        }
        .stat-number {
            font-size: 16px;
            font-weight: bold;
            color: #4472C4;
        }
        .stat-label {
            font-size: 8px;
            color: #666;
            margin-top: 2px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 8px;
        }
        .data-table th {
            background-color: #4472C4;
            color: white;
            padding: 6px 4px;
            text-align: center;
            border: 1px solid #333;
            font-weight: bold;
            font-size: 8px;
        }
        .data-table td {
            padding: 4px 3px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-expired {
            background-color: #ffe6e6;
            color: #cc0000;
            font-weight: bold;
            text-align: center;
        }
        .status-besok {
            background-color: #fff2cc;
            color: #b8860b;
            font-weight: bold;
            text-align: center;
        }
        .status-seminggu {
            background-color: #fff2cc;
            color: #ff8c00;
            font-weight: bold;
            text-align: center;
        }
        .status-sebulan {
            background-color: #e6f3ff;
            color: #4472c4;
            font-weight: bold;
            text-align: center;
        }
        .status-aman {
            background-color: #e8f5e8;
            color: #008000;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-before: always;
        }
        @page {
            margin: 15mm;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN EXPIRED SURAT IZIN OPERASIONAL (SIO)</h1>
        <h2>{{ ucwords(str_replace('_', ' ', $filterName)) }}</h2>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <table>
            <tr>
                <td class="label">Tanggal Cetak:</td>
                <td>{{ $today->format('d F Y, H:i:s') }}</td>
                <td class="label">Filter:</td>
                <td>{{ ucwords(str_replace('_', ' ', $filterName)) }}</td>
            </tr>
            <tr>
                <td class="label">Total Data:</td>
                <td>{{ $stats['total'] }} Klinik</td>
                <td class="label">User:</td>
                <td>{{ Auth::user()->name }} ({{ Auth::user()->getRoleNames()->first() }})</td>
            </tr>
        </table>
    </div>

    <!-- Statistics -->
    <div class="statistics">
        <h3 style="margin: 0 0 10px 0; font-size: 12px; color: #4472C4;">RINGKASAN STATUS SIO</h3>
        <div class="stat-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Klinik</div>
            </div>
            <div class="stat-item" style="background-color: #ffe6e6;">
                <div class="stat-number" style="color: #cc0000;">{{ $stats['expired'] }}</div>
                <div class="stat-label">Sudah Expired</div>
            </div>
            <div class="stat-item" style="background-color: #fff2cc;">
                <div class="stat-number" style="color: #b8860b;">{{ $stats['besok'] }}</div>
                <div class="stat-label">Besok Expired</div>
            </div>
            <div class="stat-item" style="background-color: #fff2cc;">
                <div class="stat-number" style="color: #ff8c00;">{{ $stats['seminggu'] }}</div>
                <div class="stat-label">Dalam Seminggu</div>
            </div>
            <div class="stat-item" style="background-color: #e6f3ff;">
                <div class="stat-number" style="color: #4472c4;">{{ $stats['sebulan'] }}</div>
                <div class="stat-label">Dalam Sebulan</div>
            </div>
            <div class="stat-item" style="background-color: #e8f5e8;">
                <div class="stat-number" style="color: #008000;">{{ $stats['aman'] }}</div>
                <div class="stat-label">Aman</div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="8%">No Anggota</th>
                <th width="15%">Nama Klinik</th>
                <th width="10%">Kab/Kota</th>
                <th width="8%">Kecamatan</th>
                <th width="12%">Email</th>
                <th width="8%">No Ijin</th>
                <th width="7%">Tgl Ijin</th>
                <th width="7%">Tgl Akhir</th>
                <th width="7%">Status</th>
                <th width="5%">Sisa</th>
                <th width="10%">Kontak</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                @php
                    $today = \Carbon\Carbon::today();
                    $tglAkhir = $item->tgl_akhir_ijin ? \Carbon\Carbon::parse($item->tgl_akhir_ijin) : null;
                    
                    $status = '-';
                    $statusClass = '';
                    $sisaHari = '-';
                    
                    if ($tglAkhir) {
                        $diffDays = $today->diffInDays($tglAkhir, false);
                        
                        if ($tglAkhir->isPast()) {
                            $status = 'EXPIRED';
                            $statusClass = 'status-expired';
                            $sisaHari = abs($diffDays) . ' hari lalu';
                        } elseif ($tglAkhir->isTomorrow()) {
                            $status = 'BESOK';
                            $statusClass = 'status-besok';
                            $sisaHari = '1 hari';
                        } elseif ($today->diffInDays($tglAkhir) <= 7) {
                            $status = 'SEMINGGU';
                            $statusClass = 'status-seminggu';
                            $sisaHari = $diffDays . ' hari';
                        } elseif ($today->diffInDays($tglAkhir) <= 30) {
                            $status = 'SEBULAN';
                            $statusClass = 'status-sebulan';
                            $sisaHari = $diffDays . ' hari';
                        } else {
                            $status = 'AMAN';
                            $statusClass = 'status-aman';
                            $sisaHari = $diffDays . ' hari';
                        }
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->no_anggota ?: '-' }}</td>
                    <td>{{ $item->nama_klinik ?: '-' }}</td>
                    <td>{{ $item->name ?: '-' }}</td>
                    <td>{{ $item->kecamatan ?: '-' }}</td>
                    <td>{{ $item->email ?: '-' }}</td>
                    <td class="text-center">{{ $item->no_ijin ?: '-' }}</td>
                    <td class="text-center">{{ $item->tgl_ijin ? \Carbon\Carbon::parse($item->tgl_ijin)->format('d-m-Y') : '-' }}</td>
                    <td class="text-center">{{ $tglAkhir ? $tglAkhir->format('d-m-Y') : '-' }}</td>
                    <td class="{{ $statusClass }}">{{ $status }}</td>
                    <td class="text-center">{{ $sisaHari }}</td>
                    <td>{{ $item->nama_kontak ?: '-' }}</td>
                </tr>
                
                @if(($index + 1) % 25 == 0 && ($index + 1) < count($data))
                    </tbody>
                    </table>
                    <div class="page-break"></div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="3%">No</th>
                                <th width="8%">No Anggota</th>
                                <th width="15%">Nama Klinik</th>
                                <th width="10%">Kab/Kota</th>
                                <th width="8%">Kecamatan</th>
                                <th width="12%">Email</th>
                                <th width="8%">No Ijin</th>
                                <th width="7%">Tgl Ijin</th>
                                <th width="7%">Tgl Akhir</th>
                                <th width="7%">Status</th>
                                <th width="5%">Sisa</th>
                                <th width="10%">Kontak</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>
            Laporan ini digenerate secara otomatis pada {{ $today->format('d F Y, H:i:s') }} | 
            Total {{ $stats['total'] }} data klinik | 
            Filter: {{ ucwords(str_replace('_', ' ', $filterName)) }}
        </p>
        <p style="margin-top: 5px; font-style: italic;">
            ** Data yang ditampilkan adalah klinik dengan status approved dan sesuai dengan role user yang melakukan export
        </p>
    </div>
</body>
</html>