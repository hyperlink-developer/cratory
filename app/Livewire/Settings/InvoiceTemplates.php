<?php

namespace App\Livewire\Settings;

use App\Models\InvoiceTemplate;
use Livewire\Component;

class InvoiceTemplates extends Component
{
    public $templates = [];
    public ?InvoiceTemplate $activeTemplate = null;

    // Form fields
    public string $name = 'Standard';
    public string $slug = 'standard';
    public string $colorPrimary = '#4F46E5';
    public string $colorSecondary = '#F59E0B';
    public string $fontChoice = 'Helvetica';
    public array $showFields = [
        'shipping_address' => true,
        'hsn' => true,
        'tax_details' => true,
    ];
    public ?string $defaultPaymentInfo = null;
    public ?string $defaultTermsAndConditions = null;

    public function mount()
    {
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        $organization = auth()->user()->currentOrganization;
        $this->templates = InvoiceTemplate::where('organization_id', $organization->id)->get();
        
        if ($this->templates->isEmpty()) {
            // Create default template if none exists
            $this->activeTemplate = InvoiceTemplate::create([
                'organization_id' => $organization->id,
                'name' => 'Standard',
                'slug' => 'standard',
                'is_default' => true,
                'color_primary' => '#4F46E5',
                'color_secondary' => '#F59E0B',
                'show_fields' => InvoiceTemplate::defaultShowFields(),
                'font_choice' => 'Helvetica',
            ]);
            $this->templates = collect([$this->activeTemplate]);
        } else {
            $this->activeTemplate = $this->templates->where('is_default', true)->first() ?? $this->templates->first();
        }

        $this->fillForm();
    }

    public function fillForm()
    {
        if ($this->activeTemplate) {
            $this->name = $this->activeTemplate->name;
            
            $slug = $this->activeTemplate->slug ?? 'standard';
            $validSlugs = ['standard', 'modern', 'minimal', 'elegant'];
            $this->slug = in_array($slug, $validSlugs) ? $slug : 'standard';
            
            $this->colorPrimary = $this->activeTemplate->color_primary;
            $this->colorSecondary = $this->activeTemplate->color_secondary;
            $this->fontChoice = $this->activeTemplate->font_choice ?? 'Helvetica';
            $this->showFields = array_merge($this->showFields, $this->activeTemplate->show_fields ?? []);
            
            $this->defaultPaymentInfo = $this->activeTemplate->default_payment_info;
            $this->defaultTermsAndConditions = $this->activeTemplate->default_terms_and_conditions;
        }
    }

    public function selectTemplate(string $slug)
    {
        $this->slug = $slug;
        $this->name = ucfirst($slug);
    }

    public function save()
    {
        $this->validate([
            'slug' => 'required|in:standard,modern,minimal,elegant',
            'colorPrimary' => 'required|string',
            'colorSecondary' => 'required|string',
            'defaultPaymentInfo' => 'nullable|string',
            'defaultTermsAndConditions' => 'nullable|string',
        ]);

        if ($this->activeTemplate) {
            $this->activeTemplate->update([
                'name' => ucfirst($this->slug),
                'slug' => $this->slug,
                'color_primary' => $this->colorPrimary,
                'color_secondary' => $this->colorSecondary,
                'font_choice' => $this->fontChoice,
                'show_fields' => $this->showFields,
                'default_payment_info' => $this->defaultPaymentInfo,
                'default_terms_and_conditions' => $this->defaultTermsAndConditions,
            ]);
        }

        $this->dispatch('notify', ['message' => 'Template settings saved successfully!']);
    }

    public function getPreviewHtml()
    {
        $organization = auth()->user()->currentOrganization;
        
        $invoice = new \stdClass();
        $invoice->invoice_number = 'INV-2026-001';
        $invoice->invoice_date = now();
        $invoice->due_date = now()->addDays(14);
        $invoice->notes = 'Thank you for your business!';
        $invoice->terms = 'Payment is due within 14 days.';
        $invoice->invoice_type = (object)['value' => 'sales'];
        $invoice->subtotal = 1700.00;
        $invoice->tax_total = 306.00;
        $invoice->discount_total = 0;
        $invoice->round_off = 0;
        $invoice->grand_total = 2006.00;
        $invoice->amount_paid = 0;
        $invoice->balance_due = 2006.00;
        $invoice->status = \App\Enums\InvoiceStatus::Sent;
        $invoice->payment_info = "Bank: State Bank of India\nAcct: 334455667788\nIFSC: SBIN0001234";
        $invoice->terms_and_conditions = "1. Payment is due within 15 days.\n2. Goods once sold will not be taken back.";
        
        $template = new \stdClass();
        $template->font_choice = $this->fontChoice;
        $template->color_primary = $this->colorPrimary;
        $template->color_secondary = $this->colorSecondary;
        $template->show_fields = $this->showFields;
        $template->footer_note = 'Thank you for your business!';

        $contact = new \stdClass();
        $contact->name = 'Acme Corporation';
        $contact->billing_address_line_1 = '123 Business Rd.';
        $contact->billing_address_line_2 = 'Suite 100';
        $contact->billing_city = 'Tech City';
        $contact->billing_state = 'Tech State';
        $contact->billing_pincode = '10101';
        $contact->billing_country = 'India';
        
        $contact->shipping_address_line_1 = '456 Warehouse Blvd.';
        $contact->shipping_address_line_2 = 'Building B';
        $contact->shipping_city = 'Tech City';
        $contact->shipping_state = 'Tech State';
        $contact->shipping_pincode = '10101';
        $contact->shipping_country = 'India';
        
        $contact->gst_number = '27AADCB2230M1Z2';
        $contact->phone = '+1 (555) 123-4567';

        $items = collect([
            (object)[
                'product' => null,
                'item_name' => 'Web Design Services',
                'description' => 'Complete website design and development',
                'hsn_code' => '9983',
                'quantity' => 1,
                'unit' => 'pcs',
                'rate' => 1500.00,
                'discount_amount' => 0,
                'discount_percent' => 0,
                'tax_amount' => 270.00,
                'taxRate' => (object)['percentage' => '18.00'],
                'line_total' => 1770.00
            ],
            (object)[
                'product' => null,
                'item_name' => 'Hosting (1 Year)',
                'description' => 'Premium cloud hosting',
                'hsn_code' => '9984',
                'quantity' => 1,
                'unit' => 'pcs',
                'rate' => 200.00,
                'discount_amount' => 0,
                'discount_percent' => 0,
                'tax_amount' => 36.00,
                'taxRate' => (object)['percentage' => '18.00'],
                'line_total' => 236.00
            ]
        ]);
        $invoice->items = $items;

        return view('pdf.templates.' . $this->slug, [
            'invoice' => $invoice,
            'template' => $template,
            'organization' => $organization,
            'contact' => $contact,
        ])->render();
    }

    public function render()
    {
        return view('livewire.settings.invoice-templates')->layout('components.layouts.app', [
            'title' => 'Invoice Templates'
        ]);
    }
}
