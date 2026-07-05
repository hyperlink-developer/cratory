<?php

namespace App\Livewire\Finance;

use App\Models\Contact;
use App\Models\PaymentVoucher;
use App\Models\PurchaseInvoice;
use App\Services\DocumentNumberGenerator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class VoucherForm extends Component
{
    public $contacts = [];
    public $openBills = [];

    public ?int $contactId = null;
    public string $paymentDate = '';
    public string $paymentMode = 'bank_transfer';
    public string $reference = '';
    public string $amountPaid = '';
    
    // Allocations: purchase_invoice_id => amount_allocated
    public array $allocations = [];
    
    public function mount()
    {
        $this->contacts = Contact::active()->vendors()->orderBy('display_name')->get();
        $this->paymentDate = date('Y-m-d');
    }

    public function updatedContactId($value)
    {
        $this->openBills = [];
        $this->allocations = [];
        
        if ($value) {
            $this->openBills = PurchaseInvoice::where('contact_id', $value)
                ->where('balance_due', '>', 0)
                ->where('status', '!=', 'draft')
                ->where('status', '!=', 'cancelled')
                ->orderBy('purchase_date')
                ->get();
                
            foreach ($this->openBills as $bill) {
                $this->allocations[$bill->id] = 0;
            }
        }
    }

    public function autoAllocate()
    {
        $remainingAmount = (float) $this->amountPaid;
        
        foreach ($this->openBills as $bill) {
            $this->allocations[$bill->id] = 0; // reset
            
            if ($remainingAmount <= 0) continue;
            
            $allocate = min($remainingAmount, $bill->balance_due);
            $this->allocations[$bill->id] = round($allocate, 2);
            $remainingAmount -= $allocate;
        }
    }

    public function save()
    {
        $this->validate([
            'contactId' => 'required|exists:contacts,id',
            'paymentDate' => 'required|date',
            'amountPaid' => 'required|numeric|min:0.01',
            'paymentMode' => 'required|string',
        ]);

        $totalAllocated = array_sum($this->allocations);
        if ($totalAllocated > (float) $this->amountPaid) {
            $this->addError('amountPaid', 'Total allocated amount cannot exceed amount paid.');
            return;
        }

        DB::transaction(function () {
            $org = auth()->user()->currentOrganization;
            $generator = app(DocumentNumberGenerator::class);
            
            $voucher = PaymentVoucher::create([
                'organization_id' => $org->id,
                'contact_id' => $this->contactId,
                'voucher_number' => $generator->generate($org, 'PAY'),
                'payment_date' => $this->paymentDate,
                'payment_mode' => $this->paymentMode,
                'reference_number' => $this->reference,
                'amount' => $this->amountPaid,
                'created_by' => auth()->id(),
            ]);

            foreach ($this->allocations as $billId => $amount) {
                if ((float)$amount > 0) {
                    $bill = PurchaseInvoice::lockForUpdate()->find($billId);
                    
                    if ($bill && $bill->balance_due >= $amount) {
                        $voucher->allocations()->create([
                            'purchase_invoice_id' => $billId,
                            'amount_allocated' => $amount,
                        ]);
                        
                        $bill->decrement('balance_due', $amount);
                        
                        if ($bill->balance_due <= 0.01) {
                            $bill->update(['status' => 'paid']);
                        } else {
                            $bill->update(['status' => 'partial']);
                        }
                    }
                }
            }
        });

        $this->dispatch('notify', ['message' => 'Payment voucher created successfully.']);
        $this->redirect(route('vouchers.index'), navigate: true);
    }

    public function render()
    {
        $totalAllocated = array_sum($this->allocations);
        $unallocated = max(0, (float)$this->amountPaid - $totalAllocated);
        
        return view('livewire.finance.voucher-form', [
            'totalAllocated' => $totalAllocated,
            'unallocated' => $unallocated,
        ])->layout('components.layouts.app', ['title' => 'Record Payment']);
    }
}
