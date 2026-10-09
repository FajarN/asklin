<!-- Modern Organization Header -->
<header class="mod-header">
    <div class="modern-org-container">
        <div class="mod-header-inner">

            <!-- Brand -->
            <a href="{{ route('home') }}" class="mod-brand">
                @if(setting('logo') && file_exists(public_path('storage/' . setting('logo'))))
                    <img src="{{ asset('storage/' . setting('logo')) }}" alt="ASKLIN Logo" class="mod-brand-logo">
                @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                    <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="ASKLIN Logo" class="mod-brand-logo">
                @else
                    <div class="mod-brand-icon">A</div>
                @endif
                <div>
                    <span class="mod-brand-title">ASKLIN</span>
                    <span class="mod-brand-sub block">Asosiasi Klinik Indonesia</span>
                </div>
            </a>

            <!-- Mobile Toggle -->
            <button type="button" class="mod-mobile-toggle" id="modMobileToggle" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Navigation Links -->
            <nav class="mod-nav" id="modMainNav">
                <ul class="mod-nav-list">
                    <li class="mod-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                        <a href="{{ route('home') }}" class="mod-nav-link">
                            <i class="fas fa-home text-xs opacity-70"></i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li class="mod-nav-item {{ request()->is('sejarah*', 'visi_misi*', 'latar_belakang*', 'pengurus_pusat*') ? 'active' : '' }}">
                        <a href="#" class="mod-nav-link">
                            <span>Tentang ASKLIN</span>
                            <i class="fas fa-chevron-down text-[10px] opacity-60"></i>
                        </a>
                        <ul class="mod-nav-dropdown">
                            <li><a href="{{ route('sejarah') }}" class="mod-nav-dropdown-link"><i class="fas fa-history text-red-400 text-xs"></i> Sejarah Pendirian</a></li>
                            <li><a href="{{ route('visimisi') }}" class="mod-nav-dropdown-link"><i class="fas fa-bullseye text-red-400 text-xs"></i> Visi &amp; Misi</a></li>
                            <li><a href="{{ route('latarbelakang') }}" class="mod-nav-dropdown-link"><i class="fas fa-landmark text-red-400 text-xs"></i> Landasan Hukum</a></li>
                            <li><a href="{{ route('penguruspusat') }}" class="mod-nav-dropdown-link"><i class="fas fa-sitemap text-red-400 text-xs"></i> Pengurus Pusat</a></li>
                        </ul>
                    </li>
                    <li class="mod-nav-item {{ request()->routeIs('berita*') ? 'active' : '' }}">
                        <a href="{{ route('berita') }}" class="mod-nav-link">
                            <span>Warta &amp; Opini</span>
                        </a>
                    </li>
                    <li class="mod-nav-item {{ request()->is('event*') ? 'active' : '' }}">
                        <a href="{{ route('event_asklin') }}" class="mod-nav-link">
                            <span>Agenda &amp; Pelatihan</span>
                        </a>
                    </li>
                    <li class="mod-nav-item {{ request()->routeIs('galery*') ? 'active' : '' }}">
                        <a href="{{ route('galery') }}" class="mod-nav-link">
                            <span>Galeri</span>
                        </a>
                    </li>
                    <li class="mod-nav-item {{ request()->is('konas*') ? 'active' : '' }}">
                        <a href="{{ route('konas') }}" class="mod-nav-link">
                            <span>Konas</span>
                        </a>
                    </li>
                    <li class="mod-nav-item {{ request()->routeIs('kontak') ? 'active' : '' }}">
                        <a href="{{ route('kontak') }}" class="mod-nav-link">
                            <span>Kontak</span>
                        </a>
                    </li>
                </ul>

                <!-- Action Button -->
                <div style="display: flex; align-items: center; gap: 10px; margin-left: 14px;">
                    @guest
                        <a href="{{ route('login') }}" class="mod-btn mod-btn-glass" style="padding: 8px 18px; font-size: 12.5px;">
                            <i class="fas fa-sign-in-alt text-xs"></i>
                            <span>Masuk</span>
                        </a>
                        <a href="{{ route('register') }}" class="mod-btn mod-btn-primary" style="padding: 8px 18px; font-size: 12.5px;">
                            <i class="fas fa-user-plus text-xs"></i>
                            <span>Gabung Klinik</span>
                        </a>
                    @else
                        @hasrole('Superadmin|Admin Cabang|Admin Daerah|Admin Pusat|Sekjen|Ketua Umum|Bendahara|Admin Konas')
                            <a href="{{ route('dashboard.index') }}" class="mod-btn mod-btn-primary" style="padding: 8px 18px; font-size: 12.5px;">
                                <i class="fas fa-columns text-xs"></i>
                                <span>Ruang Pengurus</span>
                            </a>
                        @elsehasrole('Klinik')
                            <a href="{{ route('user.profile') }}" class="mod-btn mod-btn-primary" style="padding: 8px 18px; font-size: 12.5px;">
                                <i class="fas fa-hospital-user text-xs"></i>
                                <span>Portal Anggota</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard.index') }}" class="mod-btn mod-btn-primary" style="padding: 8px 18px; font-size: 12.5px;">
                                <i class="fas fa-user-circle text-xs"></i>
                                <span>Dashboard</span>
                            </a>
                        @endhasrole
                    @endguest
                </div>
            </nav>

        </div>
    </div>
</header>

