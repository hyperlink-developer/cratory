<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-text-primary">Document Numbering</h1>
        <p class="text-sm text-text-secondary mt-1">Configure prefixes and formats for invoices, receipts, and vouchers.</p>
    </div>

    <div class="glass-card p-6">
        <form wire:submit="save" class="space-y-6">
            
            <!-- Invoices -->
            <div class="space-y-4 pb-4 border-b border-white/5">
                <h2 class="text-lg font-semibold text-text-primary">Invoices</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Prefix</label>
                        <input type="text" wire:model="invoicePrefix" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors" placeholder="INV">
                        @error('invoicePrefix') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Numbering Pattern</label>
                        <select wire:model="invoicePattern" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors">
                            <option value="{PREFIX}{SEP}{FY}{SEP}{SEQ}">Prefix & Year & Number (e.g. INV-2526-0001)</option>
                            <option value="{PREFIX}{SEP}{SEQ}">Prefix & Number (e.g. INV-0001)</option>
                            <option value="{FY}{SEP}{SEQ}">Year & Number (e.g. 2526-0001)</option>
                            <option value="{SEQ}">Number Only (e.g. 0001)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Separator</label>
                        <select wire:model="invoiceSeparator" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors">
                            <option value="-">Hyphen (-)</option>
                            <option value="/">Forward Slash (/)</option>
                            <option value=" ">Space ( )</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Receipts -->
            <div class="space-y-4 pb-4 border-b border-white/5">
                <h2 class="text-lg font-semibold text-text-primary">Receipts</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Prefix</label>
                        <input type="text" wire:model="receiptPrefix" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors" placeholder="REC">
                        @error('receiptPrefix') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Numbering Pattern</label>
                        <select wire:model="receiptPattern" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors">
                            <option value="{PREFIX}{SEP}{FY}{SEP}{SEQ}">Prefix & Year & Number (e.g. REC-2526-0001)</option>
                            <option value="{PREFIX}{SEP}{SEQ}">Prefix & Number (e.g. REC-0001)</option>
                            <option value="{FY}{SEP}{SEQ}">Year & Number (e.g. 2526-0001)</option>
                            <option value="{SEQ}">Number Only (e.g. 0001)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Separator</label>
                        <select wire:model="receiptSeparator" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors">
                            <option value="-">Hyphen (-)</option>
                            <option value="/">Forward Slash (/)</option>
                            <option value=" ">Space ( )</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Vouchers -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-text-primary">Payment Vouchers</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Prefix</label>
                        <input type="text" wire:model="voucherPrefix" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors" placeholder="PAY">
                        @error('voucherPrefix') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Numbering Pattern</label>
                        <select wire:model="voucherPattern" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors">
                            <option value="{PREFIX}{SEP}{FY}{SEP}{SEQ}">Prefix & Year & Number (e.g. PAY-2526-0001)</option>
                            <option value="{PREFIX}{SEP}{SEQ}">Prefix & Number (e.g. PAY-0001)</option>
                            <option value="{FY}{SEP}{SEQ}">Year & Number (e.g. 2526-0001)</option>
                            <option value="{SEQ}">Number Only (e.g. 0001)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Separator</label>
                        <select wire:model="voucherSeparator" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors">
                            <option value="-">Hyphen (-)</option>
                            <option value="/">Forward Slash (/)</option>
                            <option value=" ">Space ( )</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent/90 text-white text-sm font-medium rounded-lg transition-colors shadow-lg shadow-accent/20">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
