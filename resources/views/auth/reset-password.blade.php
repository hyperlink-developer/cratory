<x-layouts.auth title="Reset Password">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-text-primary">Set new password</h1>
            <p class="text-text-secondary text-sm mt-1.5">Choose a strong password for your account</p>
        </div>

        <div class="glass-card p-6 sm:p-8">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="form-label">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required class="form-input">
                    @error('email') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="form-label">New password</label>
                    <input id="password" type="password" name="password" required class="form-input" placeholder="Min 8 characters">
                    @error('password') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="form-label">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required class="form-input">
                </div>

                <button type="submit" class="btn btn-primary w-full">Reset password</button>
            </form>
        </div>
    </div>
</x-layouts.auth>
