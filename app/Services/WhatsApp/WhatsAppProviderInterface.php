<?php

namespace App\Services\WhatsApp;

interface WhatsAppProviderInterface
{
    /**
     * Send a WhatsApp message.
     *
     * @param string $to Phone number with country code.
     * @param string $message The message text or template data.
     * @param array $options Additional provider-specific options.
     * @return bool True on success, throws exception on failure.
     */
    public function send(string $to, string $message, array $options = []): bool;
}
