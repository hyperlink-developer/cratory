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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('irn', 64)->nullable()->unique();
            $table->string('irn_status', 20)->nullable(); // generated, cancelled
            $table->string('ack_no')->nullable();
            $table->timestamp('ack_date')->nullable();
            $table->text('signed_qr_code')->nullable();
            $table->text('signed_invoice')->nullable();
            $table->string('cancel_reason')->nullable();
            
            $table->string('eway_bill_number')->nullable();
            $table->timestamp('eway_bill_date')->nullable();
            $table->timestamp('eway_bill_valid_until')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('transporter_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'irn', 'irn_status', 'ack_no', 'ack_date', 
                'signed_qr_code', 'signed_invoice', 'cancel_reason',
                'eway_bill_number', 'eway_bill_date', 'eway_bill_valid_until', 
                'vehicle_number', 'transporter_id'
            ]);
        });
    }
};
