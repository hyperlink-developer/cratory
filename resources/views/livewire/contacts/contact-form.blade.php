<div>
    <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">{{ $contact ? 'Edit Contact' : 'New Contact' }}</h1>
            <p class="text-sm text-text-secondary mt-1">Fill in the contact details below</p>
        </div>
        <a href="{{ route('contacts.index') }}" class="btn btn-ghost" wire:navigate>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back
        </a>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Basic Info -->
        <div class="glass-card p-6">
            <h2 class="text-sm font-semibold text-text-primary mb-4 border-b border-white/5 pb-2">Basic Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Contact Type <span class="text-red-400">*</span></label>
                    <select wire:model="type" class="form-input cursor-pointer">
                        @foreach($this->contactTypes as $typeOption)
                            <option value="{{ $typeOption['value'] }}">{{ $typeOption['label'] }}</option>
                        @endforeach
                    </select>
                    @error('type') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-start-1">
                    <label class="form-label">Company/Contact Name <span class="text-red-400">*</span></label>
                    <input wire:model="name" type="text" class="form-input" placeholder="Acme Corp">
                    @error('name') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Display Name <span class="text-text-muted font-normal text-xs">(optional)</span></label>
                    <input wire:model="displayName" type="text" class="form-input" placeholder="Acme">
                    <p class="mt-1 text-[0.65rem] text-text-muted">Used in dropdowns and lists</p>
                    @error('displayName') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Email</label>
                    <input wire:model="email" type="email" class="form-input" placeholder="billing@acme.com">
                    @error('email') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Phone</label>
                    <input wire:model="phone" type="text" class="form-input" placeholder="+91 98765 43210">
                    @error('phone') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Tax & Financial -->
        <div class="glass-card p-6">
            <h2 class="text-sm font-semibold text-text-primary mb-4 border-b border-white/5 pb-2">Tax & Financial</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label class="form-label">GSTIN</label>
                    <input wire:model="gstNumber" type="text" maxlength="15" class="form-input uppercase" placeholder="22ABCDE1234F1Z5">
                    @error('gstNumber') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="form-label">PAN Number</label>
                    <input wire:model="panNumber" type="text" maxlength="10" class="form-input uppercase" placeholder="ABCDE1234F">
                    @error('panNumber') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Opening Balance</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-text-muted sm:text-sm">₹</span>
                        </div>
                        <input wire:model="openingBalance" type="number" step="0.01" class="form-input pl-7" placeholder="0.00">
                    </div>
                    @error('openingBalance') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Addresses -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Billing Address -->
            <div class="glass-card p-6">
                <h2 class="text-sm font-semibold text-text-primary mb-4 border-b border-white/5 pb-2">Billing Address</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Address Line 1</label>
                        <input wire:model="billingAddressLine1" type="text" class="form-input" placeholder="Street, Building">
                    </div>
                    <div>
                        <label class="form-label">Address Line 2</label>
                        <input wire:model="billingAddressLine2" type="text" class="form-input" placeholder="Area, Landmark">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">City</label>
                            <input wire:model="billingCity" type="text" class="form-input" placeholder="Mumbai">
                        </div>
                        <div>
                            <label class="form-label">State</label>
                            <input wire:model="billingState" type="text" class="form-input" placeholder="Maharashtra">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Pincode</label>
                            <input wire:model="billingPincode" type="text" class="form-input" placeholder="400001">
                        </div>
                        <div>
                            <label class="form-label">Country</label>
                            <input wire:model="billingCountry" type="text" class="form-input" placeholder="India">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-4 border-b border-white/5 pb-2">
                    <h2 class="text-sm font-semibold text-text-primary">Shipping Address</h2>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model.live="sameAsBilling" type="checkbox" class="w-4 h-4 rounded border-border bg-surface text-accent focus:ring-accent/30">
                        <span class="text-xs text-text-secondary">Same as Billing</span>
                    </label>
                </div>
                
                <div class="space-y-4 {{ $sameAsBilling ? 'opacity-50 pointer-events-none' : '' }}">
                    <div>
                        <label class="form-label">Address Line 1</label>
                        <input wire:model="shippingAddressLine1" type="text" class="form-input" placeholder="Street, Building" {{ $sameAsBilling ? 'disabled' : '' }}>
                    </div>
                    <div>
                        <label class="form-label">Address Line 2</label>
                        <input wire:model="shippingAddressLine2" type="text" class="form-input" placeholder="Area, Landmark" {{ $sameAsBilling ? 'disabled' : '' }}>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">City</label>
                            <input wire:model="shippingCity" type="text" class="form-input" placeholder="Mumbai" {{ $sameAsBilling ? 'disabled' : '' }}>
                        </div>
                        <div>
                            <label class="form-label">State</label>
                            <input wire:model="shippingState" type="text" class="form-input" placeholder="Maharashtra" {{ $sameAsBilling ? 'disabled' : '' }}>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Pincode</label>
                            <input wire:model="shippingPincode" type="text" class="form-input" placeholder="400001" {{ $sameAsBilling ? 'disabled' : '' }}>
                        </div>
                        <div>
                            <label class="form-label">Country</label>
                            <input wire:model="shippingCountry" type="text" class="form-input" placeholder="India" {{ $sameAsBilling ? 'disabled' : '' }}>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4 pt-4">
            <a href="{{ route('contacts.index') }}" class="btn btn-ghost" wire:navigate>Cancel</a>
            <button type="submit" class="btn btn-primary">
                {{ $contact ? 'Save Changes' : 'Create Contact' }}
            </button>
        </div>
    </form>
</div>
