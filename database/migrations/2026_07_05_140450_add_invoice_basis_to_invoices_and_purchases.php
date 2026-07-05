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
            $table->string('invoice_basis')->default('credit')->after('organization_id');
        });
        
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->string('invoice_basis')->default('credit')->after('organization_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('invoice_basis');
        });
        
        Schema::table('purchase_invoices', function (Blueprint $table) {
            $table->dropColumn('invoice_basis');
        });
    }
};
