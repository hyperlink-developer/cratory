<?php

namespace App\Livewire\Finance;

use App\Models\Receipt;
use Livewire\Component;
use Livewire\WithPagination;

class ReceiptList extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteReceipt(string $uuid)
    {
        $receipt = Receipt::where('uuid', $uuid)->firstOrFail();
        
        $invoices = $receipt->allocations->map->invoice->unique();
        
        $receipt->delete();
        
        foreach ($invoices as $invoice) {
            $invoice->recalculateAmountPaid();
        }
        
        $this->dispatch('notify', ['message' => 'Receipt deleted successfully']);
    }

    public function render()
    {
        $receipts = Receipt::with(['contact'])
            ->when($this->search, function ($query) {
                $query->where('receipt_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('contact', function ($q) {
                          $q->where('display_name', 'like', '%' . $this->search . '%')
                            ->orWhere('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.finance.receipt-list', [
            'receipts' => $receipts
        ])->layout('components.layouts.app', ['title' => 'Receipts']);
    }
}
