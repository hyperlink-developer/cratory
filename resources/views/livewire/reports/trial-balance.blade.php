<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-text-primary">Trial Balance</h1>
            <p class="text-sm text-text-muted mt-1">Verify that your total debits equal your total credits.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('reports.trial-balance.pdf', ['date' => $asOfDate]) }}" target="_blank" class="btn btn-primary">
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
                <h4 class="font-medium text-red-400">Trial Balance Mismatch!</h4>
                <p class="text-sm opacity-90 mt-1">Total Debits and Credits do not match. Difference: ₹{{ number_format(abs($reportData['total_debit'] - $reportData['total_credit']), 2) }}. Check your manual journal entries.</p>
            </div>
        </div>
    @endif

    <div class="glass-card overflow-hidden">
        <div class="p-6 border-b border-white/10 text-center">
            <h2 class="text-lg font-bold text-text-primary uppercase tracking-wider">{{ auth()->user()->currentOrganization->name }}</h2>
            <p class="text-sm text-text-secondary mt-1">Trial Balance</p>
            <p class="text-xs text-text-muted">As of {{ \Carbon\Carbon::parse($asOfDate)->format('M d, Y') }}</p>
        </div>

        <div class="p-0 overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-white/5 border-b border-white/5">
                        <th class="py-3 px-6 text-left font-semibold text-text-muted uppercase tracking-wider">Account</th>
                        <th class="py-3 px-6 text-right font-semibold text-text-muted uppercase tracking-wider w-40">Debit (₹)</th>
                        <th class="py-3 px-6 text-right font-semibold text-text-muted uppercase tracking-wider w-40">Credit (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($reportData['lines'] as $line)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-3 px-6">
                                <span class="text-text-primary">{{ $line['account_code'] ? $line['account_code'] . ' - ' : '' }}{{ $line['account_name'] }}</span>
                                <span class="block text-xs text-text-muted capitalize">{{ $line['type'] }}</span>
                            </td>
                            <td class="py-3 px-6 text-right text-text-primary">
                                {{ $line['debit'] > 0 ? number_format($line['debit'], 2) : '-' }}
                            </td>
                            <td class="py-3 px-6 text-right text-text-primary">
                                {{ $line['credit'] > 0 ? number_format($line['credit'], 2) : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-8 px-6 text-center text-text-muted" colspan="3">No ledger activity found as of this date.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-white/5 border-y border-white/10">
                    <tr>
                        <td class="py-5 px-6 font-bold text-text-primary uppercase text-right">Totals</td>
                        <td class="py-5 px-6 text-right font-bold {{ $reportData['is_balanced'] ? 'text-green-400' : 'text-red-400' }} border-t border-white/10">
                            {{ number_format($reportData['total_debit'], 2) }}
                        </td>
                        <td class="py-5 px-6 text-right font-bold {{ $reportData['is_balanced'] ? 'text-green-400' : 'text-red-400' }} border-t border-white/10">
                            {{ number_format($reportData['total_credit'], 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
