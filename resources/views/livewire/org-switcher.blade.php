<div x-data="{ open: @entangle('open') }" class="relative">
    @php $currentOrg = auth()->user()->currentOrganization; @endphp

    <!-- Trigger -->
    <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-xl glass-light hover:bg-white/10 transition-colors cursor-pointer w-full text-left">
        <div class="w-7 h-7 rounded-lg bg-primary/15 flex items-center justify-center text-primary text-xs font-bold flex-shrink-0">
            {{ $currentOrg ? strtoupper(substr($currentOrg->name, 0, 2)) : '?' }}
        </div>
        <div class="flex-1 min-w-0 hidden sm:block">
            <p class="text-xs font-semibold text-text-primary truncate">{{ $currentOrg?->name ?? 'Select Organization' }}</p>
        </div>
        <svg class="w-3.5 h-3.5 text-text-muted flex-shrink-0 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    <!-- Dropdown -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" @click.away="open = false" class="absolute right-0 origin-top-right mt-2 glass-dropdown p-2 space-y-1 max-h-64 overflow-y-auto" style="min-width: 220px; z-index: 60;">

        @foreach($this->organizations as $org)
            <button wire:click="switchOrg({{ $org->id }})" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors cursor-pointer text-left {{ $org->id === $currentOrgId ? 'bg-primary/10' : 'hover:bg-white/5' }}">
                <div class="w-8 h-8 rounded-lg {{ $org->id === $currentOrgId ? 'bg-primary/20 text-primary' : 'bg-white/5 text-text-muted' }} flex items-center justify-center text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($org->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium {{ $org->id === $currentOrgId ? 'text-primary' : 'text-text-primary' }} truncate">{{ $org->name }}</p>
                    <p class="text-[0.625rem] text-text-muted uppercase tracking-wide">{{ $org->pivot->role }}</p>
                </div>
                @if($org->id === $currentOrgId)
                    <svg class="w-4 h-4 text-primary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                @endif
            </button>
        @endforeach

        @if(auth()->user()->isCommander())
            <div class="border-t border-white/5 pt-1 mt-1">
                <a href="{{ route('onboarding.wizard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-accent/10 text-accent text-sm font-medium transition-colors cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    New Organization
                </a>
            </div>
        @endif
    </div>
</div>
