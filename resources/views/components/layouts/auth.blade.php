<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Cratory — Invoice & Inventory Management for your business">

    <title>{{ $title ?? 'Cratory' }} — Cratory</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased">
    <div class="min-h-dvh flex flex-col">
        <!-- Auth content centered -->
        <main class="flex-1 flex items-center justify-center px-4 py-8">
            {{ $slot }}
        </main>

        <!-- Subtle footer -->
        <footer class="text-center py-4 text-text-muted text-xs">
            &copy; {{ date('Y') }} Cratory Inc. All rights reserved. <span class="mx-1">|</span> Developed with &hearts; by YB
        </footer>
    </div>

    @livewireScripts
</body>
</html>
