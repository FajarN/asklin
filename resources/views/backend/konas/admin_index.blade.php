@extends('layouts.backend.layout')

@section('title', 'Modul Kongres Nasional (KONAS) ASKLIN')
@section('page_title', 'Modul KONAS')

@section('content')
<style>
    /* KONAS Hero Card Dual-Mode High-Contrast Styling */
    .konas-hero-card {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-left: 6px solid #059669 !important;
        border-radius: 1rem !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05) !important;
    }
    .dark .konas-hero-card {
        background-color: #0f172a !important;
        border-color: #1e293b !important;
        border-left-color: #10b981 !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3) !important;
    }
    .konas-hero-pill {
        background-color: #ecfdf5 !important;
        color: #065f46 !important;
        border: 1px solid #a7f3d0 !important;
    }
    .dark .konas-hero-pill {
        background-color: rgba(16, 185, 129, 0.15) !important;
        color: #6ee7b7 !important;
        border-color: rgba(52, 211, 153, 0.3) !important;
    }
    .konas-hero-title {
        color: #0f172a !important;
    }
    .dark .konas-hero-title {
        color: #ffffff !important;
    }
    .konas-hero-desc {
        color: #475569 !important;
    }
    .dark .konas-hero-desc {
        color: #cbd5e1 !important;
    }
    .konas-periode-label {
        color: #64748b !important;
    }
    .dark .konas-periode-label {
        color: #94a3b8 !important;
    }
    .konas-periode-badge {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        border: 1px solid #cbd5e1 !important;
    }
    .dark .konas-periode-badge {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
    }
    .konas-filter-panel {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.875rem !important;
    }
    .dark .konas-filter-panel {
        background-color: rgba(30, 41, 59, 0.6) !important;
        border-color: #334155 !important;
    }
    .konas-filter-label {
        color: #334155 !important;
    }
    .dark .konas-filter-label {
        color: #cbd5e1 !important;
    }
    .konas-select-control {
        background-color: #ffffff !important;
        color: #0f172a !important;
        border: 1px solid #cbd5e1 !important;
    }
    .dark .konas-select-control {
        background-color: #0f172a !important;
        color: #ffffff !important;
        border: 1px solid #475569 !important;
    }
    .konas-tab-btn {
        color: #64748b !important;
        background: transparent !important;
        border-bottom: 2px solid transparent !important;
    }
    .konas-tab-btn:hover {
        color: #059669 !important;
    }
    .konas-tab-btn.active {
        color: #059669 !important;
        background-color: #ffffff !important;
        border-bottom: 2px solid #059669 !important;
        font-weight: 700 !important;
    }
    .dark .konas-tab-btn {
        color: #94a3b8 !important;
        background: transparent !important;
    }
    .dark .konas-tab-btn:hover {
        color: #34d399 !important;
    }
    .dark .konas-tab-btn.active {
        color: #34d399 !important;
        background-color: #0f172a !important;
        border-bottom: 2px solid #34d399 !important;
    }
    .table-responsive table.table thead th {
        background-color: #f8fafc !important;
        color: #334155 !important;
        border-bottom: 2px solid #e2e8f0 !important;
        font-weight: 700 !important;
    }
    .dark .table-responsive table.table thead th {
        background-color: #0f172a !important;
        color: #cbd5e1 !important;
        border-bottom: 2px solid #334155 !important;
    }
    .table-responsive table.table tbody td {
        color: #1e293b !important;
    }
    .dark .table-responsive table.table tbody td {
        color: #f1f5f9 !important;
    }
</style>

