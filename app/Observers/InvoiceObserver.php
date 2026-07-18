<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Services\Inventory\InventoryService;

class InvoiceObserver
{
    protected InventoryService $inventory;

    public function __construct(InventoryService $inventory)
    {
        $this->inventory = $inventory;
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {
        if ($invoice->isDirty('status')) {
            $newStatus = $invoice->status;
            
            // If the invoice is finalized (not draft or cancelled), deduct stock
            $finalizedStatuses = [
                \App\Enums\InvoiceStatus::Sent,
                \App\Enums\InvoiceStatus::Partial,
                \App\Enums\InvoiceStatus::Paid,
                \App\Enums\InvoiceStatus::Overdue,
            ];

            if (in_array($newStatus, $finalizedStatuses)) {
                // Ensure we haven't already deducted stock for this invoice
                if (!$invoice->stockMovements()->exists()) {
                    $this->inventory->deductForInvoice($invoice);
                }
            }
        }
    }
}
