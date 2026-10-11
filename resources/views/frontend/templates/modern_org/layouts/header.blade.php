<!-- Modern Floating Glass Header -->
<div class="mod-floating-header-wrapper">
    <header class="mod-floating-header">
        <div class="mod-header-inner">

            <!-- Brand -->
            <a href="{{ route('home') }}" class="mod-brand">
                @if(setting('logo') && file_exists(public_path('storage/' . setting('logo'))))
                    <img src="{{ asset('storage/' . setting('logo')) }}" alt="ASKLIN Logo" class="mod-brand-logo-img">
                @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                    <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="ASKLIN Logo" class="mod-brand-logo-img">
                @else
                    <div class="mod-brand-badge">A</div>
                @endif
                <div class="mod-brand-text">
                    <span class="mod-brand-title">ASKLIN</span>
                    <span class="mod-brand-sub">Asosiasi Klinik Indonesia</span>
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
                            <span>Tentang Kami</span>
                            <i class="fas fa-chevron-down text-[10px] opacity-60"></i>
                        </a>
                        <ul class="mod-nav-dropdown">
                            <li><a href="{{ route('sejarah') }}" class="mod-dropdown-link"><i class="fas fa-history text-red-500 text-xs"></i> Sejarah</a></li>
                            <li><a href="{{ route('visimisi') }}" class="mod-dropdown-link"><i class="fas fa-bullseye text-red-500 text-xs"></i> Visi &amp; Misi</a></li>
                            <li><a href="{{ route('latarbelakang') }}" class="mod-dropdown-link"><i class="fas fa-landmark text-red-500 text-xs"></i> Latar Belakang</a></li>
                            <li><a href="{{ route('penguruspusat') }}" class="mod-dropdown-link"><i class="fas fa-sitemap text-red-500 text-xs"></i> Pengurus Pusat</a></li>
                        </ul>
                    </li>
                    <li class="mod-nav-item {{ request()->routeIs('berita*') ? 'active' : '' }}">
                        <a href="{{ route('berita') }}" class="mod-nav-link">
                            <span>Berita</span>
                        </a>
                    </li>
                    <li class="mod-nav-item {{ request()->is('event*') ? 'active' : '' }}">
                        <a href="{{ route('event_asklin') }}" class="mod-nav-link">
                            <span>Event</span>
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
                    <li class="mod-nav-item {{ request()->is('rakernas*') ? 'active' : '' }}">
                        <a href="{{ route('rakernas') }}" class="mod-nav-link">
                            <span>Rakernas</span>
                        </a>
                    </li>
                    <li class="mod-nav-item {{ request()->routeIs('kontak') ? 'active' : '' }}">
                        <a href="{{ route('kontak') }}" class="mod-nav-link">
                            <span>Kontak</span>
                        </a>
                    </li>
                </ul>

                <!-- Action Button & Dark Mode Switch -->
                <div class="mod-header-actions">
                    <!-- Theme Toggle Switch -->
                    <button type="button" class="mod-theme-toggle" id="themeToggleMod" aria-label="Ganti Tema" title="Ganti Mode Gelap/Terang">
                        <i class="fas fa-moon"></i>
                    </button>

                    @guest
                        <a href="{{ route('login') }}" class="mod-btn mod-btn-glass" style="padding: 8px 16px; font-size: 12.5px;">
                            <i class="fas fa-sign-in-alt text-xs"></i>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="mod-btn mod-btn-primary" style="padding: 8px 18px; font-size: 12.5px;">
                            <i class="fas fa-user-plus text-xs"></i>
                            <span>Daftar</span>
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
    </header>
</div>