<div class="space-y-6">

    <!-- Top Hero Banner & Periode Selector -->
    <div class="konas-hero-card p-5 sm:p-6 relative overflow-hidden">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full konas-hero-pill text-xs font-bold mb-2">
                    <i class="fas fa-landmark"></i> Kongres Nasional 5 Tahunan ASKLIN
                </div>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight konas-hero-title flex items-center gap-2">
                    Administrasi Terpadu Modul KONAS
                </h1>
                <p class="text-xs sm:text-sm konas-hero-desc max-w-2xl mt-1 leading-relaxed">
                    Kelola seluruh data periode kongres, pendaftaran delegasi cabang/daerah, akomodasi hotel, penerbangan, dan sponsor dalam satu antarmuka terpusat.
                </p>
                @if($currentPeriode)
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                        <span class="konas-periode-label font-medium">Periode Terpilih:</span>
                        <span class="font-bold konas-periode-badge px-2.5 py-0.5 rounded-lg">
                            {{ $currentPeriode->nama }} ({{ $currentPeriode->periode }})
                        </span>
                        @if($currentPeriode->status == '1')
                            <span class="bg-emerald-100 text-emerald-800 border border-emerald-300 dark:bg-emerald-500/20 dark:text-emerald-300 dark:border-emerald-400/30 font-bold px-2.5 py-0.5 rounded-lg inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span> Publik Aktif
                            </span>
                        @else
                            <span class="bg-amber-100 text-amber-800 border border-amber-300 dark:bg-amber-500/20 dark:text-amber-300 dark:border-amber-400/30 font-bold px-2.5 py-0.5 rounded-lg inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 dark:bg-amber-400"></span> Publik Nonaktif
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 konas-filter-panel p-3.5 shadow-sm">
                <div class="flex flex-col">
                    <label class="text-[11px] font-bold konas-filter-label mb-1 uppercase tracking-wider">Filter Periode:</label>
                    <select id="global_periode_select" class="form-control text-xs font-semibold konas-select-control rounded-lg focus:border-emerald-500" style="min-width: 220px;" onchange="changeFilterPeriode(this.value)">
                        <option value="">Semua Periode</option>
                        @foreach($allPeriodes as $p)
                            <option value="{{ $p->id }}" {{ $selectedPeriodeId == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }} ({{ $p->periode }}) {{ $p->status == '1' ? '★ Aktif' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:self-end">
                    <button type="button" class="btn btn-emerald btn-sm w-full font-bold text-white bg-emerald-600 hover:bg-emerald-500 border-0 shadow-sm" onclick="tambahPeriodeModal()">
                        <i class="fas fa-plus mr-1"></i> Buat Periode
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
        <!-- Card 1 -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700/60 shadow-sm">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Delegasi Peserta</div>
            <div class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white" id="stat_total_peserta">{{ $stats['total_peserta'] }}</div>
            <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold mt-1">
                <i class="fas fa-check-circle mr-1"></i>{{ $stats['lunas_peserta'] }} Lunas
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700/60 shadow-sm">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Menunggu Bayar</div>
            <div class="text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400" id="stat_pending_peserta">{{ $stats['pending_peserta'] }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Pending / Unpaid</div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700/60 shadow-sm">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Booking Kamar</div>
            <div class="text-xl sm:text-2xl font-black text-teal-600 dark:text-teal-400" id="stat_total_booking">{{ $stats['total_booking'] }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Reservasi Hotel</div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700/60 shadow-sm">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Hotel Rekanan</div>
            <div class="text-xl sm:text-2xl font-black text-blue-600 dark:text-blue-400">{{ $stats['total_hotel'] }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Akomodasi Resmi</div>
        </div>

        <!-- Card 5 -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700/60 shadow-sm">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Penerbangan</div>
            <div class="text-xl sm:text-2xl font-black text-indigo-600 dark:indigo-400">{{ $stats['total_flight'] }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Jadwal Delegasi</div>
        </div>

        <!-- Card 6 -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-100 dark:border-slate-700/60 shadow-sm">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Mitra & Sponsor</div>
            <div class="text-xl sm:text-2xl font-black text-purple-600 dark:text-purple-400">{{ $stats['total_partner'] }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Partner KONAS</div>
        </div>
    </div>

    <!-- Unified Administration Card with Tabs -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/60 shadow-sm overflow-hidden">
        
        <!-- Navigation Tab Header -->
        <div class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/40 px-4 sm:px-6 pt-3">
            <ul class="nav nav-tabs border-0 flex flex-wrap gap-1 sm:gap-2 text-xs sm:text-sm font-semibold" id="konasTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link konas-tab-btn py-3 px-4 flex items-center gap-2 rounded-t-lg transition-all {{ $activeTab == 'periode' ? 'active' : '' }}" 
                            id="tab-periode-nav" data-toggle="tab" data-target="#tab-periode" type="button" role="tab" onclick="switchTab('periode')">
                        <i class="fas fa-calendar-alt text-emerald-600"></i>
                        <span>Periode KONAS</span>
                        <span class="badge badge-light border text-[10px] ml-1">{{ $allPeriodes->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link konas-tab-btn py-3 px-4 flex items-center gap-2 rounded-t-lg transition-all {{ $activeTab == 'peserta' ? 'active' : '' }}" 
                            id="tab-peserta-nav" data-toggle="tab" data-target="#tab-peserta" type="button" role="tab" onclick="switchTab('peserta')">
                        <i class="fas fa-users text-teal-600"></i>
                        <span>Delegasi Peserta</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link konas-tab-btn py-3 px-4 flex items-center gap-2 rounded-t-lg transition-all {{ $activeTab == 'booking' ? 'active' : '' }}" 
                            id="tab-booking-nav" data-toggle="tab" data-target="#tab-booking" type="button" role="tab" onclick="switchTab('booking')">
                        <i class="fas fa-bed text-blue-600"></i>
                        <span>Booking Hotel</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link konas-tab-btn py-3 px-4 flex items-center gap-2 rounded-t-lg transition-all {{ $activeTab == 'penerbangan' ? 'active' : '' }}" 
                            id="tab-penerbangan-nav" data-toggle="tab" data-target="#tab-penerbangan" type="button" role="tab" onclick="switchTab('penerbangan')">
                        <i class="fas fa-plane-arrival text-indigo-600"></i>
                        <span>Data Penerbangan</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link konas-tab-btn py-3 px-4 flex items-center gap-2 rounded-t-lg transition-all {{ $activeTab == 'hotel' ? 'active' : '' }}" 
                            id="tab-hotel-nav" data-toggle="tab" data-target="#tab-hotel" type="button" role="tab" onclick="switchTab('hotel')">
                        <i class="fas fa-hotel text-amber-600"></i>
                        <span>Master Hotel & Kamar</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link konas-tab-btn py-3 px-4 flex items-center gap-2 rounded-t-lg transition-all {{ $activeTab == 'partner' ? 'active' : '' }}" 
                            id="tab-partner-nav" data-toggle="tab" data-target="#tab-partner" type="button" role="tab" onclick="switchTab('partner')">
                        <i class="fas fa-handshake text-purple-600"></i>
                        <span>Partner & Sponsor</span>
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content Panes -->
        <div class="tab-content p-4 sm:p-6" id="konasTabsContent">

            <!-- ========================================== -->
            <!-- TAB 1: PENGELOLAAN PERIODE KONAS           -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $activeTab == 'periode' ? 'show active' : '' }}" id="tab-periode" role="tabpanel">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-calendar-check text-emerald-600"></i> Pengelolaan Periode KONAS
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Setiap data peserta, hotel, dan sponsor terhubung ke periode ini. Periode yang diaktifkan akan ditampilkan pada website publik.
                        </p>
                    </div>
                    <button type="button" class="btn btn-emerald btn-sm text-white bg-emerald-600 hover:bg-emerald-500 font-semibold" onclick="tambahPeriodeModal()">
                        <i class="fas fa-plus mr-1"></i> Tambah Periode Baru
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover w-full text-xs" id="table-periode">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Nama & Periode</th>
                                <th width="25%">Tema & Lokasi</th>
                                <th width="15%">Waktu Pelaksanaan</th>
                                <th width="12%">Biaya Registrasi</th>
                                <th width="10%">Status Publik</th>
                                <th width="13%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 2: DELEGASI PESERTA KONAS             -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $activeTab == 'peserta' ? 'show active' : '' }}" id="tab-peserta" role="tabpanel">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/60">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-users text-teal-600"></i> Daftar Delegasi Peserta KONAS
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Verifikasi pendaftaran delegasi, penugasan komisi sidang, status pembayaran, dan cetak kartu.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <div class="w-40 sm:w-48">
                            <select id="peserta_provinsi" class="form-control text-xs">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinsi as $i)
                                    <option value="{{ $i->code }}">{{ $i->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-40 sm:w-48">
                            <select id="peserta_cabang" class="form-control text-xs">
                                <option value="">Semua Cabang</option>
                            </select>
                        </div>
                        <div class="w-48 sm:w-56">
                            <div class="relative">
                                <input type="text" class="form-control pl-8 text-xs" placeholder="Cari nama, klinik..." id="peserta_search">
                                <i class="fas fa-search absolute left-2.5 top-2.5 text-xs text-slate-400"></i>
                            </div>
                        </div>
                        <button type="button" class="btn btn-emerald btn-sm text-white bg-emerald-600 font-semibold" onclick="tambahPesertaModal()">
                            <i class="fas fa-plus mr-1"></i> Tambah
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover w-full text-xs" id="table-peserta">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No.Anggota</th>
                                <th>Nama Peserta</th>
                                <th>Surat Mandat</th>
                                <th>No.HP / Kontak</th>
                                <th>Klinik / Instansi</th>
                                <th>Status Delegasi</th>
                                <th>Provinsi / Cabang</th>
                                <th>Komisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 3: BOOKING HOTEL                       -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $activeTab == 'booking' ? 'show active' : '' }}" id="tab-booking" role="tabpanel">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-bed text-blue-600"></i> Pemesanan & Reservasi Hotel
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Data pemesanan kamar hotel delegasi, ekstra kasur, dan status pembayaran Xendit.
                        </p>
                    </div>
                    <div class="w-56">
                        <input type="text" class="form-control text-xs" placeholder="Cari pemesanan..." id="booking_search">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover w-full text-xs" id="table-booking">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pemesan</th>
                                <th>Hotel & Tipe</th>
                                <th>Jumlah Kamar</th>
                                <th>Extrabed</th>
                                <th>Total Biaya</th>
                                <th>Status Bayar</th>
                                <th>Tanggal Booking</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 4: DATA PENERBANGAN DELEGASI          -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $activeTab == 'penerbangan' ? 'show active' : '' }}" id="tab-penerbangan" role="tabpanel">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-plane-arrival text-indigo-600"></i> Data Penerbangan & Transportasi Delegasi
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Jadwal kedatangan dan kepulangan peserta untuk koordinasi penjemputan dan akomodasi panitia.
                        </p>
                    </div>
                    <div class="w-56">
                        <input type="text" class="form-control text-xs" placeholder="Cari penerbangan..." id="penerbangan_search">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover w-full text-xs" id="table-penerbangan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Peserta</th>
                                <th>Klinik / Kontak</th>
                                <th>Maskapai & No. Penerbangan</th>
                                <th>Rute (Dari -> Tujuan)</th>
                                <th>Tanggal & Jam</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 5: MASTER HOTEL & TIPE KAMAR           -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $activeTab == 'hotel' ? 'show active' : '' }}" id="tab-hotel" role="tabpanel">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Hotel Card -->
                    <div class="lg:col-span-6 bg-slate-50/50 dark:bg-slate-900/30 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-sm text-slate-800 dark:text-white flex items-center gap-1.5">
                                <i class="fas fa-hotel text-amber-600"></i> Daftar Hotel Rekanan
                            </h4>
                            <button type="button" class="btn btn-primary btn-sm text-xs" onclick="tambahHotelModal()">
                                <i class="fas fa-plus mr-1"></i> Tambah Hotel
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover w-full text-xs" id="table-hotel">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Hotel</th>
                                        <th>Alamat</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tipe Kamar Card -->
                    <div class="lg:col-span-6 bg-slate-50/50 dark:bg-slate-900/30 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-sm text-slate-800 dark:text-white flex items-center gap-1.5">
                                <i class="fas fa-door-open text-emerald-600"></i> Tipe Kamar & Stok
                            </h4>
                            <button type="button" class="btn btn-success btn-sm text-xs" onclick="tambahTipeHotelModal()">
                                <i class="fas fa-plus mr-1"></i> Tambah Tipe Kamar
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover w-full text-xs" id="table-tipe-hotel">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Hotel</th>
                                        <th>Tipe Kamar</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
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
            <!-- TAB 6: PARTNER & SPONSOR                   -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $activeTab == 'partner' ? 'show active' : '' }}" id="tab-partner" role="tabpanel">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-handshake text-purple-600"></i> Mitra & Sponsor KONAS
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Logo dan tautan sponsor resmi yang ditampilkan pada halaman publik KONAS.
                        </p>
                    </div>
                    <button type="button" class="btn btn-purple btn-sm text-white bg-purple-600 hover:bg-purple-500 font-semibold" onclick="tambahPartnerModal()">
                        <i class="fas fa-plus mr-1"></i> Tambah Sponsor
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover w-full text-xs" id="table-partner">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Logo Mitra</th>
                                <th width="35%">Nama Mitra / Instansi</th>
                                <th width="30%">Tautan Website</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- ========================================== -->
