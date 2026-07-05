<x-layouts.auth title="Login">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('logo.png') }}" alt="Cratory" class="w-16 h-16 rounded-2xl mx-auto mb-4 object-contain">
            <h1 class="text-2xl font-bold text-text-primary">Welcome back</h1>
            <p class="text-text-secondary text-sm mt-1.5">Sign in to your Cratory account</p>
        </div>

        <!-- Login Card -->
        <div class="glass-card p-6 sm:p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="form-label">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" class="form-input" placeholder="you@example.com">
                    @error('email') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="form-label mb-0">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-accent hover:text-accent-light transition-colors">Forgot password?</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password" class="form-input" placeholder="••••••••">
                    @error('password') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-2.5">
                    <input id="remember" type="checkbox" name="remember" class="w-4 h-4 rounded border-border bg-surface text-accent focus:ring-accent/30 cursor-pointer">
                    <label for="remember" class="text-sm text-text-secondary cursor-pointer">Remember me</label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-full">
                    Sign in
                </button>
            </form>
        </div>

        <!-- Register link -->
        <p class="text-center mt-6 text-sm text-text-secondary">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-accent hover:text-accent-light font-medium transition-colors">Create account</a>
        </p>
    </div>
</x-layouts.auth>
