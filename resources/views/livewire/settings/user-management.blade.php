<div>
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white mb-1">User Management</h1>
            <p class="text-text-muted text-sm">Manage staff and administrators for your organization.</p>
        </div>
        <button wire:click="openAddModal" class="btn bg-primary hover:bg-primary-light text-surface px-4 py-2 rounded-lg font-medium transition-colors shadow-lg flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add User
        </button>
    </div>

    <!-- Desktop View: Table -->
    <div class="hidden md:block glass-card rounded-2xl border border-white/5 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/5 bg-surface-lighter/20">
                    <th class="p-4 text-xs font-semibold text-text-muted uppercase tracking-wider">Name</th>
                    <th class="p-4 text-xs font-semibold text-text-muted uppercase tracking-wider">Email</th>
                    <th class="p-4 text-xs font-semibold text-text-muted uppercase tracking-wider">Role</th>
                    <th class="p-4 text-xs font-semibold text-text-muted uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($this->users as $user)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold text-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="text-white font-medium">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="p-4 text-text-muted text-sm">{{ $user->email }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-md text-xs font-medium 
                                {{ $user->pivot->role === 'commander' ? 'bg-purple-500/20 text-purple-400' : '' }}
                                {{ $user->pivot->role === 'org_admin' ? 'bg-primary/20 text-primary' : '' }}
                                {{ $user->pivot->role === 'accountant' ? 'bg-blue-500/20 text-blue-400' : '' }}
                                {{ $user->pivot->role === 'staff' ? 'bg-gray-500/20 text-gray-400' : '' }}
                            ">
                                {{ App\Enums\OrgUserRole::from($user->pivot->role)->label() }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openEditModal({{ $user->id }})" class="p-2 text-text-muted hover:text-white hover:bg-white/5 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                @if(auth()->id() !== $user->id)
                                    <button wire:click="removeUser({{ $user->id }})" wire:confirm="Are you sure you want to remove this user from the organization?" class="p-2 text-text-muted hover:text-red-400 hover:bg-red-400/10 rounded-lg transition-colors" title="Remove">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile View: Cards -->
    <div class="md:hidden space-y-4">
        @foreach($this->users as $user)
            <div class="glass-card p-4 rounded-xl border border-white/5 flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-white font-medium">{{ $user->name }}</div>
                        <div class="text-text-muted text-xs">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="flex items-center justify-between border-t border-white/5 pt-4">
                    <span class="px-2 py-1 rounded-md text-xs font-medium 
                        {{ $user->pivot->role === 'commander' ? 'bg-purple-500/20 text-purple-400' : '' }}
                        {{ $user->pivot->role === 'org_admin' ? 'bg-primary/20 text-primary' : '' }}
                        {{ $user->pivot->role === 'accountant' ? 'bg-blue-500/20 text-blue-400' : '' }}
                        {{ $user->pivot->role === 'staff' ? 'bg-gray-500/20 text-gray-400' : '' }}
                    ">
                        {{ App\Enums\OrgUserRole::from($user->pivot->role)->label() }}
                    </span>
                    
                    <div class="flex gap-2">
                        <button wire:click="openEditModal({{ $user->id }})" class="btn-secondary px-3 py-1.5 text-xs">Edit</button>
                        @if(auth()->id() !== $user->id)
                            <button wire:click="removeUser({{ $user->id }})" wire:confirm="Remove this user?" class="bg-red-500/10 text-red-400 hover:bg-red-500/20 px-3 py-1.5 rounded-lg font-medium text-xs transition-colors">Remove</button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add/Edit User Modal -->
    <div x-data="{ show: @entangle('showUserModal') }" 
         x-show="show" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0"
         x-cloak>
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="show = false"></div>
        
        <div class="bg-surface border border-white/10 rounded-2xl shadow-2xl w-full max-w-md relative z-10 p-6">
            <h3 class="text-xl font-bold text-white mb-6">{{ $editMode ? 'Edit User' : 'Add User' }}</h3>
            
            <form wire:submit.prevent="saveUser" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-text-muted mb-1">Name</label>
                    <input type="text" wire:model="name" class="form-input w-full" required>
                    @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-text-muted mb-1">Email</label>
                    <input type="email" wire:model="email" class="form-input w-full" required>
                    @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                @if(!$editMode)
                <div>
                    <label class="block text-sm font-medium text-text-muted mb-1">Password (Optional)</label>
                    <input type="password" wire:model="password" class="form-input w-full" placeholder="Leave blank for 'password'">
                    @error('password') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                @endif
                
                <div>
                    <label class="block text-sm font-medium text-text-muted mb-1">Role</label>
                    <select wire:model="role" class="form-input w-full" required>
                        @foreach(App\Enums\OrgUserRole::cases() as $r)
                            @if($r->value !== 'commander')
                                <option class="bg-gray-900 text-white" value="{{ $r->value }}">{{ $r->label() }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('role') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="pt-4 flex justify-end gap-3 border-t border-white/5">
                    <button type="button" @click="show = false" class="btn-secondary px-4 py-2">Cancel</button>
                    <button type="submit" class="btn bg-primary hover:bg-primary-light text-surface px-6 py-2 rounded-lg font-bold">
                        {{ $editMode ? 'Save Changes' : 'Add User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