<!-- MODAL: PERIODE KONAS                       -->
<!-- ========================================== -->
<div class="modal fade" id="modal-periode" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-sm" id="modal-periode-title">Form Periode KONAS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-periode" action="javascript:void(0)" onsubmit="savePeriode()">
                <input type="hidden" name="id" id="periode_id">
                <div class="modal-body space-y-4">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Nama Resmi Acara <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="periode_nama" class="form-control text-xs" placeholder="Contoh: Kongres Nasional (KONAS) II ASKLIN" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Label Periode <span class="text-danger">*</span></label>
                                <input type="text" name="periode" id="periode_label" class="form-control text-xs" placeholder="Contoh: 2023-2028" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Tahun Mulai <span class="text-danger">*</span></label>
                                <input type="number" name="tahun_mulai" id="periode_tahun_mulai" class="form-control text-xs" placeholder="2023" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Tahun Selesai <span class="text-danger">*</span></label>
                                <input type="number" name="tahun_selesai" id="periode_tahun_selesai" class="form-control text-xs" placeholder="2028" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Biaya Registrasi Peserta (Rp)</label>
                                <input type="text" name="biaya_registrasi" id="periode_biaya" class="form-control text-xs" placeholder="1500000">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="text-xs font-semibold">Tema Kongres</label>
                        <input type="text" name="tema" id="periode_tema" class="form-control text-xs" placeholder="Contoh: Peran ASKLIN dalam Era Transformasi Industri Wisata Medis">
                    </div>

                    <div class="form-group">
                        <label class="text-xs font-semibold">Subtema / Abstraksi Tema</label>
                        <input type="text" name="subtema" id="periode_subtema" class="form-control text-xs" placeholder="Contoh: Peran ASKLIN dalam Menghadapi Tantangan dan Meningkatkan Keunggulan Bersaing">
                    </div>

                    <div class="form-group">
                        <label class="text-xs font-semibold">Tempat / Lokasi Acara</label>
                        <input type="text" name="tempat" id="periode_tempat" class="form-control text-xs" placeholder="Contoh: Lut Tawar Grand Ballroom, Takengon - Aceh Tengah">
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Tanggal Mulai Acara</label>
                                <input type="date" name="tanggal_mulai" id="periode_tanggal_mulai" class="form-control text-xs">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Tanggal Selesai Acara</label>
                                <input type="date" name="tanggal_selesai" id="periode_tanggal_selesai" class="form-control text-xs">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Batas Akhir Pendaftaran</label>
                                <input type="date" name="tgl_tutup_daftar" id="periode_tgl_tutup" class="form-control text-xs">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="text-xs font-semibold">URL Video Teaser / Profil (YouTube Embed)</label>
                        <input type="text" name="video_url" id="periode_video_url" class="form-control text-xs" placeholder="https://www.youtube.com/embed/rieNdaumPAI">
                    </div>

                    <div class="form-group">
                        <label class="text-xs font-semibold">Deskripsi / Gambaran Umum</label>
                        <textarea name="deskripsi" id="periode_deskripsi" class="form-control text-xs" rows="3" placeholder="Uraian profil kegiatan kongres..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="text-xs font-semibold">Status Publikasi</label>
                        <select name="status" id="periode_status" class="form-control text-xs">
                            <option value="1">Aktif (Tampilkan di Website Publik)</option>
                            <option value="0">Nonaktif (Sembunyikan dari Publik)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 dark:bg-slate-800">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emerald btn-sm text-white bg-emerald-600 font-bold" id="btn-save-periode">
                        <i class="fas fa-save mr-1"></i> Simpan Periode
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: PESERTA KONAS                       -->
<!-- ========================================== -->
<div class="modal fade" id="modal-peserta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-sm" id="modal-peserta-title">Form Delegasi Peserta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-peserta" action="javascript:void(0)" onsubmit="savePeserta()">
                <input type="hidden" name="id" id="peserta_id">
                <input type="hidden" name="id_periode" id="peserta_id_periode" value="{{ $selectedPeriodeId }}">
                <div class="modal-body space-y-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Nama Peserta (Tanpa Gelar) <span class="text-danger">*</span></label>
                                <input type="text" name="nama_peserta" id="f_nama_peserta" class="form-control text-xs" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Nama Sertifikat (Dengan Gelar) <span class="text-danger">*</span></label>
                                <input type="text" name="nama_sertifikat" id="f_nama_sertifikat" class="form-control text-xs" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">NIK <span class="text-danger">*</span></label>
                                <input type="text" name="nik" id="f_nik" class="form-control text-xs" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_lahir" id="f_tgl_lahir" class="form-control text-xs" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">No. HP / WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" name="no_hp" id="f_no_hp" class="form-control text-xs" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="f_email" class="form-control text-xs" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Nama Klinik <span class="text-danger">*</span></label>
                                <input type="text" name="nama_klinik" id="f_nama_klinik" class="form-control text-xs" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Status Delegasi <span class="text-danger">*</span></label>
                                <select name="status" id="f_status" class="form-control text-xs" required>
                                    <option value="Peserta">Peserta</option>
                                    <option value="Pendamping">Pendamping</option>
                                    <option value="Peninjau">Peninjau</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="text-xs font-semibold">Alamat Klinik <span class="text-danger">*</span></label>
                        <textarea name="alamat_klinik" id="f_alamat_klinik" class="form-control text-xs" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Provinsi <span class="text-danger">*</span></label>
                                <select name="id_provinsi" id="f_id_provinsi" class="form-control text-xs" required onchange="loadKotaByProvinsi(this.value, '#f_id_kota')">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinsi as $i)
                                        <option value="{{ $i->code }}">{{ $i->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Cabang / Kab-Kota <span class="text-danger">*</span></label>
                                <select name="id_kota" id="f_id_kota" class="form-control text-xs" required>
                                    <option value="">-- Pilih Cabang --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="text-xs font-semibold">Komisi Sidang</label>
                        <select name="komisi" id="f_komisi" class="form-control text-xs">
                            <option value="">-- Pilih Komisi --</option>
                            <option value="Komisi AD-ART">Komisi AD-ART</option>
                            <option value="Komisi ORTALA">Komisi ORTALA</option>
                            <option value="Komisi Pembiayaan dan Kredensialing">Komisi Pembiayaan dan Kredensialing</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Upload Foto</label>
                                <input type="file" name="foto" id="f_foto" class="form-control text-xs">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Upload Surat Mandat</label>
                                <input type="file" name="surat_mandat" id="f_surat_mandat" class="form-control text-xs">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-slate-50 dark:bg-slate-800">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emerald btn-sm text-white bg-emerald-600 font-bold" id="btn-save-peserta">
                        <i class="fas fa-save mr-1"></i> Simpan Delegasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: UPDATE STATUS PEMBAYARAN            -->
<!-- ========================================== -->
<div class="modal fade" id="modal-status-bayar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-sm">Update Status Pembayaran Peserta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-status-bayar" action="javascript:void(0)" onsubmit="saveStatusBayar()">
                <input type="hidden" name="id" id="status_bayar_id">
                <div class="modal-body space-y-3">
                    <div class="form-group">
                        <label class="text-xs font-semibold">Nama Peserta</label>
                        <input type="text" id="status_bayar_nama" class="form-control text-xs bg-slate-100" readonly>
                    </div>
                    <div class="form-group">
                        <label class="text-xs font-semibold">Status Pembayaran Registrasi</label>
                        <select name="status_pembayaran" id="status_bayar_pembayaran" class="form-control text-xs font-bold">
                            <option value="PAID">PAID (Lunas)</option>
                            <option value="SETTLED">SETTLED (Lunas)</option>
                            <option value="PENDING">PENDING (Menunggu Pembayaran)</option>
                            <option value="EXPIRED">EXPIRED (Kedaluwarsa)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary btn-sm font-bold">Perbarui Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: HOTEL & TIPE KAMAR                  -->
<!-- ========================================== -->
<div class="modal fade" id="modal-hotel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-sm" id="modal-hotel-title">Form Hotel Rekanan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-hotel" action="javascript:void(0)" onsubmit="saveHotel()">
                <input type="hidden" name="id" id="hotel_id">
                <input type="hidden" name="id_periode" value="{{ $selectedPeriodeId }}">
                <div class="modal-body space-y-3">
                    <div class="form-group">
                        <label class="text-xs font-semibold">Nama Hotel <span class="text-danger">*</span></label>
                        <input type="text" name="hotel" id="hotel_nama" class="form-control text-xs" required>
                    </div>
                    <div class="form-group">
                        <label class="text-xs font-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="alamat" id="hotel_alamat" class="form-control text-xs" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="text-xs font-semibold">Status Kerjasama</label>
                        <select name="status" id="hotel_status" class="form-control text-xs">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm font-bold">Simpan Hotel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tipe Kamar -->
<div class="modal fade" id="modal-tipe-hotel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-sm" id="modal-tipe-title">Form Tipe Kamar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-tipe-hotel" action="javascript:void(0)" onsubmit="saveTipeHotel()">
                <input type="hidden" name="id" id="tipe_id">
                <div class="modal-body space-y-3">
                    <div class="form-group">
                        <label class="text-xs font-semibold">Pilih Hotel <span class="text-danger">*</span></label>
                        <select name="id_hotel" id="tipe_id_hotel" class="form-control text-xs" required>
                            @foreach($masterHotel as $h)
                                <option value="{{ $h->id }}">{{ $h->hotel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="text-xs font-semibold">Nama Tipe Kamar <span class="text-danger">*</span></label>
                        <input type="text" name="tipe" id="tipe_nama" class="form-control text-xs" placeholder="Deluxe Twin, dsb." required>
                    </div>
                    <div class="form-group">
                        <label class="text-xs font-semibold">Harga Kamar (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga" id="tipe_harga" class="form-control text-xs" required>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Stok Kamar <span class="text-danger">*</span></label>
                                <input type="number" name="stok" id="tipe_stok" class="form-control text-xs" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="text-xs font-semibold">Harga Extrabed (Rp)</label>
                                <input type="number" name="extrabed" id="tipe_extrabed" class="form-control text-xs">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="text-xs font-semibold">Stok Extrabed</label>
                        <input type="number" name="stok_extrabed" id="tipe_stok_extrabed" class="form-control text-xs">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm font-bold">Simpan Tipe Kamar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL: PARTNER / SPONSOR                   -->
<!-- ========================================== -->
<div class="modal fade" id="modal-partner" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-bold text-sm" id="modal-partner-title">Form Mitra & Sponsor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-partner" action="javascript:void(0)" onsubmit="savePartner()" enctype="multipart/form-data">
                <input type="hidden" name="id" id="partner_id">
                <input type="hidden" name="id_periode" value="{{ $selectedPeriodeId }}">
                <div class="modal-body space-y-3">
                    <div class="form-group">
                        <label class="text-xs font-semibold">Nama Mitra / Sponsor <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="partner_nama" class="form-control text-xs" required>
                    </div>
                    <div class="form-group">
                        <label class="text-xs font-semibold">Tautan Website Mitra</label>
                        <input type="url" name="link" id="partner_link" class="form-control text-xs" placeholder="https://example.com">
                    </div>
                    <div class="form-group">
                        <label class="text-xs font-semibold">Upload Logo / Foto Partner</label>
                        <input type="file" name="photo" id="partner_photo" class="form-control text-xs">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-purple btn-sm text-white bg-purple-600 font-bold">Simpan Mitra</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    let currentPeriodeId = '{{ $selectedPeriodeId }}';
    let dtPeriode, dtPeserta, dtBooking, dtPenerbangan, dtHotel, dtTipeHotel, dtPartner;

    $(document).ready(function() {
        // Init Datatables
        initTablePeriode();
        initTablePeserta();
        initTableBooking();
        initTablePenerbangan();
        initTableHotel();
        initTableTipeHotel();
        initTablePartner();

        // Check hash or URL query for tab
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab') || window.location.hash.replace('#', '');
        if (tab) {
            $('#tab-' + tab + '-nav').tab('show');
        }

        // Filter event listeners
        $('#peserta_provinsi').change(function() {
            loadKotaByProvinsi($(this).val(), '#peserta_cabang', function() {
                dtPeserta.ajax.reload();
            });
        });

        $('#peserta_cabang').change(function() {
            dtPeserta.ajax.reload();
        });

        $('#peserta_search').keyup(function() {
            dtPeserta.ajax.reload();
        });

        $('#booking_search').keyup(function() {
            dtBooking.search($(this).val()).draw();
        });

        $('#penerbangan_search').keyup(function() {
            dtPenerbangan.search($(this).val()).draw();
        });
    });

    function switchTab(tabName) {
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.replaceState({}, '', url);
    }

    function changeFilterPeriode(val) {
        const url = new URL(window.location);
        if (val) {
            url.searchParams.set('periode_id', val);
        } else {
            url.searchParams.delete('periode_id');
        }
        window.location.href = url.toString();
    }

    // Helper load kota
    function loadKotaByProvinsi(provCode, targetSelect, callback) {
        if (!provCode) {
            $(targetSelect).html('<option value="">Semua Cabang</option>');
            if (callback) callback();
            return;
        }
        $.ajax({
            url: "{{ route('getKota') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", id_provinsi: provCode },
            success: function(res) {
                let html = '<option value="">Semua Cabang</option>';
                $.each(res, function(k, v) {
                    html += `<option value="${v.code}">${v.name}</option>`;
                });
                $(targetSelect).html(html);
                if (callback) callback();
            }
        });
    }

    // 1. Datatables Periode
    function initTablePeriode() {
        dtPeriode = $('#table-periode').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('konas_periode.list') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { 
                    data: 'nama', 
                    name: 'nama',
                    render: function(data, type, row) {
                        return `<div class="font-bold text-slate-800 dark:text-white">${data}</div><div class="text-[11px] text-slate-400">Periode: ${row.periode}</div>`;
                    }
                },
                { 
                    data: 'tema', 
                    name: 'tema',
                    render: function(data, type, row) {
                        return `<div class="font-semibold text-slate-700 dark:text-slate-200">${data || '-'}</div><div class="text-[11px] text-slate-400"><i class="fas fa-map-marker-alt text-red-500 mr-1"></i>${row.tempat || '-'}</div>`;
                    }
                },
                { data: 'waktu_pelaksanaan', name: 'waktu_pelaksanaan' },
                { data: 'biaya_formatted', name: 'biaya_registrasi' },
                { data: 'status_badge', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }

    // 2. Datatables Peserta
    function initTablePeserta() {
        dtPeserta = $('#table-peserta').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('konas.list') }}",
                data: function(d) {
                    d.id_periode = currentPeriodeId;
                    d.provinsi = $('#peserta_provinsi').val();
                    d.cabang = $('#peserta_cabang').val();
                    d.search = $('#peserta_search').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'no_anggota', name: 'no_anggota' },
                { data: 'nama_peserta', name: 'nama_peserta' },
                { 
                    data: 'surat_mandat', 
                    name: 'surat_mandat',
                    render: function(data) {
                        return data ? `<a href="{{ asset('images/konas') }}/${data}" target="_blank" class="badge badge-primary"><i class="fas fa-file-pdf"></i> Mandat</a>` : '-';
                    }
                },
                { 
                    data: 'no_hp', 
                    name: 'no_hp',
                    render: function(data, type, row) {
                        return `<div>${data || '-'}</div><div class="text-[10px] text-slate-400">${row.email || ''}</div>`;
                    }
                },
                { data: 'nama_klinik', name: 'nama_klinik' },
                { 
                    data: 'status', 
                    name: 'status',
                    render: function(data) {
                        return `<span class="badge badge-info">${data}</span>`;
                    }
                },
                { 
                    data: 'provinsi', 
                    name: 'provinsi',
                    render: function(data, type, row) {
                        return `<div>${data || '-'}</div><div class="text-[10px] text-slate-400">${row.name || ''}</div>`;
                    }
                },
                { data: 'komisi', name: 'komisi' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }

    // 3. Datatables Booking
    function initTableBooking() {
        dtBooking = $('#table-booking').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('konas_booking.list') }}",
                data: function(d) {
                    d.id_periode = currentPeriodeId;
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'hotel', name: 'hotel' },
                { data: 'jumlah', name: 'jumlah' },
                { data: 'extrabed', name: 'extrabed' },
                { 
                    data: 'total', 
                    name: 'total',
                    render: function(data) {
                        return 'Rp ' + Number(data).toLocaleString('id-ID');
                    }
                },
                { 
                    data: 'status_pembayaran', 
                    name: 'status_pembayaran',
                    render: function(data) {
                        return data === 'SETTLED' || data === 'PAID' 
                            ? '<span class="badge badge-success">Lunas</span>' 
                            : '<span class="badge badge-warning">Pending</span>';
                    }
                },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }

    // 4. Datatables Penerbangan
    function initTablePenerbangan() {
        dtPenerbangan = $('#table-penerbangan').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('konas_penerbangan.list') }}",
                data: function(d) {
                    d.id_periode = currentPeriodeId;
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'nama_peserta', name: 'nama_peserta' },
                { 
                    data: 'nama_klinik', 
                    name: 'nama_klinik',
                    render: function(data, type, row) {
                        return `<div>${data || '-'}</div><div class="text-[10px] text-slate-400">${row.no_hp || ''}</div>`;
                    }
                },
                { data: 'pesawat', name: 'pesawat' },
                { 
                    data: 'dari', 
                    name: 'dari',
                    render: function(data, type, row) {
                        return `${data} &rarr; ${row.tujuan}`;
                    }
                },
                { data: 'tanggal', name: 'tanggal' }
            ]
        });
    }

    // 5. Datatables Master Hotel & Tipe
    function initTableHotel() {
        dtHotel = $('#table-hotel').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('konas_master_hotel.list') }}",
                data: function(d) {
                    d.id_periode = currentPeriodeId;
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'hotel', name: 'hotel' },
                { data: 'alamat', name: 'alamat' },
                { 
                    data: 'status', 
                    name: 'status',
                    render: function(data) {
                        return data == '1' ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Nonaktif</span>';
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }

    function initTableTipeHotel() {
        dtTipeHotel = $('#table-tipe-hotel').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('konas_master_tipe_hotel.list') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'hotel', name: 'hotel' },
                { data: 'tipe', name: 'tipe' },
                { 
                    data: 'harga', 
                    name: 'harga',
                    render: function(data) {
                        return 'Rp ' + Number(data).toLocaleString('id-ID');
                    }
                },
                { data: 'stok', name: 'stok' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }

    // 6. Datatables Partner
    function initTablePartner() {
        dtPartner = $('#table-partner').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('partner.list') }}",
                data: function(d) {
                    d.id_periode = currentPeriodeId;
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { 
                    data: 'photo', 
                    name: 'photo',
                    render: function(data) {
                        return `<img src="{{ asset('images/partner') }}/${data}" style="height: 38px; object-fit: contain; border-radius: 4px; padding: 2px; background: #f8fafc;" onerror="this.src='{{ asset('assets/images/logo_asklin.png') }}'">`;
                    }
                },
                { data: 'nama', name: 'nama' },
                { 
                    data: 'link', 
                    name: 'link',
                    render: function(data) {
                        return data ? `<a href="${data}" target="_blank" class="text-blue-600 hover:underline"><i class="fas fa-external-link-alt mr-1"></i>${data}</a>` : '-';
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }

    // ===============================================
    // ACTIONS: PERIODE KONAS
    // ===============================================
    function tambahPeriodeModal() {
        $('#form-periode')[0].reset();
        $('#periode_id').val('');
        $('#modal-periode-title').text('Tambah Periode KONAS Baru');
        $('#modal-periode').modal('show');
    }

    function editPeriode(id) {
        $.ajax({
            url: "{{ route('konas_periode.edit') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", id: id },
            success: function(res) {
                $('#periode_id').val(res.id);
                $('#periode_nama').val(res.nama);
                $('#periode_label').val(res.periode);
                $('#periode_tahun_mulai').val(res.tahun_mulai);
                $('#periode_tahun_selesai').val(res.tahun_selesai);
                $('#periode_biaya').val(res.biaya_registrasi);
                $('#periode_tema').val(res.tema);
                $('#periode_subtema').val(res.subtema);
                $('#periode_tempat').val(res.tempat);
                $('#periode_tanggal_mulai').val(res.tanggal_mulai ? res.tanggal_mulai.split('T')[0] : '');
                $('#periode_tanggal_selesai').val(res.tanggal_selesai ? res.tanggal_selesai.split('T')[0] : '');
                $('#periode_tgl_tutup').val(res.tgl_tutup_daftar ? res.tgl_tutup_daftar.split('T')[0] : '');
                $('#periode_video_url').val(res.video_url);
                $('#periode_deskripsi').val(res.deskripsi);
                $('#periode_status').val(res.status);

                $('#modal-periode-title').text('Edit Periode KONAS: ' + res.nama);
                $('#modal-periode').modal('show');
            }
        });
    }

    function savePeriode() {
        $.ajax({
            url: "{{ route('konas_periode.store') }}",
            type: "POST",
            data: $('#form-periode').serialize() + "&_token={{ csrf_token() }}",
            success: function(res) {
                if (res.status) {
                    Swal.fire('Berhasil!', res.message, 'success');
                    $('#modal-periode').modal('hide');
                    dtPeriode.ajax.reload();
                    setTimeout(() => location.reload(), 1200);
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function(err) {
                Swal.fire('Terjadi Kesalahan', err.responseJSON ? err.responseJSON.message : 'Silakan periksa input form', 'error');
            }
        });
    }

    function toggleStatusPeriode(id, status) {
        const text = status === 1 
            ? 'Mengaktifkan periode ini akan menampilkannya pada halaman website publik.' 
            : 'Menonaktifkan periode ini akan menyembunyikan data dan formulir pendaftaran dari publik.';

        Swal.fire({
            title: status === 1 ? 'Aktifkan Periode?' : 'Nonaktifkan Periode?',
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('konas_periode.toggle_status') }}",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}", id: id, status: status },
                    success: function(res) {
                        Swal.fire('Selesai!', res.message, 'success');
                        dtPeriode.ajax.reload();
                        setTimeout(() => location.reload(), 1200);
                    }
                });
            }
        });
    }

    function deletePeriode(id) {
        Swal.fire({
            title: 'Hapus Periode?',
            text: 'Periode yang sudah memiliki data peserta tidak dapat dihapus.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('konas_periode.delete') }}",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}", id: id },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire('Terhapus!', res.message, 'success');
                            dtPeriode.ajax.reload();
                        } else {
                            Swal.fire('Gagal', res.message, 'warning');
                        }
                    },
                    error: function(err) {
                        Swal.fire('Gagal', err.responseJSON ? err.responseJSON.message : 'Gagal menghapus periode.', 'error');
                    }
                });
            }
        });
    }

    // ===============================================
    // ACTIONS: PESERTA KONAS
    // ===============================================
    function tambahPesertaModal() {
        $('#form-peserta')[0].reset();
        $('#peserta_id').val('');
        $('#modal-peserta-title').text('Tambah Delegasi Peserta Baru');
        $('#modal-peserta').modal('show');
    }

    function edit(id) {
        $.ajax({
            url: "{{ route('konas.edit') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", id: id },
            success: function(res) {
                $('#peserta_id').val(res.id);
                $('#f_nama_peserta').val(res.nama_peserta);
                $('#f_nama_sertifikat').val(res.nama_sertifikat);
                $('#f_nik').val(res.nik);
                $('#f_tgl_lahir').val(res.tgl_lahir);
                $('#f_no_hp').val(res.no_hp);
                $('#f_email').val(res.email);
                $('#f_nama_klinik').val(res.nama_klinik);
                $('#f_status').val(res.status);
                $('#f_alamat_klinik').val(res.alamat_klinik);
                $('#f_id_provinsi').val(res.id_provinsi);
                $('#f_komisi').val(res.komisi);

                loadKotaByProvinsi(res.id_provinsi, '#f_id_kota', function() {
                    $('#f_id_kota').val(res.cabang);
                });

                $('#modal-peserta-title').text('Edit Delegasi Peserta: ' + res.nama_peserta);
                $('#modal-peserta').modal('show');
            }
        });
    }

    function savePeserta() {
        let formData = new FormData($('#form-peserta')[0]);
        formData.append('_token', "{{ csrf_token() }}");

        $.ajax({
            url: "{{ route('konas.store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                Swal.fire('Berhasil!', 'Data peserta berhasil disimpan.', 'success');
                $('#modal-peserta').modal('hide');
                dtPeserta.ajax.reload();
            },
            error: function(err) {
                Swal.fire('Gagal!', 'Terjadi kesalahan saat menyimpan data.', 'error');
            }
        });
    }

    function deleteu(id) {
        Swal.fire({
            title: 'Hapus Peserta?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('konas.delete') }}",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}", id: id },
                    success: function() {
                        Swal.fire('Terhapus!', 'Peserta berhasil dihapus.', 'success');
                        dtPeserta.ajax.reload();
                    }
                });
            }
        });
    }

    function updateStatus(id) {
        $.ajax({
            url: "{{ route('konas.updateStatus') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", id: id },
            success: function(res) {
                let data = res[0];
                $('#status_bayar_id').val(data.id);
                $('#status_bayar_nama').val(data.nama_peserta);
                $('#status_bayar_pembayaran').val(data.status_pembayaran || 'PENDING');
                $('#modal-status-bayar').modal('show');
            }
        });
    }

    function saveStatusBayar() {
        $.ajax({
            url: "{{ route('konas.status_pembayaran') }}",
            type: "POST",
            data: $('#form-status-bayar').serialize() + "&_token={{ csrf_token() }}",
            success: function() {
                Swal.fire('Berhasil!', 'Status pembayaran berhasil diperbarui.', 'success');
                $('#modal-status-bayar').modal('hide');
                dtPeserta.ajax.reload();
            }
        });
    }

    // ===============================================
    // ACTIONS: HOTEL & PARTNER
    // ===============================================
    function tambahHotelModal() {
        $('#form-hotel')[0].reset();
        $('#hotel_id').val('');
        $('#modal-hotel-title').text('Tambah Hotel Rekanan');
        $('#modal-hotel').modal('show');
    }

    function saveHotel() {
        $.ajax({
            url: "{{ route('konas_master_hotel.store') }}",
            type: "POST",
            data: $('#form-hotel').serialize() + "&_token={{ csrf_token() }}",
            success: function() {
                Swal.fire('Berhasil!', 'Data hotel berhasil disimpan.', 'success');
                $('#modal-hotel').modal('hide');
                dtHotel.ajax.reload();
            }
        });
    }

    function tambahTipeHotelModal() {
        $('#form-tipe-hotel')[0].reset();
        $('#tipe_id').val('');
        $('#modal-tipe-title').text('Tambah Tipe Kamar');
        $('#modal-tipe-hotel').modal('show');
    }

    function saveTipeHotel() {
        $.ajax({
            url: "{{ route('konas_master_tipe_hotel.store') }}",
            type: "POST",
            data: $('#form-tipe-hotel').serialize() + "&_token={{ csrf_token() }}",
            success: function() {
                Swal.fire('Berhasil!', 'Tipe kamar berhasil disimpan.', 'success');
                $('#modal-tipe-hotel').modal('hide');
                dtTipeHotel.ajax.reload();
            }
        });
    }

    function tambahPartnerModal() {
        $('#form-partner')[0].reset();
        $('#partner_id').val('');
        $('#modal-partner-title').text('Tambah Mitra / Sponsor');
        $('#modal-partner').modal('show');
    }

    function savePartner() {
        let formData = new FormData($('#form-partner')[0]);
        formData.append('_token', "{{ csrf_token() }}");

        $.ajax({
            url: "{{ route('partner.store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function() {
                Swal.fire('Berhasil!', 'Mitra/sponsor berhasil disimpan.', 'success');
                $('#modal-partner').modal('hide');
                dtPartner.ajax.reload();
            }
        });
    }
</script>
@endpush

