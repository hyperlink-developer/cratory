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
        Schema::create('payment_voucher_allocations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payment_voucher_id')->index('payment_voucher_allocations_payment_voucher_id_foreign');
            $table->unsignedBigInteger('purchase_invoice_id')->index('payment_voucher_allocations_purchase_invoice_id_foreign');
            $table->decimal('allocated_amount', 14);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_voucher_allocations');
    }
};
