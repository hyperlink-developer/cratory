<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_commander')->default(false)->after('password');
            $table->string('phone')->nullable()->after('is_commander');
            $table->string('avatar_path')->nullable()->after('phone');
            $table->foreignId('current_organization_id')->nullable()->after('avatar_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_commander', 'phone', 'avatar_path', 'current_organization_id']);
        });
    }
};
