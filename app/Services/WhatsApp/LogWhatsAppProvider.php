<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Log;

class LogWhatsAppProvider implements WhatsAppProviderInterface
{
    public function send(string $to, string $message, array $options = []): bool
    {
        Log::info("WhatsApp Message queued to {$to}", [
            'message' => $message,
            'options' => $options,
        ]);

        return true;
    }
}
