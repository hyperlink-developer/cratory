<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Organization;
use App\Models\RecurringInvoiceTemplate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RecurringInvoiceTest extends TestCase
{
    use RefreshDatabase;

    private function setupOrg()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $organization = Organization::create([
            'name' => 'Test Org',
            'type' => \App\Enums\OrganizationType::PvtLtd,
            'business_category' => \App\Enums\BusinessCategory::Both,
            'is_active' => true,
            'created_by' => $user->id,
        ]);
        
        $user->update(['current_organization_id' => $organization->id]);
        
        $contact = Contact::create([
            'organization_id' => $organization->id,
            'name' => 'B2B Customer',
            'gst_number' => '27AAPFU0939F1Z5',
            'type' => \App\Enums\ContactType::Customer,
        ]);

        return [$user, $organization, $contact];
    }

    public function test_it_generates_an_invoice_from_template()
    {
        list($user, $organization, $contact) = $this->setupOrg();

        $template = RecurringInvoiceTemplate::create([
            'organization_id' => $organization->id,
            'contact_id' => $contact->id,
            'frequency' => 'monthly',
            'next_run_date' => Carbon::today()->format('Y-m-d'),
            'template_invoice_data' => [
                'invoice_basis' => 'cash',
                'invoice_type' => 'sales',
                'due_days' => 15,
                'subtotal' => 1000,
                'tax_total' => 180,
                'grand_total' => 1180,
                'notes' => 'Monthly subscription',
                'items' => [
                    [
                        'name' => 'Web Hosting',
                        'quantity' => 1,
                        'unit_price' => 1000,
                        'line_total' => 1000,
                        'tax_amount' => 180,
                    ]
                ]
            ],
            'is_active' => true,
        ]);

        $invoice = $template->generateInvoice();

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'organization_id' => $organization->id,
            'contact_id' => $contact->id,
            'grand_total' => 1180,
            'status' => 'draft',
            'notes' => 'Monthly subscription',
        ]);

        $this->assertDatabaseHas('invoice_items', [
            'invoice_id' => $invoice->id,
            'item_name' => 'Web Hosting',
            'rate' => 1000,
            'line_total' => 1000,
        ]);
    }

    public function test_it_advances_next_run_date()
    {
        list($user, $organization, $contact) = $this->setupOrg();
        
        $template = RecurringInvoiceTemplate::create([
            'organization_id' => $organization->id,
            'contact_id' => $contact->id,
            'frequency' => 'monthly',
            'next_run_date' => Carbon::parse('2025-01-15')->format('Y-m-d'),
            'template_invoice_data' => [],
        ]);

        $template->advanceNextRunDate();

        $this->assertEquals('2025-02-15', Carbon::parse($template->fresh()->next_run_date)->format('Y-m-d'));
    }

    public function test_console_command_processes_due_templates()
    {
        list($user, $organization, $contact) = $this->setupOrg();

        $dueTemplate = RecurringInvoiceTemplate::create([
            'organization_id' => $organization->id,
            'contact_id' => $contact->id,
            'frequency' => 'monthly',
            'next_run_date' => Carbon::today()->format('Y-m-d'),
            'template_invoice_data' => ['subtotal' => 500],
            'is_active' => true,
        ]);

        $futureTemplate = RecurringInvoiceTemplate::create([
            'organization_id' => $organization->id,
            'contact_id' => $contact->id,
            'frequency' => 'monthly',
            'next_run_date' => Carbon::tomorrow()->format('Y-m-d'),
            'template_invoice_data' => ['subtotal' => 600],
            'is_active' => true,
        ]);

        $inactiveTemplate = RecurringInvoiceTemplate::create([
            'organization_id' => $organization->id,
            'contact_id' => $contact->id,
            'frequency' => 'monthly',
            'next_run_date' => Carbon::yesterday()->format('Y-m-d'),
            'template_invoice_data' => ['subtotal' => 700],
            'is_active' => false,
        ]);

        Artisan::call('invoices:process-recurring');

        // Only the due template should have generated an invoice
        $this->assertDatabaseHas('invoices', ['subtotal' => 500]);
        $this->assertDatabaseMissing('invoices', ['subtotal' => 600]);
        $this->assertDatabaseMissing('invoices', ['subtotal' => 700]);

        // The due template's next_run_date should have advanced
        $this->assertTrue(Carbon::parse($dueTemplate->fresh()->next_run_date)->isFuture());
    }
}
