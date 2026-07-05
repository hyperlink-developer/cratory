<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Receipts (money received against sales/service invoices)
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('receipt_number')->nullable();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->date('receipt_date');
            $table->decimal('amount', 14, 2);
            $table->string('payment_mode'); // PaymentMode enum
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organization_id', 'receipt_number']);
            $table->index(['organization_id', 'contact_id']);
        });

        Schema::create('receipt_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('allocated_amount', 14, 2);
            $table->timestamps();
        });

        // Payment vouchers (money paid against purchases)
        Schema::create('payment_vouchers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('voucher_number')->nullable();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->date('voucher_date');
            $table->decimal('amount', 14, 2);
            $table->string('payment_mode'); // PaymentMode enum
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organization_id', 'voucher_number']);
            $table->index(['organization_id', 'contact_id']);
        });

        Schema::create('payment_voucher_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_voucher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('purchase_invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('allocated_amount', 14, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_voucher_allocations');
        Schema::dropIfExists('payment_vouchers');
        Schema::dropIfExists('receipt_allocations');
        Schema::dropIfExists('receipts');
    }
};
