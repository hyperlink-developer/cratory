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
            $table->string('watermark_type')->default('none')->after('footer_note');
            $table->string('watermark_text')->nullable()->after('watermark_type');
            $table->string('watermark_image_path')->nullable()->after('watermark_text');
            $table->boolean('show_logo')->default(true)->after('watermark_image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_templates', function (Blueprint $table) {
            $table->dropColumn(['watermark_type', 'watermark_text', 'watermark_image_path', 'show_logo']);
        });
    }
};
