<div class="glass-card p-8 md:p-10 rounded-3xl border border-white/5 relative overflow-hidden" 
     x-data="{ showSuccess: @entangle('isSuccess') }">
    
    <!-- Success Overlay -->
    <div x-show="showSuccess" 
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 z-20 bg-surface/95 backdrop-blur-xl flex flex-col items-center justify-center text-center p-8"
         x-cloak>
        <div class="w-20 h-20 bg-green-500/20 text-green-400 rounded-full flex items-center justify-center mb-6 shadow-[0_0_30px_rgba(34,197,94,0.3)]">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
        </div>
        <h3 class="text-2xl font-bold text-white mb-2">Message Sent!</h3>
        <p class="text-text-muted mb-6">Thanks for reaching out. We've received your message and will get back to you shortly.</p>
        <button wire:click="resetSuccess" class="text-primary font-bold hover:text-primary-light transition-colors">Send another message</button>
    </div>
    
    <h2 class="text-2xl font-bold text-white mb-6">Send us a message</h2>
    
    @error('rate_limit')
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl mb-6 flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <strong class="block font-bold">Too many requests</strong>
                <span class="text-sm">{{ $message }}</span>
            </div>
        </div>
    @enderror
    
    <form wire:submit="submitForm" class="space-y-6 relative z-10">
        <!-- Honeypot -->
        <div class="hidden">
            <input type="text" wire:model="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-bold text-text-secondary mb-2">Your Name</label>
                <input type="text" id="name" wire:model="name" class="block w-full px-4 py-3.5 bg-surface-lighter/50 border border-white/10 rounded-xl text-white placeholder-text-muted/50 focus:ring-2 focus:ring-primary focus:border-transparent transition-all shadow-inner text-base" placeholder="John Doe" required>
                @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-bold text-text-secondary mb-2">Email Address</label>
                <input type="email" id="email" wire:model="email" class="block w-full px-4 py-3.5 bg-surface-lighter/50 border border-white/10 rounded-xl text-white placeholder-text-muted/50 focus:ring-2 focus:ring-primary focus:border-transparent transition-all shadow-inner text-base" placeholder="john@example.com" required>
                @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <div>
            <label for="message" class="block text-sm font-bold text-text-secondary mb-2">How can we help?</label>
            <textarea id="message" wire:model="message" rows="5" class="block w-full px-4 py-3.5 bg-surface-lighter/50 border border-white/10 rounded-xl text-white placeholder-text-muted/50 focus:ring-2 focus:ring-primary focus:border-transparent transition-all shadow-inner text-base resize-none" placeholder="Please describe your issue or question in detail..." required></textarea>
            @error('message') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        
        <div class="pt-2">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-primary-light text-surface px-6 py-4 rounded-xl font-bold shadow-lg shadow-primary/25 transition-all disabled:opacity-70 disabled:cursor-not-allowed group text-lg" wire:loading.attr="disabled">
                
                <!-- Default State -->
                <span wire:loading.remove class="flex items-center gap-2">
                    Send Message
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </span>
                
                <!-- Loading State -->
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-surface" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
                </span>
            </button>
        </div>
    </form>
</div>
