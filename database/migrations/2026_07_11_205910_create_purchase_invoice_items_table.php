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
        Schema::create('purchase_invoice_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_invoice_id')->index('purchase_invoice_items_purchase_invoice_id_foreign');
            $table->unsignedBigInteger('product_id')->nullable()->index('purchase_invoice_items_product_id_foreign');
            $table->text('description')->nullable();
            $table->string('hsn_code')->nullable();
            $table->decimal('quantity', 14)->default(1);
            $table->string('unit')->nullable();
            $table->decimal('rate', 14)->default(0);
            $table->decimal('discount_percent', 5)->default(0);
            $table->decimal('discount_amount', 14)->default(0);
            $table->unsignedBigInteger('tax_rate_id')->nullable()->index('purchase_invoice_items_tax_rate_id_foreign');
            $table->decimal('tax_amount', 14)->default(0);
            $table->decimal('line_total', 14)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_invoice_items');
    }
};
