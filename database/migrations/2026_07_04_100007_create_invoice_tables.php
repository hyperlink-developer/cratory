<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_templates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('is_default')->default(false);
            $table->string('color_primary', 7)->default('#8B5CF6');
            $table->string('color_secondary', 7)->default('#F59E0B');
            $table->string('logo_position')->default('left'); // left, center, right
            $table->json('show_fields')->nullable();
            $table->text('header_note')->nullable();
            $table->text('footer_note')->nullable();
            $table->string('font_choice')->default('Plus Jakarta Sans');
            $table->timestamps();

            $table->index('organization_id');
        });



        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_type'); // InvoiceType enum
            $table->string('invoice_number')->nullable();
            $table->foreignId('contact_id')->constrained()->cascadeOnDelete();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->foreignId('template_id')->nullable()->constrained('invoice_templates')->nullOnDelete();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount_total', 14, 2)->default(0);
            $table->decimal('tax_total', 14, 2)->default(0);
            $table->decimal('round_off', 14, 2)->default(0);
            $table->decimal('grand_total', 14, 2)->default(0);
            $table->decimal('amount_paid', 14, 2)->default(0);
            $table->decimal('balance_due', 14, 2)->default(0);
            $table->string('status')->default('draft'); // InvoiceStatus enum
            $table->text('notes')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->string('place_of_supply')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organization_id', 'invoice_number']);
            $table->index(['organization_id', 'invoice_type']);
            $table->index(['organization_id', 'status']);
            $table->index(['organization_id', 'contact_id']);
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->decimal('quantity', 14, 2)->default(1);
            $table->string('unit')->nullable();
            $table->decimal('rate', 14, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->foreignId('tax_rate_id')->nullable()->constrained('tax_rates')->nullOnDelete();
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('line_total', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_templates');
    }
};
