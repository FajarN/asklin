<header class="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-slate-200/80 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 px-4 sm:px-6 backdrop-blur-md transition-colors">
    <!-- Left: Mobile Menu Toggle & Page Heading -->
    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Mobile Sidebar Toggle -->
        <button type="button" id="mobile-sidebar-toggle" aria-label="Toggle Navigation"
            class="lg:hidden inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
            <i class="fas fa-bars text-sm"></i>
        </button>
    </div>

    <!-- Right: Actions, Dark Mode Switcher & User Profile -->
    <div class="flex items-center gap-2 sm:gap-3">
        <!-- Quick Link Website -->
        <a href="{{ url('/') }}" target="_blank" title="Kunjungi Website Publik"
            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition">
            <i class="fas fa-external-link-alt text-[10px]"></i>
            <span>Lihat Website</span>
        </a>

        <!-- Notification Bell Dropdown Button -->
        <div class="relative" id="notification-menu-container">
            <button type="button" id="notification-toggle-btn" aria-label="Lihat Notifikasi"
                class="relative inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
                <i class="fas fa-bell text-sm"></i>
                <span id="nav-notif-badge" class="hidden absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full text-[10px] font-black bg-rose-500 text-white flex items-center justify-center shadow-xs border-2 border-white dark:border-slate-900 leading-none">
                    0
                </span>
            </button>

            <!-- Dropdown Preview Panel -->
            <div id="notification-dropdown"
                class="hidden absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xl py-2 text-xs text-slate-700 dark:text-slate-300 z-50 animate-in fade-in zoom-in-95 duration-100">
                <div class="px-4 py-2.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <span class="font-bold text-slate-800 dark:text-white flex items-center gap-1.5">
                        <i class="fas fa-bell text-emerald-600 text-xs"></i> Notifikasi Masuk
                    </span>
                    <a href="{{ route('notifications.index') }}" class="text-[11px] text-emerald-600 dark:text-emerald-400 hover:underline font-semibold">
                        Lihat Semua
                    </a>
                </div>

                <div id="nav-notif-list" class="max-h-72 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/80">
                    <div class="p-6 text-center text-slate-400">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Memuat notifikasi...
                    </div>
                </div>

                <div class="px-3 pt-2 pb-1 border-t border-slate-100 dark:border-slate-800 text-center">
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary btn-sm w-full text-xs py-1.5">
                        Buka Pusat Notifikasi Lengkap
                    </a>
                </div>
            </div>
        </div>

        <!-- Dark Mode Toggle Button -->
        <button type="button" id="theme-toggle" aria-label="Ganti Tema Gelap / Terang"
            class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none focus:ring-2 focus:ring-red-500/30">
            <!-- Sun Icon (shown in dark mode) -->
            <i id="theme-toggle-light-icon" class="fas fa-sun text-amber-400 hidden text-sm"></i>
            <!-- Moon Icon (shown in light mode) -->
            <i id="theme-toggle-dark-icon" class="fas fa-moon text-slate-600 dark:text-slate-300 hidden text-sm"></i>
        </button>

        <!-- User Profile Dropdown -->
        <div class="relative" id="user-menu-container">
            <button type="button" id="user-menu-button" aria-expanded="false"
                class="flex items-center gap-2.5 pl-1.5 pr-2.5 py-1 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition focus:outline-none">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-red-600 to-rose-700 text-white font-bold text-xs flex items-center justify-center shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                </div>
                <div class="hidden md:flex flex-col text-left">
                    <span class="text-xs font-semibold text-slate-800 dark:text-slate-100 leading-tight">
                        {{ Str::limit(Auth::user()->name ?? 'Pengguna', 16) }}
                    </span>
                    <span class="text-[10px] text-slate-500 dark:text-slate-400">
                        {{ Auth::user()->roles->pluck('name')->first() ?? 'Member' }}
                    </span>
                </div>
                <i class="fas fa-chevron-down text-[10px] text-slate-400 ml-0.5 hidden sm:inline-block"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="user-menu-dropdown"
                class="hidden absolute right-0 mt-2 w-56 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xl py-1.5 text-xs text-slate-700 dark:text-slate-300 z-50 animate-in fade-in zoom-in-95 duration-100">
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800">
                    <p class="font-semibold text-slate-800 dark:text-white text-sm">{{ Auth::user()->name ?? 'Pengguna' }}</p>
                    <p class="text-slate-400 text-[11px] truncate mt-0.5">{{ Auth::user()->email ?? '-' }}</p>
                    <span class="inline-block mt-2 px-2 py-0.5 rounded-md text-[10px] font-medium bg-red-50 text-red-700 dark:bg-red-950/60 dark:text-red-300 border border-red-200/50 dark:border-red-800/50">
                        {{ Auth::user()->roles->pluck('name')->first() ?? 'Member' }}
                    </span>
                </div>

                <div class="py-1">
                    <a href="{{ route('profile.index') }}"
                        class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 transition">
                        <i class="fas fa-user-circle text-sm text-slate-400"></i>
                        <span>Profil Saya</span>
                    </a>
                    @can('secret')
                        <a href="{{ route('settings.index') }}"
                            class="flex items-center gap-2.5 px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 transition">
                            <i class="fas fa-cog text-sm text-slate-400"></i>
                            <span>Pengaturan Sistem</span>
                        </a>
                    @endcan
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800 pt-1">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('navbar-logout-form').submit();"
                        class="flex items-center gap-2.5 px-4 py-2 text-rose-600 dark:text-rose-400 hover:bg-rose-50/50 dark:hover:bg-rose-950/30 transition">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                        <span>Keluar (Logout)</span>
                    </a>
                    <form id="navbar-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

