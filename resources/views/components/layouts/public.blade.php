<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Cratory' }}</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-surface text-text-primary font-sans antialiased selection:bg-primary/30 min-h-screen flex flex-col relative overflow-x-hidden">
    
    <!-- Navigation -->
    <nav class="relative z-50 w-full backdrop-blur-xl border-b border-white/5 bg-surface/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex shrink-0 items-center gap-3">
                    <img src="{{ asset('logo.png') }}" alt="Cratory Logo" class="h-10 w-10 object-contain">
                    <span class="text-xl font-bold tracking-tight text-white">Cratory</span>
                </a>
                
                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ url('/#features') }}" class="text-sm font-medium text-text-secondary hover:text-primary transition-colors">Features</a>
                    <a href="{{ url('/#pricing') }}" class="text-sm font-medium text-text-secondary hover:text-primary transition-colors">Pricing</a>
                    <a href="{{ url('/#contact') }}" class="text-sm font-medium text-text-secondary hover:text-primary transition-colors">Contact</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-white hover:text-primary transition-colors">Dashboard &rarr;</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:block text-sm font-semibold text-text-primary hover:text-primary transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary shadow-lg shadow-primary/25">Get Started Free</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-white/10 bg-surface-light pt-12 pb-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('logo.png') }}" alt="Cratory Logo" class="h-8 w-8 object-contain grayscale opacity-70">
                    <span class="text-lg font-bold text-text-muted">Cratory</span>
                </div>
                <div class="flex gap-6 text-sm text-text-muted">
                    <a href="{{ route('privacy-policy') }}" class="hover:text-primary transition-colors">Privacy Policy</a>
                    <a href="{{ route('terms-of-service') }}" class="hover:text-primary transition-colors">Terms of Service</a>
                    <a href="{{ route('contact-support') }}" class="hover:text-primary transition-colors">Contact Support</a>
                </div>
            </div>
            <p class="text-sm text-text-muted/70 text-center mt-8">
                &copy; {{ date('Y') }} Cratory Inc. All rights reserved. <span class="mx-1">|</span> Developed with &hearts; by YB
            </p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
