<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Asosiasi Klinik Indonesia (ASKLIN)' }} - Website Resmi</title>
    <meta name="description" content="{{ $description ?? 'Website resmi Asosiasi Klinik Indonesia (ASKLIN).' }}">

    <!-- Favicon -->
    @if(setting('favicon') && file_exists(public_path('storage/' . setting('favicon'))))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('favicon')) }}">
    @else
        <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_asklin.png') }}">
    @endif

    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Instant Dark Mode Init (Zero FOUC) -->
    <script>
        (function () {
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

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Template 2 Dedicated Isolated Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/templates/custom_org/css/style.css') }}?v={{ file_exists(public_path('assets/frontend/templates/custom_org/css/style.css')) ? filemtime(public_path('assets/frontend/templates/custom_org/css/style.css')) : time() }}">

    @stack('css')
</head>
<body class="custom-org-app">

    <!-- Preview Mode Notice (Only shown when admin previews) -->
    @if(\App\Services\TemplateService::isPreviewMode())
        <div class="org-preview-bar">
            <div class="org-preview-bar-inner">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-eye"></i>
                    <span><strong>Mode Pratinjau Aktif:</strong> Anda sedang melihat <strong>Template 2 — Custom Organization</strong> (Tampilan ini hanya terlihat oleh akun admin Anda).</span>
                </div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <a href="{{ route('template_settings.index') }}" style="background: rgba(255,255,255,0.2); color: #fff; padding: 4px 10px; border-radius: 4px; font-weight: 700; text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Pengaturan Template
                    </a>
                    <a href="{{ route('template_settings.exit_preview') }}" style="background: rgba(0,0,0,0.3); color: #fff; padding: 4px 10px; border-radius: 4px; text-decoration: none;">
                        <i class="fas fa-times"></i> Tutup Pratinjau
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Header -->
    @include('frontend.templates.custom_org.layouts.header')

    <!-- Main Dynamic Content -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- Main Footer -->
    @include('frontend.templates.custom_org.layouts.footer')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggle = document.getElementById('orgMobileToggle');
            var nav = document.getElementById('orgMainNav');
            if (toggle && nav) {
                toggle.addEventListener('click', function() {
                    nav.classList.toggle('open');
                    var icon = toggle.querySelector('i');
                    if (icon) {
                        if (nav.classList.contains('open')) {
                            icon.classList.remove('fa-bars');
                            icon.classList.add('fa-times');
                        } else {
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    }
                });
            }

            // Theme Toggle Logic
            var themeBtn = document.getElementById('themeToggleOrg');
            var themeBtnTop = document.getElementById('themeToggleOrgTop');

            function syncThemeUI() {
                var isDark = document.documentElement.classList.contains('dark');
                [themeBtn, themeBtnTop].forEach(function(btn) {
                    if (!btn) return;
                    var icon = btn.querySelector('i');
                    var text = btn.querySelector('.theme-text');
                    if (isDark) {
                        if (icon) { icon.className = 'fas fa-sun text-amber-400'; }
                        if (text) { text.textContent = 'Mode Terang'; }
                        btn.setAttribute('title', 'Beralih ke Mode Terang');
                    } else {
                        if (icon) { icon.className = 'fas fa-moon text-slate-600 dark:text-slate-300'; }
                        if (text) { text.textContent = 'Mode Gelap'; }
                        btn.setAttribute('title', 'Beralih ke Mode Gelap');
                    }
                });
            }

            function toggleTheme() {
                var isDark = document.documentElement.classList.toggle('dark');
                try {
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                } catch(e) {}
                syncThemeUI();
            }

            if (themeBtn) themeBtn.addEventListener('click', toggleTheme);
            if (themeBtnTop) themeBtnTop.addEventListener('click', toggleTheme);
            syncThemeUI();
        });
    </script>
    @stack('js')
</body>
</html>

