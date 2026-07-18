<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\OrganizationSubscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\WebhookLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class RazorpayWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.razorpay.webhook_secret', 'test_secret');
    }

    public function test_it_rejects_invalid_signature()
    {
        $response = $this->postJson('/webhooks/razorpay', [
            'event' => 'subscription.charged',
        ], [
            'X-Razorpay-Signature' => 'invalid_signature'
        ]);

        $response->assertStatus(401);
    }

    public function test_it_processes_subscription_charged_webhook()
    {
        $user = User::factory()->create();
        $org = Organization::create([
            'name' => 'Test Org',
            'type' => \App\Enums\OrganizationType::PvtLtd,
            'business_category' => \App\Enums\BusinessCategory::Both,
            'is_active' => true,
            'created_by' => $user->id,
        ]);
        
        $plan = SubscriptionPlan::create([
            'name' => 'Pro',
            'price_monthly' => 1000,
            'price_yearly' => 10000,
        ]);

        $subscription = OrganizationSubscription::create([
            'organization_id' => $org->id,
            'subscription_plan_id' => $plan->id,
            'razorpay_subscription_id' => 'sub_12345',
            'status' => 'trialing',
        ]);

        $payload = json_encode([
            'event' => 'subscription.charged',
            'payload' => [
                'subscription' => [
                    'entity' => [
                        'id' => 'sub_12345',
                        'current_end' => 1700000000,
                    ]
                ]
            ]
        ]);

        // Generate valid signature using the secret
        $signature = hash_hmac('sha256', $payload, 'test_secret');

        $response = $this->call('POST', '/webhooks/razorpay', [], [], [], [
            'HTTP_X-Razorpay-Signature' => $signature,
            'HTTP_X-Razorpay-Event-Id' => 'ev_123',
            'CONTENT_TYPE' => 'application/json',
        ], $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('organization_subscriptions', [
            'id' => $subscription->id,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('webhook_logs', [
            'event_id' => 'ev_123',
            'event_type' => 'subscription.charged',
        ]);
    }

    public function test_it_is_idempotent()
    {
        WebhookLog::create([
            'event_id' => 'ev_duplicate',
            'event_type' => 'subscription.charged',
            'payload' => []
        ]);

        $payload = json_encode(['event' => 'subscription.charged']);
        $signature = hash_hmac('sha256', $payload, 'test_secret');

        $response = $this->call('POST', '/webhooks/razorpay', [], [], [], [
            'HTTP_X-Razorpay-Signature' => $signature,
            'HTTP_X-Razorpay-Event-Id' => 'ev_duplicate',
            'CONTENT_TYPE' => 'application/json',
        ], $payload);

        $response->assertStatus(200);
        $response->assertJson(['status' => 'already_processed']);
    }
}
