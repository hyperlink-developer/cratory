<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Enums\PurchaseStatus;
use App\Models\Contact;
use App\Models\GstReportPeriod;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Organization;
use App\Models\PurchaseInvoice;
use App\Models\TaxRate;
use App\Models\User;
use App\Services\GST\Gstr1ReportService;
use App\Services\GST\Gstr3bReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GstReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculates_correct_gstr1_and_gstr3b_totals()
    {
        // Setup basic org and user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $org = Organization::create([
            'name' => 'Test Org',
            'type' => \App\Enums\OrganizationType::PvtLtd,
            'business_category' => \App\Enums\BusinessCategory::Both,
            'is_active' => true,
            'created_by' => $user->id,
        ]);
        $org->users()->attach($user, ['role' => 'commander']);

        // Setup period
        $period = GstReportPeriod::create([
            'organization_id' => $org->id,
            'period_type' => 'monthly',
            'period_start' => '2026-07-01',
            'period_end' => '2026-07-31',
        ]);

        // Setup Tax Rates
        $tax18 = TaxRate::create(['organization_id' => $org->id, 'name' => 'GST 18%', 'percentage' => 18, 'is_gst' => true]);

        // Setup Contacts
        $b2bContact = Contact::create([
            'organization_id' => $org->id,
            'name' => 'B2B Customer',
            'gst_number' => '27AAPFU0939F1Z5',
            'type' => \App\Enums\ContactType::Customer,
        ]);
        
        $b2cContact = Contact::create([
            'organization_id' => $org->id,
            'name' => 'B2C Customer',
            'gst_number' => null,
            'type' => \App\Enums\ContactType::Customer,
            'billing_state' => 'Maharashtra',
        ]);

        // Create a Sales Invoice (B2B)
        $b2bInvoice = Invoice::create([
            'organization_id' => $org->id,
            'contact_id' => $b2bContact->id,
            'invoice_basis' => \App\Enums\InvoiceBasis::Credit,
            'invoice_type' => \App\Enums\InvoiceType::Sales,
            'invoice_number' => 'INV-001',
            'invoice_date' => '2026-07-10',
            'due_date' => '2026-07-10',
            'status' => InvoiceStatus::Sent,
        ]);
        $b2bItem = new InvoiceItem([
            'item_name' => 'Item 1',
            'quantity' => 1,
            'rate' => 1000,
            'tax_rate_id' => $tax18->id,
        ]);
        $b2bItem->calculateTotals();
        $b2bInvoice->items()->save($b2bItem);
        $b2bInvoice->recalculateTotals();
        $b2bInvoice->save();

        // Create a Sales Invoice (B2C)
        $b2cInvoice = Invoice::create([
            'organization_id' => $org->id,
            'contact_id' => $b2cContact->id,
            'invoice_basis' => \App\Enums\InvoiceBasis::Credit,
            'invoice_type' => \App\Enums\InvoiceType::Sales,
            'invoice_number' => 'INV-002',
            'invoice_date' => '2026-07-15',
            'due_date' => '2026-07-15',
            'status' => InvoiceStatus::Sent,
            'place_of_supply' => 'Maharashtra',
        ]);
        $b2cItem = new InvoiceItem([
            'item_name' => 'Item 2',
            'quantity' => 2,
            'rate' => 500, // total 1000
            'tax_rate_id' => $tax18->id,
        ]);
        $b2cItem->calculateTotals();
        $b2cInvoice->items()->save($b2cItem);
        $b2cInvoice->recalculateTotals();
        $b2cInvoice->save();

        // Create a Purchase Invoice (ITC)
        $vendorContact = Contact::create([
            'organization_id' => $org->id,
            'name' => 'Vendor',
            'gst_number' => '27AACCV1234D1Z2',
            'type' => \App\Enums\ContactType::Vendor,
        ]);
        $purchaseInvoice = PurchaseInvoice::create([
            'organization_id' => $org->id,
            'contact_id' => $vendorContact->id,
            'invoice_basis' => \App\Enums\InvoiceBasis::Credit,
            'purchase_number' => 'PUR-001',
            'vendor_bill_number' => 'BILL-123',
            'purchase_date' => '2026-07-05',
            'due_date' => '2026-07-05',
            'status' => PurchaseStatus::Received,
            'subtotal' => 1180,
            'discount_total' => 0,
            'tax_total' => 180,
            'grand_total' => 1180,
        ]);

        // Test GSTR-1
        $gstr1 = new Gstr1ReportService($period);
        
        $b2b = $gstr1->getB2bInvoices();
        $this->assertCount(1, $b2b);
        $this->assertEquals(1000.0, $b2b[0]['taxable_value']);
        $this->assertEquals(180.0, $b2b[0]['tax_amount']);

        $b2c = $gstr1->getB2cInvoices();
        $this->assertCount(1, $b2c);
        $this->assertEquals(1000.0, $b2c[0]['taxable_value']);
        $this->assertEquals(180.0, $b2c[0]['tax_amount']);

        // Test GSTR-3B
        $gstr3b = new Gstr3bReportService($period);
        $summary = $gstr3b->getSummary();
        
        $this->assertEquals(2000.0, $summary['outward_supplies']['taxable_value']);
        $this->assertEquals(360.0, $summary['outward_supplies']['tax_liability']);
        $this->assertEquals(1000.0, $summary['inward_supplies']['taxable_value']);
        $this->assertEquals(180.0, $summary['inward_supplies']['itc_available']);
        $this->assertEquals(180.0, $summary['net_tax_payable']);
    }
}
