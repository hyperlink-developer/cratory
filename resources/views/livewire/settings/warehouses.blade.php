<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Warehouses</h1>
            <p class="text-sm text-text-secondary mt-1">Manage physical locations for your inventory</p>
        </div>
        <button wire:click="create" class="btn btn-primary">
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Warehouse
        </button>
    </div>

    <div class="glass-card overflow-hidden">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-white/5 border-b border-white/10 text-text-muted uppercase tracking-wider text-[0.625rem] font-semibold">
                <tr>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Code</th>
                    <th class="px-6 py-4">City / State</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($warehouses as $warehouse)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-text-primary">{{ $warehouse->name }}</span>
                                @if($warehouse->is_primary)
                                    <span class="px-2 py-0.5 rounded text-[0.65rem] font-medium bg-accent/20 text-accent">Primary</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-text-secondary">
                            {{ $warehouse->code ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-text-secondary">
                            {{ $warehouse->city ?? '-' }}, {{ $warehouse->state ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="edit({{ $warehouse->id }})" class="p-1.5 text-text-muted hover:text-accent transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                            </button>
                            <button wire:click="delete({{ $warehouse->id }})" wire:confirm="Are you sure you want to delete this warehouse?" class="p-1.5 text-text-muted hover:text-red-400 transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-text-muted">
                            No warehouses found. Click "Add Warehouse" to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom glass-card text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/10">
                <form wire:submit="save">
                    <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-surface-lighter">
                        <h3 class="text-lg font-medium text-text-primary" id="modal-title">
                            {{ $editId ? 'Edit Warehouse' : 'Add Warehouse' }}
                        </h3>
                        <button type="button" wire:click="closeModal" class="text-text-muted hover:text-text-primary">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="form-label">Warehouse Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="name" class="form-input" required>
                                @error('name') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="form-label">Code</label>
                                <input type="text" wire:model="code" class="form-input">
                                @error('code') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="form-label">Address Line 1</label>
                                <input type="text" wire:model="address_line_1" class="form-input">
                                @error('address_line_1') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-2">
                                <label class="form-label">Address Line 2</label>
                                <input type="text" wire:model="address_line_2" class="form-input">
                                @error('address_line_2') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="form-label">City</label>
                                <input type="text" wire:model="city" class="form-input">
                                @error('city') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="form-label">State</label>
                                <input type="text" wire:model="state" class="form-input">
                                @error('state') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="form-label">Pincode</label>
                                <input type="text" wire:model="pincode" class="form-input">
                                @error('pincode') <span class="form-error">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex items-center mt-4">
                            <input type="checkbox" wire:model="is_primary" id="is_primary" class="form-checkbox">
                            <label for="is_primary" class="ml-2 block text-sm text-text-primary">
                                Set as Primary Warehouse
                            </label>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-white/5 bg-surface-lighter flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="btn btn-outline">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
