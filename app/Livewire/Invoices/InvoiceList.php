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

    public function sendInvoice(string $uuid, array $channels = ['mail'])
    {
        $invoice = Invoice::where('uuid', $uuid)->with('contact')->firstOrFail();
        
        if (!$invoice->contact) {
            $this->dispatch('notify', ['message' => 'Invoice has no contact.', 'type' => 'error']);
            return;
        }

        $invoice->contact->notify(new \App\Notifications\SendDocumentNotification($invoice, $channels));

        $this->dispatch('notify', ['message' => 'Invoice queued for sending.', 'type' => 'success']);
    }

    public function generateIrn(string $uuid, \App\Services\GST\EInvoiceService $service)
    {
        $invoice = Invoice::where('uuid', $uuid)->firstOrFail();
        
        try {
            if ($service->generateIrn($invoice)) {
                $this->dispatch('notify', ['message' => 'IRN generated successfully.', 'type' => 'success']);
            } else {
                $this->dispatch('notify', ['message' => 'Invoice already has an IRN.', 'type' => 'warning']);
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'Failed to generate IRN: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function generateEWayBill(string $uuid, \App\Services\GST\EInvoiceService $service)
    {
        $invoice = Invoice::where('uuid', $uuid)->firstOrFail();
        
        try {
            if ($service->generateEWayBill($invoice)) {
                $this->dispatch('notify', ['message' => 'E-Way Bill generated successfully.', 'type' => 'success']);
            } else {
                $this->dispatch('notify', ['message' => 'Invoice already has an E-Way Bill.', 'type' => 'warning']);
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', ['message' => 'Failed to generate E-Way Bill: ' . $e->getMessage(), 'type' => 'error']);
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
