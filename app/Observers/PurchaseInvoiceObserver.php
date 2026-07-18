<?php

namespace App\Observers;

use App\Models\PurchaseInvoice;
use App\Services\Inventory\InventoryService;

class PurchaseInvoiceObserver
{
    protected InventoryService $inventory;

    public function __construct(InventoryService $inventory)
    {
        $this->inventory = $inventory;
    }

    /**
     * Handle the PurchaseInvoice "updated" event.
     */
    public function updated(PurchaseInvoice $purchaseInvoice): void
    {
        if ($purchaseInvoice->isDirty('status')) {
            $newStatus = $purchaseInvoice->status;
            
            // If the purchase invoice is finalized (not draft or cancelled), add stock
            $finalizedStatuses = [
                \App\Enums\PurchaseStatus::Received,
                \App\Enums\PurchaseStatus::Partial,
                \App\Enums\PurchaseStatus::Paid,
            ];

            if (in_array($newStatus, $finalizedStatuses)) {
                // Ensure we haven't already added stock for this invoice
                if (!$purchaseInvoice->stockMovements()->exists()) {
                    $this->inventory->addForPurchase($purchaseInvoice);
                }
            }
        }
    }
}
