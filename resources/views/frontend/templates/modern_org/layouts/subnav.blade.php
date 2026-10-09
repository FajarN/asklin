<div style="background-color: #0f172a; border-bottom: 1px solid rgba(255,255,255,0.08); padding: 10px 0;">
    <div class="modern-org-container">
        <ul style="display: flex; align-items: center; list-style: none; margin: 0; padding: 0; gap: 8px; overflow-x: auto;">
            <li>
                <a href="{{ route('sejarah') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 9999px; font-size: 12.5px; font-weight: 600; {{ request()->routeIs('sejarah') ? 'background: #dc2626; color: #fff;' : 'color: #94a3b8; background: rgba(255,255,255,0.04);' }}">
                    <i class="fas fa-history text-xs"></i> Sejarah
                </a>
            </li>
            <li>
                <a href="{{ route('visimisi') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 9999px; font-size: 12.5px; font-weight: 600; {{ request()->routeIs('visimisi') ? 'background: #dc2626; color: #fff;' : 'color: #94a3b8; background: rgba(255,255,255,0.04);' }}">
                    <i class="fas fa-bullseye text-xs"></i> Visi &amp; Misi
                </a>
            </li>
            <li>
                <a href="{{ route('latarbelakang') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 9999px; font-size: 12.5px; font-weight: 600; {{ request()->routeIs('latarbelakang') ? 'background: #dc2626; color: #fff;' : 'color: #94a3b8; background: rgba(255,255,255,0.04);' }}">
                    <i class="fas fa-landmark text-xs"></i> Latar Belakang
                </a>
            </li>
            <li>
                <a href="{{ route('penguruspusat') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 9999px; font-size: 12.5px; font-weight: 600; {{ request()->routeIs('penguruspusat') ? 'background: #dc2626; color: #fff;' : 'color: #94a3b8; background: rgba(255,255,255,0.04);' }}">
                    <i class="fas fa-sitemap text-xs"></i> Pengurus Pusat
                </a>
            </li>
        </ul>
    </div>
</div>

