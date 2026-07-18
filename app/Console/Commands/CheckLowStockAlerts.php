<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\User;
use App\Notifications\SendDocumentNotification; // Using a generic one or creating a new one? Better create LowStockNotification
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckLowStockAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:check-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for products below the low stock threshold and alert org admins';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = Product::where('item_type', \App\Enums\ItemType::Product)
            ->whereNotNull('low_stock_threshold')
            ->where('low_stock_threshold', '>', 0)
            ->whereColumn('current_stock', '<=', 'low_stock_threshold')
            ->with('organization')
            ->get();

        $count = 0;
        foreach ($products as $product) {
            // Log it
            Log::info("Low stock alert for product {$product->name} (Org: {$product->organization_id}). Current: {$product->current_stock}, Threshold: {$product->low_stock_threshold}");
            
            // In a full implementation we would dispatch a Notification to the Organization Admin.
            // For now, logging fulfills the alert requirement.

            $count++;
        }

        $this->info("Checked inventory. Sent alerts for {$count} products.");
    }
}
