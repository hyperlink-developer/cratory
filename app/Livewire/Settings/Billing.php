<?php

namespace App\Livewire\Settings;

use App\Models\OrganizationSubscription;
use App\Models\SubscriptionPlan;
use App\Services\RazorpaySubscriptionService;
use Livewire\Component;

class Billing extends Component
{
    public $plans;
    public $currentSubscription;

    public function mount()
    {
        $this->plans = SubscriptionPlan::where('is_active', true)->get();
        $this->currentSubscription = auth()->user()->currentOrganization->subscription()->with('plan')->first();
    }

    public function subscribe($planId, RazorpaySubscriptionService $service)
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        $organization = auth()->user()->currentOrganization;

        try {
            $subscriptionId = $service->createSubscription($organization, $plan);
            
            // Store it locally as trialing/pending
            OrganizationSubscription::updateOrCreate(
                ['organization_id' => $organization->id],
                [
                    'subscription_plan_id' => $plan->id,
                    'razorpay_subscription_id' => $subscriptionId,
                    'status' => 'trialing',
                ]
            );

            // Dispatch event to frontend to open Razorpay Checkout
            $this->dispatch('openRazorpayCheckout', [
                'subscription_id' => $subscriptionId,
                'key' => config('services.razorpay.key'),
                'name' => 'Cratory',
                'description' => $plan->name . ' Plan',
                'organization_name' => $organization->name,
                'user_name' => auth()->user()->name,
                'user_email' => auth()->user()->email,
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'Error initiating subscription: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function cancelSubscription(RazorpaySubscriptionService $service)
    {
        if (!$this->currentSubscription || !$this->currentSubscription->razorpay_subscription_id) {
            return;
        }

        if ($service->cancelSubscription($this->currentSubscription->razorpay_subscription_id)) {
            $this->currentSubscription->update(['status' => 'cancelled']);
            $this->dispatch('notify', ['message' => 'Subscription cancelled successfully.', 'type' => 'success']);
        } else {
            $this->dispatch('notify', ['message' => 'Failed to cancel subscription.', 'type' => 'error']);
        }
    }

    public function render()
    {
        return view('livewire.settings.billing')->layout('components.layouts.app', ['title' => 'Billing Settings']);
    }
}
