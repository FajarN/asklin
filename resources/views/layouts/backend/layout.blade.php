<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', setting('nama_aplikasi', 'ASKLIN'))</title>
    <link rel="icon" href="{{ asset('assets/images/logo_asklin.png') }}" type="image/x-icon">

    <!-- Instant Dark Mode Init (Zero FOUC) -->
    <script>
        (function() {
            try {
                const storedTheme = localStorage.getItem('theme');
                const supportDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (storedTheme === 'dark' || (!storedTheme && supportDarkMode)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (_) {}
        })();
    </script>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Legacy Compatibility CSS (Bootstrap 4 grid/utilities for existing views) -->
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/bootstrap/css/bootstrap.min.css') }}">

    <!-- Compiled Tailwind CSS & App JS via Vite (with resilient fallback for production) -->
    @php
        $manifestPath = public_path('build/manifest.json');
        $manifestData = file_exists($manifestPath) ? json_decode(@file_get_contents($manifestPath), true) : null;
    @endphp
    @if ($manifestData && isset($manifestData['resources/css/app.css']))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @elseif ($manifestData && isset($manifestData['resources/sass/app.scss']))
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @endif

    <!-- Modern UI Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/backend/css/modern-custom.css') }}">

    <!-- DataTables & Notification Styles -->
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @stack('css')
</head>
<body class="h-full antialiased transition-colors duration-200">
    <div class="min-h-full flex flex-col">
        <!-- Modern Sidebar Navigation -->
        @include('layouts.partials.sidebar')

        <!-- Main Content Area -->
        <div class="lg:pl-72 flex flex-col flex-1 min-h-screen">
            <!-- Modern Top Navbar -->
            @include('layouts.partials.navbar')

            <!-- Main Content Slot -->
            <main class="flex-1 w-full max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
                <!-- Flash Notification Banner -->
                @if (session('success'))
                    <x-alert variant="success" title="Berhasil">
                        {{ session('success') }}
                    </x-alert>
                @endif
                @if (session('error'))
                    <x-alert variant="danger" title="Terjadi Kesalahan">
                        {{ session('error') }}
                    </x-alert>
                @endif

                @yield('content')
            </main>

            <!-- Modern Footer -->
            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- General JS Scripts (Preserving all existing libraries for full compatibility) -->
    <script src="{{ asset('assets/backend/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/tooltip.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/moment.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Core Layout Interactivity (Vanilla JS) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Theme toggle
            const themeToggleBtn = document.getElementById('theme-toggle');
            const lightIcon = document.getElementById('theme-toggle-light-icon');
            const darkIcon = document.getElementById('theme-toggle-dark-icon');

            function syncThemeIcons() {
                const isDark = document.documentElement.classList.contains('dark');
                if (lightIcon && darkIcon) {
                    if (isDark) {
                        lightIcon.classList.remove('hidden');
                        darkIcon.classList.add('hidden');
                    } else {
                        lightIcon.classList.add('hidden');
                        darkIcon.classList.remove('hidden');
                    }
                }
            }
            syncThemeIcons();

            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    syncThemeIcons();
                    window.dispatchEvent(new CustomEvent('themeChanged', { detail: { isDark } }));
                });
            }

            // Mobile sidebar drawer
            const sidebar = document.getElementById('main-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const openBtn = document.getElementById('mobile-sidebar-toggle');
            const closeBtn = document.getElementById('mobile-sidebar-close');

            function openSidebar() {
                if (sidebar) sidebar.classList.remove('-translate-x-full');
                if (backdrop) backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            function closeSidebar() {
                if (sidebar) sidebar.classList.add('-translate-x-full');
                if (backdrop) backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (openBtn) openBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // Accordion Dropdowns
            const dropdownBtns = document.querySelectorAll('.sidebar-dropdown-btn');
            dropdownBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const group = this.closest('.sidebar-dropdown-group');
                    const submenu = group.querySelector('.dropdown-submenu');
                    const arrow = group.querySelector('.dropdown-arrow');
                    const isExpanded = !submenu.classList.contains('hidden');
                    if (isExpanded) {
                        submenu.classList.add('hidden');
                        arrow.classList.remove('rotate-90');
                    } else {
                        submenu.classList.remove('hidden');
                        arrow.classList.add('rotate-90');
                    }
                });
            });

            document.querySelectorAll('.dropdown-submenu').forEach(sub => {
                if (sub.querySelector('.font-semibold')) {
                    sub.classList.remove('hidden');
                    const arrow = sub.closest('.sidebar-dropdown-group')?.querySelector('.dropdown-arrow');
                    if (arrow) arrow.classList.add('rotate-90');
                }
            });

            // User Profile Menu
            const userMenuBtn = document.getElementById('user-menu-button');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');
            if (userMenuBtn && userMenuDropdown) {
                userMenuBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    userMenuDropdown.classList.toggle('hidden');
                    const notifDropdown = document.getElementById('notification-dropdown');
                    if (notifDropdown) notifDropdown.classList.add('hidden');
                });
                document.addEventListener('click', function (e) {
                    if (!userMenuDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
                        userMenuDropdown.classList.add('hidden');
                    }
                });
            }

            // Notification Bell Menu & Realtime Fetch
            const notifBtn = document.getElementById('notification-toggle-btn');
            const notifDropdown = document.getElementById('notification-dropdown');
            const notifBadge = document.getElementById('nav-notif-badge');
            const notifList = document.getElementById('nav-notif-list');

            function loadNavNotifications() {
                fetch('{{ route('notifications.navbar') }}')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (data.unread_count > 0) {
                                if (notifBadge) {
                                    notifBadge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
                                    notifBadge.classList.remove('hidden');
                                    notifBadge.classList.add('flex');
                                }
                            } else {
                                if (notifBadge) {
                                    notifBadge.classList.add('hidden');
                                    notifBadge.classList.remove('flex');
                                }
                            }

                            if (!notifList) return;
                            if (data.notifications && data.notifications.length > 0) {
                                let html = '';
                                data.notifications.forEach(item => {
                                    let icon = 'fa-bell text-slate-500';
                                    if (item.type === 'sk_pengurus') icon = 'fa-stamp text-sky-500';
                                    else if (item.type === 'anggota') icon = 'fa-user-check text-emerald-500';
                                    else if (item.type === 'expired_sio') icon = 'fa-exclamation-triangle text-amber-500';

                                    let unreadDot = !item.is_read ? '<span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>' : '';
                                    let bg = !item.is_read ? 'bg-emerald-50/40 dark:bg-emerald-950/30' : 'hover:bg-slate-50 dark:hover:bg-slate-800/50';

                                    html += `
                                        <a href="${item.read_url}" class="p-3 block transition-colors ${bg}">
                                            <div class="flex items-start gap-2.5">
                                                <i class="fas ${icon} mt-0.5 text-xs shrink-0"></i>
                                                <div class="space-y-0.5 flex-1 min-w-0">
                                                    <div class="flex items-center justify-between gap-1">
                                                        <p class="font-bold text-slate-800 dark:text-white truncate text-[11px]">${item.title}</p>
                                                        ${unreadDot}
                                                    </div>
                                                    <p class="text-slate-500 dark:text-slate-400 line-clamp-2 text-[10px] leading-tight">${item.message}</p>
                                                    <span class="text-[9px] text-slate-400 block pt-0.5">${item.time_ago}</span>
                                                </div>
                                            </div>
                                        </a>
                                    `;
                                });
                                notifList.innerHTML = html;
                            } else {
                                notifList.innerHTML = `
                                    <div class="p-6 text-center text-slate-400">
                                        <i class="fas fa-check-circle text-emerald-500 text-lg mb-1 block"></i>
                                        <p class="text-xs font-semibold">Tidak ada notifikasi baru</p>
                                    </div>
                                `;
                            }
                        }
                    })
                    .catch(() => {});
            }

            if (notifBtn && notifDropdown) {
                notifBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    notifDropdown.classList.toggle('hidden');
                    if (userMenuDropdown) userMenuDropdown.classList.add('hidden');
                    loadNavNotifications();
                });
                document.addEventListener('click', function (e) {
                    if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
                        notifDropdown.classList.add('hidden');
                    }
                });
                loadNavNotifications();
            }
        });
    </script>

    <!-- Custom View JS -->
    @stack('js')
    @stack('scripts')

    <!-- Global DataTable Clinic Name Auto-Highlighter -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.$) {
                $(document).on('init.dt draw.dt', function (e, settings) {
                    if (!settings || !settings.aoColumns) return;
                    settings.aoColumns.forEach(function (col, idx) {
                        var title = (col.sTitle || '').toLowerCase();
                        var name = (col.name || '').toLowerCase();
                        var data = (typeof col.data === 'string' ? col.data : '').toLowerCase();
                        if (name === 'nama_klinik' || data === 'nama_klinik' || title.includes('nama klinik')) {
                            $(settings.nTable).find('tbody tr').each(function () {
                                var $td = $(this).children('td').eq(idx);
                                if ($td.length && !$td.hasClass('nama-klinik-highlight')) {
                                    $td.addClass('nama-klinik-highlight');
                                }
                            });
                        }
                    });
                });
            }
        });
    </script>

    <!-- Global Modal Resilience & Strict Dismissal Controller -->
    <script>
        (function() {
            function initModalController() {
                var $ = window.jQuery;
                if (!$) return;

                // 1. Force global defaults to static backdrop & no keyboard escape
                if ($.fn && $.fn.modal && $.fn.modal.Constructor) {
                    if ($.fn.modal.Constructor.Default) {
                        $.fn.modal.Constructor.Default.backdrop = 'static';
                        $.fn.modal.Constructor.Default.keyboard = false;
                    }
                    if ($.fn.modal.Constructor.DEFAULTS) {
                        $.fn.modal.Constructor.DEFAULTS.backdrop = 'static';
                        $.fn.modal.Constructor.DEFAULTS.keyboard = false;
                    }
                }

                // 2. Lock every modal that opens to static backdrop (cannot close by clicking outside)
                $(document).on('show.bs.modal', '.modal', function () {
                    var $modal = $(this);
                    $modal.attr('data-backdrop', 'static');
                    $modal.attr('data-keyboard', 'false');
                    $modal.attr('data-bs-backdrop', 'static');
                    $modal.attr('data-bs-keyboard', 'false');

                    var modalData = $modal.data('bs.modal');
                    if (modalData && modalData._config) {
                        modalData._config.backdrop = 'static';
                        modalData._config.keyboard = false;
                    }
                });

                // 3. Reliable Close & 'X' button handler
                $(document).on('click', '[data-dismiss="modal"], [data-bs-dismiss="modal"], .modal .close, .modal .btn-close, .modal button[data-dismiss="modal"], .modal a[data-dismiss="modal"]', function (e) {
                    e.preventDefault();
                    var $modal = $(this).closest('.modal');
                    if ($modal.length) {
                        // Method A: Bootstrap 4 jQuery modal hide
                        try {
                            $modal.modal('hide');
                        } catch (_) {}

                        // Method B: Bootstrap 5 Vanilla instance hide
                        try {
                            if (window.bootstrap && window.bootstrap.Modal) {
                                var bsInstance = window.bootstrap.Modal.getInstance($modal[0]);
                                if (bsInstance) {
                                    bsInstance.hide();
                                }
                            }
                        } catch (_) {}

                        // Method C: Clean up state & backdrop if stuck
                        setTimeout(function () {
                            if (!$modal.hasClass('show')) {
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open').css('padding-right', '');
                            }
                        }, 250);
                    }
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initModalController);
            } else {
                initModalController();
            }
        })();
    </script>
</body>
</html>
