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
        Schema::table('invoice_templates', function (Blueprint $table) {
            $table->string('signature_type')->default('none')->after('show_logo');
            $table->string('signature_text')->nullable()->after('signature_type');
            $table->string('signature_image_path')->nullable()->after('signature_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_templates', function (Blueprint $table) {
            $table->dropColumn(['signature_type', 'signature_text', 'signature_image_path']);
        });
    }
};
