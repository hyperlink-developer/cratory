<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-text-primary">Purchase Report</h1>
            <p class="text-sm text-text-muted mt-1">View your purchase history and expenses over a period of time.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.purchases.csv', ['start' => $startDate, 'end' => $endDate, 'status' => $status]) }}" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export CSV
            </a>
            <a href="{{ route('reports.purchases.pdf', ['start' => $startDate, 'end' => $endDate, 'status' => $status]) }}" target="_blank" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card p-4 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-medium text-text-secondary mb-1">Start Date</label>
            <input type="date" wire:model.live="startDate" class="form-input">
        </div>
        <div>
            <label class="block text-sm font-medium text-text-secondary mb-1">End Date</label>
            <input type="date" wire:model.live="endDate" class="form-input">
        </div>
        <div>
            <label class="block text-sm font-medium text-text-secondary mb-1">Status</label>
            <select wire:model.live="status" class="form-input">
                <option value="">All Statuses</option>
                <option value="draft">Draft</option>
                <option value="received">Received</option>
                <option value="partial">Partial</option>
                <option value="paid">Paid</option>
            </select>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="glass-card p-6">
            <h3 class="text-sm font-medium text-text-secondary uppercase tracking-wider">Total Purchases (Inc. Tax)</h3>
            <p class="text-3xl font-semibold text-text-primary mt-2">₹{{ number_format($totalPurchases, 2) }}</p>
        </div>
        <div class="glass-card p-6">
            <h3 class="text-sm font-medium text-text-secondary uppercase tracking-wider">Total Tax Paid</h3>
            <p class="text-3xl font-semibold text-text-primary mt-2">₹{{ number_format($totalTax, 2) }}</p>
        </div>
        <div class="glass-card p-6">
            <h3 class="text-sm font-medium text-text-secondary uppercase tracking-wider">Purchase Count</h3>
            <p class="text-3xl font-semibold text-text-primary mt-2">{{ count($purchases) }}</p>
        </div>
    </div>

    <!-- Report Table -->
    <div class="glass-card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm font-medium text-text-muted border-b border-white/5 bg-white/5">
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Bill #</th>
                        <th class="py-3 px-4">Vendor</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Subtotal</th>
                        <th class="py-3 px-4 text-right">Tax</th>
                        <th class="py-3 px-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($purchases as $purchase)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-3 px-4 text-sm text-text-primary whitespace-nowrap">{{ $purchase->purchase_date->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-sm font-medium text-text-primary">
                                <a href="{{ route('purchases.edit', $purchase->uuid) }}" class="hover:text-accent hover:underline">{{ $purchase->bill_number ?? $purchase->purchase_number }}</a>
                            </td>
                            <td class="py-3 px-4 text-sm text-text-secondary">{{ $purchase->contact?->display_name }}</td>
                            <td class="py-3 px-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $purchase->status?->value === 'paid' ? 'bg-green-500/10 text-green-400' : '' }}
                                    {{ $purchase->status?->value === 'received' ? 'bg-blue-500/10 text-blue-400' : '' }}
                                    {{ $purchase->status?->value === 'draft' ? 'bg-white/10 text-text-secondary' : '' }}
                                    {{ $purchase->status?->value === 'partial' ? 'bg-yellow-500/10 text-yellow-400' : '' }}
                                ">
                                    {{ ucfirst($purchase->status?->value ?? '') }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm text-right text-text-secondary">₹{{ number_format($purchase->subtotal, 2) }}</td>
                            <td class="py-3 px-4 text-sm text-right text-text-secondary">₹{{ number_format($purchase->tax_total, 2) }}</td>
                            <td class="py-3 px-4 text-sm text-right font-medium text-text-primary">₹{{ number_format($purchase->grand_total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-text-muted">No purchases found for the selected period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
