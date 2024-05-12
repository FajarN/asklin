<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.backend.head')

<body>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            @include('layouts.backend.header')
            @include('layouts.backend.menu')
            @yield('content')
            @include('layouts.backend.footer')
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('assets/backend/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/tooltip.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/moment.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/stisla.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('assets/backend/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/backend/js/custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JS -->
    @stack('js')
</body>

</html>
