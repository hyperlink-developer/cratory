<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Invoice Templates</h1>
            <p class="text-sm text-text-secondary mt-1">Customize the look and feel of your generated invoices</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Settings Form -->
        <div class="lg:col-span-1 space-y-6">
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-text-primary mb-4">Template Style</h3>
                
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <!-- Standard -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" wire:model="slug" value="standard" class="peer sr-only" wire:click="selectTemplate('standard')">
                        <div class="p-3 border border-white/10 rounded-xl hover:bg-white/5 transition-colors peer-checked:border-accent peer-checked:bg-accent/10">
                            <div class="w-full h-16 bg-white/5 rounded-lg mb-2 flex flex-col p-2 gap-1 border border-white/5">
                                <div class="w-1/3 h-2 bg-text-muted rounded"></div>
                                <div class="w-full h-px bg-white/10 my-1"></div>
                                <div class="w-full h-6 bg-white/5 rounded flex items-center px-1"><div class="w-1/2 h-1 bg-text-muted/50 rounded"></div></div>
                            </div>
                            <p class="text-sm font-medium text-center text-text-primary">Standard</p>
                        </div>
                    </label>

                    <!-- Modern -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" wire:model="slug" value="modern" class="peer sr-only" wire:click="selectTemplate('modern')">
                        <div class="p-3 border border-white/10 rounded-xl hover:bg-white/5 transition-colors peer-checked:border-accent peer-checked:bg-accent/10">
                            <div class="w-full h-16 bg-white/5 rounded-lg mb-2 flex flex-col p-0 gap-0 overflow-hidden border border-white/5 relative">
                                <div class="w-full h-4" style="background-color: {{ $colorPrimary }}"></div>
                                <div class="absolute top-5 right-2 w-1/4 h-2 bg-text-muted rounded"></div>
                                <div class="w-full h-6 bg-white/5 rounded flex items-center px-1 mt-auto"><div class="w-1/2 h-1 bg-text-muted/50 rounded"></div></div>
                            </div>
                            <p class="text-sm font-medium text-center text-text-primary">Modern</p>
                        </div>
                    </label>

                    <!-- Minimal -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" wire:model="slug" value="minimal" class="peer sr-only" wire:click="selectTemplate('minimal')">
                        <div class="p-3 border border-white/10 rounded-xl hover:bg-white/5 transition-colors peer-checked:border-accent peer-checked:bg-accent/10">
                            <div class="w-full h-16 bg-white/5 rounded-lg mb-2 flex flex-col p-2 items-center justify-center border border-white/5">
                                <div class="w-1/2 h-2 bg-text-muted rounded mb-2"></div>
                                <div class="w-3/4 h-1 bg-text-muted/50 rounded"></div>
                            </div>
                            <p class="text-sm font-medium text-center text-text-primary">Minimal</p>
                        </div>
                    </label>

                    <!-- Elegant -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" wire:model="slug" value="elegant" class="peer sr-only" wire:click="selectTemplate('elegant')">
                        <div class="p-3 border border-white/10 rounded-xl hover:bg-white/5 transition-colors peer-checked:border-accent peer-checked:bg-accent/10">
                            <div class="w-full h-16 bg-white/5 rounded-lg mb-2 flex flex-col p-2 gap-1 border border-white/5 items-center">
                                <div class="w-1/4 h-2 bg-text-muted rounded text-center"></div>
                                <div class="w-full h-px bg-white/10 my-1"></div>
                                <div class="w-full h-6 border border-white/10 rounded flex items-center px-1"><div class="w-1/2 h-1 bg-text-muted/50 rounded"></div></div>
                            </div>
                            <p class="text-sm font-medium text-center text-text-primary">Elegant</p>
                        </div>
                    </label>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="form-label">Primary Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="colorPrimary" class="w-10 h-10 rounded cursor-pointer bg-transparent border-0 p-0">
                            <input type="text" wire:model="colorPrimary" class="form-input flex-1 uppercase text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Secondary Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="colorSecondary" class="w-10 h-10 rounded cursor-pointer bg-transparent border-0 p-0">
                            <input type="text" wire:model="colorSecondary" class="form-input flex-1 uppercase text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Font Family</label>
                        <select wire:model="fontChoice" class="form-input">
                            <option value="Helvetica">Helvetica (Standard)</option>
                            <option value="Times-Roman">Times Roman (Serif)</option>
                            <option value="Courier">Courier (Monospace)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-text-primary mb-4">Visible Fields</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="showFields.shipping_address" class="w-4 h-4 rounded border-white/20 bg-surface/50 text-accent focus:ring-accent focus:ring-offset-surface">
                        <span class="text-sm text-text-secondary">Shipping Address</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="showFields.hsn" class="w-4 h-4 rounded border-white/20 bg-surface/50 text-accent focus:ring-accent focus:ring-offset-surface">
                        <span class="text-sm text-text-secondary">HSN / SAC Codes</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="showFields.tax_details" class="w-4 h-4 rounded border-white/20 bg-surface/50 text-accent focus:ring-accent focus:ring-offset-surface">
                        <span class="text-sm text-text-secondary">Tax Details Column</span>
                    </label>
                </div>
            </div>

            <button wire:click="save" class="btn btn-primary w-full">Save Changes</button>
        </div>

        <!-- Preview Area -->
        <div class="lg:col-span-2">
            <div class="glass-card p-1 min-h-[600px] flex flex-col items-center justify-center bg-surface-lighter/50">
                <div class="text-center p-6">
                    <div class="w-16 h-16 mx-auto mb-4 bg-accent/20 rounded-full flex items-center justify-center text-accent">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-text-primary mb-2">Live Preview Unavailable</h3>
                    <p class="text-sm text-text-muted max-w-md mx-auto">
                        To see exactly how this template looks with your real data, save your settings and download a PDF of one of your existing invoices.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('invoices.index') }}" class="btn btn-outline" wire:navigate>Go to Invoices</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
