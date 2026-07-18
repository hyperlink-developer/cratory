<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\SubscriptionPlan;
use Exception;
use Razorpay\Api\Api;

class RazorpaySubscriptionService
{
    protected Api $api;

    public function __construct()
    {
        $this->api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    }

    /**
     * Creates a subscription for an organization against a plan.
     */
    public function createSubscription(Organization $organization, SubscriptionPlan $plan): string
    {
        if (!$plan->razorpay_plan_id) {
            throw new Exception("This plan does not have an associated Razorpay Plan ID.");
        }

        $subscriptionData = [
            'plan_id' => $plan->razorpay_plan_id,
            'total_count' => 120, // max billing cycles (10 years for monthly)
            'customer_notify' => 1,
            'notes' => [
                'organization_id' => $organization->id,
            ]
        ];

        $response = $this->api->subscription->create($subscriptionData);

        return $response->id;
    }

    /**
     * Cancels an active subscription.
     */
    public function cancelSubscription(string $subscriptionId): bool
    {
        try {
            $this->api->subscription->fetch($subscriptionId)->cancel();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Fetch a subscription details from Razorpay
     */
    public function fetchSubscription(string $subscriptionId)
    {
        return $this->api->subscription->fetch($subscriptionId);
    }
}
