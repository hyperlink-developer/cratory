<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-text-primary">Profit & Loss Statement</h1>
            <p class="text-sm text-text-muted mt-1">Determine your net income over a specific date range.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.profit-loss.pdf', ['start' => $startDate, 'end' => $endDate]) }}" target="_blank" class="btn btn-primary">
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
    </div>

    <div class="glass-card overflow-hidden">
        <div class="p-6 border-b border-white/10 text-center">
            <h2 class="text-lg font-bold text-text-primary uppercase tracking-wider">{{ auth()->user()->currentOrganization->name }}</h2>
            <p class="text-sm text-text-secondary mt-1">Profit and Loss</p>
            <p class="text-xs text-text-muted">From {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
        </div>

        <div class="p-0">
            <table class="w-full text-sm">
                
                <!-- REVENUE SECTION -->
                <thead>
                    <tr class="bg-white/5 border-b border-white/5">
                        <th class="py-3 px-6 text-left font-semibold text-text-primary uppercase tracking-wider" colspan="2">Operating Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($reportData['revenue'] as $group)
                        <tr>
                            <td class="py-2 px-6 font-medium text-text-secondary pt-4" colspan="2">{{ $group['group'] }}</td>
                        </tr>
                        @foreach($group['accounts'] as $account)
                        <tr class="hover:bg-white/5">
                            <td class="py-2 px-10 text-text-muted">{{ $account['code'] ? $account['code'] . ' - ' : '' }}{{ $account['name'] }}</td>
                            <td class="py-2 px-6 text-right text-text-primary">₹{{ number_format($account['balance'], 2) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="py-2 px-6 text-right font-medium text-text-secondary">Total {{ $group['group'] }}</td>
                            <td class="py-2 px-6 text-right font-medium text-text-primary border-t border-white/10">₹{{ number_format($group['total'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-4 px-6 text-center text-text-muted" colspan="2">No revenue recorded for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-white/5 border-y border-white/10">
                    <tr>
                        <td class="py-4 px-6 font-semibold text-text-primary uppercase">Total Operating Revenue</td>
                        <td class="py-4 px-6 text-right font-bold text-green-400">₹{{ number_format($reportData['total_revenue'], 2) }}</td>
                    </tr>
                </tfoot>

                <!-- EXPENSE SECTION -->
                <thead>
                    <tr class="bg-white/5 border-b border-white/5">
                        <th class="py-3 px-6 text-left font-semibold text-text-primary uppercase tracking-wider pt-6" colspan="2">Operating Expenses</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($reportData['expenses'] as $group)
                        <tr>
                            <td class="py-2 px-6 font-medium text-text-secondary pt-4" colspan="2">{{ $group['group'] }}</td>
                        </tr>
                        @foreach($group['accounts'] as $account)
                        <tr class="hover:bg-white/5">
                            <td class="py-2 px-10 text-text-muted">{{ $account['code'] ? $account['code'] . ' - ' : '' }}{{ $account['name'] }}</td>
                            <td class="py-2 px-6 text-right text-text-primary">₹{{ number_format($account['balance'], 2) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="py-2 px-6 text-right font-medium text-text-secondary">Total {{ $group['group'] }}</td>
                            <td class="py-2 px-6 text-right font-medium text-text-primary border-t border-white/10">₹{{ number_format($group['total'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-4 px-6 text-center text-text-muted" colspan="2">No expenses recorded for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-white/5 border-y border-white/10">
                    <tr>
                        <td class="py-4 px-6 font-semibold text-text-primary uppercase">Total Operating Expenses</td>
                        <td class="py-4 px-6 text-right font-bold text-red-400">₹{{ number_format($reportData['total_expenses'], 2) }}</td>
                    </tr>
                </tfoot>

                <!-- NET PROFIT SECTION -->
                <tfoot class="bg-accent/10 border-b-4 border-accent">
                    <tr>
                        <td class="py-5 px-6 font-bold text-text-primary uppercase text-base">Net Profit / (Loss)</td>
                        <td class="py-5 px-6 text-right font-bold text-lg {{ $reportData['net_profit'] >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            ₹{{ number_format($reportData['net_profit'], 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
