<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Contacts</h1>
            <p class="text-sm text-text-secondary mt-1">Manage your customers and vendors</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('contacts.create') }}" class="btn btn-primary" wire:navigate>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Contact
            </a>
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
            <input wire:model.live.debounce.300ms="search" type="text" class="form-input pl-9" placeholder="Search contacts...">
        </div>

        <!-- Filter -->
        <div class="w-full sm:w-48">
            <select wire:model.live="typeFilter" class="form-input cursor-pointer">
                <option value="">All Types</option>
                <option value="customer">Customer</option>
                <option value="vendor">Vendor</option>
                <option value="both">Both</option>
            </select>
        </div>
    </div>

    <!-- Contacts List -->
    <div class="glass-card overflow-hidden">
        
        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-white/5">
            @forelse($contacts as $contact)
                <div class="p-4 flex flex-col gap-3">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-accent/15 text-accent flex items-center justify-center font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($contact->display_name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-text-primary truncate">{{ $contact->display_name }}</p>
                                @if($contact->name !== $contact->display_name)
                                    <p class="text-[0.65rem] text-text-muted mt-0.5 truncate">{{ $contact->name }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[0.65rem] font-medium bg-surface-lighter text-text-secondary border border-white/10 uppercase tracking-wide">
                                {{ $contact->type->label() }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-white/5">
                        <div class="flex flex-col gap-0.5 min-w-0">
                            @if($contact->email)
                                <p class="text-[0.7rem] text-text-secondary truncate">{{ $contact->email }}</p>
                            @endif
                            @if($contact->phone)
                                <p class="text-[0.7rem] text-text-muted truncate">{{ $contact->phone }}</p>
                            @endif
                            @if(!$contact->email && !$contact->phone)
                                <p class="text-[0.7rem] text-text-muted italic">No contact info</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <span class="font-bold text-sm {{ $contact->opening_balance > 0 ? 'text-primary' : 'text-text-primary' }}">
                                    ₹{{ number_format($contact->opening_balance, 2) }}
                                </span>
                            </div>
                            <div class="flex gap-1 border-l border-white/10 pl-2">
                                <a href="{{ route('contacts.edit', $contact->uuid) }}" class="p-1.5 text-text-muted hover:text-accent transition-colors" title="Edit" wire:navigate>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                </a>
                                <button wire:click="deleteContact('{{ $contact->uuid }}')" wire:confirm="Are you sure you want to delete this contact?" class="p-1.5 text-text-muted hover:text-red-400 transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-white/5 mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                    </div>
                    <p class="text-text-primary font-medium">No contacts found</p>
                    <p class="text-sm text-text-muted mt-1 mb-4">Get started by creating a new customer or vendor.</p>
                    <a href="{{ route('contacts.create') }}" class="btn btn-outline" wire:navigate>Add Contact</a>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white/5 border-b border-white/10 text-text-muted uppercase tracking-wider text-[0.625rem] font-semibold">
                    <tr>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Contact Info</th>
                        <th class="px-6 py-4 text-right">Balance</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($contacts as $contact)
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-accent/15 text-accent flex items-center justify-center font-bold text-sm">
                                        {{ strtoupper(substr($contact->display_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-text-primary">{{ $contact->display_name }}</p>
                                        @if($contact->name !== $contact->display_name)
                                            <p class="text-xs text-text-muted">{{ $contact->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-surface-lighter text-text-secondary border border-white/10">
                                    {{ $contact->type->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-text-primary">{{ $contact->email ?? '-' }}</p>
                                <p class="text-xs text-text-muted">{{ $contact->phone ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <!-- Opening balance placeholder, real balance requires ledger calculation -->
                                <span class="font-medium {{ $contact->opening_balance > 0 ? 'text-primary' : 'text-text-primary' }}">
                                    ₹{{ number_format($contact->opening_balance, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('contacts.edit', $contact->uuid) }}" class="p-1.5 rounded-lg text-text-muted hover:text-accent hover:bg-accent/10 transition-colors" title="Edit" wire:navigate>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>
                                    <button wire:click="deleteContact('{{ $contact->uuid }}')" wire:confirm="Are you sure you want to delete this contact?" class="p-1.5 rounded-lg text-text-muted hover:text-red-400 hover:bg-red-400/10 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-text-primary mb-1">No contacts found</h3>
                                <p class="text-xs text-text-muted mb-4">Get started by creating a new customer or vendor.</p>
                                <a href="{{ route('contacts.create') }}" class="btn btn-secondary text-sm" wire:navigate>
                                    Add Contact
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-white/5 bg-surface/30">
            {{ $contacts->links(data: ['scrollTo' => false]) }}
        </div>
    </div>
</div>
