<div>
    <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Record Receipt</h1>
            <p class="text-sm text-text-secondary mt-1">Log incoming payments from customers</p>
        </div>
        <a href="{{ route('receipts.index') }}" class="btn btn-ghost" wire:navigate>Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Form Details -->
        <div class="lg:col-span-1 space-y-6">
            <div class="glass-card p-6">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Customer <span class="text-red-400">*</span></label>
                        <select wire:model.live="contactId" class="form-input cursor-pointer">
                            <option value="">Select Customer</option>
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->id }}">{{ $contact->display_name }}</option>
                            @endforeach
                        </select>
                        @error('contactId') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Receipt Number <span class="text-red-400">*</span></label>
                        <input wire:model="receiptNumber" type="text" class="form-input">
                        @error('receiptNumber') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Amount Received <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-text-muted">₹</span>
                            </div>
                            <input wire:model.live="amountReceived" type="number" step="0.01" class="form-input pl-8 text-lg font-bold text-accent" placeholder="0.00">
                        </div>
                        @error('amountReceived') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Payment Date</label>
                        <input wire:model="paymentDate" type="text" x-data="datepicker" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Payment Mode</label>
                        <select wire:model="paymentMode" class="form-input cursor-pointer">
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer / NEFT / RTGS</option>
                            <option value="upi">UPI</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Reference Number <span class="text-xs font-normal text-text-muted">(Optional)</span></label>
                        <input wire:model="reference" type="text" class="form-input" placeholder="e.g. UTR Number">
                    </div>

                    <div>
                        <label class="form-label">Notes <span class="text-xs font-normal text-text-muted">(Optional)</span></label>
                        <textarea wire:model="notes" rows="3" class="form-input" placeholder="Internal notes..."></textarea>
                    </div>
                    
                    <button type="button" wire:click="save" class="btn btn-primary w-full mt-4">
                        Save Receipt
                    </button>
                </div>
            </div>
            
            @if($contactId)
            <!-- Status Card -->
            <div class="glass-card p-6 border {{ $unallocated < 0 ? 'border-red-500/50' : 'border-white/5' }}">
                <div class="flex justify-between items-center mb-2 text-sm">
                    <span class="text-text-secondary">Amount Received:</span>
                    <span class="font-medium">₹{{ number_format((float)$amountReceived, 2) }}</span>
                </div>
                <div class="flex justify-between items-center mb-2 text-sm">
                    <span class="text-text-secondary">Allocated:</span>
                    <span class="font-medium">₹{{ number_format($totalAllocated, 2) }}</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-white/10 text-sm font-semibold {{ $unallocated < 0 ? 'text-red-400' : 'text-accent' }}">
                    <span>Unallocated Balance:</span>
                    <span>₹{{ number_format($unallocated, 2) }}</span>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Invoices -->
        <div class="lg:col-span-2">
            <div class="glass-card p-0">
                <div class="p-4 border-b border-white/5 flex items-center justify-between">
                    <h2 class="font-semibold text-text-primary">Open Invoices</h2>
                    @if(count($openInvoices) > 0 && (float)$amountReceived > 0)
                        <button type="button" wire:click="autoAllocate" class="text-xs font-medium text-accent hover:text-accent-light px-3 py-1 rounded bg-accent/10 transition-colors">Auto Allocate</button>
                    @endif
                </div>
                
                @if(!$contactId)
                    <div class="p-12 text-center text-text-muted">
                        Select a customer to view and allocate payments to their open invoices.
                    </div>
                @elseif(count($openInvoices) === 0)
                    <div class="p-12 text-center text-text-muted">
                        This customer has no open invoices. You can still save this receipt as an advance payment.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-white/5 text-text-muted text-[0.65rem] uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3">Invoice</th>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3 text-right">Invoice Total</th>
                                    <th class="px-4 py-3 text-right">Balance Due</th>
                                    <th class="px-4 py-3 w-40 text-right">Allocate (₹)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($openInvoices as $invoice)
                                    <tr class="hover:bg-white/5">
                                        <td class="px-4 py-3 font-medium text-text-primary">{{ $invoice->invoice_number }}</td>
                                        <td class="px-4 py-3 text-text-muted">{{ $invoice->invoice_date->format('d M Y') }}</td>
                                        <td class="px-4 py-3 text-right">₹{{ number_format($invoice->grand_total, 2) }}</td>
                                        <td class="px-4 py-3 text-right font-medium text-red-400">₹{{ number_format($invoice->balance_due, 2) }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <input wire:model.live="allocations.{{ $invoice->id }}" type="number" step="0.01" max="{{ $invoice->balance_due }}" class="form-input py-1.5 text-right w-full text-sm">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
