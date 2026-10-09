<div class="org-subnav">
    <div class="custom-org-container">
        <ul class="org-subnav-list">
            <li class="org-subnav-item {{ request()->routeIs('sejarah') ? 'active' : '' }}">
                <a href="{{ route('sejarah') }}" class="org-subnav-link">
                    <i class="fas fa-history mr-1.5 text-xs text-red-600"></i> Sejarah ASKLIN
                </a>
            </li>
            <li class="org-subnav-item {{ request()->routeIs('visimisi') ? 'active' : '' }}">
                <a href="{{ route('visimisi') }}" class="org-subnav-link">
                    <i class="fas fa-bullseye mr-1.5 text-xs text-red-600"></i> Visi &amp; Misi
                </a>
            </li>
            <li class="org-subnav-item {{ request()->routeIs('latarbelakang') ? 'active' : '' }}">
                <a href="{{ route('latarbelakang') }}" class="org-subnav-link">
                    <i class="fas fa-landmark mr-1.5 text-xs text-red-600"></i> Latar Belakang
                </a>
            </li>
            <li class="org-subnav-item {{ request()->routeIs('penguruspusat') ? 'active' : '' }}">
                <a href="{{ route('penguruspusat') }}" class="org-subnav-link">
                    <i class="fas fa-sitemap mr-1.5 text-xs text-red-600"></i> Struktur Pengurus Pusat
                </a>
            </li>
        </ul>
    </div>
</div>

