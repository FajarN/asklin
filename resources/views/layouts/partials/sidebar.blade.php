<!-- Sidebar Backdrop for Mobile -->
<div id="sidebar-backdrop" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm hidden lg:hidden transition-opacity"></div>

<!-- Sidebar Container -->
<aside id="main-sidebar"
    class="fixed top-0 bottom-0 left-0 z-50 w-72 bg-white dark:bg-slate-900 border-r border-slate-200/80 dark:border-slate-800 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0">
    
    <!-- Brand Header -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200/80 dark:border-slate-800">
        <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 group">
            @if(setting('logo') && file_exists(public_path('storage/' . setting('logo'))))
                <img src="{{ asset('storage/' . setting('logo')) }}" alt="Logo ASKLIN" class="w-9 h-9 object-contain rounded-lg">
            @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="Logo ASKLIN" class="w-9 h-9 object-contain rounded-lg">
            @else
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-red-600 to-rose-500 text-white flex items-center justify-center font-extrabold text-sm shadow-sm group-hover:scale-105 transition-transform">
                    A
                </div>
            @endif
            <div class="flex flex-col">
                <span class="font-extrabold text-slate-800 dark:text-white text-base tracking-tight leading-none group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                    ASKLIN
                </span>
                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium tracking-wide mt-1">
                    Asosiasi Klinik Indonesia
                </span>
            </div>
        </a>

        <!-- Mobile Close Button -->
        <button type="button" id="mobile-sidebar-close" aria-label="Close Sidebar"
            class="lg:hidden w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>

    <!-- Navigation Links Scroll Area -->
    <nav class="flex-1 overflow-y-auto px-4 py-5 custom-scrollbar space-y-5 text-xs font-medium">
        
        <!-- Section: Overview -->
        <div>
            <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                Menu Utama
            </div>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ Request::url() == route('dashboard.index') ? 'bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-400 font-semibold shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60' }}">
                        <i class="fas fa-home text-sm {{ Request::url() == route('dashboard.index') ? 'text-red-600 dark:text-red-400' : 'text-slate-400' }}"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Section: Ruang Verifikasi (Core Clinical Workflow) -->
        @can('verifikasi-anggota')
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                    Verifikasi Klinik
                </div>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('verifikasi_anggota.index') }}"
                            class="flex items-center justify-between px-3 py-2 rounded-xl transition-all {{ Request::url() == route('verifikasi_anggota.index') ? 'bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60' }}">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-clipboard-check text-sm {{ Request::url() == route('verifikasi_anggota.index') ? 'text-red-600 dark:text-red-400' : 'text-slate-400' }}"></i>
                                <span>Verifikasi Anggota</span>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        </a>
                    </li>
                </ul>
            </div>
        @endcan

        <!-- Section: Data Terverifikasi -->
        @canany(['data-anggota', 'kerjasama-asuransi', 'expired-sio'])
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                    Data Keanggotaan
                </div>
                <ul class="space-y-1">
                    @can('data-anggota')
                        <li>
                            <a href="{{ route('anggota.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ Request::url() == route('anggota.index') ? 'bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60' }}">
                                <i class="fas fa-clinic-medical text-sm {{ Request::url() == route('anggota.index') ? 'text-red-600 dark:text-red-400' : 'text-slate-400' }}"></i>
                                <span>Data Anggota</span>
                            </a>
                        </li>
                    @endcan
                    @can('kerjasama-asuransi')
                        <li>
                            <a href="{{ route('kerjasama_asuransi.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ Request::url() == route('kerjasama_asuransi.index') ? 'bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60' }}">
                                <i class="fas fa-shield-alt text-sm {{ Request::url() == route('kerjasama_asuransi.index') ? 'text-red-600 dark:text-red-400' : 'text-slate-400' }}"></i>
                                <span>Asuransi Anggota</span>
                            </a>
                        </li>
                    @endcan
                    @can('expired-sio')
                        <li>
                            <a href="{{ route('expired_sio.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ Request::url() == route('expired_sio.index') ? 'bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60' }}">
                                <i class="fas fa-business-time text-sm {{ Request::url() == route('expired_sio.index') ? 'text-red-600 dark:text-red-400' : 'text-slate-400' }}"></i>
                                <span>Expired SIO</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        @endcanany

        <!-- Section: Pembayaran Iuran -->
        @canany(['pembayaran-list', 'pembayaran-pusat-list'])
            <div class="sidebar-dropdown-group" data-group="pembayaran">
                <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60 transition group sidebar-dropdown-btn">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-receipt text-sm text-slate-400 group-hover:text-red-600"></i>
                        <span>Keuangan & Iuran</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-400 transition-transform dropdown-arrow"></i>
                </button>
                <ul class="dropdown-submenu hidden pl-7 pr-1 mt-1 space-y-1">
                    @can('pembayaran-list')
                        <li>
                            <a href="{{ route('pembayaran.pangkal') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('pembayaran.pangkal') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Verif Uang Pangkal
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pembayaran.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('pembayaran.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Verif Iuran Bulanan
                            </a>
                        </li>
                    @endcan
                    @can('pembayaran-pusat-list')
                        <li>
                            <a href="{{ route('pembayaran_pusat.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('pembayaran_pusat.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Bukti Bayar Daerah/Pusat
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        @endcanany

        <!-- Section: Sistem Pemilihan Ketua (5 Tahunan) -->
        @canany(['pemilihan-list', 'pemilihan-dashboard'])
            <div class="sidebar-dropdown-group" data-group="pemilihan-ketua">
                <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60 transition group sidebar-dropdown-btn">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-vote-yea text-sm {{ Str::contains(Request::url(), 'pemilihan') ? 'text-red-600 dark:text-red-400' : 'text-slate-400 group-hover:text-red-600' }}"></i>
                        <span>Pemilihan Ketua</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-400 transition-transform dropdown-arrow"></i>
                </button>
                <ul class="dropdown-submenu {{ Str::contains(Request::url(), 'pemilihan') ? '' : 'hidden' }} pl-7 pr-1 mt-1 space-y-1">
                    @can('pemilihan-dashboard')
                        <li>
                            <a href="{{ route('pemilihan.dashboard') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::routeIs('pemilihan.dashboard*') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Dashboard Hasil
                            </a>
                        </li>
                    @endcan
                    @can('pemilihan-list')
                        <li>
                            <a href="{{ route('pemilihan.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::routeIs('pemilihan.index') || Request::routeIs('pemilihan.create') || Request::routeIs('pemilihan.edit') || Request::routeIs('pemilihan.show') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Data Agenda Pemilihan
                            </a>
                        </li>
                    @endcan
                    @can('pemilihan-laporan')
                        <li>
                            <a href="{{ route('pemilihan.laporan') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::routeIs('pemilihan.laporan*') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Laporan & Rekap Suara
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        @endcanany

        <!-- Section: Struktur Organisasi (Tersendiri) -->
        @can('struktur-organisasi-list')
            <div>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('struktur_organisasi.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ Request::url() == route('struktur_organisasi.index') || Str::contains(Request::url(), 'struktur_organisasi') ? 'bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60' }}">
                            <i class="fas fa-sitemap text-sm {{ Request::url() == route('struktur_organisasi.index') || Str::contains(Request::url(), 'struktur_organisasi') ? 'text-red-600 dark:text-red-400' : 'text-slate-400' }}"></i>
                            <span>Struktur Organisasi</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endcan

        <!-- Section: Surat Keputusan (SK) -->
        @if(auth()->check() && (auth()->user()->can('sk-pengurus-list') || auth()->user()->hasRole(['Superadmin', 'Admin Pusat', 'Admin Daerah', 'Admin Cabang', 'Sekjen', 'Ketua Umum'])))
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                    Surat Keputusan
                </div>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('sk_pengurus.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ Str::contains(Request::url(), 'sk_pengurus') ? 'bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60' }}">
                            <i class="fas fa-file-signature text-sm {{ Str::contains(Request::url(), 'sk_pengurus') ? 'text-red-600 dark:text-red-400' : 'text-slate-400' }}"></i>
                            <span>Surat Keputusan (SK)</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('sk_lama.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ Str::contains(Request::url(), 'sk-lama') ? 'bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60' }}">
                            <i class="fas fa-history text-sm {{ Str::contains(Request::url(), 'sk-lama') ? 'text-red-600 dark:text-red-400' : 'text-slate-400' }}"></i>
                            <span>History SK</span>
                        </a>
                    </li>
                    @if(auth()->user()->can('master-ketua-umum-list') || auth()->user()->hasRole(['Superadmin', 'Admin Pusat', 'Sekjen', 'Ketua Umum']))
                    <li>
                        <a href="{{ route('master_ketua_umum.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ Str::contains(Request::url(), 'master_ketua_umum') ? 'bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60' }}">
                            <i class="fas fa-user-tie text-sm {{ Str::contains(Request::url(), 'master_ketua_umum') ? 'text-red-600 dark:text-red-400' : 'text-slate-400' }}"></i>
                            <span>Master Ketua Umum</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        @endif

        <!-- Section: Manajemen Persuratan (Tersendiri) -->
        @canany(['jenis-surat-list', 'penomoran-surat-list', 'surat-keluar-list', 'surat-tugas-list', 'surat-undangan-list'])
            <div class="sidebar-dropdown-group" data-group="surat">
                <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60 transition group sidebar-dropdown-btn">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-envelope-open-text text-sm text-slate-400 group-hover:text-red-600"></i>
                        <span>Persuratan</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-400 transition-transform dropdown-arrow"></i>
                </button>
                <ul class="dropdown-submenu hidden pl-7 pr-1 mt-1 space-y-1">
                    @can('surat-keluar-list')
                        <li>
                            <a href="{{ route('surat_keluar.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Str::contains(Request::url(), 'surat_keluar') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Surat Keluar
                            </a>
                        </li>
                    @endcan
                    @can('surat-tugas-list')
                        <li>
                            <a href="{{ route('surat_tugas.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Str::contains(Request::url(), 'surat_tugas') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Surat Tugas
                            </a>
                        </li>
                    @endcan
                    @can('surat-undangan-list')
                        <li>
                            <a href="{{ route('surat_undangan.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Str::contains(Request::url(), 'surat_undangan') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Surat Undangan
                            </a>
                        </li>
                    @endcan
                    @can('penomoran-surat-list')
                        <li>
                            <a href="{{ route('surat_penomoran.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Str::contains(Request::url(), 'surat_penomoran') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Penomoran Surat
                            </a>
                        </li>
                    @endcan
                    @can('jenis-surat-list')
                        <li>
                            <a href="{{ route('jenis_surat.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Str::contains(Request::url(), 'jenis_surat') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Jenis Surat
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        @endcanany

        <!-- Section: Ruang Administrasi & Sertifikat -->
        @canany(['agenda-list', 'sertifikat-list', 'expired-sertifikat'])
            <div class="sidebar-dropdown-group" data-group="administrasi">
                <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60 transition group sidebar-dropdown-btn">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-certificate text-sm text-slate-400 group-hover:text-red-600"></i>
                        <span>Administrasi & Sertifikat</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-400 transition-transform dropdown-arrow"></i>
                </button>
                <ul class="dropdown-submenu hidden pl-7 pr-1 mt-1 space-y-1">
                    @can('agenda-list')
                        <li>
                            <a href="{{ route('agenda.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('agenda.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Agenda Kerja
                            </a>
                        </li>
                    @endcan
                    @can('sertifikat-list')
                        <li>
                            <a href="{{ route('sertifikat.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('sertifikat.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Terbitkan Sertifikat
                            </a>
                        </li>
                    @endcan
                    @can('expired-sertifikat')
                        <li>
                            <a href="{{ route('expired_sertifikat.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('expired_sertifikat.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Expired Sertifikat
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        @endcanany

        <!-- Section: Content Website (Berita, Slider, Galeri, Kontak) -->
        @canany(['slider-list', 'berita-list', 'gallery-list', 'kontak-list'])
            <div class="sidebar-dropdown-group" data-group="website">
                <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60 transition group sidebar-dropdown-btn">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-globe text-sm text-slate-400 group-hover:text-red-600"></i>
                        <span>Konten Website</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-400 transition-transform dropdown-arrow"></i>
                </button>
                <ul class="dropdown-submenu hidden pl-7 pr-1 mt-1 space-y-1">
                    @can('berita-list')
                        <li><a href="{{ route('berita.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('berita.index') ? 'text-red-600 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Berita & Artikel</a></li>
                    @endcan
                    @can('slider-list')
                        <li><a href="{{ route('slider.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('slider.index') ? 'text-red-600 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Banner Slider</a></li>
                    @endcan
                    @can('gallery-list')
                        <li><a href="{{ route('galery.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('galery.index') ? 'text-red-600 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Galeri Foto</a></li>
                    @endcan
                    @can('kontak-list')
                        <li><a href="{{ route('kontak.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('kontak.index') ? 'text-red-600 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Pesan Masuk (Kontak)</a></li>
                    @endcan
                </ul>
            </div>
        @endcanany

        <!-- Section: Menu Agenda Event -->
        @if(auth()->check() && (auth()->user()->can('event-list') || auth()->user()->hasRole(['Superadmin', 'Admin Pusat', 'Admin Konas']) || auth()->user()->canany(['konas-master-hotel-list', 'konas-tipe-hotel-list', 'konas-peserta-list', 'konas-booking-hotel-list', 'konas-data-penerbangan-list', 'konas-partner-list'])))
            <div class="sidebar-dropdown-group" data-group="agenda-event">
                <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60 transition group sidebar-dropdown-btn">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-calendar-alt text-sm text-slate-400 group-hover:text-red-600"></i>
                        <span>Agenda Event</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-400 transition-transform dropdown-arrow"></i>
                </button>
                <ul class="dropdown-submenu hidden pl-7 pr-1 mt-1 space-y-1">
                    @can('event-list')
                        <li>
                            <a href="{{ route('events.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('events.index') ? 'text-red-600 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }} flex items-center gap-2">
                                <i class="fas fa-calendar-check text-[10px]"></i>
                                <span>Event biasa</span>
                            </a>
                        </li>
                    @endcan
                    @if(auth()->user()->hasRole(['Superadmin', 'Admin Pusat', 'Admin Konas']) || auth()->user()->canany(['konas-master-hotel-list', 'konas-tipe-hotel-list', 'konas-peserta-list', 'konas-booking-hotel-list', 'konas-data-penerbangan-list', 'konas-partner-list']))
                        <li>
                            <a href="{{ route('konas_admin.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ request()->routeIs('konas_admin.*') || request()->is('backend/konas*') || request()->is('backend/peserta-konas*') || request()->is('backend/booking-hotel*') || request()->is('backend/data-penerbangan*') || request()->is('backend/konas-master-hotel*') || request()->is('backend/konas-master-tipe-hotel*') || request()->is('backend/partner*') ? 'text-red-600 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }} flex items-center gap-2">
                                <i class="fas fa-landmark text-[10px]"></i>
                                <span>Event Konas</span>
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('rakernas_admin.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ request()->routeIs('rakernas_admin.*') || request()->is('backend/rakernas*') ? 'text-red-600 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }} flex items-center gap-2">
                            <i class="fas fa-users-cog text-[10px]"></i>
                            <span>Event Rakernas</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        <!-- Section: Laporan -->
        @canany(['laporan-pusat', 'laporan-daerah', 'laporan-cabang'])
            <div class="sidebar-dropdown-group" data-group="laporan">
                <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60 transition group sidebar-dropdown-btn">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-invoice text-sm text-slate-400 group-hover:text-red-600"></i>
                        <span>Laporan Anggota</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-400 transition-transform dropdown-arrow"></i>
                </button>
                <ul class="dropdown-submenu hidden pl-7 pr-1 mt-1 space-y-1">
                    @can('laporan-pusat')
                        <li>
                            <a href="{{ route('laporan_pusat.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('laporan_pusat.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Laporan Nasional (Pusat)
                            </a>
                        </li>
                    @endcan
                    @can('laporan-daerah')
                        <li>
                            <a href="{{ route('laporan_daerah') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('laporan_daerah') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Laporan Wilayah (Daerah)
                            </a>
                        </li>
                    @endcan
                    @can('laporan-cabang')
                        <li>
                            <a href="{{ route('laporan_cabang') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('laporan_cabang') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Laporan Cabang (Kota/Kab)
                            </a>
                        </li>
                    @endcan
                    @can('laporan-pusat')
                        <li>
                            <a href="{{ route('laporan_export.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Str::contains(Request::url(), 'laporan-export') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">
                                Laporan Keseluruhan (Export)
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
        @endcanany

        <!-- Section: Master Data -->
        @hasrole('Superadmin|Admin Pusat')
            @canany(['fasilitas-list', 'fasilitas-klinik-list', 'layanan-list', 'kategori-berita-list', 'kategori-event-list', 'kategori-pembayaran-list'])
                <div class="sidebar-dropdown-group" data-group="master">
                    <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60 transition group sidebar-dropdown-btn">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-database text-sm text-slate-400 group-hover:text-red-600"></i>
                            <span>Master Data</span>
                        </div>
                        <i class="fas fa-chevron-right text-[10px] text-slate-400 transition-transform dropdown-arrow"></i>
                    </button>
                    <ul class="dropdown-submenu hidden pl-7 pr-1 mt-1 space-y-1">
                        @can('fasilitas-list')
                            <li><a href="{{ route('fasilitas.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('fasilitas.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Fasilitas</a></li>
                        @endcan
                        @can('fasilitas-klinik-list')
                            <li><a href="{{ route('fasilitas_klinik.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('fasilitas_klinik.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Fasilitas Klinik</a></li>
                        @endcan
                        @can('layanan-list')
                            <li><a href="{{ route('layanan.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('layanan.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Layanan</a></li>
                        @endcan
                        @can('kategori-berita-list')
                            <li><a href="{{ route('berita_kategori.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('berita_kategori.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Kategori Berita</a></li>
                        @endcan
                        @can('kategori-event-list')
                            <li><a href="{{ route('event_kategori.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('event_kategori.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Kategori Event</a></li>
                        @endcan
                        @can('kategori-pembayaran-list')
                            <li><a href="{{ route('kategori_pembayaran.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('kategori_pembayaran.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Kategori Pembayaran</a></li>
                        @endcan
                    </ul>
                </div>
            @endcanany
        @endhasrole

        <!-- Section: Pengaturan (RBAC & System) -->
        @can('secret')
            <div class="sidebar-dropdown-group" data-group="pengaturan">
                <button type="button" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100/70 dark:hover:bg-slate-800/60 transition group sidebar-dropdown-btn">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-sliders-h text-sm text-slate-400 group-hover:text-red-600"></i>
                        <span>Pengaturan Sistem</span>
                    </div>
                    <i class="fas fa-chevron-right text-[10px] text-slate-400 transition-transform dropdown-arrow"></i>
                </button>
                <ul class="dropdown-submenu hidden pl-7 pr-1 mt-1 space-y-1">
                    <li><a href="{{ route('users.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('users.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Kelola Pengguna</a></li>
                    <li><a href="{{ route('roles.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('roles.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Role & Group</a></li>
                    <li><a href="{{ route('permissions.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('permissions.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Hak Akses (Permission)</a></li>
                    <li><a href="{{ route('settings.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('settings.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }}">Konfigurasi Aplikasi</a></li>
                    <li><a href="{{ route('smtp_settings.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('smtp_settings.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }} flex items-center justify-between"><span>Pengaturan Email SMTP</span><span class="text-[9px] px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 font-bold">Mail</span></a></li>
                    <li><a href="{{ route('template_settings.index') }}" class="block px-3 py-1.5 rounded-lg text-[11px] {{ Request::url() == route('template_settings.index') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-slate-500 hover:text-slate-900 dark:hover:text-white' }} flex items-center justify-between"><span>Pengaturan Template</span><span class="text-[9px] px-1.5 py-0.5 rounded-full bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300 font-bold">Multi</span></a></li>
                </ul>
            </div>
        @endcan

    </nav>

    <!-- Sidebar Bottom: Active User Card -->
    <div class="p-3 border-t border-slate-200/80 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
        <div class="flex items-center gap-3 p-2 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60">
            <div class="w-8 h-8 rounded-lg bg-red-600 text-white font-bold text-xs flex items-center justify-center shrink-0">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-slate-800 dark:text-slate-100 truncate">
                    {{ Auth::user()->name ?? 'User' }}
                </p>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 truncate">
                    {{ Auth::user()->roles->pluck('name')->first() ?? 'Member' }}
                </p>
            </div>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('navbar-logout-form').submit();"
               title="Logout"
               class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <i class="fas fa-sign-out-alt text-xs"></i>
            </a>
        </div>
    </div>
</aside>
