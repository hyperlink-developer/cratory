<?php

namespace App\Livewire\Purchases;

use App\Models\PurchaseInvoice;
use Livewire\Component;
use Livewire\WithPagination;

class PurchaseList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function deletePurchase(string $uuid)
    {
        $purchase = PurchaseInvoice::where('uuid', $uuid)->firstOrFail();
        if ($purchase->status->value === 'draft') {
            $purchase->delete();
            $this->dispatch('notify', ['message' => 'Purchase invoice deleted successfully']);
        } else {
            $this->dispatch('notify', ['message' => 'Only draft purchases can be deleted.', 'type' => 'error']);
        }
    }

    public function render()
    {
        $purchases = PurchaseInvoice::with(['contact'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('bill_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('contact', function ($cq) {
                          $cq->where('display_name', 'like', '%' . $this->search . '%')
                             ->orWhere('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.purchases.purchase-list', [
            'purchases' => $purchases
        ])->layout('components.layouts.app', ['title' => 'Purchases']);
    }
}
