<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\GstReportPeriod;
use App\Models\Gstr2bReconciliationItem;
use App\Models\Organization;
use App\Models\PurchaseInvoice;
use App\Models\User;
use App\Services\Gstr2bMatchingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Gstr2bMatchingTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $org;
    protected $contact;
    protected $period;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->org = Organization::create([
            'name' => 'Test Org',
            'created_by' => $this->user->id,
            'type' => \App\Enums\OrganizationType::PvtLtd,
            'business_category' => \App\Enums\BusinessCategory::Service,
        ]);
        
        $this->contact = Contact::create([
            'organization_id' => $this->org->id,
            'name' => 'Test Contact',
            'type' => \App\Enums\ContactType::Vendor,
            'gst_number' => '27AAPFU0939F1ZV',
        ]);
        
        $this->period = clone $this->org->gstReportPeriods()->create([
            'period_type' => 'monthly',
            'period_start' => '2024-04-01',
            'period_end' => '2024-04-30',
        ]);
    }

    public function test_matches_exact_purchase_invoice()
    {
        $invoice = PurchaseInvoice::create([
            'organization_id' => $this->org->id,
            'contact_id' => $this->contact->id,
            'vendor_bill_number' => 'INV-001',
            'purchase_date' => '2024-04-01',
            'due_date' => '2024-04-15',
            'subtotal' => 1000.00,
            'tax_total' => 180.00,
            'grand_total' => 1180.00,
            'status' => 'draft',
        ]);
        
        $csvContent = "GSTIN of Supplier,Invoice number,Invoice date,Taxable Value,Integrated Tax,Central Tax,State/UT Tax\n";
        $csvContent .= "27AAPFU0939F1ZV,INV-001,01-Apr-2024,1000.00,180.00,0,0\n";
        
        $filePath = storage_path('app/temp_gstr2b_test1.csv');
        file_put_contents($filePath, $csvContent);
        
        $service = new Gstr2bMatchingService();
        $service->processCsvUpload($filePath, $this->period);
        
        $item = Gstr2bReconciliationItem::first();
        
        $this->assertNotNull($item);
        $this->assertEquals('matched', $item->match_status);
        $this->assertEquals($invoice->id, $item->purchase_invoice_id);
            
        unlink($filePath);
    }

    public function test_flags_manual_review_for_mismatched_amounts()
    {
        $invoice = PurchaseInvoice::create([
            'organization_id' => $this->org->id,
            'contact_id' => $this->contact->id,
            'vendor_bill_number' => 'INV-002',
            'purchase_date' => '2024-04-02',
            'due_date' => '2024-04-15',
            'subtotal' => 1000.00,
            'tax_total' => 180.00,
            'grand_total' => 1180.00,
            'status' => 'draft',
        ]);
        
        $csvContent = "GSTIN of Supplier,Invoice number,Invoice date,Taxable Value,Integrated Tax,Central Tax,State/UT Tax\n";
        $csvContent .= "27AAPFU0939F1ZV,INV-002,02-Apr-2024,1500.00,270.00,0,0\n";
        
        $filePath = storage_path('app/temp_gstr2b_test2.csv');
        file_put_contents($filePath, $csvContent);
        
        $service = new Gstr2bMatchingService();
        $service->processCsvUpload($filePath, $this->period);
        
        $item = Gstr2bReconciliationItem::first();
        
        $this->assertNotNull($item);
        $this->assertEquals('manual_review', $item->match_status);
        $this->assertEquals($invoice->id, $item->purchase_invoice_id);
            
        unlink($filePath);
    }

    public function test_marks_unmatched_when_local_invoice_is_missing()
    {
        $csvContent = "GSTIN of Supplier,Invoice number,Invoice date,Taxable Value,Integrated Tax,Central Tax,State/UT Tax\n";
        $csvContent .= "27AAPFU0939F1ZV,INV-003,03-Apr-2024,1000.00,180.00,0,0\n";
        
        $filePath = storage_path('app/temp_gstr2b_test3.csv');
        file_put_contents($filePath, $csvContent);
        
        $service = new Gstr2bMatchingService();
        $service->processCsvUpload($filePath, $this->period);
        
        $item = Gstr2bReconciliationItem::first();
        
        $this->assertNotNull($item);
        $this->assertEquals('unmatched', $item->match_status);
        $this->assertNull($item->purchase_invoice_id);
            
        unlink($filePath);
    }
}
