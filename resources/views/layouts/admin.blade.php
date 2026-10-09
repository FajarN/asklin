<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — ASKLIN</title>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Compiled Assets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Modern UI Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/backend/css/modern-custom.css') }}">

    @stack('css')
</head>
<body class="h-full bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans antialiased selection:bg-emerald-500 selection:text-white transition-colors duration-200">
    <div class="min-h-full flex flex-col">
        <!-- Sidebar Navigation -->
        @include('layouts.partials.sidebar')

        <!-- Main Content Column -->
        <div class="lg:pl-72 flex flex-col flex-1 min-h-screen">
            <!-- Top Navbar -->
            @include('layouts.partials.navbar')

            <!-- Main Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">
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
                @if (session('warning'))
                    <x-alert variant="warning" title="Peringatan">
                        {{ session('warning') }}
                    </x-alert>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            @include('layouts.partials.footer')
        </div>
    </div>

    <!-- Core Layout Interactivity (Vanilla JS) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- 1. DARK MODE TOGGLE ---
            const themeToggleBtn = document.getElementById('theme-toggle');
            const lightIcon = document.getElementById('theme-toggle-light-icon');
            const darkIcon = document.getElementById('theme-toggle-dark-icon');

            function syncThemeIcons() {
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) {
                    lightIcon.classList.remove('hidden');
                    darkIcon.classList.add('hidden');
                } else {
                    lightIcon.classList.add('hidden');
                    darkIcon.classList.remove('hidden');
                }
            }
            syncThemeIcons();

            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    syncThemeIcons();
                    // Dispatch event for chart re-renders
                    window.dispatchEvent(new CustomEvent('themeChanged', { detail: { isDark } }));
                });
            }

            // --- 2. MOBILE SIDEBAR DRAWER ---
            const sidebar = document.getElementById('main-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const openBtn = document.getElementById('mobile-sidebar-toggle');
            const closeBtn = document.getElementById('mobile-sidebar-close');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (openBtn) openBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);

            // --- 3. SIDEBAR ACCORDION DROPDOWNS ---
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

            // Automatically open dropdown if contains active route
            document.querySelectorAll('.dropdown-submenu').forEach(sub => {
                if (sub.querySelector('.font-semibold')) {
                    sub.classList.remove('hidden');
                    const arrow = sub.closest('.sidebar-dropdown-group')?.querySelector('.dropdown-arrow');
                    if (arrow) arrow.classList.add('rotate-90');
                }
            });

            // --- 4. USER PROFILE DROPDOWN ---
            const userMenuBtn = document.getElementById('user-menu-button');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');
            if (userMenuBtn && userMenuDropdown) {
                userMenuBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    userMenuDropdown.classList.toggle('hidden');
                });
                document.addEventListener('click', function (e) {
                    if (!userMenuDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
                        userMenuDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    @stack('js')

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
</body>
</html>

