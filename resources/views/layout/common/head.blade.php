<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $appTitle }} @yield('subtitle')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    @include('layout.common.css')
    @stack('styles')
</head>
