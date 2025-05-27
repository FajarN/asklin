<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur Organisasi - {{ $struktur->nama_struktur }}</title>
    <style>
        /* Styles for both screen and print */
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            margin-bottom: 5px;
            font-size: 22px;
        }

        .header p {
            margin: 5px 0;
            font-size: 16px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }

        .info-table th,
        .info-table td {
            padding: 8px 5px;
            vertical-align: top;
        }

        .info-table th {
            width: 30%;
            text-align: left;
        }

        .section-title {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin: 25px 0 15px;
            text-transform: uppercase;
        }

        .position {
            margin-bottom: 15px;
        }

        .position-title {
            font-weight: bold;
            display: inline-block;
            min-width: 120px;
            vertical-align: top;
        }

        .position-content {
            display: inline-block;
            vertical-align: top;
        }

        ol {
            margin-top: 0;
            margin-bottom: 0;
            padding-left: 25px;
        }

        li {
            margin-bottom: 5px;
        }

        .bidang {
            margin: 20px 0;
        }

        .bidang-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .bidang-position {
            margin-left: 20px;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
        }

        .footer-date {
            margin-bottom: 70px;
        }

        .footer-sign {
            font-weight: bold;
        }

        .no-print {
            text-align: center;
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 0 5px;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }

            .container {
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn">Cetak</button>
        <a href="{{ route('struktur_organisasi.detail', $struktur->id) }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="container">
        <div class="header">
            <h1>STRUKTUR ORGANISASI</h1>
            <p>{{ $struktur->nama_struktur }}</p>
            <p>Periode: {{ $struktur->periode }}</p>
        </div>

        <table class="info-table">
            <tr>
                <th>Tingkatan</th>
                <td>: {{ $struktur->tingkatanPengurus->nama_tingkatan }}</td>
            </tr>
            @if ($struktur->provinsi_nama)
                <tr>
                    <th>Provinsi</th>
                    <td>: {{ $struktur->provinsi_nama }}</td>
                </tr>
            @endif
            @if ($struktur->kota_nama)
                <tr>
                    <th>Kab/Kota</th>
                    <td>: {{ $struktur->kota_nama }}</td>
                </tr>
            @endif
            <tr>
                <th>Tanggal Muscab</th>
                <td>: {{ date('d-m-Y', strtotime($struktur->tgl_muscab)) }}</td>
            </tr>
        </table>

        <div class="section-title">SUSUNAN PENGURUS</div>

        @php
            $pembina = [];
            $penasehat = [];
            $bidang = [];
            $koordinator_daerah = [];

            $ketuaumum = null;
            $wakil_ketua_umum1 = null;
            $wakil_ketua_umum2 = null;
            $ketua = null;
            $wakil_ketua = null;
            $ketua1 = null;
            $ketua2 = null;
            $ketua3 = null;

            $sekretaris_jendral = null;
            $sekretaris = null;
            $sekretaris1 = null;
            $sekretaris2 = null;
            $sekretaris3 = null;
            $wakil_sekretaris1 = null;
            $wakil_sekretaris2 = null;

            $bendahara = null;
            $bendahara1 = null;
            $bendahara2 = null;
            $bendahara3 = null;
            $wakil_bendahara1 = null;
            $wakil_bendahara2 = null;
            $wakil_bendahara3 = null;

            foreach ($pengurus as $p) {
                $jabatan = strtolower(trim($p->jabatan));

                if (strpos($jabatan, 'pembina') !== false) {
                    $pembina[] = $p;
                } elseif (strpos($jabatan, 'penasehat') !== false || strpos($jabatan, 'penasihat') !== false) {
                    $penasehat[] = $p;
                } elseif ($jabatan === 'ketua umum') {
                    $ketuaumum = $p;
                } elseif ($jabatan === 'wakil ketua umum 1') {
                    $wakil_ketua_umum1 = $p;
                } elseif ($jabatan === 'wakil ketua umum 2') {
                    $wakil_ketua_umum2 = $p;
                } elseif ($jabatan === 'ketua') {
                    $ketua = $p;
                } elseif ($jabatan === 'wakil ketua') {
                    $wakil_ketua = $p;
                } elseif ($jabatan === 'ketua 1') {
                    $ketua1 = $p;
                } elseif ($jabatan === 'ketua 2') {
                    $ketua2 = $p;
                } elseif ($jabatan === 'ketua 3') {
                    $ketua3 = $p;
                } elseif ($jabatan === 'sekretaris jenderal') {
                    $sekretaris_jendral = $p;
                } elseif ($jabatan === 'sekretaris') {
                    $sekretaris = $p;
                } elseif ($jabatan === 'sekretaris I') {
                    $sekretaris1 = $p;
                } elseif ($jabatan === 'sekretaris II') {
                    $sekretaris2 = $p;
                } elseif ($jabatan === 'sekretaris III') {
                    $sekretaris3 = $p;
                } elseif ($jabatan === 'wakil sekretaris I') {
                    $wakil_sekretaris1 = $p;
                } elseif ($jabatan === 'wakil sekretaris II') {
                    $wakil_sekretaris2 = $p;
                } elseif ($jabatan === 'bendahara') {
                    $bendahara = $p;
                } elseif ($jabatan === 'bendahara I') {
                    $bendahara1 = $p;
                } elseif ($jabatan === 'bendahara II') {
                    $bendahara2 = $p;
                } elseif ($jabatan === 'bendahara III') {
                    $bendahara3 = $p;
                } elseif ($jabatan === 'wakil bendahara I') {
                    $wakil_bendahara1 = $p;
                } elseif ($jabatan === 'wakil bendahara II') {
                    $wakil_bendahara2 = $p;
                } elseif ($jabatan === 'wakil bendahara III') {
                    $wakil_bendahara3 = $p;
                } elseif (strpos($jabatan, 'koordinator daerah') !== false) {
                    $wilayah = $p->keterangan ?? '';
                    $koordinator_daerah[] = [
                        'wilayah' => $wilayah,
                        'nama_lengkap' => $p->nama_lengkap,
                    ];
                } elseif (strpos($jabatan, 'bidang') !== false) {
                    $bidangName = $p->jabatan;
                    if (!isset($bidang[$bidangName])) {
                        $bidang[$bidangName] = [
                            'ketua' => null,
                            'anggota' => [],
                        ];
                    }

                    $anggota = \App\Models\StrukturPengurus::where('parent_id', $p->id)
                        ->orderBy('urutan', 'asc')
                        ->get();

                    $bidang[$bidangName]['ketua'] = $p;
                    $bidang[$bidangName]['anggota'] = $anggota;
                }
            }
        @endphp


        <!-- Pembina -->
        @if (count($pembina) > 0)
            <div class="position">
                <div class="position-title">Dewan Pembina</div>
                <div class="position-content"><br>
                    <ol>
                        @foreach ($pembina as $p)
                            <li>{{ $p->nama_lengkap }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        @endif

        <!-- Penasehat -->
        @if (count($penasehat) > 0)
            <div class="position">
                <div class="position-title">Dewan Penasehat</div>
                <div class="position-content">
                    <ol>
                        @foreach ($penasehat as $p)
                            <li>{{ $p->nama_lengkap }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        @endif

        <!-- Ketua -->
        @if ($ketuaumum)
            <div class="position">
                <div class="position-title">Ketua Umum</div>
                <div class="position-content">{{ $ketuaumum->nama_lengkap }}</div>
            </div>
        @endif

        <!-- Wakil Ketua Umum 1 -->
        @if ($wakil_ketua_umum1)
            <div class="position">
                <div class="position-title">Wakil Ketua Umum 1</div>
                <div class="position-content">{{ $wakil_ketua_umum1->nama_lengkap }}</div>
            </div>
        @endif

        <!-- Wakil Ketua Umum 2 -->
        @if ($wakil_ketua_umum2)
            <div class="position">
                <div class="position-title">Wakil Ketua Umum 2</div>
                <div class="position-content">{{ $wakil_ketua_umum2->nama_lengkap }}</div>
            </div>
        @endif

        <!-- Ketua -->
        @if ($ketua)
            <div class="position">
                <div class="position-title">Ketua</div>
                <div class="position-content">{{ $ketua->nama_lengkap }}</div>
            </div>
        @endif

        <!-- Wakil Ketua -->
        @if ($wakil_ketua)
            <div class="position">
                <div class="position-title">Wakil Ketua</div>
                <div class="position-content">{{ $wakil_ketua->nama_lengkap }}</div>
            </div>
        @endif

        <!-- Ketua 1, 2, 3 -->
        @for ($i = 1; $i <= 3; $i++)
            @php $var = 'ketua'.$i; @endphp
            @if ($$var)
                <div class="position">
                    <div class="position-title">Ketua {{ $i }}</div>
                    <div class="position-content">{{ $$var->nama_lengkap }}</div>
                </div>
            @endif
        @endfor

        <!-- Sekretaris Jenderal -->
        @if ($sekretaris_jendral)
            <div class="position">
                <div class="position-title">Sekretaris Jenderal</div>
                <div class="position-content">{{ $sekretaris_jendral->nama_lengkap }}</div>
            </div>
        @endif


        <!-- Sekretaris -->
        @if ($sekretaris)
            <div class="position">
                <div class="position-title">Sekretaris</div>
                <div class="position-content">{{ $sekretaris->nama_lengkap }}</div>
            </div>
        @endif


        <!-- Sekretaris 1, 2, 3 -->
        @for ($i = 1; $i <= 3; $i++)
            @php $var = 'sekretaris'.$i; @endphp
            @if ($$var)
                <div class="position">
                    <div class="position-title">Sekretaris {{ $i }}</div>
                    <div class="position-content">{{ $$var->nama_lengkap }}</div>
                </div>
            @endif
        @endfor

        <!-- Wakil Sekretaris 1, 2 -->
        @for ($i = 1; $i <= 2; $i++)
            @php $var = 'wakil_sekretaris'.$i; @endphp
            @if ($$var)
                <div class="position">
                    <div class="position-title">Wakil Sekretaris {{ $i }}</div>
                    <div class="position-content">{{ $$var->nama_lengkap }}</div>
                </div>
            @endif
        @endfor

        <!-- Bendahara -->
        @if ($bendahara)
            <div class="position">
                <div class="position-title">Bendahara</div>
                <div class="position-content">{{ $bendahara->nama_lengkap }}</div>
            </div>
        @endif


        <!-- Bendahara 1, 2, 3 -->
        @for ($i = 1; $i <= 3; $i++)
            @php $var = 'bendahara'.$i; @endphp
            @if ($$var)
                <div class="position">
                    <div class="position-title">Bendahara {{ $i }}</div>
                    <div class="position-content">{{ $$var->nama_lengkap }}</div>
                </div>
            @endif
        @endfor

        <!-- Wakil Bendahara 1, 2, 3 -->
        @for ($i = 1; $i <= 3; $i++)
            @php $var = 'wakil_bendahara'.$i; @endphp
            @if ($$var)
                <div class="position">
                    <div class="position-title">Wakil Bendahara {{ $i }}</div>
                    <div class="position-content">{{ $$var->nama_lengkap }}</div>
                </div>
            @endif
        @endfor

        @if (count($koordinator_daerah) > 0)
            <div class="position">
                <div class="position-title">Koordinator Daerah</div>
                <div class="position-content">
                    <ol type="A">
                        @foreach ($koordinator_daerah as $koor)
                            <li>
                                <strong>{{ strtoupper($koor['wilayah']) }}</strong><br>
                                {{ $koor['nama_lengkap'] }}
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        @endif

        <!-- Bidang-bidang -->
        @if (count($bidang) > 0)
            <div class="position">
                <div class="position-title">BIDANG-BIDANG:</div>
            </div>

            @php $bidangCounter = 1; @endphp
            @foreach ($bidang as $namaBidang => $dataBidang)
                <div class="bidang">
                    <div class="bidang-title">{{ $bidangCounter }}. {{ $namaBidang }}</div>

                    @if ($dataBidang['ketua'])
                        <div class="bidang-position">
                            <span class="position-title">Ketua</span> : {{ $dataBidang['ketua']->nama_lengkap }}
                        </div>
                    @endif

                    @if (count($dataBidang['anggota']) > 0)
                        <div class="bidang-position">
                            <span class="position-title">Anggota</span> :
                            <ol>
                                @foreach ($dataBidang['anggota'] as $anggota)
                                    <li>{{ $anggota->nama_lengkap }}</li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                </div>
                @php $bidangCounter++; @endphp
            @endforeach
        @endif

        <div class="footer">
            <div class="footer-date">
                {{ $struktur->kota_nama ?? ($struktur->provinsi_nama ?? '') }}, {{ date('d F Y') }}
            </div>
            <div class="footer-sign">
            </div>
        </div>
    </div>
</body>

</html>
