<x-layouts.public title="Integrations">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-6">Integrations</h1>
            <p class="text-lg text-text-muted">Connect Cratory with the tools you already use.</p>
        </div>

        <div class="glass-card p-8 rounded-3xl border border-white/5 text-center">
            <div class="w-16 h-16 mx-auto bg-primary/20 rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-4">Coming Soon</h2>
            <p class="text-text-muted mb-8">We are working hard to build integrations with popular accounting and payment platforms. Stay tuned!</p>
            <a href="{{ route('welcome') }}" class="btn bg-surface-lighter hover:bg-white/10 text-white px-6 py-3 border border-white/10 rounded-xl font-medium transition-all">Back to Home</a>
        </div>
    </div>
</x-layouts.public>
