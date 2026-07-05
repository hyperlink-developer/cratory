<?php

namespace App\Livewire\Finance;

use App\Models\Contact;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Services\DocumentNumberGenerator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReceiptForm extends Component
{
    public $contacts = [];
    public $openInvoices = [];

    public ?int $contactId = null;
    public string $paymentDate = '';
    public string $paymentMode = 'bank_transfer';
    public string $reference = '';
    public string $amountReceived = '';
    
    // Allocations: invoice_id => amount_allocated
    public array $allocations = [];
    
    public function mount()
    {
        $this->contacts = Contact::active()->customers()->orderBy('display_name')->get();
        $this->paymentDate = date('Y-m-d');
    }

    public function updatedContactId($value)
    {
        $this->openInvoices = [];
        $this->allocations = [];
        
        if ($value) {
            $this->openInvoices = Invoice::where('contact_id', $value)
                ->where('balance_due', '>', 0)
                ->where('status', '!=', 'draft')
                ->where('status', '!=', 'cancelled')
                ->orderBy('invoice_date')
                ->get();
                
            // Initialize allocations array
            foreach ($this->openInvoices as $invoice) {
                $this->allocations[$invoice->id] = 0;
            }
        }
    }

    public function autoAllocate()
    {
        $remainingAmount = (float) $this->amountReceived;
        
        foreach ($this->openInvoices as $invoice) {
            $this->allocations[$invoice->id] = 0; // reset
            
            if ($remainingAmount <= 0) continue;
            
            $allocate = min($remainingAmount, $invoice->balance_due);
            $this->allocations[$invoice->id] = round($allocate, 2);
            $remainingAmount -= $allocate;
        }
    }

    public function save()
    {
        $this->validate([
            'contactId' => 'required|exists:contacts,id',
            'paymentDate' => 'required|date',
            'amountReceived' => 'required|numeric|min:0.01',
            'paymentMode' => 'required|string',
        ]);

        $totalAllocated = array_sum($this->allocations);
        if ($totalAllocated > (float) $this->amountReceived) {
            $this->addError('amountReceived', 'Total allocated amount cannot exceed amount received.');
            return;
        }

        DB::transaction(function () {
            $org = auth()->user()->currentOrganization;
            $generator = app(DocumentNumberGenerator::class);
            
            $receipt = Receipt::create([
                'organization_id' => $org->id,
                'contact_id' => $this->contactId,
                'receipt_number' => $generator->generate($org, 'REC'),
                'payment_date' => $this->paymentDate,
                'payment_mode' => $this->paymentMode,
                'reference_number' => $this->reference,
                'amount' => $this->amountReceived,
                'created_by' => auth()->id(),
            ]);

            foreach ($this->allocations as $invoiceId => $amount) {
                if ((float)$amount > 0) {
                    $invoice = Invoice::lockForUpdate()->find($invoiceId);
                    
                    if ($invoice && $invoice->balance_due >= $amount) {
                        $receipt->allocations()->create([
                            'invoice_id' => $invoiceId,
                            'amount_allocated' => $amount,
                        ]);
                        
                        $invoice->decrement('balance_due', $amount);
                        
                        // Update status if fully paid
                        if ($invoice->balance_due <= 0.01) {
                            $invoice->update(['status' => 'paid']);
                        } else {
                            $invoice->update(['status' => 'partial']);
                        }
                    }
                }
            }
        });

        $this->dispatch('notify', ['message' => 'Receipt created successfully.']);
        $this->redirect(route('receipts.index'), navigate: true);
    }

    public function render()
    {
        $totalAllocated = array_sum($this->allocations);
        $unallocated = max(0, (float)$this->amountReceived - $totalAllocated);
        
        return view('livewire.finance.receipt-form', [
            'totalAllocated' => $totalAllocated,
            'unallocated' => $unallocated,
        ])->layout('components.layouts.app', ['title' => 'Record Receipt']);
    }
}
