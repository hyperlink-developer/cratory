<div>
    <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Profile Settings</h1>
            <p class="text-sm text-text-secondary mt-1">Manage your personal information and security</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-ghost" wire:navigate>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Personal Information -->
        <div class="glass-card p-6">
            <h2 class="text-lg font-bold text-text-primary mb-4">Personal Information</h2>
            <form wire:submit="updateProfile" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1">Name</label>
                    <input type="text" wire:model="name" class="form-input" required>
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1">Email Address</label>
                    <input type="email" wire:model="email" class="form-input" required>
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2 flex items-center justify-between">
                    <span x-data="{ shown: false }"
                          x-on:profile-updated.window="shown = true; setTimeout(() => shown = false, 2000)"
                          x-show="shown"
                          x-transition
                          class="text-sm text-green-500 font-medium"
                          style="display: none;">
                        Profile saved.
                    </span>
                    <button type="submit" class="btn btn-primary ml-auto">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Security -->
        <div class="glass-card p-6">
            <h2 class="text-lg font-bold text-text-primary mb-4">Update Password</h2>
            <form wire:submit="updatePassword" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1">Current Password</label>
                    <input type="password" wire:model="current_password" class="form-input" required>
                    @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1">New Password</label>
                    <input type="password" wire:model="password" class="form-input" required>
                    @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1">Confirm Password</label>
                    <input type="password" wire:model="password_confirmation" class="form-input" required>
                </div>

                <div class="pt-2 flex items-center justify-between">
                    <span x-data="{ shown: false }"
                          x-on:password-updated.window="shown = true; setTimeout(() => shown = false, 2000)"
                          x-show="shown"
                          x-transition
                          class="text-sm text-green-500 font-medium"
                          style="display: none;">
                        Password updated.
                    </span>
                    <button type="submit" class="btn glass-card text-text-primary border border-white/5 hover:bg-white/5 ml-auto">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
