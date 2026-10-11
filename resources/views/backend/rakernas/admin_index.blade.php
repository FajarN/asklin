@extends('layouts.backend.layout')

@section('title', 'Modul Rapat Kerja Nasional (RAKERNAS) ASKLIN')
@section('page_title', 'Modul RAKERNAS')

@section('content')
<style>
    /* RAKERNAS Hero Card Dual-Mode High-Contrast Styling */
    .rakernas-hero-card {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-left: 6px solid #2563eb !important;
        border-radius: 1rem !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05) !important;
    }
    .dark .rakernas-hero-card {
        background-color: #0f172a !important;
        border-color: #1e293b !important;
        border-left-color: #3b82f6 !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3) !important;
    }
    .rakernas-hero-pill {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        border: 1px solid #bfdbfe !important;
    }
    .dark .rakernas-hero-pill {
        background-color: rgba(37, 99, 235, 0.15) !important;
        color: #93c5fd !important;
        border-color: rgba(59, 130, 246, 0.3) !important;
    }
    .rakernas-hero-title {
        color: #0f172a !important;
    }
    .dark .rakernas-hero-title {
        color: #ffffff !important;
    }
    .rakernas-hero-desc {
        color: #475569 !important;
    }
    .dark .rakernas-hero-desc {
        color: #cbd5e1 !important;
    }
    .rakernas-periode-label {
        color: #64748b !important;
    }
    .dark .rakernas-periode-label {
        color: #94a3b8 !important;
    }
    .rakernas-periode-badge {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        border: 1px solid #cbd5e1 !important;
    }
    .dark .rakernas-periode-badge {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: #ffffff !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
    }
    .rakernas-filter-panel {
        background-color: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.875rem !important;
    }
    .dark .rakernas-filter-panel {
        background-color: rgba(30, 41, 59, 0.6) !important;
        border-color: #334155 !important;
    }
    .rakernas-filter-label {
        color: #334155 !important;
    }
    .dark .rakernas-filter-label {
        color: #cbd5e1 !important;
    }
    .rakernas-select-control {
        background-color: #ffffff !important;
        color: #0f172a !important;
        border: 1px solid #cbd5e1 !important;
    }
    .dark .rakernas-select-control {
        background-color: #0f172a !important;
        color: #ffffff !important;
        border: 1px solid #475569 !important;
    }
    .rakernas-tab-btn {
        color: #64748b !important;
        background: transparent !important;
        border-bottom: 2px solid transparent !important;
    }
    .rakernas-tab-btn:hover {
        color: #2563eb !important;
    }
    .rakernas-tab-btn.active {
        color: #2563eb !important;
        background-color: #ffffff !important;
        border-bottom: 2px solid #2563eb !important;
        font-weight: 700 !important;
    }
    .dark .rakernas-tab-btn {
        color: #94a3b8 !important;
        background: transparent !important;
    }
    .dark .rakernas-tab-btn:hover {
        color: #60a5fa !important;
    }
    .dark .rakernas-tab-btn.active {
        color: #60a5fa !important;
        background-color: #0f172a !important;
        border-bottom: 2px solid #60a5fa !important;
    }
    /* Enforce 100% full width & enlarged table styles */
    .dataTables_wrapper,
    .table-responsive,
    table.dataTable,
    .table-responsive table.table {
        width: 100% !important;
        min-width: 100% !important;
    }
    .table-responsive table.table thead th,
    table.dataTable thead th {
        background-color: #f8fafc !important;
        color: #334155 !important;
        border-bottom: 2px solid #cbd5e1 !important;
        font-size: 0.8125rem !important; /* 13px */
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.04em !important;
        padding: 0.95rem 1.125rem !important;
        vertical-align: middle !important;
    }
    .dark .table-responsive table.table thead th,
    .dark table.dataTable thead th {
        background-color: #0f172a !important;
        color: #cbd5e1 !important;
        border-bottom: 2px solid #334155 !important;
    }
    .table-responsive table.table tbody td,
    table.dataTable tbody td {
        color: #1e293b !important;
        font-size: 0.875rem !important; /* 14px */
        padding: 1rem 1.125rem !important;
        vertical-align: middle !important;
        line-height: 1.5 !important;
    }
    .dark .table-responsive table.table tbody td,
    .dark table.dataTable tbody td {
        color: #f1f5f9 !important;
    }
    .table-responsive .btn {
        padding: 0.4rem 0.75rem !important;
        font-size: 0.8125rem !important;
        border-radius: 0.5rem !important;
    }
    .table-responsive .badge {
        font-size: 0.75rem !important;
        padding: 0.35rem 0.65rem !important;
        font-weight: 700 !important;
    }
</style>

