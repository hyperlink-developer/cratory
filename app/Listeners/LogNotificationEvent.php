<?php

namespace App\Listeners;

use App\Models\NotificationLog;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Str;

class LogNotificationEvent
{
    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events)
    {
        $events->listen(
            NotificationSending::class,
            [LogNotificationEvent::class, 'handleSending']
        );

        $events->listen(
            NotificationSent::class,
            [LogNotificationEvent::class, 'handleSent']
        );

        $events->listen(
            NotificationFailed::class,
            [LogNotificationEvent::class, 'handleFailed']
        );
    }

    public function handleSending(NotificationSending $event)
    {
        $notification = $event->notification;
        
        // We only care about trackable notifications
        if (!method_exists($notification, 'getLogData')) {
            return;
        }

        $logData = $notification->getLogData();
        
        // Ensure notification has an ID
        if (!$notification->id) {
            $notification->id = (string) Str::uuid();
        }

        NotificationLog::create([
            'organization_id' => $logData['organization_id'],
            'notifiable_type' => get_class($event->notifiable),
            'notifiable_id' => $event->notifiable->getKey(),
            'document_type' => $logData['document_type'] ?? null,
            'document_id' => $logData['document_id'] ?? null,
            'notification_id' => $notification->id,
            'channel' => $event->channel,
            'status' => 'queued',
        ]);
    }

    public function handleSent(NotificationSent $event)
    {
        if (!$event->notification->id) {
            return;
        }

        NotificationLog::where('notification_id', $event->notification->id)
            ->where('channel', $event->channel)
            ->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
    }

    public function handleFailed(NotificationFailed $event)
    {
        if (!$event->notification->id) {
            return;
        }

        // The exact field name depends on how we get the exception from the event
        // Sometimes it's passed directly, sometimes in data array
        $errorMessage = null;
        if (property_exists($event, 'data') && isset($event->data['exception'])) {
             $errorMessage = $event->data['exception']->getMessage();
        }

        NotificationLog::where('notification_id', $event->notification->id)
            ->where('channel', $event->channel)
            ->update([
                'status' => 'failed',
                'error_message' => $errorMessage ?? 'Unknown delivery failure',
            ]);
    }
}
