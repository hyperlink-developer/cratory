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
        Schema::create('invoice_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 36)->unique();
            $table->unsignedBigInteger('organization_id')->index();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('is_default')->default(false);
            $table->string('color_primary', 7)->default('#8B5CF6');
            $table->string('color_secondary', 7)->default('#F59E0B');
            $table->string('logo_position')->default('left');
            $table->json('show_fields')->nullable();
            $table->text('header_note')->nullable();
            $table->text('footer_note')->nullable();
            $table->text('default_payment_info')->nullable();
            $table->text('default_terms_and_conditions')->nullable();
            $table->string('font_choice')->default('Plus Jakarta Sans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_templates');
    }
};
