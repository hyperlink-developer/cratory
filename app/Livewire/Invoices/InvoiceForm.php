<?php

namespace App\Livewire\Invoices;

use App\Enums\InvoiceStatus;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\TaxRate;
use App\Services\DocumentNumberGenerator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InvoiceForm extends Component
{
    public ?Invoice $invoice = null;
    
    public string $type = 'sales'; // sales or service
    public string $invoiceBasis = 'credit'; // cash or credit
    
    // Header Info
    public ?int $contactId = null;
    public string $invoiceNumber = '';
    public string $invoiceDate = '';
    public string $dueDate = '';
    
    // Line Items
    // Array structure: [['product_id' => null, 'description' => '', 'quantity' => 1, 'unit' => 'pcs', 'rate' => 0, 'tax_rate_id' => null, 'tax_amount' => 0, 'line_total' => 0]]
    public array $items = [];
    
    // Totals
    public float $subtotal = 0.00;
    public float $taxTotal = 0.00;
    public float $discountTotal = 0.00; // Invoice-level global discount amount
    
    public string $globalDiscountType = 'amount'; // 'amount' or 'percent'
    public float $globalDiscountValue = 0.00;
    
    public float $roundOff = 0.00;
    public float $grandTotal = 0.00;

    // Extra Info
    public ?string $paymentInfo = null;
    public ?string $termsAndConditions = null;

    // Contact Details
    public array $contacts = [];
    public array $products = [];
    public array $taxRates = [];
    
    // Quick Add
    public bool $showNewContactInput = false;
    public string $newContactName = '';
    
    public bool $showNewProductInput = false;
    public string $newProductName = '';
    public float $newProductPrice = 0.00;

    public function mount(Invoice $invoice = null)
    {
        $this->type = request()->query('type', 'sales');
        
        $this->contacts = Contact::active()->orderBy('display_name')->get()->toArray();
        
        // Filter products/services based on invoice type
        $productQuery = Product::active()->orderBy('name');
        if ($this->type === 'service') {
            $productQuery->services();
        } else {
            $productQuery->products();
        }
        $this->products = $productQuery->get()->toArray();
        
        $this->taxRates = TaxRate::active()->orderBy('percentage')->get()->toArray();

        if ($invoice && $invoice->exists) {
            $this->invoice = $invoice;
            $this->type = $invoice->invoice_type->value;
            $this->invoiceBasis = $invoice->invoice_basis->value ?? 'credit';
            $this->contactId = $invoice->contact_id;
            $this->invoiceNumber = $invoice->invoice_number ?? '';
            $this->invoiceDate = $invoice->invoice_date?->format('Y-m-d') ?? '';
            $this->dueDate = $invoice->due_date?->format('Y-m-d') ?? '';
            $this->paymentInfo = $invoice->payment_info;
            $this->termsAndConditions = $invoice->terms_and_conditions;
            
            foreach ($invoice->items as $item) {
                $this->items[] = [
                    'product_id' => $item->product_id,
                    'item_name' => $item->item_name ?? '',
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'rate' => $item->rate,
                    'discount_type' => $item->discount_percent > 0 ? 'percent' : 'amount',
                    'discount_value' => $item->discount_percent > 0 ? $item->discount_percent : $item->discount_amount,
                    'discount_amount' => $item->discount_amount,
                    'tax_rate_id' => $item->tax_rate_id,
                    'tax_amount' => $item->tax_amount,
                    'line_total' => $item->line_total,
                ];
            }
            $this->globalDiscountValue = $this->invoice->discount_total;
            $this->globalDiscountType = 'amount'; // Existing invoices only stored total amount
            $this->calculateTotals();
        } else {
            $this->invoiceNumber = app(DocumentNumberGenerator::class)->peek(auth()->user()->currentOrganization, 'INV');
            $this->invoiceDate = date('Y-m-d');
            $this->dueDate = date('Y-m-d', strtotime('+15 days'));
            
            $defaultTemplate = \App\Models\InvoiceTemplate::where('organization_id', auth()->user()->currentOrganization->id)
                ->where('is_default', true)
                ->first();
                
            if ($defaultTemplate) {
                $this->paymentInfo = $defaultTemplate->default_payment_info;
                $this->termsAndConditions = $defaultTemplate->default_terms_and_conditions;
            }
            
            $this->addItem(); // Add first empty row
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'product_id' => null,
            'item_name' => '',
            'description' => '',
            'hsn_code' => '',
            'quantity' => 1,
            'unit' => 'pcs',
            'rate' => 0.00,
            'discount_type' => 'percent',
            'discount_value' => 0.00,
            'discount_amount' => 0.00,
            'tax_rate_id' => null,
            'tax_amount' => 0.00,
            'line_total' => 0.00,
        ];
    }

    public function removeItem(int $index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    public function updatedItems($value, $key)
    {
        // When product is selected, auto-fill details
        if (str_ends_with($key, '.product_id')) {
            $index = explode('.', $key)[0];
            $productId = $this->items[$index]['product_id'];
            
            if ($productId) {
                $product = collect($this->products)->firstWhere('id', $productId);
                if ($product) {
                    $this->items[$index]['item_name'] = $product['name'];
                    $this->items[$index]['description'] = $product['description'] ?? '';
                    $this->items[$index]['hsn_code'] = $product['hsn_code'] ?? $product['sac_code'] ?? '';
                    $this->items[$index]['rate'] = $product['selling_price'];
                    $this->items[$index]['unit'] = $product['unit'] ?? 'pcs';
                    $this->items[$index]['tax_rate_id'] = $product['tax_rate_id'];
                }
            }
        }
        
        $this->calculateTotals();
    }

    public function updatedGlobalDiscountValue()
    {
        $this->calculateTotals();
    }

    public function updatedGlobalDiscountType()
    {
        $this->calculateTotals();
    }

    public function createContact()
    {
        $this->validate(['newContactName' => 'required|string|max:255']);
        
        $contact = Contact::create([
            'organization_id' => auth()->user()->current_organization_id,
            'type' => 'customer',
            'name' => $this->newContactName,
            'display_name' => $this->newContactName,
        ]);
        
        $this->contacts = Contact::active()->orderBy('display_name')->get()->toArray();
        $this->contactId = $contact->id;
        $this->newContactName = '';
        $this->showNewContactInput = false;
        
        $this->dispatch('notify', ['message' => 'Customer created!']);
    }

    public function createProduct()
    {
        $this->validate([
            'newProductName' => 'required|string|max:255',
            'newProductPrice' => 'required|numeric|min:0',
        ]);
        
        $product = Product::create([
            'organization_id' => auth()->user()->current_organization_id,
            'item_type' => $this->type === 'service' ? 'service' : 'product',
            'name' => $this->newProductName,
            'selling_price' => $this->newProductPrice,
            'unit' => $this->type === 'service' ? null : 'pcs',
        ]);
        
        $productQuery = Product::active()->orderBy('name');
        if ($this->type === 'service') {
            $productQuery->services();
        } else {
            $productQuery->products();
        }
        $this->products = $productQuery->get()->toArray();
        
        $this->newProductName = '';
        $this->newProductPrice = 0.00;
        $this->showNewProductInput = false;
        
        $this->dispatch('notify', ['message' => 'Item created!']);
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        $this->taxTotal = 0;
        $lineItemDiscounts = 0;
        
        foreach ($this->items as $index => $item) {
            $qty = floatval($item['quantity']);
            $rate = floatval($item['rate']);
            $lineSubtotal = $qty * $rate;
            
            // Calculate item discount
            $discountVal = floatval($item['discount_value'] ?? 0);
            $itemDiscountAmount = 0;
            if (($item['discount_type'] ?? 'percent') === 'percent') {
                $itemDiscountAmount = $lineSubtotal * ($discountVal / 100);
            } else {
                $itemDiscountAmount = $discountVal;
            }
            $this->items[$index]['discount_amount'] = $itemDiscountAmount;
            
            $discountedSubtotal = $lineSubtotal - $itemDiscountAmount;
            
            $taxAmount = 0;
            if ($item['tax_rate_id']) {
                $tax = collect($this->taxRates)->firstWhere('id', $item['tax_rate_id']);
                if ($tax) {
                    $taxAmount = round($discountedSubtotal * ($tax['percentage'] / 100), 2);
                }
            }
            
            $this->items[$index]['tax_amount'] = $taxAmount;
            $this->items[$index]['line_total'] = round($discountedSubtotal + $taxAmount, 2);
            
            $this->subtotal += $lineSubtotal; // Subtotal before line item discounts
            $this->taxTotal += $taxAmount;
            $lineItemDiscounts += $itemDiscountAmount;
        }
        
        // Calculate global discount
        $globalDiscountAmount = 0;
        $gDiscVal = floatval($this->globalDiscountValue);
        if ($this->globalDiscountType === 'percent') {
            $globalDiscountAmount = $this->subtotal * ($gDiscVal / 100);
        } else {
            $globalDiscountAmount = $gDiscVal;
        }
        
        $this->discountTotal = $lineItemDiscounts + $globalDiscountAmount;
        
        $rawTotal = $this->subtotal + $this->taxTotal - $this->discountTotal;
        $roundedTotal = round($rawTotal);
        
        $this->roundOff = round($roundedTotal - $rawTotal, 2);
        $this->grandTotal = max(0, $roundedTotal);
    }

    public function save(string $statusAction = 'draft')
    {
        $this->validate([
            'contactId' => 'required|exists:contacts,id',
            'invoiceNumber' => 'nullable|string|max:255',
            'invoiceDate' => 'required|date',
            'dueDate' => $this->invoiceBasis === 'cash' ? 'nullable|date' : 'required|date',
            'invoiceBasis' => 'required|in:cash,credit',
            'items.*.discount_type' => 'required|in:percent,amount',
            'items.*.discount_value' => 'nullable|numeric|min:0',
            'items.*.tax_rate_id' => 'nullable|exists:tax_rates,id',
            'paymentInfo' => 'nullable|string',
            'termsAndConditions' => 'nullable|string',
            'globalDiscountType' => 'required|in:percent,amount',
            'globalDiscountValue' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.item_name' => 'required_without:items.*.product_id|string|max:255',
            'items.*.description' => 'nullable|string|max:1000',
            'items.*.hsn_code' => [
                auth()->user()->currentOrganization->gst_number ? 'required' : 'nullable',
                'string', 'max:15'
            ],
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'nullable|string|max:20',
            'items.*.rate' => 'required|numeric|min:0',
        ], [
            'contactId.required' => 'Please select a customer.',
            'items.min' => 'You must add at least one line item.',
            'items.*.item_name.required_without' => 'Please select an item or provide an item name.',
        ]);

        $this->calculateTotals();

        DB::transaction(function () use ($statusAction) {
            $organization = auth()->user()->currentOrganization;
            
            $isNew = !$this->invoice || !$this->invoice->exists;
            $generator = app(DocumentNumberGenerator::class);
            
            $status = $statusAction === 'send' ? InvoiceStatus::Sent : InvoiceStatus::Draft;

            $data = [
                'invoice_basis' => $this->invoiceBasis,
                'invoice_type' => $this->type,
                'contact_id' => $this->contactId,
                'invoice_date' => $this->invoiceDate,
                'due_date' => $this->dueDate,
                'subtotal' => $this->subtotal,
                'tax_total' => $this->taxTotal,
                'discount_total' => $this->discountTotal,
                'round_off' => $this->roundOff,
                'grand_total' => $this->grandTotal,
                'payment_info' => $this->paymentInfo,
                'terms_and_conditions' => $this->termsAndConditions,
                'balance_due' => $this->grandTotal, // Since no payment yet
                'amount_paid' => 0,
            ];

            if ($this->invoiceBasis === 'cash' && $statusAction === 'send') {
                $status = InvoiceStatus::Paid;
                $data['amount_paid'] = $this->grandTotal;
                $data['balance_due'] = 0;
            }

            if ($isNew) {
                $data['organization_id'] = $organization->id;
                $data['created_by'] = auth()->id();
                $data['status'] = $status;
                $data['invoice_number'] = $this->invoiceNumber ?: $generator->generate($organization, 'INV');
                
                $this->invoice = Invoice::create($data);
            } else {
                if ($this->invoice->status->value !== 'draft') {
                    // Reverse old stock movements before deleting
                    foreach ($this->invoice->items as $item) {
                        if ($item->product && $item->product->isProduct()) {
                            $item->product->stockMovements()->create([
                                'organization_id' => $organization->id,
                                'product_id' => $item->product_id,
                                'type' => \App\Enums\StockMovementType::AdjustmentIn,
                                'quantity' => $item->quantity,
                                'balance_after' => $item->product->current_stock + $item->quantity,
                                'notes' => 'Sales Invoice Edit Reversal #' . $this->invoice->invoice_number,
                                'created_by' => auth()->id(),
                            ]);
                            $item->product->increment('current_stock', $item->quantity);
                        }
                    }
                }

                if ($this->invoice->status->value === 'draft' && $statusAction === 'send') {
                    $data['status'] = $this->invoiceBasis === 'cash' ? InvoiceStatus::Paid : InvoiceStatus::Sent;
                }
                if ($this->invoiceNumber) {
                    $data['invoice_number'] = $this->invoiceNumber;
                }
                $this->invoice->update($data);
                // Clear old items to recreate
                $this->invoice->items()->delete();
            }

            foreach ($this->items as $item) {
                $isPercent = ($item['discount_type'] ?? 'percent') === 'percent';
                
                InvoiceItem::create([
                    'invoice_id' => $this->invoice->id,
                    'product_id' => $item['product_id'],
                    'item_name' => $item['item_name'] ?? null,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'rate' => $item['rate'],
                    'discount_percent' => $isPercent ? ($item['discount_value'] ?? 0) : 0,
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'tax_rate_id' => $item['tax_rate_id'] ?: null,
                    'tax_amount' => $item['tax_amount'],
                    'line_total' => $item['line_total'],
                ]);
            }
            
            // Stock movements would happen here if status becomes Sent and type is Sales.
            // Let's implement stock deduction if sent.
            if ($statusAction === 'send' && $this->type === 'sales') {
                $this->invoice->refresh(); // Refresh relation to load newly created items!
                foreach ($this->invoice->items as $item) {
                    if ($item->product && $item->product->isProduct()) {
                        $item->product->stockMovements()->create([
                            'organization_id' => $organization->id,
                            'product_id' => $item->product_id,
                            'type' => \App\Enums\StockMovementType::SaleOut,
                            'quantity' => $item->quantity,
                            'balance_after' => $item->product->current_stock - $item->quantity,
                            'notes' => 'Sales Invoice #' . $this->invoice->invoice_number,
                            'created_by' => auth()->id(),
                        ]);
                        $item->product->decrement('current_stock', $item->quantity);
                    }
                }
            }
            
            // Post to ledger
            app(\App\Services\LedgerService::class)->postInvoice($this->invoice);
        });

        $this->redirect(route('invoices.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.invoices.invoice-form')->layout('components.layouts.app', [
            'title' => $this->invoice && $this->invoice->exists ? 'Edit Invoice' : 'New Invoice'
        ]);
    }
}
