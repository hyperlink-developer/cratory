<div>
    <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">{{ $product ? 'Edit Item' : 'New Item' }}</h1>
            <p class="text-sm text-text-secondary mt-1">Add a new product or service to your catalog</p>
        </div>
        <a href="{{ route('inventory.index') }}" class="btn btn-ghost" wire:navigate>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back
        </a>
    </div>

    <form wire:submit="save" class="space-y-6">
        
        <!-- Top Type Selector (Radio Pills) -->
        <div class="glass-card p-6 flex justify-center">
            <div class="inline-flex rounded-xl p-1 bg-surface-lighter border border-white/5">
                @foreach($this->itemTypes as $type)
                    <label class="relative flex-1 cursor-pointer">
                        <input type="radio" wire:model.live="itemType" value="{{ $type['value'] }}" class="sr-only">
                        <div class="px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 {{ $itemType === $type['value'] ? 'bg-accent text-white shadow-sm' : 'text-text-secondary hover:text-text-primary' }}">
                            {{ $type['label'] }}
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Main Content (Left col) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Basic Info -->
                <div class="glass-card p-6">
                    <h2 class="text-sm font-semibold text-text-primary mb-4 border-b border-white/5 pb-2">Basic Information</h2>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="form-label">Name <span class="text-red-400">*</span></label>
                            <input wire:model="name" type="text" class="form-input" placeholder="e.g. Premium Widget">
                            @error('name') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">SKU / Item Code</label>
                                <input wire:model="sku" type="text" class="form-input uppercase" placeholder="WDG-001">
                                @error('sku') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="form-label">Unit of Measurement</label>
                                <select wire:model="unitOfMeasureId" class="form-input cursor-pointer">
                                    <option value="">Select Unit</option>
                                    @foreach($this->uoms as $uom)
                                        <option value="{{ $uom->id }}">{{ $uom->name }} ({{ $uom->abbreviation }})</option>
                                    @endforeach
                                </select>
                                @error('unitOfMeasureId') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="form-label flex items-center justify-between">
                                Category
                                @if(!$showNewCategoryInput)
                                    <button type="button" wire:click="$set('showNewCategoryInput', true)" class="text-xs text-accent hover:text-accent-light">Quick Add</button>
                                @endif
                            </label>
                            
                            @if($showNewCategoryInput)
                                <div class="flex items-center gap-2 mt-1">
                                    <input wire:model="newCategoryName" type="text" class="form-input text-sm py-1.5 min-h-[2.25rem]" placeholder="New category name..." wire:keydown.enter.prevent="createCategory">
                                    <button type="button" wire:click="createCategory" class="btn btn-primary px-3 min-h-[2.25rem] py-1.5 text-xs">Add</button>
                                    <button type="button" wire:click="$set('showNewCategoryInput', false)" class="btn btn-ghost px-2 min-h-[2.25rem] py-1.5">✕</button>
                                </div>
                                @error('newCategoryName') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                            @else
                                <select wire:model="categoryId" class="form-input cursor-pointer mt-1">
                                    <option value="">No Category</option>
                                    @foreach($this->categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('categoryId') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                            @endif
                        </div>

                        <div>
                            <label class="form-label">Description</label>
                            <textarea wire:model="description" rows="3" class="form-input py-2" placeholder="Item description for invoices..."></textarea>
                            @error('description') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing & Tax -->
                <div class="glass-card p-6">
                    <h2 class="text-sm font-semibold text-text-primary mb-4 border-b border-white/5 pb-2">Pricing & Taxation</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="form-label">Selling Price <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-text-muted">₹</span>
                                </div>
                                <input wire:model="sellingPrice" type="number" step="0.01" class="form-input pl-8" placeholder="0.00">
                            </div>
                            @error('sellingPrice') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">Purchase Price</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-text-muted">₹</span>
                                </div>
                                <input wire:model="purchasePrice" type="number" step="0.01" class="form-input pl-8" placeholder="0.00">
                            </div>
                            @error('purchasePrice') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label">Applicable Tax Rate</label>
                            <select wire:model="taxRateId" class="form-input cursor-pointer">
                                <option value="">No Tax / Exempt</option>
                                @foreach($this->taxRates as $tax)
                                    <option value="{{ $tax->id }}">{{ $tax->name }} ({{ rtrim(rtrim($tax->percentage, '0'), '.') }}%)</option>
                                @endforeach
                            </select>
                            @error('taxRateId') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">
                                {{ $itemType === 'product' ? 'HSN Code' : 'SAC Code' }}
                            </label>
                            <input wire:model="{{ $itemType === 'product' ? 'hsnCode' : 'sacCode' }}" type="text" class="form-input" placeholder="{{ $itemType === 'product' ? 'e.g. 123456' : 'e.g. 9988' }}">
                            <p class="mt-1 text-[0.65rem] text-text-muted">Required for GST invoicing</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Content (Right col) -->
            <div class="space-y-6">
                <!-- Inventory Tracking (Products Only) -->
                @if($itemType === 'product')
                <div class="glass-card p-6" x-data x-transition>
                    <h2 class="text-sm font-semibold text-text-primary mb-4 border-b border-white/5 pb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        Stock Tracking
                    </h2>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="form-label">Initial Stock Quantity <span class="text-red-400">*</span></label>
                            <input wire:model="openingStock" type="number" step="0.01" class="form-input" {{ $product && $product->exists ? 'disabled' : '' }}>
                            @if($product && $product->exists)
                                <p class="mt-1.5 text-[0.65rem] text-text-muted">Initial stock cannot be changed after creation. Use stock adjustments.</p>
                            @endif
                            @error('openingStock') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">Reorder Level Alerts</label>
                            <input wire:model="reorderLevel" type="number" step="0.01" class="form-input" placeholder="0">
                            <p class="mt-1 text-[0.65rem] text-text-muted">Get notified when stock drops below this level</p>
                            @error('reorderLevel') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Helper Card -->
                <div class="rounded-xl border border-blue-500/10 bg-blue-500/5 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-500/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-400 mb-1">Product vs Service</h4>
                            <p class="text-xs text-text-secondary leading-relaxed">
                                <strong class="text-text-primary">Products</strong> track physical inventory levels, have units of measurement, and use HSN codes. 
                                <br><br>
                                <strong class="text-text-primary">Services</strong> are non-physical, do not track stock, and use SAC codes for GST billing.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4 pt-4 border-t border-white/5 mt-6">
            <a href="{{ route('inventory.index') }}" class="btn btn-ghost" wire:navigate>Cancel</a>
            <button type="submit" class="btn btn-primary">
                {{ $product ? 'Save Changes' : 'Save Item' }}
            </button>
        </div>
    </form>
</div>
