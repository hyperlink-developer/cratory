<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cratory — Smart Invoicing & Inventory</title>

    <!-- Google Fonts for Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface text-text-primary font-sans antialiased selection:bg-primary/30 min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Decorative Background Effects -->
    <div class="fixed top-0 inset-x-0 h-screen pointer-events-none z-0 overflow-hidden">
        <div class="absolute -top-[30%] -left-[10%] w-[70vw] h-[70vw] rounded-full bg-accent/20 blur-[150px] opacity-40 animate-pulse" style="animation-duration: 8s;"></div>
        <div class="absolute top-[20%] -right-[10%] w-[60vw] h-[60vw] rounded-full bg-primary/15 blur-[120px] opacity-40 animate-pulse" style="animation-duration: 10s;"></div>
        <div class="absolute bottom-[-20%] left-[20%] w-[50vw] h-[50vw] rounded-full bg-blue-500/10 blur-[100px] opacity-30"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjZmZmIiBmaWxsLW9wYWNpdHk9IjAuMDIiLz4KPC9zdmc+')] opacity-20"></div>
    </div>

    <!-- Navigation -->
    <nav class="relative z-50 w-full fixed top-0 transition-all duration-300 backdrop-blur-xl border-b border-white/5 bg-surface/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="/" class="flex shrink-0 items-center gap-3 group">
                    <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-accent p-[1px] group-hover:shadow-[0_0_20px_rgba(245,158,11,0.4)] transition-shadow">
                        <div class="w-full h-full bg-surface rounded-xl flex items-center justify-center">
                            <img src="{{ asset('logo.png') }}" alt="Cratory Logo" class="h-6 w-6 object-contain">
                        </div>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-white group-hover:text-primary transition-colors">Cratory</span>
                </a>
                
                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-8 bg-white/5 px-6 py-2 rounded-full border border-white/10 backdrop-blur-md">
                    <a href="#features" class="text-sm font-medium text-text-secondary hover:text-white transition-colors">Features</a>
                    <a href="#pricing" class="text-sm font-medium text-text-secondary hover:text-white transition-colors">Pricing</a>
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

    <!-- Hero Section -->
    <main class="relative z-10 flex-grow flex flex-col justify-center pt-32 lg:pt-40 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 mb-8 backdrop-blur-sm shadow-[0_0_15px_rgba(245,158,11,0.15)] transform hover:scale-105 transition-transform cursor-default">
                <span class="flex h-2.5 w-2.5 rounded-full bg-primary animate-pulse"></span>
                <span class="text-xs font-semibold text-primary uppercase tracking-wider">Cratory 2.0 is now live</span>
            </div>
            
            <!-- Headline -->
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold text-white tracking-tight leading-[1.1] mb-8 max-w-5xl mx-auto">
                Manage your business with <br class="hidden md:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary via-yellow-300 to-accent animate-gradient-x">Smart Precision.</span>
            </h1>
            
            <!-- Subheadline -->
            <p class="text-lg md:text-2xl text-text-muted mb-12 max-w-3xl mx-auto leading-relaxed">
                Streamline financial workflows, track inventory in real-time, and impress clients with lightning-fast invoicing built for modern teams.
            </p>
            
            <!-- CTAs -->
            <div class="flex flex-col sm:flex-row items-center gap-6 justify-center">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn bg-gradient-to-r from-primary to-accent hover:from-primary-light hover:to-accent-light text-white w-full sm:w-auto px-8 py-4 text-lg rounded-xl shadow-[0_0_30px_rgba(245,158,11,0.3)] hover:shadow-[0_0_40px_rgba(245,158,11,0.5)] border-0 transition-all duration-300 transform hover:-translate-y-1 font-bold">
                        Open Workspace
                        <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @else
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn bg-gradient-to-r from-primary to-accent hover:from-primary-light hover:to-accent-light text-white w-full sm:w-auto px-8 py-4 text-lg rounded-xl shadow-[0_0_30px_rgba(245,158,11,0.3)] hover:shadow-[0_0_40px_rgba(245,158,11,0.5)] border-0 transition-all duration-300 transform hover:-translate-y-1 font-bold">
                            Create Free Account
                        </a>
                    @endif
                    <a href="#demo" class="btn glass-card w-full sm:w-auto px-8 py-4 text-lg hover:bg-white/10 transition-colors border-white/10 rounded-xl font-medium group">
                        <svg class="w-5 h-5 mr-2 text-text-secondary group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                        Watch Demo
                    </a>
                @endauth
            </div>
            
            <p class="mt-6 text-sm text-text-muted flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                No credit card required. Cancel anytime.
            </p>

            <!-- Dashboard Mockup -->
            <div class="mt-24 relative w-full max-w-6xl mx-auto group">
                <div class="absolute -inset-1 bg-gradient-to-r from-primary to-accent rounded-3xl blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative rounded-2xl overflow-hidden bg-surface-light border border-white/10 shadow-2xl transition-transform duration-700 ease-out">
                    <div class="absolute top-0 inset-x-0 h-8 bg-surface/80 border-b border-white/5 flex items-center px-4 gap-2 backdrop-blur-sm z-20">
                        <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
                    </div>
                    <div class="pt-8 relative bg-surface">
                        <div class="absolute inset-0 bg-gradient-to-b from-accent/5 to-transparent z-10 pointer-events-none"></div>
                        <img src="{{ asset('cratory.png') }}" alt="Cratory Dashboard Interface" class="w-full h-auto object-cover relative z-0 opacity-90 transition-opacity duration-500 group-hover:opacity-100">
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Logo Strip / Social Proof -->
    <section class="relative z-10 py-12 border-y border-white/5 bg-surface/30 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm font-semibold text-text-muted uppercase tracking-widest mb-8">Trusted by forward-thinking teams worldwide</p>
            <div class="flex flex-wrap justify-center gap-8 md:gap-16 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
                <div class="flex items-center gap-2 text-xl font-bold text-white"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2z"/></svg> Acme Corp</div>
                <div class="flex items-center gap-2 text-xl font-bold text-white"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg> Globex</div>
                <div class="flex items-center gap-2 text-xl font-bold text-white"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2"/></svg> Initech</div>
                <div class="flex items-center gap-2 text-xl font-bold text-white"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg> Soylent</div>
            </div>
        </div>
    </section>

    <!-- Bento Grid Features -->
    <section id="features" class="relative z-10 py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-primary font-semibold tracking-wide uppercase text-sm mb-3">Powerful Capabilities</h2>
                <h3 class="text-4xl md:text-5xl font-bold text-white mb-6">Everything you need to scale, in one place.</h3>
                <p class="text-xl text-text-muted">Cratory eliminates the friction from your daily operations, allowing you to focus on growth.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 auto-rows-min">
                <!-- Large Feature -->
                <div class="md:col-span-2 glass-card rounded-3xl p-8 lg:p-12 border border-white/5 hover:border-primary/30 transition-all duration-300 relative overflow-hidden group bg-gradient-to-br from-surface-light to-surface">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-[80px] -mr-20 -mt-20 group-hover:bg-primary/20 transition-colors"></div>
                    <div class="relative z-10 flex flex-col h-full">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center mb-6 shadow-lg shadow-primary/20 text-white">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <h4 class="text-2xl lg:text-3xl font-bold text-white mb-4">Lightning Fast Invoicing</h4>
                        <p class="text-text-muted text-lg leading-relaxed max-w-md mb-8">Create, send, and track stunning professional invoices in seconds. Integrated payments ensure you get paid up to 3x faster.</p>
                        
                        <div class="mt-auto bg-surface-lighter rounded-xl p-4 border border-white/5 shadow-inner flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center text-green-400">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <div class="text-sm text-text-muted">Invoice #INV-2024-089</div>
                                <div class="text-white font-semibold">Paid • $4,500.00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tall Feature -->
                <div class="glass-card rounded-3xl p-8 lg:p-12 border border-white/5 hover:border-accent/30 transition-all duration-300 relative overflow-hidden group bg-gradient-to-br from-surface-light to-surface">
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/10 rounded-full blur-[80px] -ml-20 -mb-20 group-hover:bg-accent/20 transition-colors"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent to-accent-dark flex items-center justify-center mb-6 shadow-lg shadow-accent/20 text-white">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                        <h4 class="text-2xl font-bold text-white mb-4">Real-time Inventory</h4>
                        <p class="text-text-muted text-lg leading-relaxed mb-6">Track stock across multiple locations instantly. Set low-stock alerts and automate purchase orders.</p>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-text-secondary">MacBook Pro 16"</span>
                                <span class="text-yellow-400 font-medium bg-yellow-400/10 px-2 py-1 rounded">Low: 3 left</span>
                            </div>
                            <div class="w-full bg-surface-lighter rounded-full h-2">
                                <div class="bg-yellow-400 h-2 rounded-full" style="width: 15%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Regular Feature -->
                <div class="glass-card rounded-3xl p-8 lg:p-10 border border-white/5 hover:border-blue-500/30 transition-all duration-300 bg-surface-light">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center mb-6 text-blue-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3">Client CRM</h4>
                    <p class="text-text-muted leading-relaxed">Centralize customer data, view transaction history, and manage relationships seamlessly.</p>
                </div>

                <!-- Regular Feature -->
                <div class="md:col-span-2 glass-card rounded-3xl p-8 lg:p-10 border border-white/5 hover:border-green-500/30 transition-all duration-300 bg-surface-light flex flex-col md:flex-row gap-8 items-center">
                    <div class="flex-1">
                        <div class="w-12 h-12 rounded-xl bg-green-500/10 flex items-center justify-center mb-6 text-green-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3">Advanced Reporting</h4>
                        <p class="text-text-muted leading-relaxed">Make data-driven decisions with real-time financial and inventory reports. Export to PDF or CSV instantly.</p>
                    </div>
                    <div class="w-full md:w-1/3 h-32 bg-gradient-to-t from-surface to-surface-lighter rounded-xl border border-white/5 flex items-end justify-around px-4 pb-2">
                        <div class="w-6 bg-primary/40 rounded-t-sm h-12"></div>
                        <div class="w-6 bg-primary/60 rounded-t-sm h-20"></div>
                        <div class="w-6 bg-primary rounded-t-sm h-24 shadow-[0_0_10px_rgba(245,158,11,0.5)]"></div>
                        <div class="w-6 bg-primary/80 rounded-t-sm h-16"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="relative z-10 py-32 bg-surface-light/30 border-y border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-primary font-semibold tracking-wide uppercase text-sm mb-3">Pricing</h2>
                <h3 class="text-4xl md:text-5xl font-bold text-white mb-6">100% Free. No Subscriptions.</h3>
                <p class="text-xl text-text-muted max-w-2xl mx-auto">We believe in empowering small businesses. Get full access to all features without paying a dime.</p>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <div class="glass-card p-10 rounded-3xl border-2 border-primary relative flex flex-col shadow-[0_0_40px_rgba(245,158,11,0.15)] bg-gradient-to-b from-surface to-surface-light z-10 text-center">
                    <div class="absolute -top-4 inset-x-0 flex justify-center">
                        <span class="bg-gradient-to-r from-primary to-accent text-white text-xs font-bold uppercase tracking-wider py-1.5 px-4 rounded-full shadow-lg">Completely Free</span>
                    </div>
                    <h4 class="text-3xl font-bold text-white mb-4">Everything Included</h4>
                    <p class="text-text-muted mb-8 text-lg">Manage your business operations properly, with zero cost.</p>
                    <div class="mb-8 flex items-baseline justify-center">
                        <span class="text-6xl font-extrabold text-white">$0</span>
                        <span class="text-text-muted ml-2">/ forever</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left mb-10 max-w-xl mx-auto">
                        <div class="flex items-center gap-3 text-white font-medium">
                            <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-surface shrink-0"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>
                            Unlimited Invoices & Bills
                        </div>
                        <div class="flex items-center gap-3 text-white font-medium">
                            <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-surface shrink-0"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>
                            Real-time Inventory Tracking
                        </div>
                        <div class="flex items-center gap-3 text-white font-medium">
                            <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-surface shrink-0"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>
                            Automated Accounting
                        </div>
                        <div class="flex items-center gap-3 text-white font-medium">
                            <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-surface shrink-0"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>
                            Advanced Financial Reports
                        </div>
                    </div>
                    
                    <a href="{{ route('register') }}" class="btn bg-gradient-to-r from-primary to-accent hover:from-primary-light hover:to-accent-light text-white w-full py-4 rounded-xl text-xl font-bold shadow-[0_0_20px_rgba(245,158,11,0.4)] hover:shadow-[0_0_30px_rgba(245,158,11,0.6)] border-0 transition-all transform hover:-translate-y-1">Create Your Free Account</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Banner -->
    <section class="relative z-10 py-24">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card rounded-3xl p-10 md:p-16 text-center relative overflow-hidden bg-gradient-to-br from-surface to-surface-light border border-white/10">
                <div class="absolute top-0 right-0 w-96 h-96 bg-primary/20 rounded-full blur-[100px] -mr-32 -mt-32 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-accent/20 rounded-full blur-[100px] -ml-32 -mb-32 pointer-events-none"></div>
                
                <h3 class="text-3xl md:text-5xl font-bold text-white mb-6 relative z-10">Ready to transform your workflow?</h3>
                <p class="text-lg text-text-muted mb-10 max-w-2xl mx-auto relative z-10">Join thousands of businesses that trust Cratory to manage their daily operations. Setup takes less than 2 minutes.</p>
                <div class="relative z-10 flex justify-center">
                    <a href="{{ route('register') }}" class="btn bg-white text-surface hover:bg-gray-100 px-10 py-4 text-lg rounded-xl font-bold shadow-xl transition-all transform hover:-translate-y-1">
                        Get Started For Free
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Footer -->
    <footer class="relative z-10 border-t border-white/10 bg-surface pt-16 pb-8">
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
                        <li><a href="#features" class="text-text-muted hover:text-primary transition-colors text-sm">Features</a></li>
                        <li><a href="#pricing" class="text-text-muted hover:text-primary transition-colors text-sm">Pricing</a></li>
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
</body>
</html>
