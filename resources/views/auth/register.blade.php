<x-layouts.auth title="Register">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('logo.png') }}" alt="Cratory" class="w-16 h-16 rounded-2xl mx-auto mb-4 object-contain">
            <h1 class="text-2xl font-bold text-text-primary">Create your account</h1>
            <p class="text-text-secondary text-sm mt-1.5">Start managing your invoices & inventory</p>
        </div>

        <!-- Register Card -->
        <div class="glass-card p-6 sm:p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="form-label">Full name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-input" placeholder="John Doe">
                    @error('name') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="form-label">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="form-input" placeholder="you@example.com">
                    @error('email') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" class="form-input" placeholder="Min 8 characters">
                    @error('password') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="form-label">Confirm password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-input" placeholder="Repeat password">
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-full">
                    Create account
                </button>
            </form>
        </div>

        <!-- Login link -->
        <p class="text-center mt-6 text-sm text-text-secondary">
            Already have an account?
            <a href="{{ route('login') }}" class="text-accent hover:text-accent-light font-medium transition-colors">Sign in</a>
        </p>
    </div>
</x-layouts.auth>
