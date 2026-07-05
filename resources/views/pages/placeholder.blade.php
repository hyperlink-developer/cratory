<x-layouts.app :title="$title">
    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="text-center max-w-md">
            <div class="w-16 h-16 rounded-2xl bg-accent/10 flex items-center justify-center mx-auto mb-5">
                <svg class="w-7 h-7 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.382 3.17m0 0a.75.75 0 01-1.12-.814l1.622-7.334-5.61-4.865a.75.75 0 01.427-1.316l7.465-.647 2.98-6.825a.75.75 0 011.316 0l2.98 6.825 7.465.647a.75.75 0 01.427 1.316l-5.61 4.865 1.622 7.334a.75.75 0 01-1.12.814L12 15.17z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-text-primary mb-2">{{ $title }}</h1>
            <p class="text-sm text-text-muted mb-6">{{ $description }}</p>
            <a href="{{ route('dashboard') }}" class="btn btn-ghost text-sm cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</x-layouts.app>
