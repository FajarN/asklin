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

    <!-- Compiled Tailwind CSS & App JS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Modernization Adapter Styles -->
    <style>
        /* Smooth base */
        body {
            font-family: 'Plus Jakarta Sans', Inter, system-ui, sans-serif !important;
            background-color: #f8fafc !important;
            color: #1e293b !important;
        }
        .dark body {
            background-color: #020617 !important;
            color: #f1f5f9 !important;
        }

        /* Override old Stisla layout wrappers */
        .main-wrapper, .main-wrapper-1, #app {
            min-height: 100vh !important;
            background: transparent !important;
        }
        .main-content {
            padding: 1.5rem !important;
            width: 100% !important;
            position: static !important;
            min-height: calc(100vh - 8rem) !important;
        }
        @media (min-width: 1024px) {
            .main-content {
                padding: 2rem !important;
            }
        }
        .navbar-bg, .main-sidebar, .main-navbar, nav.navbar {
            display: none !important;
        }
        .section-header {
            display: none !important; /* Managed by top navbar */
        }
        .section-body {
            margin-top: 0 !important;
        }

        /* Modernize Cards */
        .card {
            border-radius: 1rem !important;
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
            background: #ffffff !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05) !important;
            margin-bottom: 1.5rem !important;
            overflow: hidden !important;
        }
        .dark .card {
            background: #0f172a !important;
            border-color: #1e293b !important;
            color: #f8fafc !important;
        }
        .card-header {
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 1.125rem 1.5rem !important;
            background: transparent !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }
        .dark .card-header {
            border-bottom-color: #1e293b !important;
        }
        .card-header h4 {
            font-size: 1rem !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            letter-spacing: -0.02em !important;
            margin-bottom: 0 !important;
        }
        .dark .card-header h4 {
            color: #f8fafc !important;
        }
        .card-body {
            padding: 1.5rem !important;
        }

        /* Modernize Buttons */
        .btn {
            font-weight: 600 !important;
            border-radius: 0.75rem !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.8125rem !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.375rem !important;
            transition: all 0.15s ease-in-out !important;
        }
        .btn-sm {
            padding: 0.35rem 0.75rem !important;
            font-size: 0.75rem !important;
            border-radius: 0.5rem !important;
        }
        .btn-primary {
            background-color: #059669 !important;
            border-color: #059669 !important;
            color: #ffffff !important;
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #047857 !important;
            border-color: #047857 !important;
            transform: translateY(-1px) !important;
        }
        .btn-info {
            background-color: #0284c7 !important;
            border-color: #0284c7 !important;
            color: #ffffff !important;
        }
        .btn-info:hover {
            background-color: #0369a1 !important;
        }
        .btn-danger {
            background-color: #e11d48 !important;
            border-color: #e11d48 !important;
            color: #ffffff !important;
        }
        .btn-danger:hover {
            background-color: #be123c !important;
        }
        .btn-warning {
            background-color: #d97706 !important;
            border-color: #d97706 !important;
            color: #ffffff !important;
        }
        .btn-secondary {
            background-color: #f1f5f9 !important;
            border-color: #e2e8f0 !important;
            color: #475569 !important;
        }
        .dark .btn-secondary {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
        }

        /* Modernize Forms */
        .form-control, .form-select, select.form-control {
            border-radius: 0.75rem !important;
            border: 1px solid #cbd5e1 !important;
            padding: 0.5rem 0.875rem !important;
            font-size: 0.8125rem !important;
            background-color: #ffffff !important;
            color: #0f172a !important;
            transition: all 0.15s ease !important;
        }
        .form-control:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
        }
        .dark .form-control, .dark .form-select, .dark select.form-control {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
        }
        label, .form-label {
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            color: #64748b !important;
            margin-bottom: 0.375rem !important;
        }
        .dark label, .dark .form-label {
            color: #94a3b8 !important;
        }

        /* Modernize DataTables */
        .table {
            color: #334155 !important;
            font-size: 0.8125rem !important;
            margin-bottom: 0 !important;
        }
        .dark .table {
            color: #cbd5e1 !important;
        }
        .table thead th {
            background-color: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
            color: #64748b !important;
            font-size: 0.6875rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            padding: 0.875rem 1rem !important;
            border-top: none !important;
        }
        .dark .table thead th {
            background-color: #0f172a !important;
            border-bottom-color: #1e293b !important;
            color: #94a3b8 !important;
        }
        .table td {
            padding: 0.875rem 1rem !important;
            vertical-align: middle !important;
            border-top: 1px solid #f1f5f9 !important;
        }
        .dark .table td {
            border-top-color: #1e293b !important;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(248, 250, 252, 0.5) !important;
        }
        .dark .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(30, 41, 59, 0.3) !important;
        }
        .table tbody tr:hover {
            background-color: rgba(241, 245, 249, 0.8) !important;
        }
        .dark .table tbody tr:hover {
            background-color: rgba(30, 41, 59, 0.6) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #059669 !important;
            border-color: #059669 !important;
            color: #ffffff !important;
            border-radius: 0.5rem !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 0.5rem !important;
        }
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            font-size: 0.75rem !important;
            color: #64748b !important;
            padding: 0.75rem 0 !important;
        }
        .dark .dataTables_wrapper .dataTables_info,
        .dark .dataTables_wrapper .dataTables_length,
        .dark .dataTables_wrapper .dataTables_filter {
            color: #94a3b8 !important;
        }

        /* Modernize Modals */
        .modal-content {
            border-radius: 1.25rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
            overflow: hidden !important;
        }
        .dark .modal-content {
            background-color: #0f172a !important;
            border-color: #1e293b !important;
            color: #f8fafc !important;
        }
        .modal-header {
            padding: 1.25rem 1.5rem !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        .dark .modal-header {
            border-bottom-color: #1e293b !important;
        }
        .modal-footer {
            padding: 1rem 1.5rem !important;
            border-top: 1px solid #f1f5f9 !important;
        }
        .dark .modal-footer {
            border-top-color: #1e293b !important;
        }

        /* Modernize Badges & Alerts */
        .badge {
            border-radius: 9999px !important;
            font-weight: 600 !important;
            padding: 0.35rem 0.65rem !important;
            font-size: 0.6875rem !important;
            letter-spacing: 0.02em !important;
        }
        .alert {
            border-radius: 1rem !important;
            border: 1px solid transparent !important;
        }
        .alert-secondary {
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #475569 !important;
        }
        .dark .alert-secondary {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
        }
    </style>

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
            <main class="flex-1 w-full max-w-7xl mx-auto">
                <!-- Flash Notification Banner -->
                @if (session('success'))
                    <div class="p-4 sm:p-6 pb-0">
                        <x-alert variant="success" title="Berhasil">
                            {{ session('success') }}
                        </x-alert>
                    </div>
                @endif
                @if (session('error'))
                    <div class="p-4 sm:p-6 pb-0">
                        <x-alert variant="danger" title="Terjadi Kesalahan">
                            {{ session('error') }}
                        </x-alert>
                    </div>
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
                });
                document.addEventListener('click', function (e) {
                    if (!userMenuDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
                        userMenuDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    <!-- Custom View JS -->
    @stack('js')
</body>
</html>
