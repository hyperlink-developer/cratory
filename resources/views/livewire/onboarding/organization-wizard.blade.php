<div class="w-full max-w-lg mx-auto">
    <!-- Header -->
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-text-primary">
            @if($step === 1) Let's set up your business
            @elseif($step === 2) Tax information
            @elseif($step === 3) Business address
            @elseif($step === 4) Your details
            @else Review & create
            @endif
        </h1>
        <p class="text-text-secondary text-sm mt-1">Step {{ $step }} of {{ $totalSteps }}</p>
    </div>

    <!-- Stepper dots -->
    <div class="stepper mb-8">
        @for($i = 1; $i <= $totalSteps; $i++)
            <button
                wire:click="goToStep({{ $i }})"
                class="stepper-dot {{ $i === $step ? 'active' : '' }} {{ $i < $step ? 'completed' : '' }} cursor-pointer"
                @if($i > $step) disabled @endif
            ></button>
        @endfor
    </div>

    <!-- Card -->
    <div class="glass-card p-6 sm:p-8">

        {{-- Step 1: Business Basics --}}
        @if($step === 1)
        <div class="space-y-5" x-data x-init="$el.querySelector('input')?.focus()">
            <div>
                <label for="orgName" class="form-label">Business name <span class="text-red-400">*</span></label>
                <input wire:model.live.debounce.300ms="orgName" id="orgName" type="text" class="form-input" placeholder="e.g. Acme Industries">
                @error('orgName') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="orgType" class="form-label">Business type <span class="text-red-400">*</span></label>
                <select wire:model="orgType" id="orgType" class="form-input cursor-pointer">
                    <option value="">Select type</option>
                    @foreach($this->organizationTypes as $type)
                        <option value="{{ $type['value'] }}">{{ $type['label'] }}</option>
                    @endforeach
                </select>
                @error('orgType') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="form-label">What does your business do? <span class="text-red-400">*</span></label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-2">
                    @foreach($this->businessCategories as $cat)
                        <label class="relative flex items-center justify-center p-4 rounded-xl border cursor-pointer transition-all duration-200
                            {{ $businessCategory === $cat['value'] ? 'border-accent bg-accent/10 text-accent' : 'border-border hover:border-border-light text-text-secondary' }}">
                            <input type="radio" wire:model="businessCategory" value="{{ $cat['value'] }}" class="sr-only">
                            <span class="text-sm font-medium">{{ $cat['label'] }}</span>
                        </label>
                    @endforeach
                </div>
                @error('businessCategory') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="invoicePrefix" class="form-label">Invoice prefix</label>
                <input wire:model="invoicePrefix" id="invoicePrefix" type="text" maxlength="5" class="form-input uppercase" placeholder="CRT">
                <p class="mt-1 text-xs text-text-muted">Used in invoice numbers, e.g. {{ $invoicePrefix ?: 'CRT' }}-INV-2526-0001</p>
            </div>
        </div>

        {{-- Step 2: Tax Details --}}
        @elseif($step === 2)
        <div class="space-y-5">
            <div>
                <label for="panNumber" class="form-label">PAN number <span class="text-red-400">*</span></label>
                <input wire:model="panNumber" id="panNumber" type="text" maxlength="10" class="form-input uppercase" placeholder="ABCDE1234F">
                @error('panNumber') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="gstNumber" class="form-label">GSTIN <span class="text-text-muted text-xs font-normal">(optional)</span></label>
                <input wire:model="gstNumber" id="gstNumber" type="text" maxlength="15" class="form-input uppercase" placeholder="22ABCDE1234F1Z5">
                @error('gstNumber') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs text-text-muted">Leave blank if not GST registered</p>
            </div>

            @if($gstNumber)
            <div class="p-4 rounded-xl border border-white/10 bg-surface-lighter">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input wire:model="isComposition" type="checkbox" class="w-5 h-5 rounded border-white/20 bg-transparent text-primary focus:ring-primary focus:ring-offset-surface">
                    <div class="flex flex-col">
                        <span class="text-sm font-medium text-text-primary">Composition Tax Payer</span>
                        <span class="text-xs text-text-muted mt-0.5">Check this if your business is registered under the GST Composition Scheme</span>
                    </div>
                </label>
            </div>
            @endif
        </div>

        {{-- Step 3: Address --}}
        @elseif($step === 3)
        <div class="space-y-5">
            <div>
                <label for="addressLine1" class="form-label">Address line 1 <span class="text-red-400">*</span></label>
                <input wire:model="addressLine1" id="addressLine1" type="text" class="form-input" placeholder="Building, street">
                @error('addressLine1') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="addressLine2" class="form-label">Address line 2</label>
                <input wire:model="addressLine2" id="addressLine2" type="text" class="form-input" placeholder="Area, landmark">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="city" class="form-label">City <span class="text-red-400">*</span></label>
                    <input wire:model="city" id="city" type="text" class="form-input" placeholder="Mumbai">
                    @error('city') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="state" class="form-label">State <span class="text-red-400">*</span></label>
                    <input wire:model="state" id="state" type="text" class="form-input" placeholder="Maharashtra">
                    @error('state') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="pincode" class="form-label">Pincode <span class="text-red-400">*</span></label>
                    <input wire:model="pincode" id="pincode" type="text" maxlength="10" class="form-input" placeholder="400001">
                    @error('pincode') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="country" class="form-label">Country</label>
                    <input wire:model="country" id="country" type="text" class="form-input" placeholder="India">
                </div>
            </div>
        </div>

        {{-- Step 4: Commander Details --}}
        @elseif($step === 4)
        <div class="space-y-5">
            <div>
                <label for="commanderName" class="form-label">Your name <span class="text-red-400">*</span></label>
                <input wire:model="commanderName" id="commanderName" type="text" class="form-input" placeholder="Full name">
                @error('commanderName') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="commanderEmail" class="form-label">Email <span class="text-red-400">*</span></label>
                <input wire:model="commanderEmail" id="commanderEmail" type="email" class="form-input" placeholder="you@example.com">
                @error('commanderEmail') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="commanderPhone" class="form-label">Phone number</label>
                <input wire:model="commanderPhone" id="commanderPhone" type="tel" class="form-input" placeholder="+91 98765 43210">
                @error('commanderPhone') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Step 5: Review & Create --}}
        @else
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-text-muted uppercase tracking-wider">Review your details</h3>

            <div class="space-y-3">
                <div class="flex justify-between items-start py-2.5 border-b border-white/5">
                    <span class="text-sm text-text-muted">Business name</span>
                    <span class="text-sm text-text-primary font-medium text-right">{{ $orgName }}</span>
                </div>
                <div class="flex justify-between items-start py-2.5 border-b border-white/5">
                    <span class="text-sm text-text-muted">Type</span>
                    <span class="text-sm text-text-primary">{{ $orgType }}</span>
                </div>
                <div class="flex justify-between items-start py-2.5 border-b border-white/5">
                    <span class="text-sm text-text-muted">Category</span>
                    <span class="text-sm text-text-primary">{{ $businessCategory }}</span>
                </div>
                <div class="flex justify-between items-start py-2.5 border-b border-white/5">
                    <span class="text-sm text-text-muted">PAN</span>
                    <span class="text-sm text-text-primary font-mono">{{ strtoupper($panNumber) }}</span>
                </div>
                @if($gstNumber)
                <div class="flex justify-between items-start py-2.5 border-b border-white/5">
                    <span class="text-sm text-text-muted">GSTIN</span>
                    <span class="text-sm text-text-primary font-mono text-right">
                        {{ strtoupper($gstNumber) }}
                        @if($isComposition) <br><span class="text-xs text-accent bg-accent/10 px-2 py-0.5 rounded-full inline-block mt-1">Composition Scheme</span> @endif
                    </span>
                </div>
                @endif
                <div class="flex justify-between items-start py-2.5 border-b border-white/5">
                    <span class="text-sm text-text-muted">Address</span>
                    <span class="text-sm text-text-primary text-right max-w-[60%]">{{ $addressLine1 }}, {{ $city }}, {{ $state }} {{ $pincode }}</span>
                </div>
                <div class="flex justify-between items-start py-2.5 border-b border-white/5">
                    <span class="text-sm text-text-muted">Invoice prefix</span>
                    <span class="text-sm text-text-primary font-mono">{{ $invoicePrefix ?: 'CRT' }}</span>
                </div>
                <div class="flex justify-between items-start py-2.5">
                    <span class="text-sm text-text-muted">Commander</span>
                    <span class="text-sm text-text-primary text-right">{{ $commanderName }}<br><span class="text-text-muted text-xs">{{ $commanderEmail }}</span></span>
                </div>
            </div>

            <div class="mt-4 p-3 rounded-xl bg-accent/5 border border-accent/10">
                <p class="text-xs text-text-secondary">
                    <span class="text-accent font-medium">What happens next:</span> Your organization will be created with default GST tax rates (0%, 5%, 12%, 18%, 28%) and 4 invoice templates. You can customize everything from settings later.
                </p>
            </div>
        </div>
        @endif
    </div>

    <!-- Navigation buttons -->
    <div class="flex items-center justify-between mt-6 gap-4">
        @if($step > 1)
            <button wire:click="previousStep" class="btn btn-ghost cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back
            </button>
        @else
            <div></div>
        @endif

        @if($step < $totalSteps)
            <button wire:click="nextStep" class="btn btn-primary cursor-pointer">
                Continue
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        @else
            <button wire:click="createOrganization" class="btn btn-primary cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
                Create Organization
            </button>
        @endif
    </div>
</div>
