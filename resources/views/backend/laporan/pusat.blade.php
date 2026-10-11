@extends('layouts.backend.layout')

@section('title', 'Laporan Nasional (Pusat) - Pengurus Pusat ASKLIN')
@section('page_title', 'Laporan Tingkat Pusat')

@section('content')
<div class="space-y-6">

    <!-- Responsive Standard Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Laporan & Rekapitulasi'],
        ['label' => 'Laporan Nasional (Pusat)'],
    ]" />

    <!-- Executive National Header Banner -->
    <div class="relative rounded-2xl p-6 sm:p-7 text-white shadow-xl overflow-visible"
         style="background: linear-gradient(135deg, #1e1b4b 0%, #7f1d1d 50%, #450a0a 100%);">
        <!-- Inner decorative clipping layer -->
        <div class="absolute inset-0 rounded-2xl overflow-hidden pointer-events-none">
            <div class="absolute -right-10 -bottom-16 w-64 h-64 rounded-full bg-amber-500/10 blur-3xl"></div>
            <div class="absolute -left-10 -top-16 w-64 h-64 rounded-full bg-red-500/10 blur-3xl"></div>
        </div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="space-y-2">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-amber-400 text-slate-950 shadow-sm">
                        <i class="fas fa-landmark mr-1.5"></i> KOMANDO NASIONAL ASKLIN
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-white/10 text-white backdrop-blur-sm border border-white/20">
                        <i class="fas fa-globe-asia text-emerald-400 mr-1"></i> CAKUPAN: SELURUH INDONESIA
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-black/30 text-rose-200 border border-rose-400/30">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>EXECUTIVE LIVE ANALYTICS</span>
                    </span>
                    <span class="text-xs text-rose-100 hidden sm:inline">
                        {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold tracking-tight text-white" id="bannerTitle">
                    Laporan Eksekutif Nasional ASKLIN
                </h1>
                <p class="text-xs sm:text-sm text-rose-100 max-w-3xl leading-relaxed" id="bannerSubtitle">
                    Pusat pemantauan strategis pengurus pusat: sebaran fasilitas klinik, status persetujuan ber-NRA, kesehatan sertifikasi, dan agregasi iuran di 38 provinsi seluruh Indonesia.
                </p>
            </div>

            <!-- Export & Action Dropdowns (Clean, No Hover Tooltip Blocking) -->
            <div class="flex items-center gap-2.5 flex-wrap shrink-0 relative z-20">
                <!-- Dropdown Export Excel -->
                <div class="dropdown">
                    <button class="btn btn-sm text-white font-bold px-3.5 py-2.5 rounded-xl shadow-md transition hover:scale-105 flex items-center gap-1.5"
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
                            <i class="fas fa-clinic-medical text-emerald-600 mr-2"></i> Laporan Data Anggota Nasional
                        </a>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="excel" data-type="sertifikat">
                            <i class="fas fa-certificate text-blue-600 mr-2"></i> Laporan Sertifikat Nasional
                        </a>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="excel" data-type="pembayaran">
                            <i class="fas fa-money-bill-wave text-amber-600 mr-2"></i> Laporan Pembayaran & Iuran Nasional
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="excel" data-type="rekapitulasi">
                            <i class="fas fa-table-list text-purple-600 mr-2"></i> Rekapitulasi per 38 Provinsi
                        </a>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="excel" data-type="matriks">
                            <i class="fas fa-th text-indigo-600 mr-2"></i> Matriks Analisis Lintas Dimensi
                        </a>
                    </div>
                </div>

                <!-- Dropdown Export PDF -->
                <div class="dropdown">
                    <button class="btn btn-sm text-white font-bold px-3.5 py-2.5 rounded-xl shadow-md transition hover:scale-105 flex items-center gap-1.5"
                            type="button" id="dropdownPdf" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            style="background-color: #be123c !important;">
                        <i class="fas fa-file-pdf"></i>
                        <span>Export PDF</span>
                        <i class="fas fa-chevron-down text-[10px] ml-1"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow-xl border-0 rounded-xl py-2" aria-labelledby="dropdownPdf" style="z-index: 1050;">
                        <h6 class="dropdown-header text-[10px] uppercase font-bold text-slate-400">Pilih Kategori PDF</h6>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="pdf" data-type="anggota">
                            <i class="fas fa-clinic-medical text-emerald-600 mr-2"></i> Laporan Data Anggota
                        </a>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="pdf" data-type="sertifikat">
                            <i class="fas fa-certificate text-blue-600 mr-2"></i> Laporan Sertifikat Anggota
                        </a>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="pdf" data-type="pembayaran">
                            <i class="fas fa-money-bill-wave text-amber-600 mr-2"></i> Laporan Pembayaran & Iuran
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-xs py-2 export-link" href="#" data-format="pdf" data-type="rekapitulasi">
                            <i class="fas fa-table-list text-purple-600 mr-2"></i> Rekapitulasi & Matriks Organisasi
                        </a>
                    </div>
                </div>

                <!-- Cetak Langsung -->
                <button type="button" class="btn btn-sm btn-dark text-white font-bold px-3.5 py-2.5 rounded-xl shadow-md transition hover:scale-105 flex items-center gap-1.5"
                        id="btnPrintLaporan" style="background-color: #0f172a !important;">
                    <i class="fas fa-print"></i>
                    <span>Cetak</span>
                </button>

                <!-- Shortcut Ekspor Kolom Khusus -->
                <a href="{{ route('laporan_export.index') }}" class="btn btn-sm bg-white/20 hover:bg-white/30 text-white font-bold px-3 py-2 rounded-xl backdrop-blur-sm border border-white/30 flex items-center gap-1.5">
                    <i class="fas fa-file-export"></i>
                    <span>Export Kolom Khusus</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Control Panel Nasional -->
    <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
        <div class="card-header bg-slate-50/80 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-800 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-sliders-h text-red-600 dark:text-red-400"></i>
                    <h5 class="text-sm font-bold text-slate-800 dark:text-white mb-0">
                        Filter Data Skala Nasional
                    </h5>
                    <span class="text-xs text-slate-400 hidden sm:inline">| Sesuaikan cakupan provinsi, kabupaten/kota, status persetujuan, dan sertifikasi</span>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="btn btn-xs btn-outline-secondary" id="btnToggleFilter" title="Lipat Filter">
                        <i class="fas fa-chevron-up" id="iconToggleFilter"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-4 sm:p-5" id="filterBody">
            <form id="filterForm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    <!-- 1. Filter Provinsi (Nasional) -->
                    <div>
                        <label for="filterProvinsi" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Provinsi:
                        </label>
                        <select name="provinsi" id="filterProvinsi" class="form-control form-control-sm text-xs rounded-lg">
                            <option value="">-- Seluruh Indonesia (Semua Provinsi) --</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->code }}" {{ $scope['provinsi_id'] == $prov->code ? 'selected' : '' }}>
                                    {{ $prov->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Filter Kabupaten / Kota (Chained AJAX) -->
                    <div>
                        <label for="filterKota" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Kabupaten / Kota:
                        </label>
                        <select name="kota" id="filterKota" class="form-control form-control-sm text-xs rounded-lg">
                            <option value="">-- Seluruh Kabupaten/Kota --</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->code }}" {{ $scope['kota_id'] == $c->code ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. Filter Tahun Pendaftaran -->
                    <div>
                        <label for="filterTahun" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Tahun Pendaftaran:
                        </label>
                        <select name="tahun" id="filterTahun" class="form-control form-control-sm text-xs rounded-lg">
                            <option value="">-- Semua Tahun --</option>
                            @foreach($years as $yr)
                                <option value="{{ $yr }}">{{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 4. Filter Rentang Tanggal -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Rentang Tanggal Pendaftaran:
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" name="start_date" id="filterStartDate" class="form-control form-control-sm text-xs rounded-lg" placeholder="Mulai">
                            <input type="date" name="end_date" id="filterEndDate" class="form-control form-control-sm text-xs rounded-lg" placeholder="Selesai">
                        </div>
                    </div>

                    <!-- 5. Filter Status Persetujuan (Berbasis No. Anggota) -->
                    <div>
                        <label for="filterStatus" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Status Persetujuan Keanggotaan:
                        </label>
                        <select name="status" id="filterStatus" class="form-control form-control-sm text-xs rounded-lg">
                            <option value="">-- Semua Status Persetujuan --</option>
                            <option value="approved">Disetujui (Memiliki No. Anggota / NRA)</option>
                            <option value="belum_approved">Belum Disetujui (Belum Memiliki NRA)</option>
                            <option value="waiting">Status Sistem: Menunggu (Waiting)</option>
                            <option value="proses">Status Sistem: Sedang Diproses</option>
                        </select>
                    </div>

                    <!-- 6. Filter Status Pembayaran / Iuran -->
                    <div>
                        <label for="filterPembayaran" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Status Pembayaran / Iuran:
                        </label>
                        <select name="status_pembayaran" id="filterPembayaran" class="form-control form-control-sm text-xs rounded-lg">
                            <option value="">-- Semua Status Iuran --</option>
                            <option value="1">Lunas (Berhasil)</option>
                            <option value="0">Belum Lunas / Menunggu</option>
                        </select>
                    </div>

                    <!-- 7. Filter Status Sertifikat -->
                    <div>
                        <label for="filterSertifikat" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Status Sertifikat Anggota:
                        </label>
                        <select name="status_sertifikat" id="filterSertifikat" class="form-control form-control-sm text-xs rounded-lg">
                            <option value="">-- Semua Status Sertifikat --</option>
                            <option value="aktif">Sertifikat Aktif (Berlaku)</option>
                            <option value="expired">Sertifikat Kedaluwarsa</option>
                            <option value="belum">Belum Memiliki Sertifikat</option>
                        </select>
                    </div>

                    <!-- 8. Pencarian Cepat & Tombol Aksi -->
                    <div>
                        <label for="filterSearch" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Kata Kunci (Klinik / No Anggota):
                        </label>
                        <input type="text" name="search" id="filterSearch" class="form-control form-control-sm text-xs rounded-lg" placeholder="Cari klinik, NRA, dokter...">
                    </div>

                </div>

                <!-- Action Button Row -->
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <button type="submit" class="btn btn-sm btn-primary font-bold px-4 py-2 rounded-xl shadow-sm flex items-center gap-1.5" id="btnApplyFilter">
                            <i class="fas fa-check-circle"></i>
                            <span>Terapkan Filter</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary font-medium px-3 py-2 rounded-xl flex items-center gap-1.5" id="btnResetFilter">
                            <i class="fas fa-undo"></i>
                            <span>Reset Filter</span>
                        </button>
                    </div>
                    <div class="text-xs text-slate-500 flex items-center gap-2">
                        <span id="filterLoadingIndicator" style="display: none;" class="text-red-600 font-bold">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Memperbarui data nasional...
                        </span>
                        <span id="activeFilterBadge" class="badge badge-light border border-slate-200 text-slate-600">
                            Cakupan: Nasional (Seluruh Indonesia)
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Executive KPI Stat Cards (9 Indikator Utama) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Card 1: Total Anggota Terdaftar -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-800 relative overflow-hidden bg-white dark:bg-slate-900">
            <div class="card-body p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Anggota Klinik</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1" id="statTotalAnggota">
                            {{ number_format($stats['total_anggota'], 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl shrink-0 shadow-sm">
                        <i class="fas fa-hospital-user"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Skala Nasional Se-Indonesia</span>
                    <span class="text-blue-600 font-bold"><i class="fas fa-globe"></i> 100%</span>
                </div>
            </div>
            <div class="h-1 bg-blue-500"></div>
        </div>

        <!-- Card 2: Anggota Disetujui (Ber-NRA Resmi) -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-800 relative overflow-hidden bg-white dark:bg-slate-900">
            <div class="card-body p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Disetujui (Ber-NRA)</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1" id="statAnggotaAktif">
                            {{ number_format($stats['anggota_aktif'], 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shrink-0 shadow-sm">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Memiliki No. Registrasi Anggota</span>
                    <span class="text-emerald-600 font-bold" id="statAktifPct">
                        {{ $stats['total_anggota'] > 0 ? round(($stats['anggota_aktif'] / $stats['total_anggota']) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>
            <div class="h-1 bg-emerald-500"></div>
        </div>

        <!-- Card 3: Belum Disetujui / Pending -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-800 relative overflow-hidden bg-white dark:bg-slate-900">
            <div class="card-body p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-amber-500">Belum Ber-NRA / Pending</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1" id="statAnggotaInaktif">
                            {{ number_format($stats['anggota_inaktif'], 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl shrink-0 shadow-sm">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Menunggu Verifikasi & NRA</span>
                    <span class="text-amber-600 font-bold" id="statInaktifPct">
                        {{ $stats['total_anggota'] > 0 ? round(($stats['anggota_inaktif'] / $stats['total_anggota']) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>
            <div class="h-1 bg-amber-500"></div>
        </div>

        <!-- Card 4: Cakupan Provinsi Aktif -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-800 relative overflow-hidden bg-white dark:bg-slate-900">
            <div class="card-body p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">Provinsi Terjangkau</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1" id="statProvinsiAktif">
                            {{ $stats['total_provinsi_aktif'] ?? 0 }} <span class="text-xs font-normal text-slate-400">/ 38 Prov</span>
                        </h3>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl shrink-0 shadow-sm">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Kab/Kota Aktif: <strong id="statKotaAktif">{{ $stats['total_kota_aktif'] ?? 0 }}</strong></span>
                    <span class="text-purple-600 font-bold">Nasional</span>
                </div>
            </div>
            <div class="h-1 bg-purple-500"></div>
        </div>

    </div>

    <!-- Second Row KPI: Sertifikasi & Keuangan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Card 5: Sertifikat Aktif -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Sertifikat Aktif</p>
                        <h4 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mt-0.5" id="statSertifikatAktif">
                            {{ number_format($stats['sertifikat_aktif'], 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
                <p class="text-[11px] text-slate-500 mt-2 mb-0">
                    Kedaluwarsa: <strong class="text-rose-600" id="statSertifikatExpired">{{ number_format($stats['sertifikat_expired'], 0, ',', '.') }}</strong> klinik
                </p>
            </div>
        </div>

        <!-- Card 6: Belum Bersertifikat -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Belum Bersertifikat</p>
                        <h4 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mt-0.5" id="statSertifikatBelum">
                            {{ number_format($stats['sertifikat_belum'], 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-certificate"></i>
                    </div>
                </div>
                <p class="text-[11px] text-slate-500 mt-2 mb-0">
                    Perlu pengajuan penerbitan
                </p>
            </div>
        </div>

        <!-- Card 7: Total Iuran Lunas (Rp) -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Iuran Terkonfirmasi</p>
                        <h4 class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5" id="statNominalLunas">
                            Rp {{ number_format($stats['nominal_bayar_lunas'], 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
                <p class="text-[11px] text-slate-500 mt-2 mb-0">
                    Transaksi lunas: <strong class="text-slate-700 dark:text-slate-300" id="statTrxLunas">{{ number_format($stats['transaksi_bayar_lunas'], 0, ',', '.') }}</strong>
                </p>
            </div>
        </div>

        <!-- Card 8: Iuran Pending (Rp) -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Iuran Tertunda</p>
                        <h4 class="text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400 mt-0.5" id="statNominalPending">
                            Rp {{ number_format($stats['nominal_bayar_pending'], 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 flex items-center justify-center text-lg shrink-0">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <p class="text-[11px] text-slate-500 mt-2 mb-0">
                    Transaksi pending: <strong class="text-slate-700 dark:text-slate-300" id="statTrxPending">{{ number_format($stats['transaksi_bayar_pending'], 0, ',', '.') }}</strong>
                </p>
            </div>
        </div>

    </div>

    <!-- Interactive Navigation Tabs (6 Kategori Pelaporan Lengkap) -->
    <div class="border-b border-slate-200 dark:border-slate-800">
        <ul class="nav nav-tabs flex-nowrap overflow-x-auto text-xs font-bold border-0 -mb-px" id="reportTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active px-4 py-3 rounded-t-xl text-slate-600 border-0 hover:text-red-700 flex items-center gap-2" id="tab-dashboard-link" data-toggle="tab" href="#tab-dashboard" role="tab">
                    <i class="fas fa-chart-line text-sm text-red-600"></i>
                    <span>Dashboard & Grafik</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-3 rounded-t-xl text-slate-600 border-0 hover:text-red-700 flex items-center gap-2" id="tab-anggota-link" data-toggle="tab" href="#tab-anggota" role="tab">
                    <i class="fas fa-clinic-medical text-sm text-emerald-600"></i>
                    <span>Data Anggota Nasional</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-3 rounded-t-xl text-slate-600 border-0 hover:text-red-700 flex items-center gap-2" id="tab-sertifikat-link" data-toggle="tab" href="#tab-sertifikat" role="tab">
                    <i class="fas fa-certificate text-sm text-blue-600"></i>
                    <span>Laporan Sertifikat</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-3 rounded-t-xl text-slate-600 border-0 hover:text-red-700 flex items-center gap-2" id="tab-pembayaran-link" data-toggle="tab" href="#tab-pembayaran" role="tab">
                    <i class="fas fa-money-bill-wave text-sm text-amber-600"></i>
                    <span>Laporan Pembayaran & Iuran</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-3 rounded-t-xl text-slate-600 border-0 hover:text-red-700 flex items-center gap-2" id="tab-rekap-link" data-toggle="tab" href="#tab-rekap" role="tab">
                    <i class="fas fa-table-list text-sm text-purple-600"></i>
                    <span>Rekapitulasi 38 Provinsi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link px-4 py-3 rounded-t-xl text-slate-600 border-0 hover:text-red-700 flex items-center gap-2" id="tab-matriks-link" data-toggle="tab" href="#tab-matriks" role="tab">
                    <i class="fas fa-th text-sm text-indigo-600"></i>
                    <span>Matriks Analisis Silang</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Tab Content Area -->
    <div class="tab-content" id="reportTabsContent">

        <!-- TAB 1: DASHBOARD EKSEKUTIF & GRAFIK -->
        <div class="tab-pane fade show active" id="tab-dashboard" role="tabpanel">
            <div class="space-y-6">

                <!-- 4 Executive Charts Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Chart 1: Pertumbuhan Pendaftaran Anggota -->
                    <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                        <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3 flex items-center justify-between">
                            <h5 class="text-sm font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                                <i class="fas fa-chart-area text-red-600"></i>
                                Tren Pertumbuhan Pendaftaran Anggota Nasional
                            </h5>
                            <span class="badge badge-light border text-[10px]" id="growthChartSubtitle">Tahunan / Bulanan</span>
                        </div>
                        <div class="card-body p-4">
                            <div style="height: 280px; position: relative;">
                                <canvas id="growthChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 2: Top 15 Provinsi Terbanyak -->
                    <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                        <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3 flex items-center justify-between">
                            <h5 class="text-sm font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                                <i class="fas fa-map-location-dot text-indigo-600"></i>
                                Peringkat 15 Wilayah dengan Anggota Terbanyak
                            </h5>
                            <span class="badge badge-light border text-[10px]">Peringkat Nasional</span>
                        </div>
                        <div class="card-body p-4">
                            <div style="height: 280px; position: relative;">
                                <canvas id="regionalChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 3: Proporsi Status Keanggotaan -->
                    <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                        <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3 flex items-center justify-between">
                            <h5 class="text-sm font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                                <i class="fas fa-pie-chart text-emerald-600"></i>
                                Komposisi Status Persetujuan & Keanggotaan
                            </h5>
                            <span class="badge badge-light border text-[10px]">Validasi NRA</span>
                        </div>
                        <div class="card-body p-4">
                            <div style="height: 260px; position: relative;">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Chart 4: Status Sertifikasi & Pembayaran -->
                    <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                        <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3 flex items-center justify-between">
                            <h5 class="text-sm font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                                <i class="fas fa-shield-virus text-amber-600"></i>
                                Distribusi Status Sertifikasi Klinik
                            </h5>
                            <span class="badge badge-light border text-[10px]">Masa Berlaku</span>
                        </div>
                        <div class="card-body p-4">
                            <div style="height: 260px; position: relative;">
                                <canvas id="breakdownChart"></canvas>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- YoY Comparison Quick Widget -->
                <div class="card shadow-sm border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30">
                    <div class="card-body p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h6 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">
                                    <i class="fas fa-arrows-split-up-and-left text-red-600 mr-1.5"></i> Komparasi Pertumbuhan Antarperiode (Year-Over-Year)
                                </h6>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-0">
                                    Perbandingan pendaftar baru dan anggota ber-NRA antara Tahun {{ $rekapitulasi['yoy']['current_year'] }} dan Tahun {{ $rekapitulasi['yoy']['prev_year'] }}.
                                </p>
                            </div>
                            <div class="flex items-center gap-4 text-xs font-semibold">
                                <div class="px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border shadow-sm">
                                    <span class="text-slate-400 block text-[10px]">Tahun {{ $rekapitulasi['yoy']['current_year'] }}:</span>
                                    <strong class="text-slate-800 dark:text-white text-sm" id="yoyCurrentTotal">{{ $rekapitulasi['yoy']['anggota_current'] }}</strong> klinik
                                    (<span class="text-emerald-600 font-bold" id="yoyCurrentAktif">{{ $rekapitulasi['yoy']['aktif_current'] }}</span> Ber-NRA)
                                </div>
                                <div class="px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border shadow-sm">
                                    <span class="text-slate-400 block text-[10px]">Tahun {{ $rekapitulasi['yoy']['prev_year'] }}:</span>
                                    <strong class="text-slate-800 dark:text-white text-sm" id="yoyPrevTotal">{{ $rekapitulasi['yoy']['anggota_prev'] }}</strong> klinik
                                    (<span class="text-emerald-600 font-bold" id="yoyPrevAktif">{{ $rekapitulasi['yoy']['aktif_prev'] }}</span> Ber-NRA)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- TAB 2: DATA ANGGOTA NASIONAL -->
        <div class="tab-pane fade" id="tab-anggota" role="tabpanel">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3 flex items-center justify-between">
                    <div>
                        <h5 class="text-sm font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                            <i class="fas fa-clinic-medical text-emerald-600"></i>
                            Daftar Lengkap Anggota Klinik Nasional
                        </h5>
                        <p class="text-xs text-slate-500 mb-0">Data fasilitas pelayanan kesehatan terdaftar di seluruh provinsi</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn btn-xs btn-success font-medium export-link" data-format="excel" data-type="anggota">
                            <i class="fas fa-file-excel mr-1"></i> Export Excel Anggota
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive rounded-xl border border-slate-200 dark:border-slate-800">
                        <table class="table table-hover table-sm text-xs mb-0" id="tableAnggota" style="width: 100%;">
                            <thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                <tr>
                                    <th style="width: 35px;" class="text-center">No</th>
                                    <th>No. Anggota (NRA)</th>
                                    <th>Nama Klinik</th>
                                    <th>Nama Pemilik</th>
                                    <th>Provinsi</th>
                                    <th>Kabupaten/Kota</th>
                                    <th>Status Persetujuan</th>
                                    <th>Status Iuran</th>
                                    <th>Tgl Daftar</th>
                                    <th style="width: 60px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: LAPORAN SERTIFIKAT NASIONAL -->
        <div class="tab-pane fade" id="tab-sertifikat" role="tabpanel">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3 flex items-center justify-between">
                    <div>
                        <h5 class="text-sm font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                            <i class="fas fa-certificate text-blue-600"></i>
                            Laporan Penerbitan & Masa Berlaku Sertifikat Nasional
                        </h5>
                        <p class="text-xs text-slate-500 mb-0">Daftar sertifikat keanggotaan klinik di seluruh Indonesia</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn btn-xs btn-success font-medium export-link" data-format="excel" data-type="sertifikat">
                            <i class="fas fa-file-excel mr-1"></i> Export Excel Sertifikat
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive rounded-xl border border-slate-200 dark:border-slate-800">
                        <table class="table table-hover table-sm text-xs mb-0" id="tableSertifikat" style="width: 100%;">
                            <thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                <tr>
                                    <th style="width: 35px;" class="text-center">No</th>
                                    <th>No. Anggota</th>
                                    <th>Nama Klinik</th>
                                    <th>Kab/Kota & Provinsi</th>
                                    <th>Nomor Sertifikat (Kode)</th>
                                    <th>Tgl Terbit</th>
                                    <th>Masa Berlaku</th>
                                    <th>Status Masa Berlaku</th>
                                    <th>Status Bayar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 4: LAPORAN PEMBAYARAN & IURAN NASIONAL -->
        <div class="tab-pane fade" id="tab-pembayaran" role="tabpanel">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3 flex items-center justify-between">
                    <div>
                        <h5 class="text-sm font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                            <i class="fas fa-money-bill-wave text-amber-600"></i>
                            Laporan Transaksi Pembayaran & Iuran Nasional
                        </h5>
                        <p class="text-xs text-slate-500 mb-0">Rekonsiliasi transaksi pembayaran klinik se-Indonesia</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn btn-xs btn-success font-medium export-link" data-format="excel" data-type="pembayaran">
                            <i class="fas fa-file-excel mr-1"></i> Export Excel Iuran
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive rounded-xl border border-slate-200 dark:border-slate-800">
                        <table class="table table-hover table-sm text-xs mb-0" id="tablePembayaran" style="width: 100%;">
                            <thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                <tr>
                                    <th style="width: 35px;" class="text-center">No</th>
                                    <th>No. Anggota</th>
                                    <th>Nama Klinik</th>
                                    <th>Kab/Kota</th>
                                    <th>Kategori Pembayaran</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Periode</th>
                                    <th>Nominal (Rp)</th>
                                    <th>Status Transaksi</th>
                                    <th style="width: 70px;" class="text-center">Bukti</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 5: REKAPITULASI 38 PROVINSI -->
        <div class="tab-pane fade" id="tab-rekap" role="tabpanel">
            <div class="space-y-6">

                <!-- Rekapitulasi per Provinsi Table -->
                <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                    <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3 flex items-center justify-between">
                        <div>
                            <h5 class="text-sm font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                                <i class="fas fa-table-list text-purple-600"></i>
                                Rekapitulasi Distribusi Keanggotaan Seluruh 38 Provinsi
                            </h5>
                            <p class="text-xs text-slate-500 mb-0">Ringkasan total klinik, status ber-NRA, dan status pelunasan iuran</p>
                        </div>
                        <div>
                            <button type="button" class="btn btn-xs btn-success font-medium export-link" data-format="excel" data-type="rekapitulasi">
                                <i class="fas fa-file-excel mr-1"></i> Export Excel Rekapitulasi
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-xl border border-slate-200 dark:border-slate-800 max-h-96 overflow-y-auto">
                            <table class="table table-hover table-sm text-xs mb-0">
                                <thead class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 sticky top-0">
                                    <tr>
                                        <th style="width: 35px;" class="text-center">No</th>
                                        <th>Provinsi / Wilayah</th>
                                        <th class="text-right">Total Klinik</th>
                                        <th class="text-right text-emerald-700">Disetujui (Ber-NRA)</th>
                                        <th class="text-right text-amber-700">Belum Ber-NRA</th>
                                        <th class="text-right text-blue-700">Iuran Lunas</th>
                                        <th class="text-right text-rose-700">Iuran Pending</th>
                                        <th class="text-right">% Disetujui</th>
                                    </tr>
                                </thead>
                                <tbody id="rekapTableBody" class="divide-y divide-slate-100 dark:divide-slate-800">
                                    @php
                                        $totAll = 0; $totAktif = 0; $totInaktif = 0; $totLunas = 0; $totPending = 0;
                                    @endphp
                                    @forelse($rekapitulasi['regional_data'] as $idx => $r)
                                        @php
                                            $totAll += $r->total_anggota;
                                            $totAktif += $r->aktif;
                                            $totInaktif += $r->inaktif;
                                            $totLunas += $r->lunas;
                                            $totPending += $r->belum_lunas;
                                            $pct = $r->total_anggota > 0 ? round(($r->aktif / $r->total_anggota) * 100, 1) : 0;
                                        @endphp
                                        <tr>
                                            <td class="text-center font-mono">{{ $idx + 1 }}</td>
                                            <td class="font-bold text-slate-800 dark:text-slate-200">{{ $r->name }}</td>
                                            <td class="text-right font-semibold">{{ number_format($r->total_anggota, 0, ',', '.') }}</td>
                                            <td class="text-right text-emerald-600 font-bold">{{ number_format($r->aktif, 0, ',', '.') }}</td>
                                            <td class="text-right text-amber-600">{{ number_format($r->inaktif, 0, ',', '.') }}</td>
                                            <td class="text-right text-blue-600">{{ number_format($r->lunas, 0, ',', '.') }}</td>
                                            <td class="text-right text-rose-600">{{ number_format($r->belum_lunas, 0, ',', '.') }}</td>
                                            <td class="text-right">
                                                <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-bold {{ $pct >= 80 ? 'bg-emerald-100 text-emerald-800' : ($pct >= 50 ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                                    {{ $pct }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-slate-400">Tidak ada data provinsi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="bg-slate-200 dark:bg-slate-800 font-bold text-slate-900 dark:text-white sticky bottom-0" id="rekapTableFoot">
                                    <tr>
                                        <td colspan="2" class="text-right font-extrabold uppercase">TOTAL NASIONAL:</td>
                                        <td class="text-right font-extrabold">{{ number_format($totAll, 0, ',', '.') }}</td>
                                        <td class="text-right font-extrabold text-emerald-700">{{ number_format($totAktif, 0, ',', '.') }}</td>
                                        <td class="text-right font-extrabold text-amber-700">{{ number_format($totInaktif, 0, ',', '.') }}</td>
                                        <td class="text-right font-extrabold text-blue-700">{{ number_format($totLunas, 0, ',', '.') }}</td>
                                        <td class="text-right font-extrabold text-rose-700">{{ number_format($totPending, 0, ',', '.') }}</td>
                                        <td class="text-right font-extrabold">
                                            {{ $totAll > 0 ? round(($totAktif / $totAll) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Rekapitulasi Tahunan -->
                <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                    <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3">
                        <h5 class="text-sm font-bold text-slate-800 dark:text-white mb-0">
                            Rekapitulasi Pendaftaran Berdasarkan Tahun
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-xl border border-slate-200 dark:border-slate-800">
                            <table class="table table-hover table-sm text-xs mb-0">
                                <thead class="bg-slate-100 dark:bg-slate-800">
                                    <tr>
                                        <th>Tahun Pendaftaran</th>
                                        <th class="text-right">Total Anggota Mendaftar</th>
                                        <th class="text-right text-emerald-700">Disetujui (Ber-NRA)</th>
                                        <th class="text-right text-blue-700">Iuran Lunas</th>
                                    </tr>
                                </thead>
                                <tbody id="rekapTahunBody">
                                    @foreach($rekapitulasi['tahun_breakdown'] as $tb)
                                        <tr>
                                            <td class="font-bold">{{ $tb->tahun }}</td>
                                            <td class="text-right">{{ number_format($tb->total, 0, ',', '.') }}</td>
                                            <td class="text-right text-emerald-600 font-bold">{{ number_format($tb->disetujui, 0, ',', '.') }}</td>
                                            <td class="text-right text-blue-600 font-bold">{{ number_format($tb->lunas, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- TAB 6: MATRIKS ANALISIS SILANG -->
        <div class="tab-pane fade" id="tab-matriks" role="tabpanel">
            <div class="space-y-6">

                <!-- Header Actions Matriks -->
                <div class="flex items-center justify-between">
                    <div>
                        <h5 class="text-sm font-bold text-slate-800 dark:text-white mb-0 flex items-center gap-2">
                            <i class="fas fa-th text-indigo-600"></i>
                            Matriks Pivot Analisis Multi-Dimensi Nasional
                        </h5>
                        <p class="text-xs text-slate-500 mb-0">Analisis korelasi silang antara wilayah, waktu pendaftaran, status keanggotaan, dan sertifikasi</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-xs btn-success font-medium export-link" data-format="excel" data-type="matriks">
                            <i class="fas fa-file-excel mr-1"></i> Export Excel Matriks
                        </button>
                    </div>
                </div>

                <!-- Matriks 1: Wilayah x Status Anggota -->
                <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                    <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3">
                        <h6 class="text-xs font-bold text-slate-800 dark:text-white mb-0">
                            Matriks 1: Wilayah (Provinsi) × Status Persetujuan Anggota
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-xl border border-slate-200 dark:border-slate-800 max-h-80 overflow-y-auto">
                            <table class="table table-hover table-sm text-xs mb-0">
                                <thead class="bg-slate-100 dark:bg-slate-800 sticky top-0">
                                    <tr>
                                        <th>Provinsi / Wilayah</th>
                                        <th class="text-right text-emerald-700">Disetujui (Ber-NRA)</th>
                                        <th class="text-right text-amber-700">Belum Ber-NRA</th>
                                        <th class="text-right text-blue-700">Iuran Lunas</th>
                                        <th class="text-right text-purple-700">Lainnya / Revisi</th>
                                        <th class="text-right font-black">Total Baris</th>
                                    </tr>
                                </thead>
                                <tbody id="matrix1Body">
                                    @foreach($matriks['matrix_wilayah_status'] as $m1)
                                        <tr>
                                            <td class="font-bold">{{ $m1->baris }}</td>
                                            <td class="text-right text-emerald-600 font-bold">{{ number_format($m1->col_approved, 0, ',', '.') }}</td>
                                            <td class="text-right text-amber-600">{{ number_format($m1->col_waiting, 0, ',', '.') }}</td>
                                            <td class="text-right text-blue-600">{{ number_format($m1->col_proses, 0, ',', '.') }}</td>
                                            <td class="text-right text-purple-600">{{ number_format($m1->col_lainnya, 0, ',', '.') }}</td>
                                            <td class="text-right font-black">{{ number_format($m1->col_total, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Matriks 2 & 3 Side by Side -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Matriks 2: Tahun x Status -->
                    <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                        <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3">
                            <h6 class="text-xs font-bold text-slate-800 dark:text-white mb-0">
                                Matriks 2: Tahun Pendaftaran × Status Anggota
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive rounded-xl border border-slate-200 dark:border-slate-800">
                                <table class="table table-hover table-sm text-xs mb-0">
                                    <thead class="bg-slate-100 dark:bg-slate-800">
                                        <tr>
                                            <th>Tahun</th>
                                            <th class="text-right text-emerald-700">Ber-NRA</th>
                                            <th class="text-right text-amber-700">Pending</th>
                                            <th class="text-right text-blue-700">Lunas</th>
                                            <th class="text-right font-black">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="matrix2Body">
                                        @foreach($matriks['matrix_tahun_status'] as $m2)
                                            <tr>
                                                <td class="font-bold">{{ $m2->baris }}</td>
                                                <td class="text-right text-emerald-600 font-bold">{{ $m2->col_approved }}</td>
                                                <td class="text-right text-amber-600">{{ $m2->col_waiting }}</td>
                                                <td class="text-right text-blue-600">{{ $m2->col_proses }}</td>
                                                <td class="text-right font-black">{{ $m2->col_total }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Matriks 3: Tahun x Status Sertifikat -->
                    <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                        <div class="card-header bg-slate-50/70 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-800 py-3">
                            <h6 class="text-xs font-bold text-slate-800 dark:text-white mb-0">
                                Matriks 3: Tahun Pendaftaran × Status Sertifikat
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive rounded-xl border border-slate-200 dark:border-slate-800">
                                <table class="table table-hover table-sm text-xs mb-0">
                                    <thead class="bg-slate-100 dark:bg-slate-800">
                                        <tr>
                                            <th>Tahun</th>
                                            <th class="text-right text-emerald-700">Aktif</th>
                                            <th class="text-right text-rose-700">Expired</th>
                                            <th class="text-right text-slate-500">Belum Ada</th>
                                            <th class="text-right font-black">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="matrix3Body">
                                        @foreach($matriks['matrix_tahun_sertifikat'] as $m3)
                                            <tr>
                                                <td class="font-bold">{{ $m3->baris }}</td>
                                                <td class="text-right text-emerald-600 font-bold">{{ $m3->col_aktif }}</td>
                                                <td class="text-right text-rose-600">{{ $m3->col_expired }}</td>
                                                <td class="text-right text-slate-500">{{ $m3->col_belum }}</td>
                                                <td class="text-right font-black">{{ $m3->col_total }}</td>
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

</div>

<!-- Loading Modal -->
<div class="modal fade" id="pusatLoadingModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-danger mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                <h5 class="modal-title font-bold text-slate-800 mb-1">Sedang Menyiapkan Dokumen Nasional</h5>
                <p class="text-xs text-slate-500 mb-0">Sistem sedang merangkum dan mengekspor data berskala nasional...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js 2.7.1 -->
<script src="{{ asset('backend/assets/bundles/chartjs/chart.min.js') }}"></script>

<script>
$(document).ready(function() {
    let growthChartInst = null;
    let regionalChartInst = null;
    let statusChartInst = null;
    let breakdownChartInst = null;

    let dtAnggota = null;
    let dtSertifikat = null;
    let dtPembayaran = null;

    // Toggle panel filter
    $('#btnToggleFilter').on('click', function() {
        $('#filterBody').slideToggle(200);
        $('#iconToggleFilter').toggleClass('fa-chevron-up fa-chevron-down');
    });

    // Chained AJAX: Provinsi -> Kota/Kabupaten
    $('#filterProvinsi').on('change', function() {
        let provId = $(this).val();
        let $kotaSelect = $('#filterKota');
        $kotaSelect.html('<option value="">-- Memuat Kab/Kota... --</option>');

        if (!provId) {
            $kotaSelect.html('<option value="">-- Seluruh Kabupaten/Kota --</option>');
            return;
        }

        $.ajax({
            url: "{{ route('getKota') }}",
            type: "POST",
            data: {
                id_provinsi: provId,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                $kotaSelect.html(response);
            },
            error: function() {
                $kotaSelect.html('<option value="">-- Gagal Memuat Kab/Kota --</option>');
            }
        });
    });

    // Helper Number Format
    function fmtNum(n) {
        return new Intl.NumberFormat('id-ID').format(n || 0);
    }

    // Inisialisasi Grafik Tren Pertumbuhan
    function initGrowthChart(chartData) {
        let ctx = document.getElementById('growthChart');
        if (!ctx) return;
        if (growthChartInst) growthChartInst.destroy();

        growthChartInst = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: chartData.labels || [],
                datasets: [
                    {
                        label: 'Total Pendaftaran',
                        data: chartData.totals || [],
                        backgroundColor: 'rgba(220, 38, 38, 0.25)',
                        borderColor: '#dc2626',
                        borderWidth: 2
                    },
                    {
                        label: 'Disetujui (Ber-NRA)',
                        data: chartData.aktifs || [],
                        backgroundColor: 'rgba(16, 185, 129, 0.75)',
                        borderColor: '#10b981',
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { position: 'top', labels: { fontSize: 11, boxWidth: 12 } },
                scales: {
                    yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }],
                    xAxes: [{ gridLines: { display: false } }]
                }
            }
        });
    }

    // Inisialisasi Grafik Sebaran 15 Wilayah
    function initRegionalChart(chartData) {
        let ctx = document.getElementById('regionalChart');
        if (!ctx) return;
        if (regionalChartInst) regionalChartInst.destroy();

        regionalChartInst = new Chart(ctx.getContext('2d'), {
            type: 'horizontalBar',
            data: {
                labels: chartData.labels || [],
                datasets: [{
                    label: 'Jumlah Fasilitas Klinik',
                    data: chartData.data || [],
                    backgroundColor: 'rgba(79, 70, 229, 0.75)',
                    borderColor: '#4f46e5',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                scales: {
                    xAxes: [{ ticks: { beginAtZero: true, precision: 0 } }],
                    yAxes: [{ ticks: { fontSize: 10 } }]
                }
            }
        });
    }

    // Inisialisasi Grafik Status Keanggotaan
    function initStatusChart(chartData) {
        let ctx = document.getElementById('statusChart');
        if (!ctx) return;
        if (statusChartInst) statusChartInst.destroy();

        statusChartInst = new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: chartData.labels || [],
                datasets: [{
                    data: chartData.data || [],
                    backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#8b5cf6', '#ec4899', '#64748b', '#ef4444'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { position: 'right', labels: { fontSize: 10, boxWidth: 12 } },
                cutoutPercentage: 65
            }
        });
    }

    // Inisialisasi Grafik Breakdown Sertifikat
    function initBreakdownChart(breakdownData) {
        let ctx = document.getElementById('breakdownChart');
        if (!ctx) return;
        if (breakdownChartInst) breakdownChartInst.destroy();

        let cert = breakdownData.certificate || { labels: [], data: [] };

        breakdownChartInst = new Chart(ctx.getContext('2d'), {
            type: 'pie',
            data: {
                labels: cert.labels || [],
                datasets: [{
                    data: cert.data || [],
                    backgroundColor: ['#10b981', '#ef4444', '#94a3b8'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { position: 'right', labels: { fontSize: 10, boxWidth: 12 } }
            }
        });
    }

    // Inisialisasi Charts dari data awal server
    initGrowthChart(@json($growthChart));
    initRegionalChart(@json($regionalChart));
    initStatusChart(@json($statusChart));
    initBreakdownChart(@json($breakdownChart));

    // DataTables: Anggota
    function initDataTableAnggota() {
        if ($.fn.DataTable.isDataTable('#tableAnggota')) return;

        dtAnggota = $('#tableAnggota').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('laporan_pusat.list') }}",
                data: function(d) {
                    let formVals = $('#filterForm').serializeArray();
                    formVals.forEach(function(item) {
                        d[item.name] = item.value;
                    });
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'no_anggota', name: 'no_anggota', defaultContent: '-' },
                { data: 'nama_klinik', name: 'nama_klinik' },
                { data: 'nama_pemilik_klinik', name: 'nama_pemilik_klinik', defaultContent: '-' },
                { data: 'provinsi_name', name: 'indonesia_provinces.name', defaultContent: '-' },
                { data: 'kota_name', name: 'indonesia_cities.name', defaultContent: '-' },
                { data: 'status_badge', name: 'status' },
                { data: 'status_pembayaran_badge', name: 'status_pembayaran' },
                { data: 'tgl_daftar', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            order: [[8, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json' }
        });
    }

    // DataTables: Sertifikat
    function initDataTableSertifikat() {
        if ($.fn.DataTable.isDataTable('#tableSertifikat')) return;

        dtSertifikat = $('#tableSertifikat').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('laporan_pusat.sertifikat_list') }}",
                data: function(d) {
                    let formVals = $('#filterForm').serializeArray();
                    formVals.forEach(function(item) {
                        d[item.name] = item.value;
                    });
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'no_anggota', name: 'anggota.no_anggota', defaultContent: '-' },
                { data: 'nama_klinik', name: 'anggota.nama_klinik' },
                { data: 'kota_name', name: 'indonesia_cities.name', defaultContent: '-' },
                { data: 'kode', name: 'kode', defaultContent: '-' },
                { data: 'tgl_terbit', name: 'dari' },
                { data: 'tgl_berlaku', name: 'sampai' },
                { data: 'status_badge', name: 'status_masa_berlaku' },
                { data: 'status_bayar_badge', name: 'sertifikat.status_pembayaran' }
            ],
            order: [[6, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json' }
        });
    }

    // DataTables: Pembayaran
    function initDataTablePembayaran() {
        if ($.fn.DataTable.isDataTable('#tablePembayaran')) return;

        dtPembayaran = $('#tablePembayaran').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('laporan_pusat.pembayaran_list') }}",
                data: function(d) {
                    let formVals = $('#filterForm').serializeArray();
                    formVals.forEach(function(item) {
                        d[item.name] = item.value;
                    });
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'no_anggota', name: 'anggota.no_anggota', defaultContent: '-' },
                { data: 'nama_klinik', name: 'anggota.nama_klinik' },
                { data: 'kota_name', name: 'indonesia_cities.name', defaultContent: '-' },
                { data: 'nama_kategori', name: 'pembayaran_kategori.nama', defaultContent: 'Iuran Anggota' },
                { data: 'tanggal_bayar_formatted', name: 'tanggal_bayar' },
                { data: 'periode', name: 'periode', orderable: false },
                { data: 'nominal_formatted', name: 'total' },
                { data: 'status_badge', name: 'pembayaran.status' },
                { data: 'bukti_view', name: 'bukti', orderable: false, searchable: false, className: 'text-center' }
            ],
            order: [[5, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json' }
        });
    }

    // Lazy load datatables when tabs are activated
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        let target = $(e.target).attr('href');
        if (target === '#tab-anggota') {
            initDataTableAnggota();
            if (dtAnggota) dtAnggota.columns.adjust().draw(false);
        } else if (target === '#tab-sertifikat') {
            initDataTableSertifikat();
            if (dtSertifikat) dtSertifikat.columns.adjust().draw(false);
        } else if (target === '#tab-pembayaran') {
            initDataTablePembayaran();
            if (dtPembayaran) dtPembayaran.columns.adjust().draw(false);
        }
    });

    // Handle AJAX Filter Submission
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        $('#filterLoadingIndicator').show();

        let formData = $(this).serialize();

        // 1. Ambil data analitik nasional terbaru
        $.ajax({
            url: "{{ route('laporan_pusat.chart_data') }}",
            type: "GET",
            data: formData,
            success: function(res) {
                $('#filterLoadingIndicator').hide();

                // Perbarui Stat Cards KPI
                let s = res.stats;
                $('#statTotalAnggota').text(fmtNum(s.total_anggota));
                $('#statAnggotaAktif').text(fmtNum(s.anggota_aktif));
                $('#statAnggotaInaktif').text(fmtNum(s.anggota_inaktif));
                $('#statProvinsiAktif').html(s.total_provinsi_aktif + ' <span class="text-xs font-normal text-slate-400">/ 38 Prov</span>');
                $('#statKotaAktif').text(s.total_kota_aktif);

                let aktPct = s.total_anggota > 0 ? ((s.anggota_aktif / s.total_anggota) * 100).toFixed(1) : 0;
                let inaktPct = s.total_anggota > 0 ? ((s.anggota_inaktif / s.total_anggota) * 100).toFixed(1) : 0;
                $('#statAktifPct').text(aktPct + '%');
                $('#statInaktifPct').text(inaktPct + '%');

                $('#statSertifikatAktif').text(fmtNum(s.sertifikat_aktif));
                $('#statSertifikatExpired').text(fmtNum(s.sertifikat_expired));
                $('#statSertifikatBelum').text(fmtNum(s.sertifikat_belum));

                $('#statNominalLunas').text('Rp ' + fmtNum(s.nominal_bayar_lunas));
                $('#statNominalPending').text('Rp ' + fmtNum(s.nominal_bayar_pending));
                $('#statTrxLunas').text(fmtNum(s.transaksi_bayar_lunas));
                $('#statTrxPending').text(fmtNum(s.transaksi_bayar_pending));

                // Perbarui 4 Grafik
                initGrowthChart(res.growth);
                initRegionalChart(res.regional);
                initStatusChart(res.status);
                initBreakdownChart(res.breakdown);

                // Perbarui Tabel Rekapitulasi per Provinsi
                let rekapRows = '';
                let tAll = 0, tAkt = 0, tInakt = 0, tLun = 0, tPend = 0;
                (res.rekapitulasi.regional_data || []).forEach(function(row, i) {
                    tAll += parseInt(row.total_anggota || 0);
                    tAkt += parseInt(row.aktif || 0);
                    tInakt += parseInt(row.inaktif || 0);
                    tLun += parseInt(row.lunas || 0);
                    tPend += parseInt(row.belum_lunas || 0);
                    let p = row.total_anggota > 0 ? ((row.aktif / row.total_anggota) * 100).toFixed(1) : 0;
                    let pBadge = p >= 80 ? 'bg-emerald-100 text-emerald-800' : (p >= 50 ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800');

                    rekapRows += `<tr>
                        <td class="text-center font-mono">${i + 1}</td>
                        <td class="font-bold text-slate-800 dark:text-slate-200">${row.name}</td>
                        <td class="text-right font-semibold">${fmtNum(row.total_anggota)}</td>
                        <td class="text-right text-emerald-600 font-bold">${fmtNum(row.aktif)}</td>
                        <td class="text-right text-amber-600">${fmtNum(row.inaktif)}</td>
                        <td class="text-right text-blue-600">${fmtNum(row.lunas)}</td>
                        <td class="text-right text-rose-600">${fmtNum(row.belum_lunas)}</td>
                        <td class="text-right"><span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-bold ${pBadge}">${p}%</span></td>
                    </tr>`;
                });
                $('#rekapTableBody').html(rekapRows || '<tr><td colspan="8" class="text-center py-4 text-slate-400">Tidak ada data.</td></tr>');

                let tPct = tAll > 0 ? ((tAkt / tAll) * 100).toFixed(1) : 0;
                $('#rekapTableFoot').html(`<tr>
                    <td colspan="2" class="text-right font-extrabold uppercase">TOTAL NASIONAL:</td>
                    <td class="text-right font-extrabold">${fmtNum(tAll)}</td>
                    <td class="text-right font-extrabold text-emerald-700">${fmtNum(tAkt)}</td>
                    <td class="text-right font-extrabold text-amber-700">${fmtNum(tInakt)}</td>
                    <td class="text-right font-extrabold text-blue-700">${fmtNum(tLun)}</td>
                    <td class="text-right font-extrabold text-rose-700">${fmtNum(tPend)}</td>
                    <td class="text-right font-extrabold">${tPct}%</td>
                </tr>`);

                // Update YoY Values
                if (res.rekapitulasi.yoy) {
                    $('#yoyCurrentTotal').text(res.rekapitulasi.yoy.anggota_current);
                    $('#yoyCurrentAktif').text(res.rekapitulasi.yoy.aktif_current);
                    $('#yoyPrevTotal').text(res.rekapitulasi.yoy.anggota_prev);
                    $('#yoyPrevAktif').text(res.rekapitulasi.yoy.aktif_prev);
                }

                // Update Active Badge
                let provSelected = $('#filterProvinsi option:selected').text().trim();
                let provCode = $('#filterProvinsi').val();
                if (provCode) {
                    $('#activeFilterBadge').text('Cakupan: ' + provSelected);
                } else {
                    $('#activeFilterBadge').text('Cakupan: Nasional (Seluruh Indonesia)');
                }
            },
            error: function() {
                $('#filterLoadingIndicator').hide();
            }
        });

        // 2. Reload DataTables jika sudah aktif
        if (dtAnggota) dtAnggota.ajax.reload();
        if (dtSertifikat) dtSertifikat.ajax.reload();
        if (dtPembayaran) dtPembayaran.ajax.reload();
    });

    // Reset Filter Button
    $('#btnResetFilter').on('click', function() {
        $('#filterForm')[0].reset();
        $('#filterKota').html('<option value="">-- Seluruh Kabupaten/Kota --</option>');
        $('#filterForm').submit();
    });

    // Export Helper (Direct, No hover blocking)
    function executeExport(format, type) {
        let formVals = $('#filterForm').serialize();
        let baseUrl = format === 'excel' 
            ? "{{ route('laporan_pusat.export.excel') }}" 
            : "{{ route('laporan_pusat.export.pdf') }}";

        let fullUrl = baseUrl + '?type=' + encodeURIComponent(type) + '&' + formVals;

        $('#pusatLoadingModal').modal('show');

        let iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = fullUrl;
        document.body.appendChild(iframe);

        setTimeout(function() {
            $('#pusatLoadingModal').modal('hide');
        }, 3000);
    }

    // Export Links Handler
    $(document).on('click', '.export-link', function(e) {
        e.preventDefault();
        let format = $(this).data('format');
        let type = $(this).data('type') || 'anggota';
        executeExport(format, type);
    });

    // Cetak Laporan Handler
    $('#btnPrintLaporan').on('click', function() {
        let formVals = $('#filterForm').serialize();
        let activeTab = $('#reportTabs .nav-link.active').attr('href').replace('#tab-', '');
        let printType = activeTab === 'dashboard' ? 'rekapitulasi' : activeTab;
        let printUrl = "{{ route('laporan_pusat.print') }}?type=" + encodeURIComponent(printType) + '&' + formVals;
        window.open(printUrl, '_blank');
    });
});
</script>
@endpush