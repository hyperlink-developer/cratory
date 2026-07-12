<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Invoices</h1>
            <p class="text-sm text-text-secondary mt-1">Manage your sales and service invoices</p>
        </div>
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="btn btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Create Invoice
                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            
            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 glass-dropdown p-2 space-y-1" style="z-index: 60;">
                <a href="{{ route('invoices.create', ['type' => 'sales']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 text-text-primary text-sm font-medium transition-colors cursor-pointer">
                    <span class="w-8 h-8 rounded-lg bg-accent/15 flex items-center justify-center text-accent">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                    </span>
                    Sales Invoice
                </a>
                <a href="{{ route('invoices.create', ['type' => 'service']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 text-text-primary text-sm font-medium transition-colors cursor-pointer">
                    <span class="w-8 h-8 rounded-lg bg-blue-500/15 flex items-center justify-center text-blue-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" /></svg>
                    </span>
                    Service Invoice
                </a>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="glass-card p-4 mb-6 flex flex-col sm:flex-row gap-4">
        <!-- Search -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-4 h-4 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="form-input pl-9" placeholder="Search invoices...">
        </div>

        <!-- Filter -->
        <div class="w-full sm:w-48">
            <select wire:model.live="statusFilter" class="form-input cursor-pointer">
                <option value="">All Statuses</option>
                <option value="draft">Draft</option>
                <option value="sent">Sent</option>
                <option value="partial">Partial</option>
                <option value="paid">Paid</option>
                <option value="overdue">Overdue</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    <!-- Invoices List -->
    <div class="glass-card overflow-hidden">
        
        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-white/5">
            @forelse($invoices as $invoice)
                <div class="p-4 flex flex-col gap-3">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-surface-lighter flex items-center justify-center flex-shrink-0 text-text-secondary font-semibold text-xs border border-white/5">
                                {{ $invoice->invoice_date ? $invoice->invoice_date->format('M d') : '-' }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-text-primary">{{ $invoice->invoice_number ?? 'Draft' }}</p>
                                <p class="text-xs text-text-muted mt-0.5">{{ $invoice->contact?->display_name ?? 'Unknown Contact' }}</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-bold text-text-primary text-sm">₹{{ number_format($invoice->grand_total, 2) }}</p>
                            <span class="status-pill status-{{ $invoice->status?->value ?? "" }} inline-block mt-1 scale-90 origin-top-right">{{ $invoice->status?->label() ?? "" }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-white/5">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full {{ $invoice->invoice_type->value === 'sales' ? 'bg-accent' : 'bg-blue-400' }}"></span>
                            <span class="text-[0.65rem] text-text-muted capitalize">{{ $invoice->invoice_type->value }} Invoice</span>
                            @if($invoice->balance_due > 0 && $invoice->status?->value !== 'draft')
                                <span class="mx-1 text-text-muted/30">•</span>
                                <span class="text-[0.65rem] text-red-400">Due: ₹{{ number_format($invoice->balance_due, 2) }}</span>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button type="button" wire:click="$dispatch('open-download-modal', { invoiceUuid: '{{ $invoice->uuid }}' })" class="p-1.5 text-text-muted hover:text-green-400 transition-colors" title="Download PDF">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                            </button>
                            <a href="{{ route('invoices.edit', $invoice->uuid) }}" class="p-1.5 text-text-muted hover:text-accent transition-colors" title="Edit" wire:navigate>
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                            </a>
                            @if($invoice->status?->value === 'draft')
                            <button wire:click="deleteInvoice('{{ $invoice->uuid }}')" wire:confirm="Delete this draft invoice?" class="p-1.5 text-text-muted hover:text-red-400 transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-white/5 mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    </div>
                    <p class="text-text-primary font-medium">No invoices found</p>
                    <p class="text-sm text-text-muted mt-1 mb-4">Get started by creating a new sales or service invoice.</p>
                    <a href="{{ route('invoices.create', ['type' => 'sales']) }}" class="btn btn-outline" wire:navigate>Create Invoice</a>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white/5 border-b border-white/10 text-text-muted uppercase tracking-wider text-[0.625rem] font-semibold">
                    <tr>
                        <th class="px-6 py-4">Invoice Details</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Amount</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-surface-lighter flex items-center justify-center flex-shrink-0 text-text-secondary font-semibold text-xs border border-white/5">
                                        {{ $invoice->invoice_date ? $invoice->invoice_date->format('M d') : '-' }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-text-primary">{{ $invoice->invoice_number ?? 'Draft' }}</p>
                                        @if($invoice->invoice_basis === \App\Enums\InvoiceBasis::Cash)
                                            <p class="text-xs text-text-muted">Cash</p>
                                        @else
                                            <p class="text-xs text-text-muted">Due: {{ $invoice->due_date ? $invoice->due_date->format('d M, Y') : '-' }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-text-primary font-medium">{{ $invoice->contact?->display_name ?? 'Unknown Contact' }}</p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <span class="inline-block w-2 h-2 rounded-full {{ $invoice->invoice_type->value === 'sales' ? 'bg-accent' : 'bg-blue-400' }}"></span>
                                    <p class="text-xs text-text-muted capitalize">{{ $invoice->invoice_type->value }} Invoice</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="status-pill status-{{ $invoice->status?->value ?? "" }}">{{ $invoice->status?->label() ?? "" }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="font-medium text-text-primary">₹{{ number_format($invoice->grand_total, 2) }}</p>
                                @if($invoice->balance_due > 0 && $invoice->status?->value !== 'draft')
                                    <p class="text-[0.65rem] text-red-400 mt-0.5">Due: ₹{{ number_format($invoice->balance_due, 2) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                    <button type="button" wire:click="$dispatch('open-download-modal', { invoiceUuid: '{{ $invoice->uuid }}' })" class="p-1.5 rounded-lg text-text-muted hover:text-green-400 hover:bg-green-400/10 transition-colors" title="Download PDF">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                    </button>
                                    <a href="{{ route('invoices.edit', $invoice->uuid) }}" class="p-1.5 rounded-lg text-text-muted hover:text-accent hover:bg-accent/10 transition-colors" title="Edit" wire:navigate>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                    @if($invoice->status?->value === 'draft')
                                    <button wire:click="deleteInvoice('{{ $invoice->uuid }}')" wire:confirm="Delete this draft invoice?" class="p-1.5 rounded-lg text-text-muted hover:text-red-400 hover:bg-red-400/10 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-text-primary mb-1">No invoices found</h3>
                                <p class="text-xs text-text-muted mb-4">Get started by creating a new sales or service invoice.</p>
                                <a href="{{ route('invoices.create', ['type' => 'sales']) }}" class="btn btn-secondary text-sm" wire:navigate>
                                    Create Invoice
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-white/5 bg-surface/30">
            {{ $invoices->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
    <livewire:invoices.invoice-download-modal />
</div>
