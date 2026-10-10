<!-- Official Topbar -->
<div class="org-topbar">
    <div class="custom-org-container">
        <div class="org-topbar-inner">
            <div class="org-topbar-items">
                <div class="org-topbar-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>PP ASKLIN • Jakarta Timur, DKI Jakarta</span>
                </div>
                <div class="org-topbar-item">
                    <i class="fas fa-envelope"></i>
                    <span>ppasklin.indonesia@gmail.com</span>
                </div>
                <div class="org-topbar-item">
                    <i class="fas fa-phone-alt"></i>
                    <span>021-8690 1234</span>
                </div>
            </div>
            <div class="org-topbar-items">
                <button type="button" class="org-theme-toggle-top" id="themeToggleOrgTop" aria-label="Ganti Tema" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 3px 10px; border-radius: 9999px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; font-size: 11px; transition: all 0.2s;">
                    <i class="fas fa-moon"></i>
                    <span class="theme-text">Mode Gelap</span>
                </button>
                <span class="org-topbar-badge">
                    <i class="fas fa-shield-alt text-red-400 mr-1"></i> SK Kemenkumham: AHU-0004928.AH.01.07
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Main Institutional Header -->
<header class="org-header">
    <div class="custom-org-container">
        <div class="org-header-inner">

            <!-- Logo & Brand -->
            <a href="{{ route('home') }}" class="org-brand">
                @if(setting('logo') && file_exists(public_path('storage/' . setting('logo'))))
                    <img src="{{ asset('storage/' . setting('logo')) }}" alt="ASKLIN Logo" class="org-brand-logo">
                @elseif(file_exists(public_path('assets/images/logo_asklin.png')))
                    <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="ASKLIN Logo" class="org-brand-logo">
                @else
                    <div class="org-brand-fallback">A</div>
                @endif
                <div class="org-brand-text">
                    <span class="org-brand-title">ASKLIN</span>
                    <span class="org-brand-subtitle">Asosiasi Klinik Indonesia</span>
                </div>
            </a>

            <!-- Mobile Navigation Toggle -->
            <button type="button" class="org-mobile-toggle" id="orgMobileToggle" aria-label="Toggle Navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Navigation Links -->
            <nav class="org-nav" id="orgMainNav">
                <ul class="org-nav-list">
                    <li class="org-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                        <a href="{{ route('home') }}" class="org-nav-link">
                            <i class="fas fa-home text-xs opacity-70"></i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li class="org-nav-item {{ request()->is('sejarah*', 'visi_misi*', 'latar_belakang*', 'pengurus_pusat*') ? 'active' : '' }}">
                        <a href="#" class="org-nav-link">
                            <span>Tentang Kami</span>
                            <i class="fas fa-chevron-down text-[10px] opacity-60"></i>
                        </a>
                        <ul class="org-nav-dropdown">
                            <li><a href="{{ route('sejarah') }}" class="org-nav-dropdown-link"><i class="fas fa-history text-xs text-red-600"></i> Sejarah</a></li>
                            <li><a href="{{ route('visimisi') }}" class="org-nav-dropdown-link"><i class="fas fa-bullseye text-xs text-red-600"></i> Visi &amp; Misi</a></li>
                            <li><a href="{{ route('latarbelakang') }}" class="org-nav-dropdown-link"><i class="fas fa-landmark text-xs text-red-600"></i> Latar Belakang</a></li>
                            <li><a href="{{ route('penguruspusat') }}" class="org-nav-dropdown-link"><i class="fas fa-sitemap text-xs text-red-600"></i> Pengurus Pusat</a></li>
                        </ul>
                    </li>
                    <li class="org-nav-item {{ request()->routeIs('berita*') ? 'active' : '' }}">
                        <a href="{{ route('berita') }}" class="org-nav-link">
                            <span>Berita</span>
                        </a>
                    </li>
                    <li class="org-nav-item {{ request()->is('event*') ? 'active' : '' }}">
                        <a href="{{ route('event_asklin') }}" class="org-nav-link">
                            <span>Event</span>
                        </a>
                    </li>
                    <li class="org-nav-item {{ request()->routeIs('galery*') ? 'active' : '' }}">
                        <a href="{{ route('galery') }}" class="org-nav-link">
                            <span>Galeri</span>
                        </a>
                    </li>
                    <li class="org-nav-item {{ request()->is('konas*') ? 'active' : '' }}">
                        <a href="{{ route('konas') }}" class="org-nav-link">
                            <span>Konas</span>
                        </a>
                    </li>
                    <li class="org-nav-item {{ request()->routeIs('kontak') ? 'active' : '' }}">
                        <a href="{{ route('kontak') }}" class="org-nav-link">
                            <span>Kontak</span>
                        </a>
                    </li>
                </ul>

                <!-- Action Button in Header -->
                <div class="org-header-actions" style="margin-left: 12px; display: flex; align-items: center; gap: 8px;">
                    <button type="button" class="org-theme-toggle" id="themeToggleOrg" aria-label="Ganti Tema" title="Ganti Mode Gelap/Terang">
                        <i class="fas fa-moon"></i>
                    </button>
                    @guest
                        <a href="{{ route('login') }}" class="org-btn org-btn-outline">
                            <i class="fas fa-sign-in-alt text-xs"></i>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="org-btn org-btn-primary">
                            <i class="fas fa-user-plus text-xs"></i>
                            <span>Daftar</span>
                        </a>
                    @else
                        @hasrole('Superadmin|Admin Cabang|Admin Daerah|Admin Pusat|Sekjen|Ketua Umum|Bendahara|Admin Konas')
                            <a href="{{ route('dashboard.index') }}" class="org-btn org-btn-primary">
                                <i class="fas fa-columns text-xs"></i>
                                <span>Ruang Pengurus</span>
                            </a>
                        @elsehasrole('Klinik')
                            <a href="{{ route('user.profile') }}" class="org-btn org-btn-primary">
                                <i class="fas fa-hospital-user text-xs"></i>
                                <span>Portal Klinik</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard.index') }}" class="org-btn org-btn-primary">
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

