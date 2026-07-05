<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-text-primary">Chart of Accounts</h1>
            <p class="text-sm text-text-muted mt-1">Manage your ledger accounts and account groups.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="openGroupModal" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Group
            </button>
            <button wire:click="openAccountModal" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                New Account
            </button>
        </div>
    </div>

    <!-- Accounts List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @php
            $types = ['asset' => 'Assets', 'liability' => 'Liabilities', 'equity' => 'Equity', 'revenue' => 'Revenue', 'expense' => 'Expenses'];
        @endphp

        @foreach($types as $typeKey => $typeLabel)
            <div class="glass-card flex flex-col h-full">
                <div class="p-4 border-b border-white/5 flex items-center justify-between">
                    <h2 class="text-lg font-medium text-text-primary uppercase tracking-wider">{{ $typeLabel }}</h2>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-6">
                    @php
                        $typeGroups = $groups->where('type', $typeKey);
                    @endphp
                    @if($typeGroups->isEmpty())
                        <div class="text-sm text-text-muted text-center py-4">No {{ strtolower($typeLabel) }} groups found.</div>
                    @else
                        @foreach($typeGroups as $group)
                            <div>
                                <div class="flex items-center justify-between mb-3 group">
                                    <h3 class="text-sm font-semibold text-text-secondary">{{ $group->code ? $group->code . ' - ' : '' }}{{ $group->name }}</h3>
                                    <button wire:click="openGroupModal({{ $group->id }})" class="opacity-0 group-hover:opacity-100 text-text-muted hover:text-accent transition-opacity">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    @foreach($group->accounts as $account)
                                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-white/5 transition-colors group/account">
                                            <div>
                                                <div class="text-sm text-text-primary">{{ $account->code ? $account->code . ' - ' : '' }}{{ $account->name }}</div>
                                            </div>
                                            <button wire:click="openAccountModal({{ $account->id }})" class="opacity-0 group-hover/account:opacity-100 text-text-muted hover:text-accent transition-opacity">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                    @if($group->accounts->isEmpty())
                                        <div class="text-xs text-text-muted px-2 italic">No accounts</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Account Modal -->
    @if($showAccountModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="$set('showAccountModal', false)"></div>
            <div class="relative bg-surface border border-white/10 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-text-primary mb-6">{{ $accountId ? 'Edit Account' : 'New Account' }}</h2>
                    <form wire:submit="saveAccount" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-text-secondary mb-1">Account Group</label>
                            <select wire:model="accountGroupId" class="form-input w-full">
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ ucfirst($group->type) }} - {{ $group->name }}</option>
                                @endforeach
                            </select>
                            @error('accountGroupId') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-secondary mb-1">Account Name</label>
                            <input type="text" wire:model="accountName" class="form-input w-full" placeholder="e.g. Petty Cash">
                            @error('accountName') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-secondary mb-1">Account Code</label>
                            <input type="text" wire:model="accountCode" class="form-input w-full" placeholder="e.g. 1010">
                            @error('accountCode') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-secondary mb-1">Description</label>
                            <textarea wire:model="accountDescription" class="form-input w-full" rows="2"></textarea>
                            @error('accountDescription') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="mt-8 flex gap-3">
                            <button type="button" wire:click="$set('showAccountModal', false)" class="btn btn-secondary flex-1">Cancel</button>
                            <button type="submit" class="btn btn-primary flex-1">Save Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Group Modal -->
    @if($showGroupModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" wire:click="$set('showGroupModal', false)"></div>
            <div class="relative bg-surface border border-white/10 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-text-primary mb-6">{{ $groupId ? 'Edit Group' : 'New Group' }}</h2>
                    <form wire:submit="saveGroup" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-text-secondary mb-1">Group Type</label>
                            <select wire:model="groupType" class="form-input w-full">
                                <option value="asset">Asset</option>
                                <option value="liability">Liability</option>
                                <option value="equity">Equity</option>
                                <option value="revenue">Revenue</option>
                                <option value="expense">Expense</option>
                            </select>
                            @error('groupType') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-secondary mb-1">Group Name</label>
                            <input type="text" wire:model="groupName" class="form-input w-full" placeholder="e.g. Current Assets">
                            @error('groupName') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text-secondary mb-1">Group Code</label>
                            <input type="text" wire:model="groupCode" class="form-input w-full" placeholder="e.g. 1000">
                            @error('groupCode') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="mt-8 flex gap-3">
                            <button type="button" wire:click="$set('showGroupModal', false)" class="btn btn-secondary flex-1">Cancel</button>
                            <button type="submit" class="btn btn-primary flex-1">Save Group</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
