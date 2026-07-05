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
        Schema::table('organizations', function (Blueprint $table) {
            $table->boolean('is_composition_tax_payer')->default(false)->after('is_active');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('hsn_code')->nullable()->after('description');
        });

        Schema::table('purchase_invoice_items', function (Blueprint $table) {
            $table->string('hsn_code')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('is_composition_tax_payer');
        });

        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn('hsn_code');
        });

        Schema::table('purchase_invoice_items', function (Blueprint $table) {
            $table->dropColumn('hsn_code');
        });
    }
};
