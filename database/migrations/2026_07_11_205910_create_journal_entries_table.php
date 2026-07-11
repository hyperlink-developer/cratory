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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('organization_id')->index('journal_entries_organization_id_foreign');
            $table->date('date');
            $table->string('reference_number')->nullable();
            $table->text('description')->nullable();
            $table->string('journalable_type')->nullable();
            $table->unsignedBigInteger('journalable_id')->nullable();
            $table->timestamps();

            $table->index(['journalable_type', 'journalable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
