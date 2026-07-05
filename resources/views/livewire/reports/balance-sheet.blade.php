<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-text-primary">Balance Sheet</h1>
            <p class="text-sm text-text-muted mt-1">Snapshot of your organization's financial position at a specific point in time.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.balance-sheet.pdf', ['date' => $asOfDate]) }}" target="_blank" class="btn btn-primary">
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
            <label class="block text-sm font-medium text-text-secondary mb-1">As of Date</label>
            <input type="date" wire:model.live="asOfDate" class="form-input">
        </div>
    </div>

    @if(!$reportData['is_balanced'])
        <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex items-start gap-3 text-red-400">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <h4 class="font-medium text-red-400">Balance Sheet Mismatch!</h4>
                <p class="text-sm opacity-90 mt-1">Total Assets do not equal Total Liabilities + Equity. Difference: ₹{{ number_format(abs($reportData['total_assets'] - $reportData['total_liabilities_and_equity']), 2) }}. Check your manual journal entries.</p>
            </div>
        </div>
    @endif

    <div class="glass-card overflow-hidden">
        <div class="p-6 border-b border-white/10 text-center">
            <h2 class="text-lg font-bold text-text-primary uppercase tracking-wider">{{ auth()->user()->currentOrganization->name }}</h2>
            <p class="text-sm text-text-secondary mt-1">Balance Sheet</p>
            <p class="text-xs text-text-muted">As of {{ \Carbon\Carbon::parse($asOfDate)->format('M d, Y') }}</p>
        </div>

        <div class="p-0">
            <table class="w-full text-sm">
                
                <!-- ASSETS SECTION -->
                <thead>
                    <tr class="bg-white/5 border-b border-white/5">
                        <th class="py-3 px-6 text-left font-semibold text-text-primary uppercase tracking-wider" colspan="2">Assets</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($reportData['assets'] as $group)
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
                            <td class="py-4 px-6 text-center text-text-muted" colspan="2">No assets recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-white/5 border-y border-white/10">
                    <tr>
                        <td class="py-4 px-6 font-semibold text-text-primary uppercase">Total Assets</td>
                        <td class="py-4 px-6 text-right font-bold text-green-400">₹{{ number_format($reportData['total_assets'], 2) }}</td>
                    </tr>
                </tfoot>

                <!-- LIABILITIES SECTION -->
                <thead>
                    <tr class="bg-white/5 border-b border-white/5">
                        <th class="py-3 px-6 text-left font-semibold text-text-primary uppercase tracking-wider pt-6" colspan="2">Liabilities</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($reportData['liabilities'] as $group)
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
                            <td class="py-4 px-6 text-center text-text-muted" colspan="2">No liabilities recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-white/5 border-y border-white/10">
                    <tr>
                        <td class="py-4 px-6 font-semibold text-text-secondary uppercase">Total Liabilities</td>
                        <td class="py-4 px-6 text-right font-medium text-text-primary">₹{{ number_format($reportData['total_liabilities'], 2) }}</td>
                    </tr>
                </tfoot>

                <!-- EQUITY SECTION -->
                <thead>
                    <tr class="bg-white/5 border-b border-white/5">
                        <th class="py-3 px-6 text-left font-semibold text-text-primary uppercase tracking-wider pt-6" colspan="2">Equity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($reportData['equity'] as $group)
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
                            <td class="py-4 px-6 text-center text-text-muted" colspan="2">No equity recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-white/5 border-y border-white/10">
                    <tr>
                        <td class="py-4 px-6 font-semibold text-text-secondary uppercase">Total Equity</td>
                        <td class="py-4 px-6 text-right font-medium text-text-primary">₹{{ number_format($reportData['total_equity'], 2) }}</td>
                    </tr>
                </tfoot>

                <!-- TOTAL LIABILITIES & EQUITY SECTION -->
                <tfoot class="bg-accent/10 border-b-4 border-accent">
                    <tr>
                        <td class="py-5 px-6 font-bold text-text-primary uppercase text-base">Total Liabilities & Equity</td>
                        <td class="py-5 px-6 text-right font-bold text-lg {{ $reportData['is_balanced'] ? 'text-green-400' : 'text-red-400' }}">
                            ₹{{ number_format($reportData['total_liabilities_and_equity'], 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
