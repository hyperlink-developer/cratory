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
        Schema::create('receipts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 36)->unique();
            $table->unsignedBigInteger('organization_id');
            $table->string('receipt_number')->nullable();
            $table->unsignedBigInteger('contact_id')->index('receipts_contact_id_foreign');
            $table->date('receipt_date');
            $table->decimal('amount', 14);
            $table->string('payment_mode');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index('receipts_created_by_foreign');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'contact_id']);
            $table->unique(['organization_id', 'receipt_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
