<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Payment Vouchers</h1>
            <p class="text-sm text-text-secondary mt-1">Track outgoing vendor payments</p>
        </div>
        <a href="{{ route('vouchers.create') }}" class="btn btn-primary" wire:navigate>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Record Payment
        </a>
    </div>

    <div class="glass-card p-4 mb-6 flex gap-4">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="form-input pl-9" placeholder="Search by voucher or vendor...">
        </div>
    </div>

    <!-- Vouchers List -->
    <div class="glass-card overflow-hidden">
        
        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-white/5">
            @forelse($vouchers as $voucher)
                <div class="p-4 flex flex-col gap-3">
                    <div class="flex items-start justify-between">
                        <div class="min-w-0">
                            <p class="font-bold text-text-primary truncate">{{ $voucher->contact->display_name }}</p>
                            <p class="text-[0.7rem] text-text-muted mt-0.5">{{ $voucher->voucher_number }} • {{ $voucher->payment_date->format('d M, Y') }}</p>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <p class="font-bold text-accent text-sm">₹{{ number_format($voucher->amount, 2) }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[0.65rem] bg-surface-lighter text-text-secondary border border-white/10 uppercase tracking-wide">
                                {{ $voucher->payment_mode }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end pt-2 border-t border-white/5">
                        <button wire:click="deleteVoucher('{{ $voucher->uuid }}')" wire:confirm="Delete this payment voucher and revert bill balances?" class="p-1.5 text-text-muted hover:text-red-400 transition-colors" title="Delete">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-text-muted">
                    No payment vouchers found.
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white/5 border-b border-white/10 text-text-muted uppercase tracking-wider text-[0.625rem] font-semibold">
                    <tr>
                        <th class="px-6 py-4">Voucher No.</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Vendor</th>
                        <th class="px-6 py-4">Payment Mode</th>
                        <th class="px-6 py-4 text-right">Amount</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($vouchers as $voucher)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4 font-medium text-text-primary">{{ $voucher->voucher_number }}</td>
                            <td class="px-6 py-4 text-text-secondary">{{ $voucher->payment_date->format('d M, Y') }}</td>
                            <td class="px-6 py-4 font-medium text-text-primary">{{ $voucher->contact->display_name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-0.5 rounded text-xs bg-surface-lighter text-text-secondary border border-white/10 uppercase tracking-wide">
                                    {{ $voucher->payment_mode }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-accent">₹{{ number_format($voucher->amount, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="deleteVoucher('{{ $voucher->uuid }}')" wire:confirm="Delete this payment voucher and revert bill balances?" class="p-1.5 rounded-lg text-text-muted hover:text-red-400 hover:bg-red-400/10 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-text-muted">No payment vouchers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-white/5 bg-surface/30">
            {{ $vouchers->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
