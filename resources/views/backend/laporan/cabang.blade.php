@extends('layouts.backend.layout')

@section('title', 'Laporan Cabang - ' . $scope['kota_name'])
@section('page_title', 'Laporan Tingkat Cabang')

@section('content')
<div class="space-y-6">

    <!-- Responsive Standard Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Laporan & Rekapitulasi'],
        ['label' => 'Laporan Cabang (Kota/Kab)'],
    ]" />

    <!-- Executive Header Banner -->
    <div class="relative rounded-2xl p-6 sm:p-7 text-white shadow-lg overflow-visible"
         style="background: linear-gradient(135deg, #991b1b 0%, #dc2626 50%, #7f1d1d 100%);">
        <!-- Inner decorative clipping layer -->
        <div class="absolute inset-0 rounded-2xl overflow-hidden pointer-events-none">
            <div class="absolute -right-10 -bottom-16 w-56 h-56 rounded-full bg-white/10 blur-2xl"></div>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-white/20 text-white backdrop-blur-sm border border-white/30">
                        <i class="fas fa-building mr-1"></i> PENGURUS CABANG <span id="bannerRegionName">{{ strtoupper($scope['kota_name']) }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-black/25 text-rose-200 border border-rose-400/30">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>LIVE REPORTING</span>
                    </span>
                    <span class="text-xs text-rose-100">
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-white" id="bannerTitle">
                    {{ $scope['title'] }}
                </h1>
                <p class="text-xs sm:text-sm text-rose-100 max-w-2xl leading-relaxed" id="bannerSubtitle">
                    {{ $scope['subtitle'] }}. Seluruh statistik, visualisasi grafik, tabel data, serta ekspor diselaraskan secara otomatis dengan filter aktif.
                </p>
            </div>

            <!-- Export & Action Dropdowns -->
            <div class="flex items-center gap-2.5 flex-wrap shrink-0 relative z-20">
                <!-- Dropdown Export Excel -->
                <div class="dropdown">
                    <button class="btn btn-sm text-white font-bold px-3 py-2 rounded-xl shadow-md transition hover:scale-105 flex items-center gap-1.5"
                            type="button" id="dropdownExcel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            style="background-color: #15803d !important;">
                        <i class="fas fa-file-excel"></i>
                        <span>Export Excel</span>
                        <i class="fas fa-chevron-down text-[10px] ml-1"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow-xl border-0 rounded-xl py-2" aria-labelledby="dropdownExcel" style="z-index: 1050;">
                        <h6 class="dropdown-header text-[10px] uppercase font-bold text-slate-400">Pilih Kategori Excel</h6>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="excel" data-type="dashboard">
                            <i class="fas fa-chart-pie text-red-600 mr-2"></i> Ringkasan Eksekutif & KPI
                        </a>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="excel" data-type="anggota">
                            <i class="fas fa-clinic-medical text-emerald-600 mr-2"></i> Laporan Data Anggota
                        </a>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="excel" data-type="sertifikat">
                            <i class="fas fa-certificate text-blue-600 mr-2"></i> Laporan Sertifikat
                        </a>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="excel" data-type="pembayaran">
                            <i class="fas fa-money-bill-wave text-amber-600 mr-2"></i> Laporan Pembayaran & Iuran
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="excel" data-type="rekapitulasi">
                            <i class="fas fa-table-list text-purple-600 mr-2"></i> Rekapitulasi Wilayah & Tahunan
                        </a>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="excel" data-type="matriks">
                            <i class="fas fa-th text-indigo-600 mr-2"></i> Matriks Analisis Silang
                        </a>
                    </div>
                </div>

                <!-- Dropdown Export PDF -->
                <div class="dropdown">
                    <button class="btn btn-sm text-white font-bold px-3 py-2 rounded-xl shadow-md transition hover:scale-105 flex items-center gap-1.5"
                            type="button" id="dropdownPdf" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            style="background-color: #be123c !important;">
                        <i class="fas fa-file-pdf"></i>
                        <span>Export PDF</span>
                        <i class="fas fa-chevron-down text-[10px] ml-1"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow-xl border-0 rounded-xl py-2" aria-labelledby="dropdownPdf" style="z-index: 1050;">
                        <h6 class="dropdown-header text-[10px] uppercase font-bold text-slate-400">Pilih Kategori PDF</h6>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="pdf" data-type="anggota">
                            <i class="fas fa-clinic-medical text-rose-600 mr-2"></i> Laporan Data Anggota
                        </a>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="pdf" data-type="sertifikat">
                            <i class="fas fa-certificate text-blue-600 mr-2"></i> Laporan Sertifikat
                        </a>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="pdf" data-type="pembayaran">
                            <i class="fas fa-money-bill-wave text-amber-600 mr-2"></i> Laporan Pembayaran & Iuran
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="pdf" data-type="rekapitulasi">
                            <i class="fas fa-table-list text-purple-600 mr-2"></i> Rekapitulasi & Matriks Wilayah
                        </a>
                    </div>
                </div>

                <!-- Tombol Cetak Dokumen -->
                <button type="button" class="btn btn-sm font-bold px-3 py-2 rounded-xl shadow-md transition hover:scale-105 flex items-center gap-1.5"
                        id="btnPrintPreview" style="background-color: #ffffff !important; color: #991b1b !important;">
                    <i class="fas fa-print"></i>
                    <span>Cetak</span>
                </button>

                <!-- Dashboard Back -->
                <a href="{{ route('dashboard.index') }}" class="btn btn-sm btn-secondary font-semibold px-3 py-2 rounded-xl border border-white/20">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Control Panel -->
    <div class="card border border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
        <div class="card-header bg-slate-50/70 dark:bg-slate-900/60 px-5 py-3.5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-filter text-red-600"></i>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                    Panel Filter Laporan Cabang
                </h3>
            </div>
            <span class="text-xs text-slate-400">
                Kewenangan Cabang: <strong class="text-slate-700 dark:text-slate-200">{{ $scope['kota_name'] }}</strong>
            </span>
        </div>
        <div class="card-body p-5">
            <form id="filterForm" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @if($scope['is_national_admin'])
                    <!-- Pilihan Provinsi Khusus Nasional / Superadmin -->
                    <div>
                        <label for="filter_provinsi" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">
                            Provinsi
                        </label>
                        <select id="filter_provinsi" name="provinsi" class="form-control form-control-sm rounded-xl">
                            @foreach($provinces as $p)
                                <option value="{{ $p->code }}" {{ $scope['provinsi_id'] == $p->code ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilihan Kabupaten/Kota Khusus Nasional / Superadmin -->
                    <div>
                        <label for="filter_kota" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">
                            Kabupaten / Kota (Cabang)
                        </label>
                        <select id="filter_kota" name="kota" class="form-control form-control-sm rounded-xl">
                            @foreach($cities as $c)
                                <option value="{{ $c->code }}" {{ $scope['kota_id'] == $c->code ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- 1. Tahun Pendaftaran -->
                    <div>
                        <label for="filter_tahun" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">
                            Tahun Pendaftaran
                        </label>
                        <select id="filter_tahun" name="tahun" class="form-control form-control-sm rounded-xl">
                            <option value="">-- Semua Tahun --</option>
                            @foreach($years as $yr)
                                <option value="{{ $yr }}">{{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Rentang Tanggal Mulai -->
                    <div>
                        <label for="filter_start_date" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">
                            Tanggal Daftar Mulai
                        </label>
                        <input type="date" id="filter_start_date" name="start_date" class="form-control form-control-sm rounded-xl">
                    </div>

                    <!-- 3. Rentang Tanggal Akhir -->
                    <div>
                        <label for="filter_end_date" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">
                            Tanggal Daftar Sampai
                        </label>
                        <input type="date" id="filter_end_date" name="end_date" class="form-control form-control-sm rounded-xl">
                    </div>

                    <!-- 4. Kecamatan (Wilayah Cabang) -->
                    <div>
                        <label for="filter_kecamatan" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">
                            Kecamatan
                        </label>
                        <select id="filter_kecamatan" name="kecamatan" class="form-control form-control-sm rounded-xl">
                            <option value="">-- Semua Kecamatan di Cabang --</option>
                            @foreach($subRegions as $sub)
                                <option value="{{ $sub['code'] }}">{{ $sub['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 5. Status Keanggotaan -->
                    <div>
                        <label for="filter_status" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">
                            Status Keanggotaan
                        </label>
                        <select id="filter_status" name="status" class="form-control form-control-sm rounded-xl">
                            <option value="">-- Semua Status --</option>
                            <option value="approved">Disetujui (Aktif)</option>
                            <option value="waiting">Menunggu Verifikasi</option>
                            <option value="proses">Dalam Proses</option>
                            <option value="Terverifikasi Cabang">Terverifikasi Cabang</option>
                            <option value="Diproses Cabang">Diproses Cabang</option>
                            <option value="create_dokter">Pengisian Dokter</option>
                            <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                        </select>
                    </div>

                    <!-- 6. Status Pembayaran Iuran -->
                    <div>
                        <label for="filter_status_pembayaran" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">
                            Status Pembayaran
                        </label>
                        <select id="filter_status_pembayaran" name="status_pembayaran" class="form-control form-control-sm rounded-xl">
                            <option value="">-- Semua Status Pembayaran --</option>
                            <option value="1">Lunas</option>
                            <option value="0">Belum Lunas / Tertunda</option>
                        </select>
                    </div>

                    <!-- 7. Status Sertifikat -->
                    <div>
                        <label for="filter_status_sertifikat" class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">
                            Status Sertifikat
                        </label>
                        <select id="filter_status_sertifikat" name="status_sertifikat" class="form-control form-control-sm rounded-xl">
                            <option value="">-- Semua Status Sertifikat --</option>
                            <option value="aktif">Sertifikat Aktif</option>
                            <option value="expired">Sertifikat Kedaluwarsa</option>
                            <option value="belum">Belum Memiliki Sertifikat</option>
                        </select>
                    </div>

                    <!-- 8. Tombol Filter Aksi -->
                    <div class="flex items-end gap-2">
                        <button type="button" id="btnApplyFilter" class="btn btn-sm btn-primary flex-1 font-bold rounded-xl py-2 shadow-sm">
                            <i class="fas fa-check-circle mr-1"></i> Terapkan Filter
                        </button>
                        <button type="button" id="btnResetFilter" class="btn btn-sm btn-outline-secondary font-semibold rounded-xl py-2" title="Reset Filter">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab Navigation Bar -->
    <div class="border-b border-slate-200 dark:border-slate-800">
        <ul class="flex flex-wrap gap-2 -mb-px text-xs font-bold text-center" id="reportTabs" role="tablist">
            <li class="mr-1" role="presentation">
                <button class="inline-flex items-center gap-2 py-3 px-4 rounded-t-xl border-b-2 border-red-600 text-red-600 active tab-btn"
                        id="tab-dashboard-btn" data-target="#tab-dashboard" type="button" role="tab">
                    <i class="fas fa-chart-pie text-sm"></i>
                    <span>Dashboard & Visualisasi</span>
                </button>
            </li>
            <li class="mr-1" role="presentation">
                <button class="inline-flex items-center gap-2 py-3 px-4 rounded-t-xl border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-white tab-btn"
                        id="tab-anggota-btn" data-target="#tab-anggota" type="button" role="tab">
                    <i class="fas fa-clinic-medical text-sm"></i>
                    <span>Data Anggota</span>
                </button>
            </li>
            <li class="mr-1" role="presentation">
                <button class="inline-flex items-center gap-2 py-3 px-4 rounded-t-xl border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-white tab-btn"
                        id="tab-sertifikat-btn" data-target="#tab-sertifikat" type="button" role="tab">
                    <i class="fas fa-certificate text-sm"></i>
                    <span>Sertifikat Anggota</span>
                </button>
            </li>
            <li class="mr-1" role="presentation">
                <button class="inline-flex items-center gap-2 py-3 px-4 rounded-t-xl border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-white tab-btn"
                        id="tab-pembayaran-btn" data-target="#tab-pembayaran" type="button" role="tab">
                    <i class="fas fa-file-invoice-dollar text-sm"></i>
                    <span>Pembayaran & Iuran</span>
                </button>
            </li>
            <li class="mr-1" role="presentation">
                <button class="inline-flex items-center gap-2 py-3 px-4 rounded-t-xl border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-white tab-btn"
                        id="tab-rekap-btn" data-target="#tab-rekap" type="button" role="tab">
                    <i class="fas fa-table-list text-sm"></i>
                    <span>Rekapitulasi Cabang</span>
                </button>
            </li>
            <li role="presentation">
                <button class="inline-flex items-center gap-2 py-3 px-4 rounded-t-xl border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-white tab-btn"
                        id="tab-matriks-btn" data-target="#tab-matriks" type="button" role="tab">
                    <i class="fas fa-th text-sm"></i>
                    <span>Matriks Analisis</span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Contents Container -->
    <div id="tabContent">

        <!-- ========================================== -->
        <!-- TAB 1: DASHBOARD & VISUALISASI             -->
        <!-- ========================================== -->
        <div class="tab-pane space-y-6" id="tab-dashboard">
            <!-- Header Tab 1: Title & Export Action -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-2 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-chart-pie text-red-600"></i>
                        Ringkasan Eksekutif & Visualisasi KPI Cabang
                    </h2>
                    <p class="text-xs text-slate-400">Ringkasan performa keanggotaan, sertifikasi, dan iuran cabang secara real-time</p>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-success font-semibold px-3 py-1.5 rounded-xl export-link shadow-sm" data-format="excel" data-type="dashboard">
                        <i class="fas fa-file-excel mr-1 text-emerald-600"></i> Export Excel KPI
                    </button>
                </div>
            </div>

            <!-- 6 KPI Stat Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                <!-- 1. Total Anggota -->
                <div class="card p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm bg-white dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Anggota</p>
                            <h3 class="text-xl font-extrabold text-slate-800 dark:text-white mt-1" id="kpi_total_anggota">
                                {{ number_format($stats['total_anggota']) }}
                            </h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 dark:bg-red-950/50 flex items-center justify-center text-base">
                            <i class="fas fa-clinic-medical"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2">Terdaftar di {{ $scope['kota_name'] }}</p>
                </div>

                <!-- 2. Anggota Aktif -->
                <div class="card p-4 rounded-2xl border border-emerald-200 dark:border-emerald-950/60 shadow-sm bg-white dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Anggota Aktif</p>
                            <h3 class="text-xl font-extrabold text-emerald-700 dark:text-emerald-400 mt-1" id="kpi_anggota_aktif">
                                {{ number_format($stats['anggota_aktif']) }}
                            </h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 flex items-center justify-center text-base">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-emerald-600 mt-2">Status Disetujui (Approved)</p>
                </div>

                <!-- 3. Anggota Inaktif / Menunggu -->
                <div class="card p-4 rounded-2xl border border-amber-200 dark:border-amber-950/60 shadow-sm bg-white dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-amber-600">Menunggu / Proses</p>
                            <h3 class="text-xl font-extrabold text-amber-700 dark:text-amber-400 mt-1" id="kpi_anggota_inaktif">
                                {{ number_format($stats['anggota_inaktif']) }}
                            </h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 dark:bg-amber-950/50 flex items-center justify-center text-base">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-amber-600 mt-2">Menunggu atau Dalam Proses</p>
                </div>

                <!-- 4. Anggota Baru 30 Hari -->
                <div class="card p-4 rounded-2xl border border-blue-200 dark:border-blue-950/60 shadow-sm bg-white dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Anggota Baru</p>
                            <h3 class="text-xl font-extrabold text-blue-700 dark:text-blue-400 mt-1" id="kpi_anggota_baru">
                                {{ number_format($stats['anggota_baru_30_hari']) }}
                            </h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-950/50 flex items-center justify-center text-base">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-blue-600 mt-2">Pendaftar 30 Hari Terakhir</p>
                </div>

                <!-- 5. Sertifikat Aktif -->
                <div class="card p-4 rounded-2xl border border-purple-200 dark:border-purple-950/60 shadow-sm bg-white dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-purple-600">Sertifikat Aktif</p>
                            <h3 class="text-xl font-extrabold text-purple-700 dark:text-purple-400 mt-1" id="kpi_sertifikat_aktif">
                                {{ number_format($stats['sertifikat_aktif']) }}
                            </h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 dark:bg-purple-950/50 flex items-center justify-center text-base">
                            <i class="fas fa-certificate"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-purple-600 mt-2">
                        <span id="kpi_sertifikat_exp">{{ number_format($stats['sertifikat_expired']) }}</span> Kedaluwarsa
                    </p>
                </div>

                <!-- 6. Pembayaran Iuran Lunas -->
                <div class="card p-4 rounded-2xl border border-teal-200 dark:border-teal-950/60 shadow-sm bg-white dark:bg-slate-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-teal-600">Iuran Lunas</p>
                            <h3 class="text-xl font-extrabold text-teal-700 dark:text-teal-400 mt-1" id="kpi_trx_lunas">
                                {{ number_format($stats['transaksi_bayar_lunas']) }}
                            </h3>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-600 dark:bg-teal-950/50 flex items-center justify-center text-base">
                            <i class="fas fa-receipt"></i>
                        </div>
                    </div>
                    <p class="text-[10px] text-teal-600 mt-2">
                        <span id="kpi_trx_pending">{{ number_format($stats['transaksi_bayar_pending']) }}</span> Menunggu Lunas
                    </p>
                </div>
            </div>

            <!-- Charts Row 1: Tren Pertumbuhan & Komposisi Status -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Grafik Pertumbuhan -->
                <div class="lg:col-span-2 card p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm bg-white dark:bg-slate-900">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <i class="fas fa-chart-line text-red-600"></i>
                                Tren Pertumbuhan Anggota Cabang
                            </h4>
                            <p class="text-[11px] text-slate-400">Total klinik pendaftar dan anggota disetujui aktif per periode</p>
                        </div>
                        <span class="badge badge-primary text-[10px]">Pertumbuhan</span>
                    </div>
                    <div class="h-64 relative">
                        <canvas id="growthChartCanvas"></canvas>
                    </div>
                </div>

                <!-- Grafik Status Keanggotaan -->
                <div class="card p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm bg-white dark:bg-slate-900">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <i class="fas fa-pie-chart text-red-600"></i>
                                Status Anggota
                            </h4>
                            <p class="text-[11px] text-slate-400">Distribusi status verifikasi berkas</p>
                        </div>
                    </div>
                    <div class="h-64 relative">
                        <canvas id="statusChartCanvas"></canvas>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2: Distribusi Wilayah Kecamatan & Status Sertifikat -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Distribusi Anggota per Kecamatan -->
                <div class="lg:col-span-2 card p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm bg-white dark:bg-slate-900">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <i class="fas fa-map-marked-alt text-red-600"></i>
                                Persebaran Anggota per Kecamatan
                            </h4>
                            <p class="text-[11px] text-slate-400">Jumlah klinik terdaftar di wilayah kecamatan se-{{ $scope['kota_name'] }}</p>
                        </div>
                    </div>
                    <div class="h-64 relative">
                        <canvas id="regionalChartCanvas"></canvas>
                    </div>
                </div>

                <!-- Komposisi Sertifikat & Pembayaran -->
                <div class="card p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm bg-white dark:bg-slate-900">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <i class="fas fa-certificate text-purple-600"></i>
                                Status Sertifikasi Anggota
                            </h4>
                            <p class="text-[11px] text-slate-400">Proporsi sertifikat aktif vs expired</p>
                        </div>
                    </div>
                    <div class="h-64 relative">
                        <canvas id="certChartCanvas"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: DATA ANGGOTA                        -->
        <!-- ========================================== -->
        <div class="tab-pane hidden space-y-4" id="tab-anggota">
            <div class="card border border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <div class="card-header bg-slate-50/70 dark:bg-slate-900/60 px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-clinic-medical text-red-600"></i>
                            Daftar Lengkap Anggota ASKLIN Cabang {{ $scope['kota_name'] }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Seluruh klinik anggota yang berada di bawah wewenang pengurus cabang</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary export-link" data-format="excel" data-type="anggota">
                        <i class="fas fa-file-excel mr-1"></i> Download Excel
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive p-4">
                        <table class="table table-hover table-striped w-full text-xs" id="tableAnggota">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Anggota</th>
                                    <th>Nama Klinik</th>
                                    <th>Jenis Klinik</th>
                                    <th>Kab/Kota</th>
                                    <th>Kecamatan</th>
                                    <th>Pemilik / Kontak</th>
                                    <th>Tgl Daftar</th>
                                    <th>Status Anggota</th>
                                    <th>Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: DATA SERTIFIKAT                     -->
        <!-- ========================================== -->
        <div class="tab-pane hidden space-y-4" id="tab-sertifikat">
            <div class="card border border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <div class="card-header bg-slate-50/70 dark:bg-slate-900/60 px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-certificate text-blue-600"></i>
                            Daftar Sertifikat Keanggotaan ASKLIN
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Pemantauan masa berlaku dan penomoran sertifikat anggota cabang</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary export-link" data-format="excel" data-type="sertifikat">
                        <i class="fas fa-file-excel mr-1"></i> Download Excel
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive p-4">
                        <table class="table table-hover table-striped w-full text-xs" id="tableSertifikat">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Anggota</th>
                                    <th>Nama Klinik</th>
                                    <th>Kab/Kota</th>
                                    <th>Nomor Sertifikat (Kode)</th>
                                    <th>Jenis Sertifikat</th>
                                    <th>Tanggal Terbit</th>
                                    <th>Masa Berlaku</th>
                                    <th>Status Masa Berlaku</th>
                                    <th>Status Bayar</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 4: DATA PEMBAYARAN & IURAN             -->
        <!-- ========================================== -->
        <div class="tab-pane hidden space-y-4" id="tab-pembayaran">
            <div class="card border border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <div class="card-header bg-slate-50/70 dark:bg-slate-900/60 px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-money-bill-wave text-amber-600"></i>
                            Daftar Pembayaran Uang Pangkal & Iuran Anggota
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Rekapitulasi transaksi pembayaran resmi klinik anggota cabang {{ $scope['kota_name'] }}</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary export-link" data-format="excel" data-type="pembayaran">
                        <i class="fas fa-file-excel mr-1"></i> Download Excel
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive p-4">
                        <table class="table table-hover table-striped w-full text-xs" id="tablePembayaran">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Anggota</th>
                                    <th>Nama Klinik</th>
                                    <th>Kab/Kota</th>
                                    <th>Kategori Pembayaran</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Periode Iuran</th>
                                    <th>Nominal (Rp)</th>
                                    <th>Status Transaksi</th>
                                    <th>Bukti</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 5: REKAPITULASI CABANG                 -->
        <!-- ========================================== -->
        <div class="tab-pane hidden space-y-6" id="tab-rekap">
            <!-- Rekapitulasi per Kecamatan -->
            <div class="card border border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <div class="card-header bg-slate-50/70 dark:bg-slate-900/60 px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-building text-red-600"></i>
                            Rekapitulasi Anggota per Kecamatan di {{ $scope['kota_name'] }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Ringkasan distribusi keanggotaan dan status iuran menurut kecamatan</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-success export-link font-semibold rounded-xl" data-format="excel" data-type="rekapitulasi">
                        <i class="fas fa-file-excel mr-1 text-emerald-600"></i> Download Excel
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-xs mb-0" id="tableRekapWilayah">
                            <thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                <tr>
                                    <th>Kecamatan</th>
                                    <th class="text-center">Total Anggota</th>
                                    <th class="text-center">Disetujui (Aktif)</th>
                                    <th class="text-center">Menunggu / Inaktif</th>
                                    <th class="text-center">Status Lunas</th>
                                    <th class="text-center">Status Tertunda</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rekapitulasi['regional_data'] as $r)
                                    <tr>
                                        <td><strong>{{ $r->name }}</strong></td>
                                        <td class="text-center font-bold">{{ number_format($r->total_anggota) }}</td>
                                        <td class="text-center text-emerald-600 font-bold">{{ number_format($r->aktif) }}</td>
                                        <td class="text-center text-amber-600">{{ number_format($r->inaktif) }}</td>
                                        <td class="text-center text-emerald-600">{{ number_format($r->lunas) }}</td>
                                        <td class="text-center text-rose-600">{{ number_format($r->belum_lunas) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Rekapitulasi Status & YoY Komparasi Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Rekap Tahun Pendaftaran -->
                <div class="card border border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                    <div class="card-header bg-slate-50/70 dark:bg-slate-900/60 px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Pendaftaran Anggota Cabang per Tahun
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped text-xs mb-0">
                                <thead class="bg-slate-100 dark:bg-slate-800">
                                    <tr>
                                        <th>Tahun</th>
                                        <th class="text-center">Total Pendaftar</th>
                                        <th class="text-center">Disetujui Aktif</th>
                                        <th class="text-center">Lunas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rekapitulasi['tahun_breakdown'] as $t)
                                        <tr>
                                            <td><strong>{{ $t->tahun }}</strong></td>
                                            <td class="text-center font-bold">{{ number_format($t->total) }}</td>
                                            <td class="text-center text-emerald-600 font-bold">{{ number_format($t->disetujui) }}</td>
                                            <td class="text-center text-emerald-600">{{ number_format($t->lunas) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Komparasi Antarperiode (YoY) -->
                <div class="card border border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl p-5 space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-2">
                        <i class="fas fa-balance-scale text-red-600"></i>
                        Komparasi Antarperiode Cabang (Year-over-Year)
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-center">
                            <span class="text-xs text-slate-400 font-semibold">Tahun Ini ({{ $rekapitulasi['yoy']['current_year'] }})</span>
                            <div class="text-2xl font-black text-slate-800 dark:text-white mt-1">
                                {{ number_format($rekapitulasi['yoy']['anggota_current']) }}
                            </div>
                            <span class="text-[11px] text-emerald-600 font-bold">
                                {{ number_format($rekapitulasi['yoy']['aktif_current']) }} Disetujui
                            </span>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-center">
                            <span class="text-xs text-slate-400 font-semibold">Tahun Lalu ({{ $rekapitulasi['yoy']['prev_year'] }})</span>
                            <div class="text-2xl font-black text-slate-800 dark:text-white mt-1">
                                {{ number_format($rekapitulasi['yoy']['anggota_prev']) }}
                            </div>
                            <span class="text-[11px] text-emerald-600 font-bold">
                                {{ number_format($rekapitulasi['yoy']['aktif_prev']) }} Disetujui
                            </span>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400 leading-relaxed">
                        Perbandingan pertumbuhan keanggotaan klinik antara periode berjalan dan periode tahun sebelumnya pada cabang {{ $scope['kota_name'] }}.
                    </p>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 6: MATRIKS ANALISIS                    -->
        <!-- ========================================== -->
        <div class="tab-pane hidden space-y-6" id="tab-matriks">
            <!-- Matriks 1: Kecamatan x Status Keanggotaan -->
            <div class="card border border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                <div class="card-header bg-slate-50/70 dark:bg-slate-900/60 px-5 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-th-large text-red-600"></i>
                            Matriks 1: Kecamatan &times; Status Keanggotaan
                        </h4>
                        <p class="text-[11px] text-slate-400">Distribusi status verifikasi silang per kecamatan di {{ $scope['kota_name'] }}</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-success export-link font-semibold rounded-xl" data-format="excel" data-type="matriks">
                        <i class="fas fa-file-excel mr-1 text-emerald-600"></i> Download Excel Matriks
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-xs mb-0">
                            <thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                <tr>
                                    <th>Kecamatan</th>
                                    <th class="text-center">Disetujui (Aktif)</th>
                                    <th class="text-center">Menunggu (Waiting)</th>
                                    <th class="text-center">Sedang Proses</th>
                                    <th class="text-center">Lainnya / Revisi</th>
                                    <th class="text-center bg-slate-200 dark:bg-slate-700 font-bold">Total Baris</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totApproved = 0; $totWaiting = 0; $totProses = 0; $totLainnya = 0; $totGrand = 0;
                                @endphp
                                @foreach($matriks['matrix_wilayah_status'] as $m)
                                    @php
                                        $totApproved += $m->col_approved;
                                        $totWaiting += $m->col_waiting;
                                        $totProses += $m->col_proses;
                                        $totLainnya += $m->col_lainnya;
                                        $totGrand += $m->col_total;
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $m->baris }}</strong></td>
                                        <td class="text-center text-emerald-600 font-semibold">{{ number_format($m->col_approved) }}</td>
                                        <td class="text-center text-amber-600">{{ number_format($m->col_waiting) }}</td>
                                        <td class="text-center text-blue-600">{{ number_format($m->col_proses) }}</td>
                                        <td class="text-center text-slate-500">{{ number_format($m->col_lainnya) }}</td>
                                        <td class="text-center font-bold bg-slate-50 dark:bg-slate-900/40">{{ number_format($m->col_total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-slate-200 dark:bg-slate-800 font-bold">
                                <tr>
                                    <td>TOTAL KESELURUHAN</td>
                                    <td class="text-center text-emerald-700">{{ number_format($totApproved) }}</td>
                                    <td class="text-center text-amber-700">{{ number_format($totWaiting) }}</td>
                                    <td class="text-center text-blue-700">{{ number_format($totProses) }}</td>
                                    <td class="text-center">{{ number_format($totLainnya) }}</td>
                                    <td class="text-center text-red-700 bg-red-100 dark:bg-red-950/40">{{ number_format($totGrand) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Matriks 2 & 3 Grid: Tahun x Status & Tahun x Sertifikat -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Matriks 2: Tahun x Status -->
                <div class="card border border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                    <div class="card-header bg-slate-50/70 dark:bg-slate-900/60 px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Matriks 2: Tahun Pendaftaran &times; Status Anggota
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-xs mb-0">
                                <thead class="bg-slate-100 dark:bg-slate-800">
                                    <tr>
                                        <th>Tahun</th>
                                        <th class="text-center">Disetujui</th>
                                        <th class="text-center">Waiting</th>
                                        <th class="text-center">Proses</th>
                                        <th class="text-center font-bold bg-slate-200 dark:bg-slate-700">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matriks['matrix_tahun_status'] as $m2)
                                        <tr>
                                            <td><strong>{{ $m2->baris }}</strong></td>
                                            <td class="text-center text-emerald-600 font-semibold">{{ number_format($m2->col_approved) }}</td>
                                            <td class="text-center text-amber-600">{{ number_format($m2->col_waiting) }}</td>
                                            <td class="text-center text-blue-600">{{ number_format($m2->col_proses) }}</td>
                                            <td class="text-center font-bold">{{ number_format($m2->col_total) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Matriks 3: Tahun x Sertifikat -->
                <div class="card border border-slate-200 dark:border-slate-800 shadow-sm rounded-2xl overflow-hidden">
                    <div class="card-header bg-slate-50/70 dark:bg-slate-900/60 px-5 py-4 border-b border-slate-200 dark:border-slate-800">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                            Matriks 3: Tahun Pendaftaran &times; Status Sertifikat
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-xs mb-0">
                                <thead class="bg-slate-100 dark:bg-slate-800">
                                    <tr>
                                        <th>Tahun</th>
                                        <th class="text-center text-emerald-600">Sertifikat Aktif</th>
                                        <th class="text-center text-rose-600">Kedaluwarsa</th>
                                        <th class="text-center text-slate-500">Belum Ada</th>
                                        <th class="text-center font-bold bg-slate-200 dark:bg-slate-700">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matriks['matrix_tahun_sertifikat'] as $m3)
                                        <tr>
                                            <td><strong>{{ $m3->baris }}</strong></td>
                                            <td class="text-center text-emerald-600 font-semibold">{{ number_format($m3->col_aktif) }}</td>
                                            <td class="text-center text-rose-600">{{ number_format($m3->col_expired) }}</td>
                                            <td class="text-center text-slate-500">{{ number_format($m3->col_belum) }}</td>
                                            <td class="text-center font-bold">{{ number_format($m3->col_total) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
<style>
    .tab-btn.active {
        color: #dc2626 !important;
        border-bottom-color: #dc2626 !important;
        font-weight: 700;
    }
    .dark .tab-btn.active {
        color: #f87171 !important;
        border-bottom-color: #f87171 !important;
    }
</style>
@endpush

@push('js')
<script src="{{ asset('assets/backend/modules/chart.min.js') }}"></script>
<script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>

<script>
$(document).ready(function () {
    // Setup CSRF
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // Tab switcher
    $('.tab-btn').on('click', function () {
        $('.tab-btn').removeClass('active border-red-600 text-red-600').addClass('border-transparent text-slate-500');
        $(this).addClass('active border-red-600 text-red-600').removeClass('border-transparent text-slate-500');

        const target = $(this).data('target');
        $('.tab-pane').addClass('hidden');
        $(target).removeClass('hidden');

        // Adjust DataTables columns on tab show
        if (target === '#tab-anggota' && $.fn.DataTable.isDataTable('#tableAnggota')) {
            $('#tableAnggota').DataTable().columns.adjust().draw();
        } else if (target === '#tab-sertifikat' && $.fn.DataTable.isDataTable('#tableSertifikat')) {
            $('#tableSertifikat').DataTable().columns.adjust().draw();
        } else if (target === '#tab-pembayaran' && $.fn.DataTable.isDataTable('#tablePembayaran')) {
            $('#tablePembayaran').DataTable().columns.adjust().draw();
        }
    });

    // Helper: Collect current filters
    function getFilterData() {
        return {
            provinsi: $('#filter_provinsi').length ? $('#filter_provinsi').val() : '',
            kota: $('#filter_kota').length ? $('#filter_kota').val() : '',
            tahun: $('#filter_tahun').val(),
            start_date: $('#filter_start_date').val(),
            end_date: $('#filter_end_date').val(),
            kecamatan: $('#filter_kecamatan').val(),
            status: $('#filter_status').val(),
            status_pembayaran: $('#filter_status_pembayaran').val(),
            status_sertifikat: $('#filter_status_sertifikat').val(),
        };
    }

    // Dynamic chained filter for Provinsi -> Kota -> Kecamatan
    $('#filter_provinsi').on('change', function () {
        var idProv = $(this).val();
        if (idProv) {
            $.ajax({
                type: 'POST',
                url: "{{ route('getKota') }}",
                data: { 
                    id_provinsi: idProv,
                    _token: "{{ csrf_token() }}"
                },
                success: function (msg) {
                    $('#filter_kota').html(msg);
                    $('#filter_kecamatan').html('<option value="">-- Semua Kecamatan di Cabang --</option>');
                }
            });
        } else {
            $('#filter_kota').html('<option value="">-- Pilih Kota/Kabupaten --</option>');
            $('#filter_kecamatan').html('<option value="">-- Semua Kecamatan di Cabang --</option>');
        }
    });

    $('#filter_kota').on('change', function () {
        var idKota = $(this).val();
        if (idKota) {
            $.ajax({
                type: 'POST',
                url: "{{ route('getKecamatan') }}",
                data: { id_kota: idKota },
                success: function (msg) {
                    $('#filter_kecamatan').html(msg);
                }
            });
        } else {
            $('#filter_kecamatan').html('<option value="">-- Semua Kecamatan di Cabang --</option>');
        }
    });

    // Initialize DataTables
    var dtAnggota = $('#tableAnggota').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        ajax: {
            url: "{{ route('laporan_cabang.list') }}",
            data: function (d) {
                $.extend(d, getFilterData());
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'no_anggota', name: 'anggota.no_anggota' },
            { data: 'nama_klinik', name: 'anggota.nama_klinik', className: 'font-weight-bold' },
            { data: 'jenis_klinik', name: 'anggota.jenis_klinik' },
            { data: 'kota_name', name: 'indonesia_cities.name' },
            { data: 'kecamatan_name', name: 'indonesia_districts.name' },
            { data: 'nama_pemilik_klinik', name: 'anggota.nama_pemilik_klinik' },
            { data: 'tgl_daftar', name: 'tgl_daftar_raw', className: 'text-center' },
            { data: 'status_badge', name: 'anggota.status', className: 'text-center' },
            { data: 'status_pembayaran_badge', name: 'anggota.status_pembayaran', className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        language: {
            search: "Pencarian:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Tidak ada data anggota yang sesuai",
            info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            paginate: { first: "Awal", last: "Akhir", next: "Berikutnya", previous: "Sebelumnya" }
        }
    });

    var dtSertifikat = $('#tableSertifikat').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        ajax: {
            url: "{{ route('laporan_cabang.sertifikat_list') }}",
            data: function (d) {
                $.extend(d, getFilterData());
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'no_anggota', name: 'anggota.no_anggota' },
            { data: 'nama_klinik', name: 'anggota.nama_klinik', className: 'font-weight-bold' },
            { data: 'kota_name', name: 'indonesia_cities.name' },
            { data: 'kode', name: 'sertifikat.kode', className: 'text-center font-weight-bold' },
            { data: 'sertifikat', name: 'sertifikat.sertifikat' },
            { data: 'tgl_terbit', name: 'sertifikat.dari', className: 'text-center' },
            { data: 'tgl_berlaku', name: 'sertifikat.sampai', className: 'text-center' },
            { data: 'status_badge', name: 'status_masa_berlaku', className: 'text-center' },
            { data: 'status_bayar_badge', name: 'sertifikat.status_pembayaran', className: 'text-center' }
        ],
        language: {
            search: "Pencarian:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Tidak ada data sertifikat yang sesuai",
            info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            paginate: { first: "Awal", last: "Akhir", next: "Berikutnya", previous: "Sebelumnya" }
        }
    });

    var dtPembayaran = $('#tablePembayaran').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        ajax: {
            url: "{{ route('laporan_cabang.pembayaran_list') }}",
            data: function (d) {
                $.extend(d, getFilterData());
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'no_anggota', name: 'anggota.no_anggota' },
            { data: 'nama_klinik', name: 'anggota.nama_klinik', className: 'font-weight-bold' },
            { data: 'kota_name', name: 'indonesia_cities.name' },
            { data: 'nama_kategori', name: 'pembayaran_kategori.nama' },
            { data: 'tanggal_bayar_formatted', name: 'pembayaran.tanggal_bayar', className: 'text-center' },
            { data: 'periode', name: 'pembayaran.dari', className: 'text-center' },
            { data: 'nominal_formatted', name: 'pembayaran.total', className: 'text-right font-weight-bold' },
            { data: 'status_badge', name: 'pembayaran.status', className: 'text-center' },
            { data: 'bukti_view', name: 'pembayaran.bukti', orderable: false, searchable: false, className: 'text-center' }
        ],
        language: {
            search: "Pencarian:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Tidak ada data transaksi pembayaran yang sesuai",
            info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            paginate: { first: "Awal", last: "Akhir", next: "Berikutnya", previous: "Sebelumnya" }
        }
    });

    // Chart.js Instances
    var growthChart, statusChart, regionalChart, certChart;

    // Initial Chart Data from PHP
    var initialGrowth = @json($growthChart);
    var initialStatus = @json($statusChart);
    var initialRegional = @json($regionalChart);
    var initialBreakdown = @json($breakdownChart);

    // 1. Growth Chart
    var ctxGrowth = document.getElementById('growthChartCanvas').getContext('2d');
    growthChart = new Chart(ctxGrowth, {
        type: 'line',
        data: {
            labels: initialGrowth.labels,
            datasets: [
                {
                    label: 'Total Pendaftar',
                    data: initialGrowth.totals,
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Disetujui Aktif',
                    data: initialGrowth.aktifs,
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.05)',
                    borderWidth: 2,
                    borderDash: [4, 4],
                    fill: false,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { position: 'top', labels: { boxWidth: 12, fontSize: 11 } }
        }
    });

    // 2. Status Chart
    var ctxStatus = document.getElementById('statusChartCanvas').getContext('2d');
    statusChart = new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: initialStatus.labels,
            datasets: [{
                data: initialStatus.data,
                backgroundColor: ['#16a34a', '#f59e0b', '#3b82f6', '#8b5cf6', '#ec4899', '#64748b', '#ef4444'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { position: 'bottom', labels: { boxWidth: 10, fontSize: 10 } }
        }
    });

    // 3. Regional Chart (Kecamatan)
    var ctxRegional = document.getElementById('regionalChartCanvas').getContext('2d');
    regionalChart = new Chart(ctxRegional, {
        type: 'bar',
        data: {
            labels: initialRegional.labels,
            datasets: [{
                label: 'Jumlah Klinik',
                data: initialRegional.data,
                backgroundColor: '#dc2626',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }],
                xAxes: [{ ticks: { autoSkip: false, maxRotation: 45, minRotation: 45, fontSize: 9 } }]
            }
        }
    });

    // 4. Certificate Chart
    var ctxCert = document.getElementById('certChartCanvas').getContext('2d');
    certChart = new Chart(ctxCert, {
        type: 'pie',
        data: {
            labels: initialBreakdown.certificate.labels,
            datasets: [{
                data: initialBreakdown.certificate.data,
                backgroundColor: ['#16a34a', '#ef4444', '#94a3b8']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { position: 'bottom', labels: { boxWidth: 10, fontSize: 10 } }
        }
    });

    // Dynamic Filter Trigger
    function applyCurrentFilters() {
        const filters = getFilterData();

        // 1. Reload DataTables
        dtAnggota.ajax.reload();
        dtSertifikat.ajax.reload();
        dtPembayaran.ajax.reload();

        // 2. Fetch Chart & Summary Updates
        $.ajax({
            url: "{{ route('laporan_cabang.chart_data') }}",
            type: "GET",
            data: filters,
            success: function (res) {
                // Update Banner Title & Subtitle if Scope changes (Superadmin / Nasional filter)
                if (res.scope) {
                    if (res.scope.kota_name) {
                        $('#bannerRegionName').text(res.scope.kota_name.toUpperCase());
                    }
                    if (res.scope.title) {
                        $('#bannerTitle').text(res.scope.title);
                    }
                    if (res.scope.subtitle) {
                        $('#bannerSubtitle').text(res.scope.subtitle + '. Seluruh statistik, visualisasi grafik, tabel data, serta ekspor diselaraskan secara otomatis dengan filter aktif.');
                    }
                }

                // Update KPI Cards
                $('#kpi_total_anggota').text(Number(res.stats.total_anggota).toLocaleString());
                $('#kpi_anggota_aktif').text(Number(res.stats.anggota_aktif).toLocaleString());
                $('#kpi_anggota_inaktif').text(Number(res.stats.anggota_inaktif).toLocaleString());
                $('#kpi_anggota_baru').text(Number(res.stats.anggota_baru_30_hari).toLocaleString());
                $('#kpi_sertifikat_aktif').text(Number(res.stats.sertifikat_aktif).toLocaleString());
                $('#kpi_sertifikat_exp').text(Number(res.stats.sertifikat_expired).toLocaleString());
                $('#kpi_trx_lunas').text(Number(res.stats.transaksi_bayar_lunas).toLocaleString());
                $('#kpi_trx_pending').text(Number(res.stats.transaksi_bayar_pending).toLocaleString());

                // Update Growth Chart
                growthChart.data.labels = res.growth.labels;
                growthChart.data.datasets[0].data = res.growth.totals;
                growthChart.data.datasets[1].data = res.growth.aktifs;
                growthChart.update();

                // Update Status Chart
                statusChart.data.labels = res.status.labels;
                statusChart.data.datasets[0].data = res.status.data;
                statusChart.update();

                // Update Regional Chart
                regionalChart.data.labels = res.regional.labels;
                regionalChart.data.datasets[0].data = res.regional.data;
                regionalChart.update();

                // Update Cert Chart
                certChart.data.labels = res.breakdown.certificate.labels;
                certChart.data.datasets[0].data = res.breakdown.certificate.data;
                certChart.update();

                iziToast.success({
                    title: 'Filter Diterapkan',
                    message: 'Seluruh grafik, tabel, dan indikator cabang telah diperbarui.',
                    position: 'topRight',
                    timeout: 2500
                });
            },
            error: function () {
                iziToast.error({
                    title: 'Gagal',
                    message: 'Gagal memuat pembaruan data filter.',
                    position: 'topRight'
                });
            }
        });
    }

    $('#btnApplyFilter').on('click', function () {
        applyCurrentFilters();
    });

    $('#btnResetFilter').on('click', function () {
        $('#filterForm')[0].reset();
        applyCurrentFilters();
    });

    // Export handler
    $('.export-link').on('click', function (e) {
        e.preventDefault();
        const format = $(this).data('format'); // 'excel' or 'pdf'
        const type = $(this).data('type');     // 'anggota', 'sertifikat', etc.
        const filters = getFilterData();
        filters.type = type;

        let baseUrl = format === 'excel'
            ? "{{ route('laporan_cabang.export_excel') }}"
            : "{{ route('laporan_cabang.export_pdf') }}";

        const params = new URLSearchParams(filters).toString();
        window.open(baseUrl + '?' + params, '_blank');
    });

    // Print Preview Handler
    $('#btnPrintPreview').on('click', function (e) {
        e.preventDefault();
        const activeTab = $('.tab-btn.active').attr('id');
        let type = 'anggota';
        if (activeTab === 'tab-sertifikat-btn') type = 'sertifikat';
        else if (activeTab === 'tab-pembayaran-btn') type = 'pembayaran';
        else if (activeTab === 'tab-rekap-btn' || activeTab === 'tab-matriks-btn') type = 'rekapitulasi';

        const filters = getFilterData();
        filters.type = type;
        filters.auto = 1;

        const params = new URLSearchParams(filters).toString();
        window.open("{{ route('laporan_cabang.print') }}?" + params, '_blank');
    });
});
</script>
@endpush