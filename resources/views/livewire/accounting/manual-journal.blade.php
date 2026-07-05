<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-text-primary">Manual Journal Entry</h1>
            <p class="text-sm text-text-muted mt-1">Post a double-entry journal directly to the ledger.</p>
        </div>
        <button wire:click="save" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Post Journal
        </button>
    </div>

    @error('totals')
        <div class="p-4 bg-red-500/10 border border-red-500/20 rounded-xl text-red-400 text-sm">
            {{ $message }}
        </div>
    @enderror

    <div class="glass-card p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1">Date *</label>
                <input type="date" wire:model="date" class="form-input w-full">
                @error('date') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1">Reference Number</label>
                <input type="text" wire:model="referenceNumber" class="form-input w-full" placeholder="e.g. ADJ-001">
                @error('referenceNumber') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-text-secondary mb-1">Notes / Description</label>
                <textarea wire:model="description" class="form-input w-full" rows="2" placeholder="Why are you posting this journal?"></textarea>
                @error('description') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm font-medium text-text-muted border-b border-white/5">
                        <th class="pb-3 pl-4 w-1/3">Account *</th>
                        <th class="pb-3 pl-4">Description</th>
                        <th class="pb-3 pl-4 w-32 text-right">Debit (₹)</th>
                        <th class="pb-3 pl-4 w-32 text-right">Credit (₹)</th>
                        <th class="pb-3 pr-4 w-12 text-center"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($lines as $index => $line)
                    <tr class="group">
                        <td class="py-3 pl-4">
                            <select wire:model="lines.{{ $index }}.account_id" class="form-input w-full text-sm">
                                <option value="">Select Account</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->code ? $account->code . ' - ' : '' }}{{ $account->name }}</option>
                                @endforeach
                            </select>
                            @error('lines.'.$index.'.account_id') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                        </td>
                        <td class="py-3 pl-4">
                            <input type="text" wire:model="lines.{{ $index }}.description" class="form-input w-full text-sm" placeholder="Line description">
                        </td>
                        <td class="py-3 pl-4">
                            <input type="number" step="0.01" min="0" wire:model.live="lines.{{ $index }}.debit" class="form-input w-full text-sm text-right">
                        </td>
                        <td class="py-3 pl-4">
                            <input type="number" step="0.01" min="0" wire:model.live="lines.{{ $index }}.credit" class="form-input w-full text-sm text-right">
                        </td>
                        <td class="py-3 pr-4 text-center">
                            @if(count($lines) > 2)
                                <button type="button" wire:click="removeLine({{ $index }})" class="text-text-muted hover:text-red-400 transition-colors p-1" title="Remove Line">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-white/10 font-semibold">
                        <td colspan="2" class="py-4 pl-4 text-right text-text-secondary">
                            <button type="button" wire:click="addLine" class="text-accent hover:text-accent-hover text-sm font-medium mr-4 float-left flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Line
                            </button>
                            Totals:
                        </td>
                        <td class="py-4 pl-4 text-right text-text-primary text-lg {{ $this->totalDebit != $this->totalCredit ? 'text-red-400' : 'text-green-400' }}">
                            {{ number_format($this->totalDebit, 2) }}
                        </td>
                        <td class="py-4 pl-4 text-right text-text-primary text-lg {{ $this->totalDebit != $this->totalCredit ? 'text-red-400' : 'text-green-400' }}">
                            {{ number_format($this->totalCredit, 2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
