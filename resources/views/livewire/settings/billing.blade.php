<div>
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Billing & Subscriptions</h1>
            <p class="mt-2 text-sm text-gray-700">Manage your subscription plan and billing details.</p>
        </div>
    </div>

    @if($currentSubscription)
        <div class="bg-white shadow sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Current Plan</h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500">
                    <p>You are currently on the <strong>{{ $currentSubscription->plan->name }}</strong> plan.</p>
                    <p class="mt-1">
                        Status: 
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize
                            {{ $currentSubscription->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $currentSubscription->status === 'trialing' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $currentSubscription->status === 'past_due' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $currentSubscription->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                        ">
                            {{ $currentSubscription->status }}
                        </span>
                    </p>
                    @if($currentSubscription->current_period_end)
                        <p class="mt-1">Renews on: {{ $currentSubscription->current_period_end->format('M d, Y') }}</p>
                    @endif
                </div>
                <div class="mt-5">
                    @if($currentSubscription->status === 'active' || $currentSubscription->status === 'trialing')
                        <button type="button" wire:click="cancelSubscription" class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-100 px-4 py-2 font-medium text-red-700 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:text-sm">
                            Cancel Subscription
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="mt-8 space-y-4 sm:mt-12 sm:grid sm:grid-cols-2 sm:gap-6 sm:space-y-0 lg:mx-auto lg:max-w-4xl xl:mx-0 xl:max-w-none xl:grid-cols-3">
        @foreach($plans as $plan)
            <div class="divide-y divide-gray-200 rounded-lg border border-gray-200 shadow-sm bg-white">
                <div class="p-6">
                    <h2 class="text-lg font-medium leading-6 text-gray-900">{{ $plan->name }}</h2>
                    <p class="mt-4 text-sm text-gray-500">{{ $plan->description ?? 'All the basics for your business.' }}</p>
                    <p class="mt-8">
                        <span class="text-4xl font-bold tracking-tight text-gray-900">₹{{ number_format($plan->price_monthly) }}</span>
                        <span class="text-base font-medium text-gray-500">/mo</span>
                    </p>
                    <button wire:click="subscribe({{ $plan->id }})" class="mt-8 block w-full rounded-md border border-transparent bg-indigo-600 py-2 text-center text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ $currentSubscription && $currentSubscription->subscription_plan_id === $plan->id ? 'Current Plan' : 'Subscribe to ' . $plan->name }}
                    </button>
                </div>
                <div class="px-6 pt-6 pb-8">
                    <h3 class="text-sm font-medium text-gray-900">What's included</h3>
                    <ul role="list" class="mt-6 space-y-4">
                        <li class="flex space-x-3">
                            <svg class="h-5 w-5 flex-shrink-0 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm text-gray-500">{{ $plan->max_organizations > 1 ? $plan->max_organizations . ' Organizations' : '1 Organization' }}</span>
                        </li>
                        <li class="flex space-x-3">
                            <svg class="h-5 w-5 flex-shrink-0 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm text-gray-500">{{ $plan->max_invoices_per_month > 0 ? $plan->max_invoices_per_month . ' Invoices / month' : 'Unlimited Invoices' }}</span>
                        </li>
                        @if($plan->features)
                            @foreach($plan->features as $feature)
                                <li class="flex space-x-3">
                                    <svg class="h-5 w-5 flex-shrink-0 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-gray-500">{{ $feature }}</span>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Razorpay script injected via event -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('openRazorpayCheckout', (data) => {
                const options = data[0];
                var rzp1 = new Razorpay({
                    "key": options.key,
                    "subscription_id": options.subscription_id,
                    "name": options.name,
                    "description": options.description,
                    "prefill": {
                        "name": options.user_name,
                        "email": options.user_email
                    },
                    "handler": function (response){
                        // The webhook will handle the success
                        Livewire.dispatch('notify', { message: 'Payment successful! Setting up your subscription...', type: 'success' });
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                });
                rzp1.open();
            });
        });
    </script>
</div>
