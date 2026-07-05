<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cratory — Smart Invoicing & Inventory</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface text-text-primary font-sans antialiased selection:bg-primary/30 min-h-screen flex flex-col relative overflow-x-hidden">
    
    <!-- Background Glow Effects -->
    <div class="fixed top-0 inset-x-0 h-[500px] pointer-events-none z-0">
        <div class="absolute inset-0 bg-gradient-to-b from-primary/10 via-transparent to-transparent"></div>
        <div class="absolute -top-[200px] -left-[200px] w-[600px] h-[600px] bg-accent/20 rounded-full blur-[120px] opacity-50"></div>
        <div class="absolute top-[100px] -right-[100px] w-[500px] h-[500px] bg-primary/15 rounded-full blur-[100px] opacity-40"></div>
    </div>

    <!-- Navigation -->
    <nav class="relative z-50 w-full backdrop-blur-xl border-b border-white/5 bg-surface/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex shrink-0 items-center gap-3">
                    <img src="{{ asset('logo.png') }}" alt="Cratory Logo" class="h-10 w-10 object-contain">
                    <span class="text-xl font-bold tracking-tight text-white">Cratory</span>
                </div>
                
                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm font-medium text-text-secondary hover:text-primary transition-colors">Features</a>
                    <a href="#pricing" class="text-sm font-medium text-text-secondary hover:text-primary transition-colors">Pricing</a>
                    <a href="#contact" class="text-sm font-medium text-text-secondary hover:text-primary transition-colors">Contact</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-white hover:text-primary transition-colors">Go to Dashboard &rarr;</a>
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

    <!-- Hero Section -->
    <main class="relative z-10 flex-grow flex flex-col justify-center pt-16 lg:pt-24 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Hero Text -->
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 mb-8 backdrop-blur-sm">
                    <span class="flex h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                    <span class="text-xs font-medium text-text-secondary">Cratory v1.0 is now live</span>
                </div>
                
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-white tracking-tight leading-tight mb-6">
                    Smart Invoicing & <br class="hidden lg:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-accent">Inventory Control</span>
                </h1>
                
                <p class="text-lg md:text-xl text-text-muted mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Streamline your financial workflow, track inventory in real-time, and manage clients effortlessly with a modern dashboard built for speed.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary w-full sm:w-auto px-8 py-4 text-base shadow-xl shadow-primary/20">Open Dashboard</a>
                    @else
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary w-full sm:w-auto px-8 py-4 text-base shadow-xl shadow-primary/20">Start 14-Day Free Trial</a>
                        @endif
                        <a href="#demo" class="btn glass-card w-full sm:w-auto px-8 py-4 text-base hover:bg-white/10 transition-colors border-white/10">View Live Demo</a>
                    @endauth
                </div>
                
                <p class="mt-6 text-sm text-text-muted flex items-center justify-center lg:justify-start gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    No credit card required
                </p>
            </div>

            <!-- Hero Image -->
            <div class="relative w-full max-w-2xl mx-auto perspective-1000">
                <div class="relative rounded-2xl overflow-hidden glass-card border border-white/10 shadow-2xl shadow-accent/20 transform rotate-y-[-5deg] rotate-x-[5deg] hover:rotate-0 transition-transform duration-700 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-tr from-accent/10 to-primary/5"></div>
                    <img src="{{ asset('hero.png') }}" alt="Cratory Dashboard" class="w-full object-cover relative z-10">
                </div>
            </div>
        </div>
    </main>

    <!-- Features Section -->
    <section id="features" class="relative z-10 py-24 bg-surface-light border-y border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-sm font-semibold text-primary uppercase tracking-widest mb-2">Features</h2>
                <h3 class="text-3xl md:text-4xl font-bold text-white">Everything you need to run your business</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-card p-8 rounded-2xl hover:-translate-y-2 transition-transform duration-300 border border-white/5 hover:border-primary/30">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3">Lightning Fast Invoicing</h4>
                    <p class="text-text-muted leading-relaxed">Create, send, and track professional invoices in seconds. Get paid faster with integrated payment gateways and automatic reminders.</p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card p-8 rounded-2xl hover:-translate-y-2 transition-transform duration-300 border border-white/5 hover:border-accent/30">
                    <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3">Real-time Inventory</h4>
                    <p class="text-text-muted leading-relaxed">Track stock levels across multiple locations. Get automatic low-stock alerts and generate instant purchase orders.</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card p-8 rounded-2xl hover:-translate-y-2 transition-transform duration-300 border border-white/5 hover:border-primary/30">
                    <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3">CRM & Contacts</h4>
                    <p class="text-text-muted leading-relaxed">Maintain a complete database of customers and vendors. View transaction history, outstanding balances, and contact details instantly.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="relative z-10 py-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-3xl md:text-4xl font-bold text-white mb-4">Simple, transparent pricing</h3>
                <p class="text-lg text-text-muted">Start for free, upgrade when you need more power.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Free Plan -->
                <div class="glass-card p-8 rounded-3xl border border-white/5 flex flex-col">
                    <h4 class="text-2xl font-bold text-white mb-2">Starter</h4>
                    <p class="text-text-muted mb-6">Perfect for freelancers and small teams.</p>
                    <div class="mb-8">
                        <span class="text-5xl font-extrabold text-white">$0</span>
                        <span class="text-text-muted">/ forever</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center gap-3 text-text-primary">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Up to 50 Invoices/month
                        </li>
                        <li class="flex items-center gap-3 text-text-primary">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Basic Inventory tracking
                        </li>
                        <li class="flex items-center gap-3 text-text-primary">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            1 User Account
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn w-full bg-surface-lighter hover:bg-white/10 text-white py-3 border border-white/10">Get Started</a>
                </div>

                <!-- Pro Plan -->
                <div class="glass-card p-8 rounded-3xl border border-primary/50 relative flex flex-col shadow-2xl shadow-primary/10 transform md:-translate-y-4">
                    <div class="absolute top-0 right-8 transform -translate-y-1/2">
                        <span class="bg-primary text-white text-xs font-bold uppercase tracking-wider py-1 px-3 rounded-full">Most Popular</span>
                    </div>
                    <h4 class="text-2xl font-bold text-white mb-2">Business Pro</h4>
                    <p class="text-text-muted mb-6">Advanced features for growing businesses.</p>
                    <div class="mb-8">
                        <span class="text-5xl font-extrabold text-white">$29</span>
                        <span class="text-text-muted">/ month</span>
                    </div>
                    <ul class="space-y-4 mb-8 flex-grow">
                        <li class="flex items-center gap-3 text-text-primary">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Unlimited Invoices
                        </li>
                        <li class="flex items-center gap-3 text-text-primary">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Multi-location Inventory
                        </li>
                        <li class="flex items-center gap-3 text-text-primary">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Unlimited User Accounts
                        </li>
                        <li class="flex items-center gap-3 text-text-primary">
                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Priority Support
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-primary w-full py-3 shadow-lg shadow-primary/20">Start Free Trial</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="relative z-10 py-24 bg-surface border-t border-white/5">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-3xl md:text-4xl font-bold text-white mb-6">Get in touch</h3>
            <p class="text-lg text-text-muted mb-10">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
            <a href="mailto:support@cratory.com" class="btn btn-primary px-8 py-4 text-base shadow-xl shadow-primary/20">Contact Support</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-white/10 bg-surface-light pt-12 pb-8">
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
            <div class="mt-8 text-center text-xs text-text-muted/50">
                &copy; {{ date('Y') }} Cratory. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
