<?php

namespace App\Livewire\Invoices;

use App\Models\Contact;
use App\Models\Product;
use App\Models\RecurringInvoiceTemplate;
use App\Models\TaxRate;
use Carbon\Carbon;
use Livewire\Component;

class RecurringInvoiceForm extends Component
{
    public $contact_id;
    public $frequency = 'monthly';
    public $next_run_date;
    public $auto_send = false;
    
    // Invoice data
    public $invoice_type = 'sales';
    public $items = [];
    public $notes;
    
    // Calculated
    public $subtotal = 0;
    public $tax_total = 0;
    public $grand_total = 0;

    public function mount()
    {
        $this->next_run_date = Carbon::today()->format('Y-m-d');
        $this->addItem();
    }

    public function addItem()
    {
        $this->items[] = [
            'name' => '',
            'quantity' => 1,
            'unit_price' => 0,
            'tax_rate_id' => null,
            'line_total' => 0,
            'tax_amount' => 0,
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    public function updatedItems()
    {
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        $this->tax_total = 0;

        foreach ($this->items as $index => $item) {
            $qty = (float)($item['quantity'] ?? 0);
            $price = (float)($item['unit_price'] ?? 0);
            
            $lineTotal = $qty * $price;
            $this->items[$index]['line_total'] = $lineTotal;
            $this->subtotal += $lineTotal;

            if (!empty($item['tax_rate_id'])) {
                $taxRate = TaxRate::find($item['tax_rate_id']);
                if ($taxRate) {
                    $taxAmount = round($lineTotal * ($taxRate->percentage / 100), 2);
                    $this->items[$index]['tax_amount'] = $taxAmount;
                    $this->tax_total += $taxAmount;
                }
            } else {
                $this->items[$index]['tax_amount'] = 0;
            }
        }

        $this->grand_total = $this->subtotal + $this->tax_total;
    }

    public function save()
    {
        $this->validate([
            'contact_id' => 'required',
            'frequency' => 'required|in:weekly,monthly,quarterly,yearly',
            'next_run_date' => 'required|date',
            'items.*.name' => 'required',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $this->calculateTotals();

        $data = [
            'invoice_type' => $this->invoice_type,
            'subtotal' => $this->subtotal,
            'tax_total' => $this->tax_total,
            'grand_total' => $this->grand_total,
            'notes' => $this->notes,
            'items' => $this->items,
        ];

        RecurringInvoiceTemplate::create([
            'organization_id' => auth()->user()->current_organization_id,
            'contact_id' => $this->contact_id,
            'frequency' => $this->frequency,
            'next_run_date' => $this->next_run_date,
            'auto_send' => $this->auto_send,
            'template_invoice_data' => $data,
            'is_active' => true,
        ]);

        $this->dispatch('notify', ['message' => 'Recurring Invoice Template created!', 'type' => 'success']);
        return redirect()->route('invoices.recurring.index');
    }

    public function render()
    {
        $orgId = auth()->user()->current_organization_id;
        
        return view('livewire.invoices.recurring-invoice-form', [
            'contacts' => Contact::where('organization_id', $orgId)->get(),
            'taxRates' => TaxRate::where('organization_id', $orgId)->get(),
            'products' => Product::where('organization_id', $orgId)->get(),
        ])->layout('components.layouts.app', ['title' => 'Create Recurring Invoice']);
    }
}
