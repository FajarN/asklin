<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Anggota ASKLIN</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #333;
        }
        .header h2 {
            font-size: 14px;
            margin: 10px 0;
            color: #666;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 6px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        .info-table .label {
            font-weight: bold;
            background-color: #f5f5f5;
            width: 120px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }
        .data-table th {
            background-color: #4472C4;
            color: white;
            padding: 6px 3px;
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
            font-size: 8px;
        }
        .data-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center { text-align: center; }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        @page {
            margin: 15mm;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN DATA ANGGOTA ASKLIN</h1>
        <h2>Laporan Komprehensif Keanggotaan</h2>
    </div>

    <!-- Info Section -->
    <table class="info-table">
        <tr>
            <td class="label">Tanggal Cetak:</td>
            <td>{{ $today->format('d F Y, H:i:s') }}</td>
            <td class="label">Total Data:</td>
            <td>{{ $data->count() }} Anggota</td>
        </tr>
        <tr>
            <td class="label">Kolom Dipilih:</td>
            <td colspan="3">
                @foreach($selectedColumns as $column)
                    {{ $availableColumnsData[$column]['label'] ?? ucwords(str_replace('_', ' ', $column)) }}@if(!$loop->last), @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <td class="label">User:</td>
            <td>{{ Auth::user()->name }}</td>
            <td class="label">Role:</td>
            <td>{{ Auth::user()->getRoleNames()->first() }}</td>
        </tr>
    </table>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                @foreach($selectedColumns as $column)
                    @php
                        $width = match($column) {
                            'id' => '4%',
                            'no_anggota' => '8%',
                            'nama_klinik' => '15%',
                            'provinsi', 'name' => '10%',
                            'kecamatan', 'kelurahan' => '8%',
                            'email' => '12%',
                            'tlf', 'tlf_klinik' => '8%',
                            'alamat_klinik' => '15%',
                            'jenis_klinik', 'status' => '8%',
                            'kriteria' => '12%',
                            'tgl_ijin', 'tgl_akhir_ijin', 'created_on' => '7%',
                            default => '6%'
                        };
                    @endphp
                    <th width="{{ $width }}">{{ $availableColumnsData[$column]['label'] ?? ucwords(str_replace('_', ' ', $column)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    @foreach($selectedColumns as $column)
                        <td>
                            @switch($column)
                                @case('created_on')
                                    {{ \Carbon\Carbon::createFromTimestamp($item->created_on)->format('d-m-Y') }}
                                    @break
                                @case('tgl_ijin')
                                    {{ $item->tgl_ijin ? \Carbon\Carbon::parse($item->tgl_ijin)->format('d-m-Y') : '-' }}
                                    @break
                                @case('tgl_akhir_ijin')
                                    {{ $item->tgl_akhir_ijin ? \Carbon\Carbon::parse($item->tgl_akhir_ijin)->format('d-m-Y') : '-' }}
                                    @break
                                @case('verifikasi_cabang')
                                    {{ $item->verifikasi_cabang ? \Carbon\Carbon::parse($item->verifikasi_cabang)->format('d-m-Y H:i') : '-' }}
                                    @break
                                @case('verifikasi_pusat')
                                    {{ $item->verifikasi_pusat ? \Carbon\Carbon::parse($item->verifikasi_pusat)->format('d-m-Y H:i') : '-' }}
                                    @break
                                @case('status_pembayaran')
                                    {{ $item->status_pembayaran == '1' ? 'Lunas' : 'Belum Lunas' }}
                                    @break
                                @case('alamat_klinik')
                                    {{ Str::limit($item->alamat_klinik ?: '-', 50) }}
                                    @break
                                @case('kriteria')
                                    {{ Str::limit($item->kriteria ?: '-', 40) }}
                                    @break
                                @default
                                    {{ $item->{$column} ?: '-' }}
                            @endswitch
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p>
            Laporan ini digenerate secara otomatis pada {{ $today->format('d F Y, H:i:s') }} | 
            Total {{ $data->count() }} data anggota | 
            {{ count($selectedColumns) }} kolom dipilih
        </p>
        <p style="margin-top: 5px; font-style: italic;">
            ** Data yang ditampilkan sesuai dengan kolom yang dipilih oleh user
        </p>
    </div>
</body>
</html>