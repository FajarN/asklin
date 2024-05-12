<head>
    <title>{{ setting('nama_aplikasi', 'ASKLIN') . ' - ' . $title }}</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="Asosiasi Klinik Indonesia" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
        href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Poppins:300,400,500,600,700|PT+Serif:400,400i&display=swap"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/bootstrap.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/style.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/swiper.css') }}" type="text/css" />
    <!--<link rel="stylesheet" href="{{ asset('assets/frontend/css/dark.css') }}" type="text/css" />-->
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/font-icons.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/animate.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/magnific-popup.css') }}" type="text/css" />

    <link rel="stylesheet" href="{{ asset('assets/frontend/css/custom.css') }}" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link href="{{ asset('assets/frontend/js/leaflet/leaflet.css') }}" type="text/css" id="color"
        rel="stylesheet" />
    <script src="{{ asset('assets/frontend/js/leaflet/leaflet.js') }}"></script>



    <style>
        #map {
            height: 300px;
        }
    </style>
    @stack('css')
</head>
