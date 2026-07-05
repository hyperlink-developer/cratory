<div>
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="closeModal"></div>
            
            <!-- Modal -->
            <div class="relative bg-surface border border-white/10 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-text-primary">Download Options</h2>
                        <button wire:click="closeModal" class="text-text-muted hover:text-text-primary transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-text-secondary mb-2">Invoice Template</label>
                            <select wire:model.live="templateId" class="form-input w-full">
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-secondary mb-3">Columns & Fields to Show</label>
                            <div class="space-y-3 bg-white/5 p-4 rounded-xl border border-white/5">
                                @php
                                    $fields = [
                                        'item_description' => 'Item Descriptions',
                                        'hsn' => 'HSN/SAC Code',
                                        'tax' => 'Tax Columns',
                                        'discount' => 'Discount',
                                        'shipping_address' => 'Shipping Address',
                                    ];
                                @endphp

                                @foreach($fields as $key => $label)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" wire:model="showFields.{{ $key }}" class="w-4 h-4 rounded border-white/20 bg-transparent text-primary focus:ring-primary focus:ring-offset-surface">
                                        </div>
                                        <span class="text-sm text-text-primary group-hover:text-accent transition-colors">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button wire:click="closeModal" class="btn btn-secondary flex-1">Cancel</button>
                        <button wire:click="download" class="btn btn-primary flex-1">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('open-url-new-tab', (data) => {
                    window.open(data.url, '_blank');
                });
            });
        </script>
    @endif
</div>
