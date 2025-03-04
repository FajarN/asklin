<head>
    <title>{{ setting('nama_aplikasi') }}</title>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('storage/' . setting('favicon')) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('storage/' . setting('favicon')) }}" type="image/x-icon">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/fontawesome/css/all.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/backend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/chocolat/dist/css/chocolat.css') }}">


    <!-- Custom CSS -->
    @stack('css')
</head>