<div class="space-y-6">

    <!-- Top Hero Banner & Periode Selector -->
    <div class="rakernas-hero-card p-5 sm:p-6 relative overflow-hidden">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full rakernas-hero-pill text-xs font-bold mb-2">
                    <i class="fas fa-users-cog"></i> Rapat Kerja Nasional Pengurus Pusat ASKLIN
                </div>
                <h1 class="text-xl sm:text-2xl font-black tracking-tight rakernas-hero-title flex items-center gap-2">
                    Administrasi Terpadu Modul RAKERNAS
                </h1>
                <p class="text-xs sm:text-sm rakernas-hero-desc max-w-2xl mt-1 leading-relaxed">
                    Kelola penyelenggaraan Rapat Kerja Nasional (maksimal 2x per periode kepengurusan), pendaftaran delegasi klinik, pihak tamu kehormatan (Kemenkes, BPJS, Pemda), serta akomodasi hotel dan venue acara.
                </p>
                @if($currentPeriode)
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                        <span class="rakernas-periode-label font-medium">Acara Terpilih:</span>
                        <span class="font-bold rakernas-periode-badge px-2.5 py-0.5 rounded-lg">
                            {{ $currentPeriode->nama }} (Periode {{ $currentPeriode->periode }})
                        </span>
                        <span class="badge badge-info px-2 py-0.5 font-bold">Rakernas ke-{{ $currentPeriode->rakernas_ke }}</span>
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

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 rakernas-filter-panel p-3.5 shadow-sm">
                <div class="flex flex-col">
                    <label class="text-[11px] font-bold rakernas-filter-label mb-1 uppercase tracking-wider">Pilih Penyelenggaraan:</label>
                    <select id="global_periode_select" class="form-control text-xs font-semibold rakernas-select-control rounded-lg focus:border-blue-500" style="min-width: 240px;" onchange="changeFilterPeriode(this.value)">
                        <option value="">Semua Rakernas</option>
                        @foreach($allPeriodes as $p)
                            <option value="{{ $p->id }}" {{ $selectedPeriodeId == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }} (Rakernas {{ $p->rakernas_ke }} - {{ $p->periode }}) {{ $p->status == '1' ? '★ Aktif' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:self-end">
                    <button type="button" class="btn btn-primary btn-sm w-full font-bold text-white bg-blue-600 hover:bg-blue-500 border-0 shadow-sm" onclick="tambahPeriodeModal()">
                        <i class="fas fa-plus mr-1"></i> Buat Rakernas
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        <!-- Card 1 -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700/60 shadow-sm">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Peserta</div>
            <div class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white" id="stat_total_peserta">{{ $stats['total_peserta'] }}</div>
            <div class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold mt-1">
                <i class="fas fa-users mr-1"></i>Pendaftar Klinik
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700/60 shadow-sm">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Hadir Fisik</div>
            <div class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400" id="stat_hadir_fisik">{{ $stats['hadir_fisik'] }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Di Venue / Hotel</div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700/60 shadow-sm">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Hadir Online</div>
            <div class="text-xl sm:text-2xl font-black text-cyan-600 dark:text-cyan-400" id="stat_hadir_online">{{ $stats['hadir_online'] }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Zoom / Hybrid</div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700/60 shadow-sm">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Pihak Diundang</div>
            <div class="text-xl sm:text-2xl font-black text-purple-600 dark:text-purple-400" id="stat_total_undangan">{{ $stats['total_undangan'] }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Kemenkes, BPJS, dll</div>
        </div>

        <!-- Card 5 -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700/60 shadow-sm">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Venue & Galeri</div>
            <div class="text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400" id="stat_total_galeri">{{ $stats['total_galeri'] }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Hotel & Dokumentasi</div>
        </div>

        <!-- Card 6 -->
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-slate-200 dark:border-slate-700/60 shadow-sm">
            <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Rakernas</div>
            <div class="text-xl sm:text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ $stats['total_periode'] }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Data Tersimpan</div>
        </div>
    </div>

    <!-- Unified Administration Card with Tabs -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700/60 shadow-sm overflow-hidden">
        
        <!-- Navigation Tab Header -->
        <div class="border-b border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/40 px-4 sm:px-6 pt-3">
            <ul class="nav nav-tabs border-0 flex flex-wrap gap-1 sm:gap-2 text-xs sm:text-sm font-semibold" id="rakernasTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link rakernas-tab-btn py-3 px-4 flex items-center gap-2 rounded-t-lg transition-all {{ $activeTab == 'periode' ? 'active' : '' }}" 
                            id="tab-periode-nav" data-toggle="tab" data-target="#tab-periode" type="button" role="tab" onclick="switchTab('periode')">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                        <span>Penyelenggaraan & Periode</span>
                        <span class="badge badge-light border text-[10px] ml-1">{{ $allPeriodes->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rakernas-tab-btn py-3 px-4 flex items-center gap-2 rounded-t-lg transition-all {{ $activeTab == 'peserta' ? 'active' : '' }}" 
                            id="tab-peserta-nav" data-toggle="tab" data-target="#tab-peserta" type="button" role="tab" onclick="switchTab('peserta')">
                        <i class="fas fa-users text-teal-600"></i>
                        <span>Pendaftaran Peserta (Klinik)</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rakernas-tab-btn py-3 px-4 flex items-center gap-2 rounded-t-lg transition-all {{ $activeTab == 'undangan' ? 'active' : '' }}" 
                            id="tab-undangan-nav" data-toggle="tab" data-target="#tab-undangan" type="button" role="tab" onclick="switchTab('undangan')">
                        <i class="fas fa-user-tie text-purple-600"></i>
                        <span>Tamu Undangan & Sponsor</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rakernas-tab-btn py-3 px-4 flex items-center gap-2 rounded-t-lg transition-all {{ $activeTab == 'galeri' ? 'active' : '' }}" 
                            id="tab-galeri-nav" data-toggle="tab" data-target="#tab-galeri" type="button" role="tab" onclick="switchTab('galeri')">
                        <i class="fas fa-hotel text-amber-600"></i>
                        <span>Venue, Hotel & Galeri</span>
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content Panes -->
        <div class="tab-content p-4 sm:p-6" id="rakernasTabsContent">

            <!-- ========================================== -->
            <!-- TAB 1: PERIODE & PENYELENGGARAAN RAKERNAS  -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $activeTab == 'periode' ? 'show active' : '' }}" id="tab-periode" role="tabpanel">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-calendar-check text-blue-600"></i> Penyelenggaraan Rakernas (Maks. 2x per Periode)
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Kelola jadwal, tempat penyelenggaraan, tema, batas pendaftaran, dan status aktif publik untuk Rakernas ke-1 atau ke-2.
                        </p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm text-white bg-blue-600 hover:bg-blue-500 font-semibold" onclick="tambahPeriodeModal()">
                        <i class="fas fa-plus mr-1"></i> Tambah Penyelenggaraan Rakernas
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover w-full" id="table-periode">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Acara & Periode</th>
                                <th>Waktu & Tempat</th>
                                <th>Total Peserta</th>
                                <th>Status Publik</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 2: PENDAFTARAN PESERTA RAKERNAS        -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $activeTab == 'peserta' ? 'show active' : '' }}" id="tab-peserta" role="tabpanel">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/60">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-users text-teal-600"></i> Daftar Peserta Rakernas
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Daftar pendaftar perwakilan klinik se-Indonesia. Kolom <strong>Nama Klinik</strong> merupakan data utama wajib.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <div class="w-40 sm:w-48">
                            <select id="peserta_provinsi" class="form-control text-sm font-medium" onchange="reloadPesertaTable()">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinsi as $i)
                                    <option value="{{ $i->code }}">{{ $i->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-36">
                            <select id="peserta_kehadiran" class="form-control text-sm font-medium" onchange="reloadPesertaTable()">
                                <option value="">Semua Kehadiran</option>
                                <option value="Hadir">Hadir Fisik</option>
                                <option value="Online">Hadir Online</option>
                                <option value="Batal">Batal</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm text-white font-semibold px-3 py-2" onclick="tambahPesertaModal()">
                            <i class="fas fa-plus mr-1"></i> Tambah Peserta
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover w-full" id="table-peserta">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Peserta & Kontak</th>
                                <th>Nama Klinik & Lokasi</th>
                                <th>Kehadiran</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 3: TAMU UNDANGAN & SPONSOR             -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $activeTab == 'undangan' ? 'show active' : '' }}" id="tab-undangan" role="tabpanel">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/60">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-user-tie text-purple-600"></i> Pihak Yang Diundang & Sponsor Rakernas
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Kelola tokoh kehormatan dari Kementerian Kesehatan, BPJS Kesehatan, Gubernur/Bupati/Pemda, serta sponsor dan mitra.
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <select id="undangan_kategori" class="form-control text-sm font-medium w-44" onchange="reloadUndanganTable()">
                            <option value="">Semua Kategori</option>
                            <option value="kemenkes">Kemenkes</option>
                            <option value="bpjs">BPJS Kesehatan</option>
                            <option value="pemerintah">Pemerintah Daerah</option>
                            <option value="sponsor">Sponsor & Mitra</option>
                            <option value="narasumber">Narasumber</option>
                        </select>
                        <button type="button" class="btn btn-purple btn-sm text-white bg-purple-600 hover:bg-purple-500 font-semibold px-3 py-2" onclick="tambahUndanganModal()">
                            <i class="fas fa-plus mr-1"></i> Tambah Undangan / Sponsor
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover w-full" id="table-undangan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tokoh / Instansi</th>
                                <th>Kategori</th>
                                <th>Status Kehadiran</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- TAB 4: VENUE, HOTEL & GALERI               -->
            <!-- ========================================== -->
            <div class="tab-pane fade {{ $activeTab == 'galeri' ? 'show active' : '' }}" id="tab-galeri" role="tabpanel">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/60">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                            <i class="fas fa-hotel text-amber-600"></i> Venue, Hotel Rekanan & Galeri Foto
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                            Kelola slider acara, foto tempat penyelenggaraan, hotel rekanan peserta, serta dokumentasi kegiatan.
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <select id="galeri_kategori" class="form-control text-sm font-medium w-40" onchange="reloadGaleriTable()">
                            <option value="">Semua Kategori</option>
                            <option value="venue">Venue / Lokasi</option>
                            <option value="hotel">Hotel Rekanan</option>
                            <option value="slider">Slider Banner</option>
                            <option value="kegiatan">Dokumentasi</option>
                        </select>
                        <button type="button" class="btn btn-amber btn-sm text-white bg-amber-600 hover:bg-amber-500 font-semibold px-3 py-2" onclick="tambahGaleriModal()">
                            <i class="fas fa-plus mr-1"></i> Tambah Foto / Media
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover w-full" id="table-galeri">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Media / Judul</th>
                                <th>Kategori</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ==================================================== -->
<!-- MODAL: TAMBAH / EDIT PERIODE RAKERNAS                -->
<!-- ==================================================== -->
<div class="modal fade" id="modalPeriode" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formPeriode" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="periode_id">
            <div class="modal-content">
                <div class="modal-header bg-blue-600 text-white">
                    <h5 class="modal-title font-bold text-sm" id="modalPeriodeTitle">
                        <i class="fas fa-calendar-plus mr-1.5"></i> Form Penyelenggaraan Rakernas
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-xs space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="md:col-span-2">
                            <label class="font-bold text-slate-700">Nama Resmi Acara <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="form_nama" class="form-control text-xs" placeholder="Contoh: Rapat Kerja Nasional I ASKLIN" required>
                        </div>
                        <div>
                            <label class="font-bold text-slate-700">Rakernas Ke- <span class="text-red-500">*</span></label>
                            <select name="rakernas_ke" id="form_rakernas_ke" class="form-control text-xs" required>
                                <option value="1">Rakernas I</option>
                                <option value="2">Rakernas II</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="font-bold text-slate-700">Periode Kepengurusan <span class="text-red-500">*</span></label>
                            <input type="text" name="periode" id="form_periode" class="form-control text-xs" value="2023-2028" placeholder="Contoh: 2023-2028" required>
                        </div>
                        <div>
                            <label class="font-bold text-slate-700">Tahun Pelaksanaan <span class="text-red-500">*</span></label>
                            <input type="number" name="tahun" id="form_tahun" class="form-control text-xs" value="{{ date('Y') }}" required>
                        </div>
                    </div>

                    <div>
                        <label class="font-bold text-slate-700">Tema Utama</label>
                        <input type="text" name="tema" id="form_tema" class="form-control text-xs" placeholder="Tema Rapat Kerja Nasional">
                    </div>

                    <div>
                        <label class="font-bold text-slate-700">Subtema / Slogan</label>
                        <input type="text" name="subtema" id="form_subtema" class="form-control text-xs" placeholder="Subtema penjelas">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="font-bold text-slate-700">Tempat / Hotel Penyelenggaraan</label>
                            <input type="text" name="tempat" id="form_tempat" class="form-control text-xs" placeholder="Contoh: Hotel Grand Sahid Jaya, Jakarta">
                        </div>
                        <div>
                            <label class="font-bold text-slate-700">Biaya Registrasi (Rp)</label>
                            <input type="text" name="biaya_registrasi" id="form_biaya_registrasi" class="form-control text-xs" value="0" placeholder="0 jika gratis">
                        </div>
                    </div>

                    <div>
                        <label class="font-bold text-slate-700">Alamat Lengkap Venue</label>
                        <textarea name="alamat_venue" id="form_alamat_venue" class="form-control text-xs" rows="2" placeholder="Alamat lengkap lokasi/hotel"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="font-bold text-slate-700">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="form_tanggal_mulai" class="form-control text-xs">
                        </div>
                        <div>
                            <label class="font-bold text-slate-700">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="form_tanggal_selesai" class="form-control text-xs">
                        </div>
                        <div>
                            <label class="font-bold text-slate-700">Tgl Tutup Pendaftaran</label>
                            <input type="date" name="tgl_tutup_daftar" id="form_tgl_tutup_daftar" class="form-control text-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="font-bold text-slate-700">Banner / Poster Acara</label>
                            <input type="file" name="banner_img" class="form-control text-xs" accept="image/*">
                        </div>
                        <div>
                            <label class="font-bold text-slate-700">File Rundown / Jadwal (PDF)</label>
                            <input type="file" name="rundown_file" class="form-control text-xs" accept=".pdf,.doc,.docx">
                        </div>
                    </div>

                    <div>
                        <label class="font-bold text-slate-700">Deskripsi / Informasi Acara</label>
                        <textarea name="deskripsi" id="form_deskripsi" class="form-control text-xs" rows="3"></textarea>
                    </div>

                    <div>
                        <label class="font-bold text-slate-700">Sambutan Ketua Umum</label>
                        <textarea name="sambutan_ketum" id="form_sambutan_ketum" class="form-control text-xs" rows="3"></textarea>
                    </div>

                    <div>
                        <label class="font-bold text-slate-700">Status Publikasi</label>
                        <select name="status" id="form_status" class="form-control text-xs">
                            <option value="1">Aktif (Ditampilkan di publik & menerima pendaftaran)</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm text-white font-bold" id="btn-save-periode">Simpan Rakernas</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ==================================================== -->
<!-- MODAL: TAMBAH / EDIT PESERTA RAKERNAS                -->
<!-- ==================================================== -->
<div class="modal fade" id="modalPeserta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formPeserta">
            @csrf
            <input type="hidden" name="id" id="peserta_id">
            <div class="modal-content">
                <div class="modal-header bg-teal-600 text-white">
                    <h5 class="modal-title font-bold text-sm" id="modalPesertaTitle">
                        <i class="fas fa-user-plus mr-1.5"></i> Form Data Peserta Rakernas
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-xs space-y-3">
                    <div>
                        <label class="font-bold text-slate-700">Acara Rakernas <span class="text-red-500">*</span></label>
                        <select name="id_periode" id="form_peserta_periode" class="form-control text-xs" required>
                            @foreach($allPeriodes as $p)
                                <option value="{{ $p->id }}" {{ $selectedPeriodeId == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} (Rakernas {{ $p->rakernas_ke }} - {{ $p->periode }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="font-bold text-slate-700">Nama Lengkap Peserta (Beserta Gelar) <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_peserta" id="form_peserta_nama" class="form-control text-xs" required>
                        </div>
                        <div>
                            <label class="font-bold text-emerald-700">Nama Klinik <span class="text-red-500">* (Wajib Diisi)</span></label>
                            <input type="text" name="nama_klinik" id="form_peserta_klinik" class="form-control text-xs border-emerald-400 focus:border-emerald-600" placeholder="Nama klinik yang diwakili" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="font-bold text-slate-700">Jabatan di Klinik / Organisasi</label>
                            <input type="text" name="jabatan" id="form_peserta_jabatan" class="form-control text-xs" placeholder="Pimpinan Klinik, Dokter, dll">
                        </div>
                        <div>
                            <label class="font-bold text-slate-700">No. WhatsApp / HP <span class="text-red-500">*</span></label>
                            <input type="text" name="no_hp" id="form_peserta_hp" class="form-control text-xs" required>
                        </div>
                        <div>
                            <label class="font-bold text-slate-700">Email Aktif <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="form_peserta_email" class="form-control text-xs" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="font-bold text-slate-700">Provinsi Domisili</label>
                            <select name="id_provinsi" id="form_peserta_provinsi" class="form-control text-xs">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinsi as $i)
                                    <option value="{{ $i->code }}">{{ $i->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="font-bold text-slate-700">Status Kehadiran</label>
                            <select name="status_kehadiran" id="form_peserta_kehadiran" class="form-control text-xs">
                                <option value="Hadir">Hadir Fisik di Venue</option>
                                <option value="Online">Hadir Online (Hybrid)</option>
                                <option value="Batal">Batal</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="font-bold text-slate-700">Alamat Klinik</label>
                        <textarea name="alamat_klinik" id="form_peserta_alamat" class="form-control text-xs" rows="2"></textarea>
                    </div>

                    <div>
                        <label class="font-bold text-slate-700">Catatan Khusus / Kebutuhan Akomodasi</label>
                        <textarea name="kebutuhan_khusus" id="form_peserta_catatan" class="form-control text-xs" rows="2" placeholder="Catatan makanan, akomodasi, dll"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm text-white font-bold" id="btn-save-peserta">Simpan Peserta</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ==================================================== -->
<!-- MODAL: TAMBAH / EDIT UNDANGAN & SPONSOR              -->
<!-- ==================================================== -->
<div class="modal fade" id="modalUndangan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formUndangan" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="undangan_id">
            <div class="modal-content">
                <div class="modal-header bg-purple-600 text-white">
                    <h5 class="modal-title font-bold text-sm" id="modalUndanganTitle">
                        <i class="fas fa-user-tie mr-1.5"></i> Form Tamu Undangan / Sponsor
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-xs space-y-3">
                    <div>
                        <label class="font-bold text-slate-700">Acara Rakernas <span class="text-red-500">*</span></label>
                        <select name="id_periode" id="form_undangan_periode" class="form-control text-xs" required>
                            @foreach($allPeriodes as $p)
                                <option value="{{ $p->id }}" {{ $selectedPeriodeId == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} (Rakernas {{ $p->rakernas_ke }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="font-bold text-slate-700">Nama Tokoh / Pejabat / Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" id="form_undangan_nama" class="form-control text-xs" placeholder="Contoh: Ir. Budi Gunadi Sadikin / PT Kalbe Farma" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="font-bold text-slate-700">Instansi / Institusi <span class="text-red-500">*</span></label>
                            <input type="text" name="instansi" id="form_undangan_instansi" class="form-control text-xs" placeholder="Kemenkes RI, BPJS, Pemda, dll" required>
                        </div>
                        <div>
                            <label class="font-bold text-slate-700">Jabatan</label>
                            <input type="text" name="jabatan" id="form_undangan_jabatan" class="form-control text-xs" placeholder="Menteri, Dirut, Gubernur, Sponsor">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="font-bold text-slate-700">Kategori <span class="text-red-500">*</span></label>
                            <select name="kategori" id="form_undangan_kategori" class="form-control text-xs" required>
                                <option value="kemenkes">Kementerian Kesehatan (Kemenkes)</option>
                                <option value="bpjs">BPJS Kesehatan</option>
                                <option value="pemerintah">Pemerintah Daerah (Gubernur/Bupati)</option>
                                <option value="sponsor">Sponsor / Mitra</option>
                                <option value="narasumber">Narasumber / Panelis</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="font-bold text-slate-700">Status Kehadiran</label>
                            <select name="status_kehadiran" id="form_undangan_kehadiran" class="form-control text-xs">
                                <option value="Terkonfirmasi">Terkonfirmasi Hadir</option>
                                <option value="Dalam Konfirmasi">Dalam Konfirmasi</option>
                                <option value="Berhalangan">Berhalangan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="font-bold text-slate-700">Foto Tokoh / Logo Sponsor</label>
                            <input type="file" name="foto_logo" class="form-control text-xs" accept="image/*">
                        </div>
                        <div>
                            <label class="font-bold text-slate-700">Urutan Tampil</label>
                            <input type="number" name="urutan" id="form_undangan_urutan" class="form-control text-xs" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm text-white font-bold" id="btn-save-undangan">Simpan Undangan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ==================================================== -->
<!-- MODAL: TAMBAH / EDIT GALERI & VENUE                  -->
<!-- ==================================================== -->
<div class="modal fade" id="modalGaleri" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formGaleri" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" id="galeri_id">
            <div class="modal-content">
                <div class="modal-header bg-amber-600 text-white">
                    <h5 class="modal-title font-bold text-sm" id="modalGaleriTitle">
                        <i class="fas fa-camera mr-1.5"></i> Form Venue, Hotel & Galeri
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-xs space-y-3">
                    <div>
                        <label class="font-bold text-slate-700">Acara Rakernas <span class="text-red-500">*</span></label>
                        <select name="id_periode" id="form_galeri_periode" class="form-control text-xs" required>
                            @foreach($allPeriodes as $p)
                                <option value="{{ $p->id }}" {{ $selectedPeriodeId == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} (Rakernas {{ $p->rakernas_ke }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="font-bold text-slate-700">Judul / Keterangan Foto <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" id="form_galeri_judul" class="form-control text-xs" placeholder="Contoh: Grand Ballroom Ruang Sidang Pleno" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="font-bold text-slate-700">Kategori Media <span class="text-red-500">*</span></label>
                            <select name="kategori" id="form_galeri_kategori" class="form-control text-xs" required>
                                <option value="venue">Venue / Lokasi Acara</option>
                                <option value="hotel">Hotel Rekanan</option>
                                <option value="slider">Slider Banner Acara</option>
                                <option value="kegiatan">Dokumentasi Kegiatan</option>
                            </select>
                        </div>
                        <div>
                            <label class="font-bold text-slate-700">Urutan Tampil</label>
                            <input type="number" name="urutan" id="form_galeri_urutan" class="form-control text-xs" value="0">
                        </div>
                    </div>

                    <div>
                        <label class="font-bold text-slate-700">File Foto <span class="text-red-500">*</span></label>
                        <input type="file" name="foto" class="form-control text-xs" accept="image/*">
                    </div>

                    <div>
                        <label class="font-bold text-slate-700">Deskripsi Singkat</label>
                        <textarea name="deskripsi" id="form_galeri_deskripsi" class="form-control text-xs" rows="2" placeholder="Keterangan fasilitas / tempat"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm text-white font-bold" id="btn-save-galeri">Simpan Foto</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentPeriodeId = '{{ $selectedPeriodeId }}';
    let tablePeriode, tablePeserta, tableUndangan, tableGaleri;

    $(document).ready(function() {
        initTables();
        setupFormHandlers();
    });

    function changeFilterPeriode(val) {
        window.location.href = '{{ route("rakernas_admin.index") }}?periode_id=' + val + '&tab=' + getActiveTab();
    }

    function getActiveTab() {
        return $('#rakernasTabs .nav-link.active').attr('id').replace('tab-', '').replace('-nav', '');
    }

    function switchTab(tabName) {
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.replaceState({}, '', url);

        setTimeout(() => {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }, 100);
    }

    function initTables() {
        // Handle tab shown to recalculate columns automatically
        $('button[data-toggle="tab"], a[data-toggle="tab"]').on('shown.bs.tab', function() {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });

        $(window).on('resize', function() {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });

        // Table Periode
        tablePeriode = $('#table-periode').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route("rakernas_periode.list") }}'
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%' },
                { data: 'rakernas_label', name: 'nama' },
                { data: 'waktu_tempat', name: 'tempat' },
                { data: 'peserta_count', name: 'peserta_count', orderable: false, searchable: false },
                { data: 'status_badge', name: 'status', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: { url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json' }
        });

        // Table Peserta
        tablePeserta = $('#table-peserta').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route("rakernas_peserta.list") }}',
                data: function(d) {
                    d.id_periode = currentPeriodeId;
                    d.provinsi = $('#peserta_provinsi').val();
                    d.status_kehadiran = $('#peserta_kehadiran').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%' },
                { data: 'peserta_info', name: 'nama_peserta' },
                { data: 'klinik_info', name: 'nama_klinik' },
                { data: 'kehadiran_badge', name: 'status_kehadiran' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: { url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json' }
        });

        // Table Undangan
        tableUndangan = $('#table-undangan').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route("rakernas_undangan.list") }}',
                data: function(d) {
                    d.id_periode = currentPeriodeId;
                    d.kategori = $('#undangan_kategori').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%' },
                { data: 'tokoh_info', name: 'nama' },
                { data: 'kategori_badge', name: 'kategori' },
                { data: 'status_kehadiran_badge', name: 'status_kehadiran' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: { url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json' }
        });

        // Table Galeri
        tableGaleri = $('#table-galeri').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route("rakernas_galeri.list") }}',
                data: function(d) {
                    d.id_periode = currentPeriodeId;
                    d.kategori = $('#galeri_kategori').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%' },
                { data: 'media_preview', name: 'judul' },
                { data: 'kategori_badge', name: 'kategori' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: { url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json' }
        });

        // Initial adjustment after load
        setTimeout(() => {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }, 200);
    }

    function reloadPesertaTable() { tablePeserta.ajax.reload(); }
    function reloadUndanganTable() { tableUndangan.ajax.reload(); }
    function reloadGaleriTable() { tableGaleri.ajax.reload(); }

    // Toggle Status Periode
    function toggleStatusPeriode(id) {
        Swal.fire({
            title: 'Ubah Status Penyelenggaraan?',
            text: 'Periode yang aktif akan ditampilkan pada publik website dan menerima pendaftaran delegasi.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("rakernas_periode.toggle_status") }}', { _token: '{{ csrf_token() }}', id: id }, function(res) {
                    Swal.fire('Berhasil', res.message, 'success');
                    tablePeriode.ajax.reload();
                    setTimeout(() => location.reload(), 1000);
                }).fail(function() {
                    Swal.fire('Error', 'Gagal mengubah status', 'error');
                });
            }
        });
    }

    // Modal Tambah Periode
    function tambahPeriodeModal() {
        $('#formPeriode')[0].reset();
        $('#periode_id').val('');
        $('#modalPeriodeTitle').html('<i class="fas fa-calendar-plus mr-1.5"></i> Tambah Penyelenggaraan Rakernas');
        $('#modalPeriode').modal('show');
    }

    function editPeriode(id) {
        $.post('{{ route("rakernas_periode.edit") }}', { _token: '{{ csrf_token() }}', id: id }, function(data) {
            $('#periode_id').val(data.id);
            $('#form_nama').val(data.nama);
            $('#form_rakernas_ke').val(data.rakernas_ke);
            $('#form_periode').val(data.periode);
            $('#form_tahun').val(data.tahun);
            $('#form_tema').val(data.tema);
            $('#form_subtema').val(data.subtema);
            $('#form_tempat').val(data.tempat);
            $('#form_alamat_venue').val(data.alamat_venue);
            $('#form_biaya_registrasi').val(data.biaya_registrasi);
            $('#form_tanggal_mulai').val(data.tanggal_mulai ? data.tanggal_mulai.substring(0, 10) : '');
            $('#form_tanggal_selesai').val(data.tanggal_selesai ? data.tanggal_selesai.substring(0, 10) : '');
            $('#form_tgl_tutup_daftar').val(data.tgl_tutup_daftar ? data.tgl_tutup_daftar.substring(0, 10) : '');
            $('#form_deskripsi').val(data.deskripsi);
            $('#form_sambutan_ketum').val(data.sambutan_ketum);
            $('#form_status').val(data.status);

            $('#modalPeriodeTitle').html('<i class="fas fa-edit mr-1.5"></i> Edit Penyelenggaraan Rakernas');
            $('#modalPeriode').modal('show');
        });
    }

    function deletePeriode(id) {
        Swal.fire({
            title: 'Hapus Penyelenggaraan Rakernas?',
            text: 'Seluruh data peserta, undangan, dan galeri yang terhubung ke acara ini akan ikut terhapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("rakernas_periode.delete") }}', { _token: '{{ csrf_token() }}', id: id }, function(res) {
                    Swal.fire('Berhasil', res.message, 'success');
                    tablePeriode.ajax.reload();
                    setTimeout(() => location.reload(), 1000);
                });
            }
        });
    }

    // Modal Peserta
    function tambahPesertaModal() {
        $('#formPeserta')[0].reset();
        $('#peserta_id').val('');
        $('#modalPesertaTitle').html('<i class="fas fa-user-plus mr-1.5"></i> Tambah Peserta Rakernas');
        $('#modalPeserta').modal('show');
    }

    function editPeserta(id) {
        $.post('{{ route("rakernas_peserta.edit") }}', { _token: '{{ csrf_token() }}', id: id }, function(data) {
            $('#peserta_id').val(data.id);
            $('#form_peserta_periode').val(data.id_periode);
            $('#form_peserta_nama').val(data.nama_peserta);
            $('#form_peserta_klinik').val(data.nama_klinik);
            $('#form_peserta_jabatan').val(data.jabatan);
            $('#form_peserta_hp').val(data.no_hp);
            $('#form_peserta_email').val(data.email);
            $('#form_peserta_provinsi').val(data.id_provinsi);
            $('#form_peserta_kehadiran').val(data.status_kehadiran);
            $('#form_peserta_alamat').val(data.alamat_klinik);
            $('#form_peserta_catatan').val(data.kebutuhan_khusus);

            $('#modalPesertaTitle').html('<i class="fas fa-edit mr-1.5"></i> Edit Peserta Rakernas');
            $('#modalPeserta').modal('show');
        });
    }

    function deletePeserta(id) {
        Swal.fire({
            title: 'Hapus Peserta Rakernas?',
            text: 'Data pendaftaran peserta ini akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("rakernas_peserta.delete") }}', { _token: '{{ csrf_token() }}', id: id }, function(res) {
                    Swal.fire('Berhasil', res.message, 'success');
                    tablePeserta.ajax.reload();
                });
            }
        });
    }

    // Modal Undangan
    function tambahUndanganModal() {
        $('#formUndangan')[0].reset();
        $('#undangan_id').val('');
        $('#modalUndanganTitle').html('<i class="fas fa-user-plus mr-1.5"></i> Tambah Tamu Undangan / Sponsor');
        $('#modalUndangan').modal('show');
    }

    function editUndangan(id) {
        $.post('{{ route("rakernas_undangan.edit") }}', { _token: '{{ csrf_token() }}', id: id }, function(data) {
            $('#undangan_id').val(data.id);
            $('#form_undangan_periode').val(data.id_periode);
            $('#form_undangan_nama').val(data.nama);
            $('#form_undangan_instansi').val(data.instansi);
            $('#form_undangan_jabatan').val(data.jabatan);
            $('#form_undangan_kategori').val(data.kategori);
            $('#form_undangan_kehadiran').val(data.status_kehadiran);
            $('#form_undangan_urutan').val(data.urutan);

            $('#modalUndanganTitle').html('<i class="fas fa-edit mr-1.5"></i> Edit Tamu Undangan / Sponsor');
            $('#modalUndangan').modal('show');
        });
    }

    function deleteUndangan(id) {
        Swal.fire({
            title: 'Hapus Tamu Undangan / Sponsor?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("rakernas_undangan.delete") }}', { _token: '{{ csrf_token() }}', id: id }, function(res) {
                    Swal.fire('Berhasil', res.message, 'success');
                    tableUndangan.ajax.reload();
                });
            }
        });
    }

    // Modal Galeri
    function tambahGaleriModal() {
        $('#formGaleri')[0].reset();
        $('#galeri_id').val('');
        $('#modalGaleriTitle').html('<i class="fas fa-camera mr-1.5"></i> Tambah Foto Venue / Galeri');
        $('#modalGaleri').modal('show');
    }

    function editGaleri(id) {
        $.post('{{ route("rakernas_galeri.edit") }}', { _token: '{{ csrf_token() }}', id: id }, function(data) {
            $('#galeri_id').val(data.id);
            $('#form_galeri_periode').val(data.id_periode);
            $('#form_galeri_judul').val(data.judul);
            $('#form_galeri_kategori').val(data.kategori);
            $('#form_galeri_deskripsi').val(data.deskripsi);
            $('#form_galeri_urutan').val(data.urutan);

            $('#modalGaleriTitle').html('<i class="fas fa-edit mr-1.5"></i> Edit Foto Venue / Galeri');
            $('#modalGaleri').modal('show');
        });
    }

    function deleteGaleri(id) {
        Swal.fire({
            title: 'Hapus Foto Galeri?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('{{ route("rakernas_galeri.delete") }}', { _token: '{{ csrf_token() }}', id: id }, function(res) {
                    Swal.fire('Berhasil', res.message, 'success');
                    tableGaleri.ajax.reload();
                });
            }
        });
    }

    // Form Submissions
    function setupFormHandlers() {
        $('#formPeriode').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $('#btn-save-periode').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

            $.ajax({
                url: '{{ route("rakernas_periode.store") }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    $('#modalPeriode').modal('hide');
                    Swal.fire('Berhasil', res.message, 'success');
                    tablePeriode.ajax.reload();
                    setTimeout(() => location.reload(), 1000);
                },
                error: function(xhr) {
                    let err = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan saat menyimpan data.';
                    Swal.fire('Gagal', err, 'error');
                },
                complete: function() {
                    $('#btn-save-periode').prop('disabled', false).html('Simpan Rakernas');
                }
            });
        });

        $('#formPeserta').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $('#btn-save-peserta').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

            $.post('{{ route("rakernas_peserta.store") }}', formData, function(res) {
                $('#modalPeserta').modal('hide');
                Swal.fire('Berhasil', res.message, 'success');
                tablePeserta.ajax.reload();
            }).fail(function(xhr) {
                let err = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menyimpan data peserta.';
                Swal.fire('Gagal', err, 'error');
            }).always(function() {
                $('#btn-save-peserta').prop('disabled', false).html('Simpan Peserta');
            });
        });

        $('#formUndangan').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $('#btn-save-undangan').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

            $.ajax({
                url: '{{ route("rakernas_undangan.store") }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    $('#modalUndangan').modal('hide');
                    Swal.fire('Berhasil', res.message, 'success');
                    tableUndangan.ajax.reload();
                },
                error: function(xhr) {
                    let err = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menyimpan data undangan.';
                    Swal.fire('Gagal', err, 'error');
                },
                complete: function() {
                    $('#btn-save-undangan').prop('disabled', false).html('Simpan Undangan');
                }
            });
        });

        $('#formGaleri').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $('#btn-save-galeri').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...');

            $.ajax({
                url: '{{ route("rakernas_galeri.store") }}',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    $('#modalGaleri').modal('hide');
                    Swal.fire('Berhasil', res.message, 'success');
                    tableGaleri.ajax.reload();
                },
                error: function(xhr) {
                    let err = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal menyimpan foto galeri.';
                    Swal.fire('Gagal', err, 'error');
                },
                complete: function() {
                    $('#btn-save-galeri').prop('disabled', false).html('Simpan Foto');
                }
            });
        });
    }
</script>
@endpush

