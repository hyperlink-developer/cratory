<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\WhatsApp\WhatsAppProviderInterface;

class WhatsAppChannel
{
    protected $provider;

    public function __construct(WhatsAppProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $message = $notification->toWhatsApp($notifiable);

        if (empty($message['to']) || empty($message['content'])) {
            return;
        }

        $this->provider->send($message['to'], $message['content'], $message['options'] ?? []);
    }
}
