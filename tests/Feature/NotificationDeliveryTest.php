<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Invoice;
use App\Models\NotificationLog;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\SendDocumentNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationDeliveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_dispatch_creates_log()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $org = Organization::create([
            'name' => 'Test Org',
            'created_by' => $user->id,
            'type' => \App\Enums\OrganizationType::PvtLtd,
            'business_category' => \App\Enums\BusinessCategory::Service,
        ]);
        
        $contact = Contact::create([
            'organization_id' => $org->id,
            'name' => 'Test Contact',
            'type' => \App\Enums\ContactType::Customer,
        ]);
        
        $invoice = Invoice::create([
            'organization_id' => $org->id,
            'contact_id' => $contact->id,
            'invoice_basis' => \App\Enums\InvoiceBasis::Credit,
            'invoice_type' => \App\Enums\InvoiceType::Sales,
            'invoice_date' => now(),
            'due_date' => now()->addDays(15),
            'subtotal' => 1000,
            'grand_total' => 1000,
            'status' => \App\Enums\InvoiceStatus::Draft,
        ]);

        // Instead of testing queue which runs in a different process or sync, 
        // we test the notification events by triggering it synchronously.
        $contact->notify(new SendDocumentNotification($invoice, ['mail', 'whatsapp']));

        // Notification sending event should have populated the NotificationLog
        $this->assertDatabaseHas('notification_logs', [
            'organization_id' => $org->id,
            'notifiable_id' => $contact->id,
            'document_id' => $invoice->id,
            'channel' => 'mail',
            'status' => 'sent', // Since we run synchronously, the handleSent event fires immediately
        ]);

        $this->assertDatabaseHas('notification_logs', [
            'organization_id' => $org->id,
            'notifiable_id' => $contact->id,
            'document_id' => $invoice->id,
            'channel' => \App\Channels\WhatsAppChannel::class,
            'status' => 'sent',
        ]);
    }
}
