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

        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

        <!-- Leaflet JS (antes do Alpine, para que initMap() encontre L) -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- reCAPTCHA v3 (invisível — sem badge visual intrusivo) -->
        @if(config('services.recaptcha.site_key'))
            <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}" async defer></script>
            <script>window.recaptchaSiteKey = '{{ config('services.recaptcha.site_key') }}';</script>
        @endif
    </head>
    <body class="font-sans text-zinc-100 antialiased bg-surface-darker selection:bg-blue-500/30">
        <div class="min-h-screen flex flex-col items-center w-full">
            {{ $slot }}
        </div>
    </body>
</html>
