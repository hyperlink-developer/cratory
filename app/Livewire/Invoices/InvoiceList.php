<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceList extends Component
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

    public function deleteInvoice(string $uuid)
    {
        $invoice = Invoice::where('uuid', $uuid)->firstOrFail();
        // Only allow deleting drafts
        if ($invoice->status->value === 'draft') {
            $invoice->delete();
            $this->dispatch('notify', ['message' => 'Invoice deleted successfully']);
        } else {
            $this->dispatch('notify', ['message' => 'Only draft invoices can be deleted.', 'type' => 'error']);
        }
    }

    public function render()
    {
        $invoices = Invoice::with(['contact'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('invoice_number', 'like', '%' . $this->search . '%')
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

        return view('livewire.invoices.invoice-list', [
            'invoices' => $invoices
        ])->layout('components.layouts.app', ['title' => 'Invoices']);
    }
}
