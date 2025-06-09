<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Expired Sertifikat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
            color: #333;
        }
        .header h2 {
            font-size: 16px;
            margin: 10px 0;
            color: #666;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        .info-table .label {
            font-weight: bold;
            background-color: #f5f5f5;
            width: 150px;
        }
        .stats-section {
            margin-bottom: 30px;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .stats-table th, .stats-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        .stats-table th {
            background-color: #4472C4;
            color: white;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        .data-table th {
            background-color: #4472C4;
            color: white;
            padding: 8px 4px;
            text-align: center;
            border: 1px solid #333;
            font-weight: bold;
        }
        .data-table td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-expired { background-color: #ffebee; color: #c62828; font-weight: bold; }
        .status-hari-ini { background-color: #fff3e0; color: #ef6c00; font-weight: bold; }
        .status-besok { background-color: #fff3e0; color: #ef6c00; font-weight: bold; }
        .status-seminggu { background-color: #fff3e0; color: #f57c00; font-weight: bold; }
        .status-sebulan { background-color: #e3f2fd; color: #1976d2; font-weight: bold; }
        .status-aman { background-color: #e8f5e8; color: #388e3c; font-weight: bold; }
        .text-center { text-align: center; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN EXPIRED SERTIFIKAT KLINIK</h1>
        <h2>Filter: {{ ucwords(str_replace('_', ' ', $filterName)) }}</h2>
    </div>

    <!-- Info Section -->
    <table class="info-table">
        <tr>
            <td class="label">Tanggal Cetak:</td>
            <td>{{ $today->format('d F Y, H:i:s') }}</td>
            <td class="label">Total Data:</td>
            <td>{{ $stats['total'] }} Sertifikat</td>
        </tr>
        <tr>
            <td class="label">Filter:</td>
            <td>{{ ucwords(str_replace('_', ' ', $filterName)) }}</td>
            <td class="label">User:</td>
            <td>{{ Auth::user()->name }} ({{ Auth::user()->getRoleNames()->first() }})</td>
        </tr>
    </table>

    <!-- Statistics -->
    <div class="stats-section">
        <h3 style="margin-bottom: 15px; color: #4472C4;">RINGKASAN STATUS SERTIFIKAT</h3>
        <table class="stats-table">
            <tr>
                <th>Total Sertifikat</th>
                <th>Sudah Expired</th>
                <th>Hari Ini</th>
                <th>Besok Expired</th>
                <th>Dalam Seminggu</th>
                <th>Dalam Sebulan</th>
                <th>Aman</th>
            </tr>
            <tr>
                <td><strong>{{ $stats['total'] }}</strong></td>
                <td style="background-color: #ffebee;"><strong>{{ $stats['expired'] }}</strong></td>
                <td style="background-color: #fff3e0;"><strong>{{ $stats['hari_ini'] }}</strong></td>
                <td style="background-color: #fff3e0;"><strong>{{ $stats['besok'] }}</strong></td>
                <td style="background-color: #fff3e0;"><strong>{{ $stats['seminggu'] }}</strong></td>
                <td style="background-color: #e3f2fd;"><strong>{{ $stats['sebulan'] }}</strong></td>
                <td style="background-color: #e8f5e8;"><strong>{{ $stats['aman'] }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">No Anggota</th>
                <th width="20%">Nama Klinik</th>
                <th width="12%">Kab/Kota</th>
                <th width="15%">Email</th>
                <th width="12%">No Sertifikat</th>
                <th width="9%">Tgl Terbit</th>
                <th width="9%">Tgl Expired</th>
                <th width="7%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                @php
                    $today = \Carbon\Carbon::today();
                    $expiredDate = \Carbon\Carbon::parse($item->expired_date);
                    
                    $status = '-';
                    $statusClass = '';
                    
                    if ($expiredDate->isPast()) {
                        $status = 'EXPIRED';
                        $statusClass = 'status-expired';
                    } elseif ($expiredDate->isToday()) {
                        $status = 'HARI INI';
                        $statusClass = 'status-hari-ini';
                    } elseif ($expiredDate->isTomorrow()) {
                        $status = 'BESOK';
                        $statusClass = 'status-besok';
                    } elseif ($today->diffInDays($expiredDate) <= 7) {
                        $status = 'SEMINGGU';
                        $statusClass = 'status-seminggu';
                    } elseif ($today->diffInDays($expiredDate) <= 30) {
                        $status = 'SEBULAN';
                        $statusClass = 'status-sebulan';
                    } else {
                        $status = 'AMAN';
                        $statusClass = 'status-aman';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->no_anggota ?: '-' }}</td>
                    <td>{{ $item->nama_klinik ?: '-' }}</td>
                    <td>{{ $item->kota ?: '-' }}</td>
                    <td>{{ $item->email ?: '-' }}</td>
                    <td class="text-center">{{ $item->no_sertifikat ?: '-' }}</td>
                    <td class="text-center">{{ $item->dari ? \Carbon\Carbon::parse($item->dari)->format('d-m-Y') : '-' }}</td>
                    <td class="text-center">{{ $expiredDate->format('d-m-Y') }}</td>
                    <td class="text-center {{ $statusClass }}">{{ $status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>
            Laporan ini digenerate secara otomatis pada {{ $today->format('d F Y, H:i:s') }} | 
            Total {{ $stats['total'] }} data sertifikat | 
            Filter: {{ ucwords(str_replace('_', ' ', $filterName)) }}
        </p>
    </div>
</body>
</html>