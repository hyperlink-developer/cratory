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
        Schema::create('organizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 36)->unique();
            $table->string('name');
            $table->string('type');
            $table->string('business_category');
            $table->string('pan_number')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('country')->default('India');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('invoice_prefix', 10)->default('CRT');
            $table->json('document_settings')->nullable();
            $table->unsignedTinyInteger('financial_year_start_month')->default(4);
            $table->string('currency', 10)->default('INR');
            $table->unsignedBigInteger('created_by')->index('organizations_created_by_foreign');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_composition_tax_payer')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
