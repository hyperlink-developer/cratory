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

                    <!-- Tally -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" wire:model="slug" value="tally" class="peer sr-only" wire:click="selectTemplate('tally')">
                        <div class="p-3 border border-white/10 rounded-xl hover:bg-white/5 transition-colors peer-checked:border-accent peer-checked:bg-accent/10">
                            <div class="w-full h-16 bg-white/5 rounded-lg mb-2 flex flex-col p-1 gap-[2px] border border-white/5">
                                <div class="flex gap-[2px] h-1/3">
                                    <div class="w-1/2 h-full border border-white/10 rounded-sm bg-white/5"></div>
                                    <div class="w-1/2 h-full border border-white/10 rounded-sm bg-white/5"></div>
                                </div>
                                <div class="w-full h-1/2 border border-white/10 rounded-sm flex bg-white/5">
                                    <div class="w-1/5 h-full border-r border-white/10"></div>
                                    <div class="w-3/5 h-full border-r border-white/10"></div>
                                    <div class="w-1/5 h-full"></div>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-center text-text-primary">Tally (Classic)</p>
                        </div>
                    </label>
                    <!-- Blue Professional -->
                    <label class="relative cursor-pointer group">
                        <input type="radio" wire:model="slug" value="blue_classic" class="peer sr-only" wire:click="selectTemplate('blue_classic')">
                        <div class="p-3 border border-white/10 rounded-xl hover:bg-white/5 transition-colors peer-checked:border-accent peer-checked:bg-accent/10">
                            <div class="w-full h-16 bg-white/5 rounded-lg mb-2 flex flex-col overflow-hidden border border-white/5 relative">
                                <div class="w-full h-3 bg-blue-500/80 mb-1"></div>
                                <div class="flex gap-1 px-1 h-3 mb-1">
                                    <div class="w-1/2 h-full bg-white/10 rounded-sm"></div>
                                    <div class="w-1/2 h-full bg-white/10 rounded-sm"></div>
                                </div>
                                <div class="w-full h-8 border-t border-blue-500/30 bg-white/5 px-1 mt-auto flex items-center">
                                    <div class="w-full h-1 bg-text-muted/50 rounded"></div>
                                </div>
                            </div>
                            <p class="text-sm font-medium text-center text-text-primary">Blue Professional</p>
                        </div>
                    </label>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="form-label">Primary Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model.live="colorPrimary" class="w-10 h-10 rounded cursor-pointer bg-transparent border-0 p-0">
                            <input type="text" wire:model.live="colorPrimary" class="form-input flex-1 uppercase text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Secondary Color</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model.live="colorSecondary" class="w-10 h-10 rounded cursor-pointer bg-transparent border-0 p-0">
                            <input type="text" wire:model.live="colorSecondary" class="form-input flex-1 uppercase text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Font Family</label>
                        <select wire:model.live="fontChoice" class="form-input">
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
                        <input type="checkbox" wire:model.live="showFields.shipping_address" class="w-4 h-4 rounded border-white/20 bg-surface/50 text-accent focus:ring-accent focus:ring-offset-surface">
                        <span class="text-sm text-text-secondary">Shipping Address</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="showFields.quantity" class="w-4 h-4 rounded border-white/20 bg-surface/50 text-accent focus:ring-accent focus:ring-offset-surface">
                        <span class="text-sm text-text-secondary">Quantity Column</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="showFields.rate" class="w-4 h-4 rounded border-white/20 bg-surface/50 text-accent focus:ring-accent focus:ring-offset-surface">
                        <span class="text-sm text-text-secondary">Rate Column</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="showFields.discount" class="w-4 h-4 rounded border-white/20 bg-surface/50 text-accent focus:ring-accent focus:ring-offset-surface">
                        <span class="text-sm text-text-secondary">Discount Column</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="showFields.hsn" class="w-4 h-4 rounded border-white/20 bg-surface/50 text-accent focus:ring-accent focus:ring-offset-surface">
                        <span class="text-sm text-text-secondary">HSN / SAC Codes</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="showFields.tax_details" class="w-4 h-4 rounded border-white/20 bg-surface/50 text-accent focus:ring-accent focus:ring-offset-surface">
                        <span class="text-sm text-text-secondary">Tax Details Column</span>
                    </label>
                </div>
            </div>
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-text-primary mb-4">Branding</h3>
                <div class="space-y-4">
                    <label class="flex items-center gap-3 cursor-pointer mb-2">
                        <input type="checkbox" wire:model.live="showLogo" class="w-4 h-4 rounded border-white/20 bg-surface/50 text-accent focus:ring-accent focus:ring-offset-surface">
                        <span class="text-sm text-text-secondary">Show Logo on Invoice</span>
                    </label>
                    
                    @if($showLogo)
                    <div>
                        <label class="form-label">Upload Logo</label>
                        <input type="file" wire:model="orgLogo" class="form-input text-sm" accept="image/*">
                        @if ($orgLogo)
                            <div class="mt-2"><span class="text-xs text-green-400">Logo selected for upload</span></div>
                        @elseif(auth()->user()->currentOrganization->logo_path)
                            <div class="mt-2"><span class="text-xs text-text-secondary">Current logo is active</span></div>
                        @endif
                    </div>
                    @endif

                    <div class="mt-4 border-t border-white/10 pt-4">
                        <label class="form-label">Watermark Type</label>
                        <select wire:model.live="watermarkType" class="form-select">
                            <option value="none">None</option>
                            <option value="text">Custom Text</option>
                            <option value="image">Custom Image</option>
                            <option value="logo">Organization Logo</option>
                        </select>
                    </div>

                    @if($watermarkType === 'text')
                        <div>
                            <label class="form-label">Watermark Text</label>
                            <input type="text" wire:model.live="watermarkText" class="form-input" placeholder="e.g. CONFIDENTIAL">
                        </div>
                    @elseif($watermarkType === 'image')
                        <div>
                            <label class="form-label">Upload Watermark Image</label>
                            <input type="file" wire:model="watermarkImage" class="form-input text-sm" accept="image/*">
                        </div>
                    @endif
                </div>
            </div>

            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-text-primary mb-4">Signature</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Signature Type</label>
                        <select wire:model.live="signatureType" class="form-select">
                            <option value="none">None</option>
                            <option value="text">Custom Text (Cursive)</option>
                            <option value="image">Custom Image</option>
                        </select>
                    </div>

                    @if($signatureType === 'text')
                        <div>
                            <label class="form-label">Signature Text</label>
                            <input type="text" wire:model.live="signatureText" class="form-input" placeholder="e.g. John Doe">
                        </div>
                    @elseif($signatureType === 'image')
                        <div>
                            <label class="form-label">Upload Signature Image</label>
                            <input type="file" wire:model="signatureImage" class="form-input text-sm" accept="image/*">
                        </div>
                    @endif
                </div>
            </div>

            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-text-primary mb-4">Default Text</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Payment Information / Notes</label>
                        <textarea wire:model.defer="defaultPaymentInfo" class="form-input min-h-[80px] resize-y" placeholder="Bank account details, UPI ID..."></textarea>
                    </div>
                    <div>
                        <label class="form-label">Terms & Conditions</label>
                        <textarea wire:model.defer="defaultTermsAndConditions" class="form-input min-h-[80px] resize-y" placeholder="Warranty details, payment terms..."></textarea>
                    </div>
                </div>
            </div>

            <button wire:click="save" class="btn btn-primary w-full">Save Changes</button>
        </div>

        <!-- Preview Area -->
        <div class="lg:col-span-2">
            <div class="glass-card overflow-hidden bg-white border border-white/10 relative shadow-2xl" style="height: 800px;">
                <!-- A thin overlay to indicate it's a preview on dark mode backgrounds -->
                <div class="absolute inset-0 pointer-events-none ring-1 ring-inset ring-black/10"></div>
                <iframe srcdoc="{{ $this->getPreviewHtml() }}" class="w-full h-full border-0" title="Invoice Preview"></iframe>
            </div>
        </div>
    </div>
</div>
