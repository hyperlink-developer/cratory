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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 36)->unique();
            $table->unsignedBigInteger('organization_id');
            $table->string('item_type');
            $table->unsignedBigInteger('category_id')->nullable()->index('products_category_id_foreign');
            $table->string('name');
            $table->string('sku')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('unit_of_measure_id')->nullable()->index('products_unit_of_measure_id_foreign');
            $table->string('hsn_code')->nullable();
            $table->string('sac_code')->nullable();
            $table->decimal('purchase_price', 14)->nullable();
            $table->decimal('selling_price', 14)->default(0);
            $table->unsignedBigInteger('tax_rate_id')->nullable()->index('products_tax_rate_id_foreign');
            $table->decimal('opening_stock', 14)->default(0);
            $table->decimal('current_stock', 14)->default(0);
            $table->decimal('reorder_level', 14)->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['organization_id', 'item_type']);
            $table->index(['organization_id', 'name']);
            $table->unique(['organization_id', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
