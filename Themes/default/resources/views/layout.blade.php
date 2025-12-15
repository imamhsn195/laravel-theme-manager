<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @if(config('theme-manager.asset_path'))
        <link rel="stylesheet" href="{{ asset(config('theme-manager.asset_path') . '/default/css/app.css') }}">
    @endif

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('theme-default::partials.header')

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        @include('theme-default::partials.footer')
    </div>

    <!-- Scripts -->
    @if(config('theme-manager.asset_path'))
        <script src="{{ asset(config('theme-manager.asset_path') . '/default/js/app.js') }}"></script>
    @endif

    @stack('scripts')
</body>
</html>

