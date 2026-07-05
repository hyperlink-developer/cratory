<x-layouts.auth title="Forgot Password">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-accent/15 mb-4">
                <svg class="w-7 h-7 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-text-primary">Reset password</h1>
            <p class="text-text-secondary text-sm mt-1.5">Enter your email to receive a reset link</p>
        </div>

        <div class="glass-card p-6 sm:p-8">
            @if (session('status'))
                <div class="mb-4 p-3 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="form-label">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-input" placeholder="you@example.com">
                    @error('email') <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="btn btn-primary w-full">Send reset link</button>
            </form>
        </div>

        <p class="text-center mt-6 text-sm text-text-secondary">
            <a href="{{ route('login') }}" class="text-accent hover:text-accent-light font-medium transition-colors">Back to sign in</a>
        </p>
    </div>
</x-layouts.auth>
