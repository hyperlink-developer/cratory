<?php

namespace App\Livewire\Purchases;

use App\Enums\PurchaseStatus;
use App\Models\Contact;
use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\TaxRate;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseForm extends Component
{
    public ?PurchaseInvoice $purchase = null;
    
    // Header Info
    public ?int $contactId = null;
    public string $billNumber = ''; // Vendor's bill number
    public string $billDate = '';
    public string $dueDate = '';
    
    // Line Items
    public array $items = [];
    
    // Totals
    public float $subtotal = 0.00;
    public float $taxTotal = 0.00;
    public float $discountTotal = 0.00;
    public float $roundOff = 0.00;
    public float $grandTotal = 0.00;

    // Data for dropdowns
    public array $contacts = [];
    public array $products = [];
    public array $taxRates = [];
    
    // Quick Add
    public bool $showNewContactInput = false;
    public string $newContactName = '';
    
    public bool $showNewProductInput = false;
    public string $newProductName = '';
    public float $newProductPrice = 0.00;

    public function mount(PurchaseInvoice $purchase = null)
    {
        $this->contacts = Contact::active()->vendors()->orderBy('display_name')->get()->toArray();
        $this->products = Product::active()->orderBy('name')->get()->toArray();
        $this->taxRates = TaxRate::active()->orderBy('percentage')->get()->toArray();

        if ($purchase && $purchase->exists) {
            $this->purchase = $purchase;
            $this->contactId = $purchase->contact_id;
            $this->billNumber = $purchase->bill_number ?? '';
            $this->billDate = $purchase->purchase_date?->format('Y-m-d') ?? '';
            $this->dueDate = $purchase->due_date?->format('Y-m-d') ?? '';
            
            foreach ($purchase->items as $item) {
                $this->items[] = [
                    'product_id' => $item->product_id,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'rate' => $item->rate,
                    'tax_rate_id' => $item->tax_rate_id,
                    'tax_amount' => $item->tax_amount,
                    'line_total' => $item->line_total,
                ];
            }
            $this->calculateTotals();
        } else {
            $this->billDate = date('Y-m-d');
            $this->dueDate = date('Y-m-d', strtotime('+15 days'));
            $this->addItem();
        }
    }

    public function addItem()
    {
        $this->items[] = [
            'product_id' => null,
            'description' => '',
            'quantity' => 1,
            'unit' => 'pcs',
            'rate' => 0.00,
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
        // When product is selected, auto-fill details (use purchase_price instead of selling_price)
        if (str_ends_with($key, '.product_id')) {
            $index = explode('.', $key)[0];
            $productId = $this->items[$index]['product_id'];
            
            if ($productId) {
                $product = collect($this->products)->firstWhere('id', $productId);
                if ($product) {
                    $this->items[$index]['description'] = $product['description'] ?? '';
                    $this->items[$index]['rate'] = $product['purchase_price'];
                    $this->items[$index]['unit'] = $product['unit'] ?? 'pcs';
                    $this->items[$index]['tax_rate_id'] = $product['tax_rate_id'];
                }
            }
        }
        
        $this->calculateTotals();
    }

    public function createContact()
    {
        $this->validate(['newContactName' => 'required|string|max:255']);
        
        $contact = Contact::create([
            'organization_id' => auth()->user()->current_organization_id,
            'type' => 'vendor',
            'name' => $this->newContactName,
            'display_name' => $this->newContactName,
        ]);
        
        $this->contacts = Contact::active()->vendors()->orderBy('display_name')->get()->toArray();
        $this->contactId = $contact->id;
        $this->newContactName = '';
        $this->showNewContactInput = false;
        
        $this->dispatch('notify', ['message' => 'Vendor created!']);
    }

    public function createProduct()
    {
        $this->validate([
            'newProductName' => 'required|string|max:255',
            'newProductPrice' => 'required|numeric|min:0',
        ]);
        
        $product = Product::create([
            'organization_id' => auth()->user()->current_organization_id,
            'item_type' => 'product',
            'name' => $this->newProductName,
            'purchase_price' => $this->newProductPrice,
            'unit' => 'pcs',
        ]);
        
        $this->products = Product::active()->orderBy('name')->get()->toArray();
        
        $this->newProductName = '';
        $this->newProductPrice = 0.00;
        $this->showNewProductInput = false;
        
        $this->dispatch('notify', ['message' => 'Product created!']);
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        $this->taxTotal = 0;
        
        foreach ($this->items as $index => $item) {
            $qty = floatval($item['quantity']);
            $rate = floatval($item['rate']);
            $lineSubtotal = $qty * $rate;
            
            $taxAmount = 0;
            if ($item['tax_rate_id']) {
                $tax = collect($this->taxRates)->firstWhere('id', $item['tax_rate_id']);
                if ($tax) {
                    $taxAmount = round($lineSubtotal * ($tax['percentage'] / 100), 2);
                }
            }
            
            $this->items[$index]['tax_amount'] = $taxAmount;
            $this->items[$index]['line_total'] = round($lineSubtotal + $taxAmount, 2);
            
            $this->subtotal += $lineSubtotal;
            $this->taxTotal += $taxAmount;
        }
        
        $rawTotal = $this->subtotal + $this->taxTotal;
        $roundedTotal = round($rawTotal);
        
        $this->roundOff = round($roundedTotal - $rawTotal, 2);
        $this->grandTotal = $roundedTotal;
    }

    public function save(string $statusAction = 'draft')
    {
        $this->validate([
            'contactId' => 'required|exists:contacts,id',
            'billNumber' => 'required|string|max:100',
            'billDate' => 'required|date',
            'dueDate' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.rate' => 'required|numeric|min:0',
        ], [
            'contactId.required' => 'Please select a vendor.',
            'items.min' => 'You must add at least one line item.',
            'items.*.product_id.required' => 'Please select an item for all rows.',
        ]);

        $this->calculateTotals();

        DB::transaction(function () use ($statusAction) {
            $organization = auth()->user()->currentOrganization;
            $isNew = !$this->purchase || !$this->purchase->exists;
            $status = $statusAction === 'receive' ? PurchaseStatus::Received : PurchaseStatus::Draft;

            $data = [
                'contact_id' => $this->contactId,
                'bill_number' => $this->billNumber,
                'purchase_date' => $this->billDate,
                'due_date' => $this->dueDate,
                'subtotal' => $this->subtotal,
                'tax_total' => $this->taxTotal,
                'discount_total' => $this->discountTotal,
                'round_off' => $this->roundOff,
                'grand_total' => $this->grandTotal,
                'balance_due' => $this->grandTotal,
            ];

            if ($isNew) {
                $data['organization_id'] = $organization->id;
                $data['created_by'] = auth()->id();
                $data['status'] = $status;
                
                $this->purchase = PurchaseInvoice::create($data);
            } else {
                if ($this->purchase->status->value === 'draft' && $statusAction === 'receive') {
                    $data['status'] = PurchaseStatus::Received;
                }
                $this->purchase->update($data);
                $this->purchase->items()->delete();
            }

            foreach ($this->items as $item) {
                $this->purchase->items()->create([
                    'product_id' => $item['product_id'],
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'rate' => $item['rate'],
                    'discount_percent' => 0,
                    'discount_amount' => 0,
                    'tax_rate_id' => $item['tax_rate_id'],
                    'tax_amount' => $item['tax_amount'],
                    'line_total' => $item['line_total'],
                ]);
            }
            
            // Stock movements: Increment stock if received
            if ($statusAction === 'receive') {
                foreach ($this->purchase->items as $item) {
                    if ($item->product->isProduct()) {
                        $item->product->stockMovements()->create([
                            'organization_id' => $organization->id,
                            'product_id' => $item->product_id,
                            'type' => 'in',
                            'quantity' => $item->quantity,
                            'notes' => 'Purchase Bill #' . $this->purchase->bill_number,
                            'created_by' => auth()->id(),
                        ]);
                        $item->product->increment('current_stock', $item->quantity);
                    }
                }
            }
        });

        $this->redirect(route('purchases.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.purchases.purchase-form')->layout('components.layouts.app', [
            'title' => $this->purchase && $this->purchase->exists ? 'Edit Bill' : 'Record Bill'
        ]);
    }
}
