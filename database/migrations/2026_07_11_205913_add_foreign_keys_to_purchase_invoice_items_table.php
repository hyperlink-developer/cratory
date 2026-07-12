<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchase_invoice_items', function (Blueprint $table) {
            $table->foreign(['product_id'])->references(['id'])->on('products')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['purchase_invoice_id'])->references(['id'])->on('purchase_invoices')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['tax_rate_id'])->references(['id'])->on('tax_rates')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_invoice_items', function (Blueprint $table) {
            $table->dropForeign('purchase_invoice_items_product_id_foreign');
            $table->dropForeign('purchase_invoice_items_purchase_invoice_id_foreign');
            $table->dropForeign('purchase_invoice_items_tax_rate_id_foreign');
        });
    }
};
