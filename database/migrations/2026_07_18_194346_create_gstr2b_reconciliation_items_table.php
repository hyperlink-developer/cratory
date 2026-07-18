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
        Schema::create('gstr2b_reconciliation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gst_report_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('purchase_invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->string('uploaded_gstin')->nullable();
            $table->string('uploaded_invoice_number')->nullable();
            $table->decimal('uploaded_taxable_value', 15, 2)->nullable();
            $table->decimal('uploaded_tax_amount', 15, 2)->nullable();
            $table->enum('match_status', ['matched', 'unmatched', 'manual_review'])->default('unmatched');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gstr2b_reconciliation_items');
    }
};
