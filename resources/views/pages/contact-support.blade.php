<x-layouts.public title="Contact Support">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        
        <!-- Hero Section -->
        <div class="text-center mb-16 relative">
            <div class="absolute inset-0 flex justify-center items-center pointer-events-none opacity-20">
                <div class="w-[500px] h-[500px] bg-accent rounded-full filter blur-[120px] mix-blend-screen"></div>
            </div>
            
            <span class="inline-block py-1 px-3 rounded-full bg-accent/10 border border-accent/20 text-accent text-xs font-bold tracking-widest uppercase mb-6 shadow-[0_0_15px_rgba(139,92,246,0.2)]">Get in Touch</span>
            
            <h1 class="relative text-4xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-white to-white/60 mb-6 tracking-tight leading-tight">
                Contact Support
            </h1>
            <p class="relative text-lg text-text-muted max-w-2xl mx-auto">
                Need help with something? We are here to answer your questions and assist you in any way we can.
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 relative z-10 max-w-5xl mx-auto">
            
            <!-- Left Side: Contact Info -->
            <div class="lg:w-1/3 space-y-6">
                <!-- Direct Email Card -->
                <a href="mailto:cratory.support@yagneshbhanani.com" class="block glass-card p-6 rounded-3xl border border-white/5 hover:border-primary/50 transition-all duration-300 group hover:shadow-[0_0_30px_rgba(245,158,11,0.15)] relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-surface-lighter text-primary flex items-center justify-center mb-4 group-hover:bg-primary group-hover:text-surface transition-colors duration-300 shadow-lg">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Email Us</h3>
                        <p class="text-text-muted text-sm mb-4">Our friendly team is here to help.</p>
                        <span class="text-primary font-medium group-hover:text-primary-light transition-colors">cratory@yagneshbhanani.com &rarr;</span>
                    </div>
                </a>

                <!-- Support Hours Card -->
                <div class="glass-card p-6 rounded-3xl border border-white/5 hover:border-accent/50 transition-all duration-300 group hover:shadow-[0_0_30px_rgba(139,92,246,0.15)] relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-accent/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-surface-lighter text-accent flex items-center justify-center mb-4 group-hover:bg-accent group-hover:text-surface transition-colors duration-300 shadow-lg">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Support Hours</h3>
                        <p class="text-text-muted text-sm mb-4">We're online and ready to assist you.</p>
                        <span class="text-white font-medium">Mon-Fri, 9am - 5pm EST</span>
                    </div>
                </div>
                
                <!-- Help Center Link -->
                <div class="glass-card p-6 rounded-3xl border border-white/5 bg-surface-lighter/10 hover:bg-surface-lighter/30 transition-all duration-300 text-center relative overflow-hidden">
                    <p class="text-text-muted text-sm mb-3">Looking for quick answers?</p>
                    <a href="{{ route('help-center') }}" class="inline-flex items-center justify-center gap-2 text-white hover:text-primary transition-colors text-sm font-bold">
                        Browse our Help Center
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                </div>
            </div>

            <!-- Right Side: Contact Form -->
            <div class="lg:w-2/3">
                <livewire:contact-support-form />
            </div>
            
        </div>
    </div>
</x-layouts.public>
