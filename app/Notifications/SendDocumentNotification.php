<?php

namespace App\Notifications;

use App\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendDocumentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $document;
    public $channels;

    /**
     * Create a new notification instance.
     *
     * @param mixed $document The document model (e.g. Invoice)
     * @param array $channels Array of channels to send to: ['mail', 'whatsapp']
     */
    public function __construct($document, array $channels = ['mail'])
    {
        $this->document = $document;
        $this->channels = $channels;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $resolvedChannels = [];

        if (in_array('mail', $this->channels)) {
            $resolvedChannels[] = 'mail';
        }

        if (in_array('whatsapp', $this->channels)) {
            $resolvedChannels[] = WhatsAppChannel::class;
        }

        return $resolvedChannels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $documentType = class_basename($this->document);
        $documentName = $documentType === 'PurchaseInvoice' ? 'Purchase Invoice' : 'Invoice';
        $number = $this->document->invoice_number ?? $this->document->vendor_bill_number;
        
        $url = url('/invoices/' . $this->document->id . '/pdf'); // Link to download PDF

        return (new MailMessage)
                    ->subject("New {$documentName} #{$number} from " . $this->document->organization->name)
                    ->greeting("Hello {$notifiable->name},")
                    ->line("You have received a new {$documentName} (Amount: {$this->document->grand_total}).")
                    ->action('View / Download PDF', $url)
                    ->line('Thank you for your business!');
    }

    /**
     * Get the WhatsApp representation of the notification.
     */
    public function toWhatsApp(object $notifiable): array
    {
        $documentType = class_basename($this->document);
        $documentName = $documentType === 'PurchaseInvoice' ? 'Purchase Invoice' : 'Invoice';
        $number = $this->document->invoice_number ?? $this->document->vendor_bill_number;
        $url = url('/invoices/' . $this->document->id . '/pdf');

        $content = "Hello {$notifiable->name},\n\nYou have received a new {$documentName} #{$number} from {$this->document->organization->name}.\nAmount: {$this->document->grand_total}\n\nView or download your document here: {$url}";

        return [
            'to' => $notifiable->phone, // Assuming notifiable has a phone attribute
            'content' => $content,
        ];
    }

    /**
     * Data used by LogNotificationEvent to track this notification.
     */
    public function getLogData(): array
    {
        return [
            'organization_id' => $this->document->organization_id,
            'document_type' => get_class($this->document),
            'document_id' => $this->document->getKey(),
        ];
    }
}
