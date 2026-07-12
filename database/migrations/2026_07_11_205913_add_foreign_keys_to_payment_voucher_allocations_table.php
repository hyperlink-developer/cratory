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
        Schema::table('payment_voucher_allocations', function (Blueprint $table) {
            $table->foreign(['payment_voucher_id'])->references(['id'])->on('payment_vouchers')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['purchase_invoice_id'])->references(['id'])->on('purchase_invoices')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_voucher_allocations', function (Blueprint $table) {
            $table->dropForeign('payment_voucher_allocations_payment_voucher_id_foreign');
            $table->dropForeign('payment_voucher_allocations_purchase_invoice_id_foreign');
        });
    }
};
