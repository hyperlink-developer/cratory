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
        Schema::table('products', function (Blueprint $table) {
            $table->foreign(['category_id'])->references(['id'])->on('product_categories')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['organization_id'])->references(['id'])->on('organizations')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['tax_rate_id'])->references(['id'])->on('tax_rates')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['unit_of_measure_id'])->references(['id'])->on('unit_of_measures')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_category_id_foreign');
            $table->dropForeign('products_organization_id_foreign');
            $table->dropForeign('products_tax_rate_id_foreign');
            $table->dropForeign('products_unit_of_measure_id_foreign');
        });
    }
};
