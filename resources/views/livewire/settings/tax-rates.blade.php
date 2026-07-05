<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Tax Rates</h1>
            <p class="text-sm text-text-secondary mt-1">Manage applicable taxes for your invoices and items.</p>
        </div>
        <button wire:click="create" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Tax Rate
        </button>
    </div>

    <div class="glass-card overflow-hidden">
        
        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-white/5">
            @forelse($taxRates as $tax)
                <div class="p-4 flex flex-col gap-3">
                    <div class="flex items-start justify-between">
                        <div class="min-w-0">
                            <p class="font-bold text-text-primary truncate">{{ $tax->name }}</p>
                            <p class="text-xs text-text-primary font-mono mt-0.5">{{ rtrim(rtrim($tax->percentage, '0'), '.') }}%</p>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <button wire:click="toggleActive('{{ $tax->uuid }}')" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $tax->is_active ? 'bg-primary' : 'bg-surface-lighter' }}">
                                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $tax->is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-2 border-t border-white/5">
                        <div>
                            @if($tax->is_gst)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.65rem] font-medium uppercase tracking-wide bg-accent/10 text-accent border border-accent/20">
                                    GST
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.65rem] font-medium uppercase tracking-wide bg-surface-lighter text-text-secondary border border-white/10">
                                    Other
                                </span>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="edit('{{ $tax->uuid }}')" class="p-1.5 text-text-muted hover:text-accent transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                            </button>
                            <button wire:click="delete('{{ $tax->uuid }}')" wire:confirm="Delete this tax rate?" class="p-1.5 text-text-muted hover:text-red-400 transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-text-muted">
                    No tax rates found.
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white/5 border-b border-white/10 text-text-muted uppercase tracking-wider text-[0.625rem] font-semibold">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Percentage</th>
                        <th class="px-6 py-4">GST Type</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($taxRates as $tax)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4">
                                <p class="font-medium text-text-primary">{{ $tax->name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-text-primary font-mono">{{ rtrim(rtrim($tax->percentage, '0'), '.') }}%</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($tax->is_gst)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.65rem] font-medium uppercase tracking-wide bg-accent/10 text-accent border border-accent/20">
                                        GST
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.65rem] font-medium uppercase tracking-wide bg-surface-lighter text-text-secondary border border-white/10">
                                        Other
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="toggleActive('{{ $tax->uuid }}')" class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $tax->is_active ? 'bg-primary' : 'bg-surface-lighter' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $tax->is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="edit('{{ $tax->uuid }}')" class="p-1.5 rounded-lg text-text-muted hover:text-accent hover:bg-accent/10 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </button>
                                    <button wire:click="delete('{{ $tax->uuid }}')" wire:confirm="Delete this tax rate?" class="p-1.5 rounded-lg text-text-muted hover:text-red-400 hover:bg-red-400/10 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-text-muted">No tax rates found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Create/Edit -->
    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 60;">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-surface/80 backdrop-blur-sm" wire:click="$set('showModal', false)"></div>
            
            <!-- Modal Content -->
            <div class="relative w-full max-w-md glass-card p-6 shadow-2xl">
                <h3 class="text-lg font-bold text-text-primary mb-4">{{ $editingId ? 'Edit Tax Rate' : 'New Tax Rate' }}</h3>
                
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="form-label">Tax Name</label>
                        <input wire:model="name" type="text" class="form-input" placeholder="e.g. GST 18%">
                        @error('name') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="form-label">Percentage</label>
                        <div class="relative">
                            <input wire:model="percentage" type="number" step="0.01" class="form-input pr-8" placeholder="18">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-text-muted">%</span>
                            </div>
                        </div>
                        @error('percentage') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input wire:model="isGst" type="checkbox" id="isGst" class="w-4 h-4 rounded border-border bg-surface text-accent focus:ring-accent/30 cursor-pointer">
                        <label for="isGst" class="text-sm text-text-secondary cursor-pointer">Is this a GST rate?</label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/5 mt-4">
                        <button type="button" wire:click="$set('showModal', false)" class="btn btn-ghost px-4 py-2">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 py-2">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
