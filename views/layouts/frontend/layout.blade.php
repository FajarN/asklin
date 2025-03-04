<!DOCTYPE html>
<html dir="ltr" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.frontend.head')

<body class="stretched">
    @include('layouts.frontend.header')

    <div id="content-index">
        @yield('content')
        @include('layouts.frontend.footer')
    </div>

    <div id="gotoTop" class="icon-angle-up"></div>

    <script src="{{ asset('assets/frontend/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/plugins.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/leaflet/revolution.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/functions.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/show-pass.js') }}"></script>
    @stack('js')
</body>

</html>
