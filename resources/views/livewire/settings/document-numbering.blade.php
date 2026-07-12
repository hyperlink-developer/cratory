<div>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-text-primary">Document Numbering</h1>
        <p class="text-sm text-text-secondary mt-1">Configure prefixes and formats for invoices, receipts, and vouchers.</p>
    </div>

    <div class="glass-card p-6">
        <form wire:submit="save" class="space-y-6">
            <!-- Instructions -->
            <div class="bg-surface-lighter rounded-lg p-4 border border-white/5 mb-6">
                <h3 class="text-sm font-medium text-text-primary mb-2">Available Variables</h3>
                <ul class="list-disc list-inside text-xs text-text-secondary space-y-1">
                    <li><code>{PREFIX}</code> - The document prefix (e.g., INV, REC)</li>
                    <li><code>{DOC_TYPE}</code> - The document type code (e.g., INV, REC, PAY)</li>
                    <li><code>{FY}</code> - The financial year (e.g., 2526)</li>
                    <li><code>{SEQ}</code> - The 4-digit sequence number (e.g., 0001)</li>
                </ul>
                <p class="text-xs text-text-muted mt-2">Example: <code>{PREFIX}-{FY}-{SEQ}</code> will generate <code>INV-2526-0001</code></p>
            </div>

            <!-- Invoices -->
            <div class="space-y-4 pb-4 border-b border-white/5">
                <h2 class="text-lg font-semibold text-text-primary">Invoices</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Prefix</label>
                        <input type="text" wire:model="invoicePrefix" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors" placeholder="INV">
                        @error('invoicePrefix') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Format</label>
                        <input type="text" wire:model="invoiceFormat" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors" placeholder="{PREFIX}-{FY}-{SEQ}">
                        @error('invoiceFormat') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Receipts -->
            <div class="space-y-4 pb-4 border-b border-white/5">
                <h2 class="text-lg font-semibold text-text-primary">Receipts</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Prefix</label>
                        <input type="text" wire:model="receiptPrefix" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors" placeholder="REC">
                        @error('receiptPrefix') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Format</label>
                        <input type="text" wire:model="receiptFormat" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors" placeholder="{PREFIX}-{FY}-{SEQ}">
                        @error('receiptFormat') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Vouchers -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-text-primary">Payment Vouchers</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Prefix</label>
                        <input type="text" wire:model="voucherPrefix" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors" placeholder="PAY">
                        @error('voucherPrefix') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text-secondary mb-1">Format</label>
                        <input type="text" wire:model="voucherFormat" class="w-full bg-surface-lighter border border-white/5 rounded-lg px-4 py-2 text-sm text-text-primary focus:outline-none focus:border-accent/50 focus:ring-1 focus:ring-accent/50 transition-colors" placeholder="{PREFIX}-{FY}-{SEQ}">
                        @error('voucherFormat') <span class="text-xs text-red-400 mt-1">{{ $message }}</span> @enderror
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
