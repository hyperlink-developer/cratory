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
    <nav class="relative z-50 w-full fixed top-0 transition-all duration-300 backdrop-blur-xl border-b border-white/5 bg-surface/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex shrink-0 items-center gap-3 group">
                    <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-accent p-[1px] group-hover:shadow-[0_0_20px_rgba(245,158,11,0.4)] transition-shadow">
                        <div class="w-full h-full bg-surface rounded-xl flex items-center justify-center">
                            <img src="{{ asset('logo.png') }}" alt="Cratory Logo" class="h-6 w-6 object-contain">
                        </div>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white group-hover:text-primary transition-colors">Cratory</span>
                </a>
                
                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-8 bg-white/5 px-6 py-2 rounded-full border border-white/10 backdrop-blur-md">
                    <a href="{{ url('/#features') }}" class="text-sm font-medium text-text-secondary hover:text-white transition-colors">Features</a>
                    <a href="{{ url('/#pricing') }}" class="text-sm font-medium text-text-secondary hover:text-white transition-colors">Pricing</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn bg-white/10 hover:bg-white/20 text-white border border-white/10 shadow-lg backdrop-blur-md rounded-xl">Go to Dashboard <span aria-hidden="true">&rarr;</span></a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:block text-sm font-semibold text-text-secondary hover:text-white transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn bg-gradient-to-r from-primary to-accent hover:from-primary-light hover:to-accent-light text-white shadow-[0_0_20px_rgba(245,158,11,0.3)] hover:shadow-[0_0_30px_rgba(245,158,11,0.5)] border-0 transition-all duration-300 transform hover:-translate-y-0.5 rounded-xl">Get Started - Free</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-24 lg:pt-32">
        {{ $slot }}
    </main>

    <!-- Modern Footer -->
    <footer class="relative z-10 border-t border-white/10 bg-surface pt-16 pb-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-2">
                    <a href="/" class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('logo.png') }}" alt="Cratory Logo" class="h-8 w-8 object-contain grayscale opacity-70">
                        <span class="text-2xl font-bold text-white">Cratory</span>
                    </a>
                    <p class="text-text-muted max-w-sm mb-6">Smart invoicing and inventory control platform built for modern, fast-moving businesses.</p>
                </div>

                <!-- Links -->
                <div>
                    <h5 class="text-white font-semibold mb-4">Product</h5>
                    <ul class="space-y-3">
                        <li><a href="{{ url('/#features') }}" class="text-text-muted hover:text-primary transition-colors text-sm">Features</a></li>
                        <li><a href="{{ url('/#pricing') }}" class="text-text-muted hover:text-primary transition-colors text-sm">Pricing</a></li>
                        <li><a href="{{ route('changelog') }}" class="text-text-muted hover:text-primary transition-colors text-sm">Changelog</a></li>
                        <li><a href="{{ route('integrations') }}" class="text-text-muted hover:text-primary transition-colors text-sm">Integrations</a></li>
                    </ul>
                </div>
                
                <div>
                    <h5 class="text-white font-semibold mb-4">Legal & Support</h5>
                    <ul class="space-y-3">
                        <li><a href="{{ route('privacy-policy') ?? '#' }}" class="text-text-muted hover:text-primary transition-colors text-sm">Privacy Policy</a></li>
                        <li><a href="{{ route('terms-of-service') ?? '#' }}" class="text-text-muted hover:text-primary transition-colors text-sm">Terms of Service</a></li>
                        <li><a href="{{ route('contact-support') ?? 'mailto:cratory.support@yagneshbhanani.com' }}" class="text-text-muted hover:text-primary transition-colors text-sm">Contact Support</a></li>
                        <li><a href="{{ route('help-center') }}" class="text-text-muted hover:text-primary transition-colors text-sm">Help Center</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-text-muted/70">
                    &copy; {{ date('Y') }} Cratory Inc. All rights reserved. <span class="mx-1">|</span> Developed with &hearts; by The Late Night Artisan (Yagnesh Bhanani)
                </p>
                <div class="flex items-center gap-2 text-xs text-text-muted/70">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                    All systems operational
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
