<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('document_type');
            $table->string('financial_year'); // e.g., '2023-24'
            $table->integer('last_number')->default(0);
            $table->timestamps();

            // Provide a shorter custom name for the unique constraint to prevent MySQL 64-char limits
            $table->unique(['organization_id', 'document_type', 'financial_year'], 'org_doc_fy_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_sequences');
    }
};
