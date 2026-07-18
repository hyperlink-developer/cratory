<?php

namespace App\Http\Controllers;

use App\Models\OrganizationSubscription;
use App\Models\WebhookLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');
        $webhookSecret = config('services.razorpay.webhook_secret');

        if (empty($signature)) {
            Log::warning('Razorpay webhook missing signature.');
            return response()->json(['error' => 'Missing signature'], 401);
        }

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $api->utility->verifyWebhookSignature($payload, $signature, $webhookSecret);
        } catch (SignatureVerificationError $e) {
            Log::warning('Razorpay webhook signature verification failed.');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = json_decode($payload, true);
        
        // Handle webhook event, e.g. "subscription.charged", "subscription.cancelled"
        $event = $data['event'] ?? null;
        
        if (!$event) {
            return response()->json(['status' => 'ignored']);
        }
        
        $eventId = $request->header('X-Razorpay-Event-Id') ?? uniqid('rzp_event_');

        // Check Idempotency
        if (WebhookLog::where('event_id', $eventId)->exists()) {
            return response()->json(['status' => 'already_processed']);
        }

        WebhookLog::create([
            'event_id' => $eventId,
            'event_type' => $event,
            'payload' => $data,
        ]);

        if (str_starts_with($event, 'subscription.')) {
            $this->handleSubscriptionEvent($event, $data['payload']['subscription']['entity'] ?? []);
        }

        return response()->json(['status' => 'success']);
    }

    protected function handleSubscriptionEvent(string $event, array $subscriptionData)
    {
        $subscriptionId = $subscriptionData['id'] ?? null;
        
        if (!$subscriptionId) {
            return;
        }

        $subscription = OrganizationSubscription::where('razorpay_subscription_id', $subscriptionId)->first();
        
        if (!$subscription) {
            return;
        }

        switch ($event) {
            case 'subscription.charged':
            case 'subscription.authenticated':
                $subscription->status = 'active';
                if (isset($subscriptionData['current_end'])) {
                    $subscription->current_period_end = Carbon::createFromTimestamp($subscriptionData['current_end']);
                }
                $subscription->save();
                break;

            case 'subscription.halted':
            case 'subscription.paused':
                $subscription->status = 'past_due';
                $subscription->save();
                break;

            case 'subscription.cancelled':
            case 'subscription.completed':
                $subscription->status = 'cancelled';
                $subscription->save();
                break;
        }
    }
}
