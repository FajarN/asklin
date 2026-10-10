<div class="mod-subnav-bar">
    <div class="modern-org-container">
        <ul class="mod-subnav-list">
            <li>
                <a href="{{ route('sejarah') }}" class="mod-subnav-link {{ request()->routeIs('sejarah') ? 'active' : '' }}">
                    <i class="fas fa-history text-xs"></i> Sejarah
                </a>
            </li>
            <li>
                <a href="{{ route('visimisi') }}" class="mod-subnav-link {{ request()->routeIs('visimisi') ? 'active' : '' }}">
                    <i class="fas fa-bullseye text-xs"></i> Visi &amp; Misi
                </a>
            </li>
            <li>
                <a href="{{ route('latarbelakang') }}" class="mod-subnav-link {{ request()->routeIs('latarbelakang') ? 'active' : '' }}">
                    <i class="fas fa-landmark text-xs"></i> Latar Belakang
                </a>
            </li>
            <li>
                <a href="{{ route('penguruspusat') }}" class="mod-subnav-link {{ request()->routeIs('penguruspusat') ? 'active' : '' }}">
                    <i class="fas fa-sitemap text-xs"></i> Pengurus Pusat
                </a>
            </li>
        </ul>
    </div>
</div>
