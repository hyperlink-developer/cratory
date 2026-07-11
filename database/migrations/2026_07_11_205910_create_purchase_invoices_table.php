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
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 36)->unique();
            $table->unsignedBigInteger('organization_id');
            $table->string('invoice_basis')->default('credit');
            $table->string('purchase_number')->nullable();
            $table->string('vendor_bill_number')->nullable();
            $table->unsignedBigInteger('contact_id')->index('purchase_invoices_contact_id_foreign');
            $table->date('purchase_date');
            $table->date('due_date')->nullable();
            $table->decimal('subtotal', 14)->default(0);
            $table->decimal('discount_total', 14)->default(0);
            $table->decimal('tax_total', 14)->default(0);
            $table->decimal('round_off', 14)->default(0);
            $table->decimal('grand_total', 14)->default(0);
            $table->decimal('amount_paid', 14)->default(0);
            $table->decimal('balance_due', 14)->default(0);
            $table->string('status')->default('draft');
            $table->string('attachment_path')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('purchase_invoices_created_by_foreign');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'contact_id']);
            $table->unique(['organization_id', 'purchase_number']);
            $table->index(['organization_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_invoices');
    }
};
