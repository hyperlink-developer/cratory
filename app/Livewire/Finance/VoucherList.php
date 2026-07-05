<?php

namespace App\Livewire\Finance;

use App\Models\PaymentVoucher;
use Livewire\Component;
use Livewire\WithPagination;

class VoucherList extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteVoucher(string $uuid)
    {
        $voucher = PaymentVoucher::where('uuid', $uuid)->firstOrFail();
        
        foreach ($voucher->allocations as $allocation) {
            $allocation->purchaseInvoice->increment('balance_due', $allocation->amount_allocated);
            if ($allocation->purchaseInvoice->balance_due > 0 && $allocation->purchaseInvoice->status->value === 'paid') {
                $allocation->purchaseInvoice->update(['status' => 'partial']);
            }
        }
        
        $voucher->delete();
        $this->dispatch('notify', ['message' => 'Payment voucher deleted successfully']);
    }

    public function render()
    {
        $vouchers = PaymentVoucher::with(['contact'])
            ->when($this->search, function ($query) {
                $query->where('voucher_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('contact', function ($q) {
                          $q->where('display_name', 'like', '%' . $this->search . '%')
                            ->orWhere('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.finance.voucher-list', [
            'vouchers' => $vouchers
        ])->layout('components.layouts.app', ['title' => 'Payment Vouchers']);
    }
}
