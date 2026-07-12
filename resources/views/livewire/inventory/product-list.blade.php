<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Inventory</h1>
            <p class="text-sm text-text-secondary mt-1">Manage your products and services</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('inventory.create') }}" class="btn btn-primary" wire:navigate>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Item
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="glass-card p-4 mb-6 flex flex-col sm:flex-row gap-4 items-center">
        <!-- Search -->
        <div class="relative flex-1 w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="form-input pl-9" placeholder="Search by name or SKU...">
        </div>

        <!-- Filter -->
        <div class="w-full sm:w-48">
            <select wire:model.live="typeFilter" class="form-input cursor-pointer">
                <option value="">All Items</option>
                <option value="product">Products</option>
                <option value="service">Services</option>
            </select>
        </div>

        <!-- Low Stock Toggle -->
        @if(auth()->user()->currentOrganization?->showsInventory())
        <div class="w-full sm:w-auto">
            <label class="flex items-center gap-2 cursor-pointer p-2 rounded-xl hover:bg-white/5 transition-colors border border-transparent hover:border-white/10">
                <input wire:model.live="lowStockOnly" type="checkbox" class="w-4 h-4 rounded border-border bg-surface text-red-500 focus:ring-red-500/30">
                <span class="text-sm font-medium {{ $lowStockOnly ? 'text-red-400' : 'text-text-secondary' }}">Low Stock Alerts</span>
            </label>
        </div>
        @endif
    </div>

    <!-- Products List -->
    <div class="glass-card overflow-hidden">
        
        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-white/5">
            @forelse($products as $product)
                <div class="p-4 flex flex-col gap-3">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $product->isProduct() ? 'bg-primary/10 text-primary' : 'bg-blue-500/10 text-blue-400' }}">
                                @if($product->isProduct())
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-text-primary truncate">{{ $product->name }}</p>
                                @if($product->sku)
                                    <p class="text-[0.65rem] text-text-muted mt-0.5">SKU: <span class="font-mono">{{ $product->sku }}</span></p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-bold text-text-primary text-sm">₹{{ number_format($product->selling_price, 2) }}</p>
                            @if($product->taxRate)
                                <p class="text-[0.65rem] text-text-muted mt-0.5">+ {{ rtrim(rtrim($product->taxRate->percentage, '0'), '.') }}% Tax</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-white/5">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.65rem] font-medium uppercase tracking-wide {{ $product->isProduct() ? 'bg-primary/10 text-primary border border-primary/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                                {{ $product->item_type->label() }}
                            </span>
                            @if($product->isProduct())
                                @if($product->track_stock)
                                    @if($product->current_stock <= 0)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[0.65rem] font-medium bg-red-500/10 text-red-400 border border-red-500/20">Out of Stock</span>
                                    @elseif($product->current_stock <= $product->reorder_level)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[0.65rem] font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">Low: {{ $product->current_stock }}</span>
                                    @else
                                        <span class="text-xs text-text-secondary">{{ $product->current_stock }} in stock</span>
                                    @endif
                                @else
                                    <span class="text-[0.65rem] text-text-muted italic">Not tracked</span>
                                @endif
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('inventory.edit', $product) }}" class="p-1.5 text-text-muted hover:text-accent transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                            </a>
                            <button wire:click="delete({{ $product->id }})" wire:confirm="Are you sure you want to delete this item?" class="p-1.5 text-text-muted hover:text-red-400 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-white/5 mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                    </div>
                    <p class="text-text-primary font-medium">No items found</p>
                    <p class="text-sm text-text-muted mt-1 mb-4">Get started by creating a new product or service.</p>
                    <a href="{{ route('inventory.create') }}" class="btn btn-outline" wire:navigate>Add Item</a>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white/5 border-b border-white/10 text-text-muted uppercase tracking-wider text-[0.625rem] font-semibold">
                    <tr>
                        <th class="px-6 py-4">Item Details</th>
                        <th class="px-6 py-4">Type/Category</th>
                        <th class="px-6 py-4 text-right">Selling Price</th>
                        <th class="px-6 py-4 text-center">Stock</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($products as $product)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $product->isProduct() ? 'bg-primary/10 text-primary' : 'bg-blue-500/10 text-blue-400' }}">
                                        @if($product->isProduct())
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-text-primary truncate">{{ $product->name }}</p>
                                        @if($product->sku)
                                            <p class="text-xs text-text-muted">SKU: <span class="font-mono">{{ $product->sku }}</span></p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex w-max items-center px-2 py-0.5 rounded text-[0.65rem] font-medium uppercase tracking-wide {{ $product->isProduct() ? 'bg-primary/10 text-primary border border-primary/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                                        {{ $product->item_type->label() }}
                                    </span>
                                    @if($product->category)
                                        <span class="text-xs text-text-muted truncate">{{ $product->category->name }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="font-medium text-text-primary">₹{{ number_format($product->selling_price, 2) }}</p>
                                @if($product->taxRate)
                                    <p class="text-[0.65rem] text-text-muted">+ {{ rtrim(rtrim($product->taxRate->percentage, '0'), '.') }}% Tax</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($product->isProduct())
                                    <div class="inline-flex flex-col items-center justify-center">
                                        <span class="font-bold text-sm {{ $product->isLowStock() ? 'text-red-400' : 'text-text-primary' }}">
                                            {{ $product->current_stock + 0 }}
                                        </span>
                                        <span class="text-[0.65rem] text-text-muted uppercase tracking-wider">{{ $product->unitOfMeasure->abbreviation ?? 'UNITS' }}</span>
                                    </div>
                                @else
                                    <span class="text-text-muted text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('inventory.edit', $product->uuid) }}" class="p-1.5 rounded-lg text-text-muted hover:text-accent hover:bg-accent/10 transition-colors" title="Edit" wire:navigate>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                    <button wire:click="deleteProduct('{{ $product->uuid }}')" wire:confirm="Are you sure you want to delete this item?" class="p-1.5 rounded-lg text-text-muted hover:text-red-400 hover:bg-red-400/10 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-text-primary mb-1">No items found</h3>
                                <p class="text-xs text-text-muted mb-4">Get started by creating a new product or service.</p>
                                <a href="{{ route('inventory.create') }}" class="btn btn-secondary text-sm" wire:navigate>
                                    Add Item
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-white/5 bg-surface/30">
            {{ $products->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
