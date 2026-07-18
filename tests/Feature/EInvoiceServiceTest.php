<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Invoice;
use App\Models\Organization;
use App\Models\User;
use App\Services\GST\EInvoiceService;
use App\Services\GST\MockGspProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EInvoiceServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_einvoice_generation()
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

        $service = new EInvoiceService(new MockGspProvider());
        
        $result = $service->generateIrn($invoice);
        
        $this->assertTrue($result);
        $this->assertNotNull($invoice->fresh()->irn);
        $this->assertEquals('generated', $invoice->fresh()->irn_status);
        $this->assertNotNull($invoice->fresh()->signed_qr_code);
    }
}
