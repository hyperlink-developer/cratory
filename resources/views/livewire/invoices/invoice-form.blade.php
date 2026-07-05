<div x-data="{
    isDirty: false,
    init() {
        this.$watch('isDirty', value => {
            // Watcher if needed
        });
        
        // Handle browser back/refresh
        window.addEventListener('beforeunload', (e) => {
            if (this.isDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Handle Livewire SPA navigation
        document.addEventListener('livewire:navigating', (e) => {
            if (this.isDirty) {
                if (!confirm('You have unsaved changes. Are you sure you want to leave?')) {
                    e.preventDefault();
                }
            }
        });
    }
}" @input="isDirty = true" @change="isDirty = true">
    <div class="flex items-start sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <!-- Mobile Back Chevron -->
            <a href="{{ route('invoices.index') }}" class="sm:hidden p-1.5 -ml-1.5 text-text-muted hover:text-text-primary transition-colors rounded-lg hover:bg-white/5" wire:navigate>
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-text-primary leading-tight">{{ $invoice ? 'Edit Invoice' : 'New ' . ucfirst($type) . ' Invoice' }}</h1>
                <p class="text-sm text-text-secondary mt-1">Fill in the details to generate your invoice</p>
            </div>
        </div>
        <!-- Desktop Back Button -->
        <div class="hidden sm:block">
            <a href="{{ route('invoices.index') }}" class="btn btn-ghost" wire:navigate>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back
            </a>
        </div>
    </div>

    <form class="space-y-6">
        <!-- Header Details -->
        <div class="glass-card p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Customer Selection -->
                <div class="md:col-span-1">
                    <label class="form-label flex items-center justify-between">
                        Customer <span class="text-red-400">*</span>
                        @if(!$showNewContactInput)
                            <button type="button" wire:click="$set('showNewContactInput', true)" class="text-xs text-accent hover:text-accent-light">Quick Add</button>
                        @endif
                    </label>
                    @if($showNewContactInput)
                        <div class="flex items-center gap-2 mt-1">
                            <input wire:model="newContactName" type="text" class="form-input text-sm py-1.5 min-h-[2.25rem]" placeholder="New customer name..." wire:keydown.enter.prevent="createContact">
                            <button type="button" wire:click="createContact" class="btn btn-primary px-3 min-h-[2.25rem] py-1.5 text-xs">Add</button>
                            <button type="button" wire:click="$set('showNewContactInput', false)" class="btn btn-ghost px-2 min-h-[2.25rem] py-1.5">✕</button>
                        </div>
                        @error('newContactName') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    @else
                        <select wire:model="contactId" class="form-input cursor-pointer">
                            <option value="">Select Customer</option>
                            @foreach($contacts as $contact)
                                <option value="{{ $contact['id'] }}">{{ $contact['display_name'] }}</option>
                            @endforeach
                        </select>
                        @error('contactId') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    @endif
                </div>

                <!-- Dates -->
                <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label class="form-label flex items-center justify-between">
                            Invoice # <span class="text-red-400">*</span>
                            <span class="text-[0.6rem] text-text-muted font-normal">Auto-generated if empty</span>
                        </label>
                        <input wire:model="invoiceNumber" type="text" class="form-input" placeholder="INV-...">
                        @error('invoiceNumber') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Invoice Date <span class="text-red-400">*</span></label>
                        <input wire:model="invoiceDate" type="text" x-data="datepicker" class="form-input">
                        @error('invoiceDate') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Due Date <span class="text-red-400">*</span></label>
                        <input wire:model="dueDate" type="text" x-data="datepicker" class="form-input">
                        @error('dueDate') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Line Items -->
        <div class="glass-card p-0 overflow-hidden">
            
            <!-- DESKTOP VIEW (Hidden on Mobile) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-white/5 border-b border-white/10 text-text-muted uppercase tracking-wider text-[0.625rem] font-semibold">
                        <tr>
                            <th class="px-4 py-3 w-1/3 min-w-[250px]">Item Details <span class="text-red-400">*</span></th>
                            <th class="px-4 py-3 w-32 min-w-[100px]">Quantity</th>
                            <th class="px-4 py-3 w-40 min-w-[120px]">Rate (₹)</th>
                            <th class="px-4 py-3 w-48 min-w-[150px]">Tax</th>
                            <th class="px-4 py-3 w-32 min-w-[120px] text-right">Amount (₹)</th>
                            <th class="px-4 py-3 w-12 text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($items as $index => $item)
                        <tr>
                            <!-- Item Details -->
                            <td class="px-4 py-3 align-top">
                                <div class="flex flex-col gap-2 whitespace-normal">
                                    <select wire:model.live="items.{{ $index }}.product_id" class="form-input py-1.5 text-sm cursor-pointer w-full">
                                        <option value="">Custom Item</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if(!$item['product_id'])
                                        <input wire:model.live="items.{{ $index }}.item_name" type="text" class="form-input py-1.5 text-sm w-full" placeholder="Enter item name *">
                                    @endif
                                    <input wire:model.live="items.{{ $index }}.description" type="text" class="form-input py-1.5 text-xs bg-transparent border-dashed border-white/10 focus:border-accent w-full" placeholder="Item description (optional)">
                                </div>
                            </td>

                            <!-- Quantity -->
                            <td class="px-4 py-3 align-top">
                                <input wire:model.live="items.{{ $index }}.quantity" type="number" step="0.01" class="form-input py-1.5 text-sm w-full">
                            </td>

                            <!-- Rate -->
                            <td class="px-4 py-3 align-top">
                                <input wire:model.live="items.{{ $index }}.rate" type="number" step="0.01" class="form-input py-1.5 text-sm w-full">
                            </td>

                            <!-- Tax -->
                            <td class="px-4 py-3 align-top">
                                <select wire:model.live="items.{{ $index }}.tax_rate_id" class="form-input py-1.5 text-sm cursor-pointer w-full">
                                    <option value="">No Tax</option>
                                    @foreach($taxRates as $tax)
                                        <option value="{{ $tax['id'] }}">{{ $tax['name'] }}</option>
                                    @endforeach
                                </select>
                                @if($item['tax_amount'] > 0)
                                    <p class="text-[0.65rem] text-text-muted mt-1">+ ₹{{ number_format($item['tax_amount'], 2) }}</p>
                                @endif
                            </td>

                            <!-- Amount -->
                            <td class="px-4 py-3 align-top text-right">
                                <p class="font-medium text-text-primary mt-2">₹{{ number_format($item['line_total'], 2) }}</p>
                            </td>

                            <!-- Desktop Delete -->
                            <td class="px-4 py-3 align-top text-center">
                                <button type="button" wire:click="removeItem({{ $index }})" class="p-1.5 mt-0.5 rounded-lg text-text-muted hover:text-red-400 hover:bg-red-400/10 transition-colors" title="Remove row">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- MOBILE VIEW (Hidden on Desktop) -->
            <div class="md:hidden divide-y divide-white/5">
                @foreach($items as $index => $item)
                <div class="p-4 flex flex-col gap-4 relative">
                    <!-- Item Details -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[0.65rem] text-text-muted uppercase">Item Details <span class="text-red-400">*</span></label>
                            <button type="button" wire:click="removeItem({{ $index }})" class="p-1 text-text-muted hover:text-red-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex flex-col gap-2">
                            <select wire:model.live="items.{{ $index }}.product_id" class="form-input py-1.5 text-sm cursor-pointer w-full">
                                <option value="">Custom Item</option>
                                @foreach($products as $product)
                                    <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                                @endforeach
                            </select>
                            @if(!$item['product_id'])
                                <input wire:model.live="items.{{ $index }}.item_name" type="text" class="form-input py-1.5 text-sm w-full" placeholder="Enter item name *">
                            @endif
                            <input wire:model.live="items.{{ $index }}.description" type="text" class="form-input py-1.5 text-xs bg-transparent border-dashed border-white/10 focus:border-accent w-full" placeholder="Item description (optional)">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Quantity -->
                        <div>
                            <label class="text-[0.65rem] text-text-muted uppercase mb-1 block">Quantity</label>
                            <input wire:model.live="items.{{ $index }}.quantity" type="number" step="0.01" class="form-input py-1.5 text-sm w-full">
                        </div>

                        <!-- Rate -->
                        <div>
                            <label class="text-[0.65rem] text-text-muted uppercase mb-1 block">Rate (₹)</label>
                            <input wire:model.live="items.{{ $index }}.rate" type="number" step="0.01" class="form-input py-1.5 text-sm w-full">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 items-start">
                        <!-- Tax -->
                        <div>
                            <label class="text-[0.65rem] text-text-muted uppercase mb-1 block">Tax</label>
                            <select wire:model.live="items.{{ $index }}.tax_rate_id" class="form-input py-1.5 text-sm cursor-pointer w-full">
                                <option value="">No Tax</option>
                                @foreach($taxRates as $tax)
                                    <option value="{{ $tax['id'] }}">{{ $tax['name'] }}</option>
                                @endforeach
                            </select>
                            @if($item['tax_amount'] > 0)
                                <p class="text-[0.65rem] text-text-muted mt-1">+ ₹{{ number_format($item['tax_amount'], 2) }}</p>
                            @endif
                        </div>

                        <!-- Amount -->
                        <div class="text-right">
                            <label class="text-[0.65rem] text-text-muted uppercase mb-1 block text-right">Amount (₹)</label>
                            <p class="font-medium text-text-primary mt-2">₹{{ number_format($item['line_total'], 2) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Add Row & Totals -->
            <div class="px-4 py-4 flex flex-col md:flex-row items-start justify-between gap-6 border-t border-white/5 bg-surface/30">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <button type="button" wire:click="addItem" class="btn btn-secondary text-xs px-3 py-1.5">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Add New Row
                        </button>
                        @if(!$showNewProductInput)
                            <button type="button" wire:click="$set('showNewProductInput', true)" class="btn btn-ghost text-xs px-3 py-1.5 text-accent">
                                + Quick Add Item
                            </button>
                        @endif
                    </div>
                    @error('items') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
                    @error('items.*.product_id') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
                    @error('items.*.item_name') <p class="text-xs text-red-400">{{ $message }}</p> @enderror
                    
                    @if($showNewProductInput)
                        <div class="mt-2 p-3 border border-white/10 bg-surface-lighter rounded-xl flex items-end gap-3 max-w-lg">
                            <div class="flex-1">
                                <label class="form-label text-[0.65rem]">Item Name</label>
                                <input wire:model="newProductName" type="text" class="form-input py-1 text-sm min-h-[2rem]" placeholder="Name">
                            </div>
                            <div class="w-24">
                                <label class="form-label text-[0.65rem]">Price (₹)</label>
                                <input wire:model="newProductPrice" type="number" class="form-input py-1 text-sm min-h-[2rem]" placeholder="0.00">
                            </div>
                            <button type="button" wire:click="createProduct" class="btn btn-primary px-3 text-xs min-h-[2rem]">Save</button>
                            <button type="button" wire:click="$set('showNewProductInput', false)" class="btn btn-ghost px-2 text-xs min-h-[2rem]">✕</button>
                        </div>
                    @endif
                </div>

                <div class="w-full md:w-72 space-y-3 p-4 rounded-xl border border-white/5 bg-surface-lighter/50">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-text-secondary">Subtotal</span>
                        <span class="text-text-primary font-medium">₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($taxTotal > 0)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-text-secondary">Tax Total</span>
                        <span class="text-text-primary font-medium">₹{{ number_format($taxTotal, 2) }}</span>
                    </div>
                    @endif
                    @if($roundOff != 0)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-text-secondary">Round Off</span>
                        <span class="text-text-primary font-medium">₹{{ number_format($roundOff, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between pt-3 border-t border-white/10">
                        <span class="text-base font-bold text-text-primary">Grand Total</span>
                        <span class="text-lg font-bold text-primary">₹{{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-4">
            <a href="{{ route('invoices.index') }}" class="btn btn-ghost w-full sm:w-auto" wire:navigate>Cancel</a>
            <button type="button" wire:click="save('draft')" @click="isDirty = false" class="btn btn-secondary w-full sm:w-auto">
                Save as Draft
            </button>
            <button type="button" wire:click="save('send')" @click="isDirty = false" class="btn btn-primary w-full sm:w-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                </svg>
                Save & Mark as Sent
            </button>
        </div>
    </form>
</div>
