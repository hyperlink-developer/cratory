<?php

namespace App\Services\Inventory;

use App\Models\Invoice;
use App\Models\PurchaseInvoice;
use App\Models\WarehouseStock;
use App\Models\StockMovement;
use App\Enums\StockMovementType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class InventoryService
{
    /**
     * Deduct stock for a sales invoice.
     * 
     * @param Invoice $invoice
     * @return void
     * @throws Exception
     */
    public function deductForInvoice(Invoice $invoice): void
    {
        // Only process sales invoices
        if (!$invoice->isSales()) {
            return;
        }

        if (!$invoice->warehouse_id) {
            Log::warning("Cannot deduct stock for Invoice {$invoice->invoice_number}: No warehouse selected.");
            return;
        }

        DB::transaction(function () use ($invoice) {
            foreach ($invoice->items as $item) {
                if (!$item->product || !$item->product->isProduct()) {
                    continue;
                }

                $stock = WarehouseStock::firstOrCreate(
                    [
                        'warehouse_id' => $invoice->warehouse_id,
                        'product_id' => $item->product_id,
                        'product_batch_id' => null, // Basic implementation without batch tracking for now
                    ],
                    ['quantity' => 0]
                );

                $stock->quantity -= $item->quantity;
                $stock->save();

                // Record stock movement
                $movement = new StockMovement([
                    'organization_id' => $invoice->organization_id,
                    'product_id' => $item->product_id,
                    'warehouse_id' => $invoice->warehouse_id,
                    'type' => StockMovementType::SaleOut,
                    'quantity' => $item->quantity,
                    'reference_type' => Invoice::class,
                    'reference_id' => $invoice->id,
                    'balance_after' => $stock->quantity,
                    'created_by' => $invoice->created_by,
                ]);

                $movement->save();

                // Update product total stock cache
                if (method_exists($item->product, 'recalculateStock')) {
                    $item->product->recalculateStock();
                }
            }
        });
    }

    /**
     * Add stock for a purchase invoice.
     * 
     * @param PurchaseInvoice $purchase
     * @return void
     * @throws Exception
     */
    public function addForPurchase(PurchaseInvoice $purchase): void
    {
        if (!$purchase->warehouse_id) {
            Log::warning("Cannot add stock for Purchase Invoice {$purchase->purchase_number}: No warehouse selected.");
            return;
        }

        DB::transaction(function () use ($purchase) {
            foreach ($purchase->items as $item) {
                if (!$item->product || !$item->product->isProduct()) {
                    continue;
                }

                $stock = WarehouseStock::firstOrCreate(
                    [
                        'warehouse_id' => $purchase->warehouse_id,
                        'product_id' => $item->product_id,
                        'product_batch_id' => null,
                    ],
                    ['quantity' => 0]
                );

                $stock->quantity += $item->quantity;
                $stock->save();

                // Record stock movement
                $movement = new StockMovement([
                    'organization_id' => $purchase->organization_id,
                    'product_id' => $item->product_id,
                    'warehouse_id' => $purchase->warehouse_id,
                    'type' => StockMovementType::PurchaseIn,
                    'quantity' => $item->quantity,
                    'reference_type' => PurchaseInvoice::class,
                    'reference_id' => $purchase->id,
                    'balance_after' => $stock->quantity,
                    'created_by' => $purchase->created_by,
                ]);

                $movement->save();

                // Update product total stock cache
                if (method_exists($item->product, 'recalculateStock')) {
                    $item->product->recalculateStock();
                }
            }
        });
    }
}
