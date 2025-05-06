@extends('layouts.frontend.layout')

@section('content')
    <section id="page-title" class="bg-light">
        <div class="container clearfix py-4">
            <h1 class="mb-0">STRUKTUR ORGANISASI</h1>
            <h2 class="h4 text-muted mb-0">{{ $struktur->nama_struktur }}</h2>
        </div>
    </section>

    @include('frontend.home.menu')

    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">
                <div class="row mb-4">
                </div>

                <div class="card shadow-sm">
                    <div class="card-heade text-white text-center">
                        <h3 class="mb-0">STRUKTUR ORGANISASI</h3>
                        <h4 class="mb-0">{{ $struktur->nama_struktur }}</h4>
                        <p class="mb-0">Periode: {{ $struktur->periode }}</p>
                    </div>

                    <div class="card-body">
                        {{-- <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th class="w-25">Tingkatan</th>
                                        <td>{{ $struktur->tingkatanPengurus->nama_tingkatan }}</td>
                                    </tr>
                                    @if ($struktur->provinsi_nama)
                                        <tr>
                                            <th>Provinsi</th>
                                            <td>{{ $struktur->provinsi_nama }}</td>
                                        </tr>
                                    @endif
                                    @if ($struktur->kota_nama)
                                        <tr>
                                            <th>Kab/Kota</th>
                                            <td>{{ $struktur->kota_nama }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>Tanggal Muscab</th>
                                        <td>{{ date('d-m-Y', strtotime($struktur->tgl_muscab)) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> --}}

                        <h4 class="text-center text-uppercase fw-bold border-bottom pb-2 mb-4">SUSUNAN PENGURUS</h4>

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
                                } elseif (
                                    strpos($jabatan, 'penasehat') !== false ||
                                    strpos($jabatan, 'penasihat') !== false
                                ) {
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
                                } elseif ($jabatan === 'sekretaris 1') {
                                    $sekretaris1 = $p;
                                } elseif ($jabatan === 'sekretaris 2') {
                                    $sekretaris2 = $p;
                                } elseif ($jabatan === 'sekretaris 3') {
                                    $sekretaris3 = $p;
                                } elseif ($jabatan === 'wakil sekretaris 1') {
                                    $wakil_sekretaris1 = $p;
                                } elseif ($jabatan === 'wakil sekretaris 2') {
                                    $wakil_sekretaris2 = $p;
                                } elseif ($jabatan === 'bendahara') {
                                    $bendahara = $p;
                                } elseif ($jabatan === 'bendahara 1') {
                                    $bendahara1 = $p;
                                } elseif ($jabatan === 'bendahara 2') {
                                    $bendahara2 = $p;
                                } elseif ($jabatan === 'bendahara 3') {
                                    $bendahara3 = $p;
                                } elseif ($jabatan === 'wakil bendahara 1') {
                                    $wakil_bendahara1 = $p;
                                } elseif ($jabatan === 'wakil bendahara 2') {
                                    $wakil_bendahara2 = $p;
                                } elseif ($jabatan === 'wakil bendahara 3') {
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
                            <div class="d-flex mb-3">
                                <div class="fw-bold me-3" style="min-width: 150px;">Dewan Pembina</div>
                                <div>
                                    <ol class="mb-0">
                                        @foreach ($pembina as $p)
                                            <li>{{ $p->nama_lengkap }}</li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                        @endif

                        <!-- Penasehat -->
                        @if (count($penasehat) > 0)
                            <div class="d-flex mb-3">
                                <div class="fw-bold me-3" style="min-width: 150px;">Dewan Penasehat</div>
                                <div>
                                    <ol class="mb-0">
                                        @foreach ($penasehat as $p)
                                            <li>{{ $p->nama_lengkap }}</li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                        @endif

                        <!-- Ketua -->
                        @if ($ketuaumum)
                            <div class="d-flex mb-3">
                                <div class="fw-bold me-3" style="min-width: 150px;">Ketua Umum</div>
                                <div>{{ $ketuaumum->nama_lengkap }}</div>
                            </div>
                        @endif

                        <!-- Wakil Ketua Umum 1 -->
                        @if ($wakil_ketua_umum1)
                            <div class="d-flex mb-3">
                                <div class="fw-bold me-3" style="min-width: 150px;">Wakil Ketua Umum 1</div>
                                <div>{{ $wakil_ketua_umum1->nama_lengkap }}</div>
                            </div>
                        @endif

                        <!-- Wakil Ketua Umum 2 -->
                        @if ($wakil_ketua_umum2)
                            <div class="d-flex mb-3">
                                <div class="fw-bold me-3" style="min-width: 150px;">Wakil Ketua Umum 2</div>
                                <div>{{ $wakil_ketua_umum2->nama_lengkap }}</div>
                            </div>
                        @endif

                        <!-- Ketua -->
                        @if ($ketua)
                            <div class="d-flex mb-3">
                                <div class="fw-bold me-3" style="min-width: 150px;">Ketua</div>
                                <div>{{ $ketua->nama_lengkap }}</div>
                            </div>
                        @endif

                        <!-- Wakil Ketua -->
                        @if ($wakil_ketua)
                            <div class="d-flex mb-3">
                                <div class="fw-bold me-3" style="min-width: 150px;">Wakil Ketua</div>
                                <div>{{ $wakil_ketua->nama_lengkap }}</div>
                            </div>
                        @endif

                        <!-- Ketua 1, 2, 3 -->
                        @for ($i = 1; $i <= 3; $i++)
                            @php $var = 'ketua'.$i; @endphp
                            @if ($$var)
                                <div class="d-flex mb-3">
                                    <div class="fw-bold me-3" style="min-width: 150px;">Ketua {{ $i }}</div>
                                    <div>{{ $$var->nama_lengkap }}</div>
                                </div>
                            @endif
                        @endfor

                        <!-- Sekretaris Jenderal -->
                        @if ($sekretaris_jendral)
                            <div class="d-flex mb-3">
                                <div class="fw-bold me-3" style="min-width: 150px;">Sekretaris Jenderal</div>
                                <div>{{ $sekretaris_jendral->nama_lengkap }}</div>
                            </div>
                        @endif

                        <!-- Sekretaris -->
                        @if ($sekretaris)
                            <div class="d-flex mb-3">
                                <div class="fw-bold me-3" style="min-width: 150px;">Sekretaris</div>
                                <div>{{ $sekretaris->nama_lengkap }}</div>
                            </div>
                        @endif

                        <!-- Sekretaris 1, 2, 3 -->
                        @for ($i = 1; $i <= 3; $i++)
                            @php $var = 'sekretaris'.$i; @endphp
                            @if ($$var)
                                <div class="d-flex mb-3">
                                    <div class="fw-bold me-3" style="min-width: 150px;">Sekretaris {{ $i }}
                                    </div>
                                    <div>{{ $$var->nama_lengkap }}</div>
                                </div>
                            @endif
                        @endfor

                        <!-- Wakil Sekretaris 1, 2 -->
                        @for ($i = 1; $i <= 2; $i++)
                            @php $var = 'wakil_sekretaris'.$i; @endphp
                            @if ($$var)
                                <div class="d-flex mb-3">
                                    <div class="fw-bold me-3" style="min-width: 150px;">Wakil Sekretaris
                                        {{ $i }}</div>
                                    <div>{{ $$var->nama_lengkap }}</div>
                                </div>
                            @endif
                        @endfor

                        <!-- Bendahara -->
                        @if ($bendahara)
                            <div class="d-flex mb-3">
                                <div class="fw-bold me-3" style="min-width: 150px;">Bendahara</div>
                                <div>{{ $bendahara->nama_lengkap }}</div>
                            </div>
                        @endif

                        <!-- Bendahara 1, 2, 3 -->
                        @for ($i = 1; $i <= 3; $i++)
                            @php $var = 'bendahara'.$i; @endphp
                            @if ($$var)
                                <div class="d-flex mb-3">
                                    <div class="fw-bold me-3" style="min-width: 150px;">Bendahara {{ $i }}</div>
                                    <div>{{ $$var->nama_lengkap }}</div>
                                </div>
                            @endif
                        @endfor

                        <!-- Wakil Bendahara 1, 2, 3 -->
                        @for ($i = 1; $i <= 3; $i++)
                            @php $var = 'wakil_bendahara'.$i; @endphp
                            @if ($$var)
                                <div class="d-flex mb-3">
                                    <div class="fw-bold me-3" style="min-width: 150px;">Wakil Bendahara {{ $i }}
                                    </div>
                                    <div>{{ $$var->nama_lengkap }}</div>
                                </div>
                            @endif
                        @endfor

                        @if (count($koordinator_daerah) > 0)
                            <div class="d-flex mb-3">
                                <div class="fw-bold me-3" style="min-width: 150px;">Koordinator Daerah</div>
                                <div>
                                    <ol type="A" class="mb-0">
                                        @foreach ($koordinator_daerah as $koor)
                                            <li class="mb-2">
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
                            <div class="d-flex mb-3">
                                <div class="fw-bold me-3" style="min-width: 150px;">BIDANG-BIDANG:</div>
                            </div>

                            @php $bidangCounter = 1; @endphp
                            @foreach ($bidang as $namaBidang => $dataBidang)
                                <div class="mb-4 ps-4">
                                    <h5 class="fw-bold">{{ $bidangCounter }}. {{ $namaBidang }}</h5>

                                    @if ($dataBidang['ketua'])
                                        <div class="d-flex mb-2">
                                            <div class="fw-bold me-3" style="min-width: 100px;">Ketua</div>
                                            <div>{{ $dataBidang['ketua']->nama_lengkap }}</div>
                                        </div>
                                    @endif

                                    @if (count($dataBidang['anggota']) > 0)
                                        <div class="d-flex">
                                            <div class="fw-bold me-3" style="min-width: 100px;">Anggota</div>
                                            <div>
                                                <ol class="mb-0">
                                                    @foreach ($dataBidang['anggota'] as $anggota)
                                                        <li>{{ $anggota->nama_lengkap }}</li>
                                                    @endforeach
                                                </ol>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @php $bidangCounter++; @endphp
                            @endforeach
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #content,
            #content * {
                visibility: visible;
            }

            #content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .card-header {
                background-color: #f8f9fa !important;
                color: #000 !important;
                border-bottom: 2px solid #333 !important;
            }
        }
    </style>
@endsection
