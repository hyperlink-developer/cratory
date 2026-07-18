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
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            
            // Polymorphic for recipient (Contact/User)
            $table->nullableMorphs('notifiable');
            
            // Polymorphic for document (Invoice/Receipt)
            $table->nullableMorphs('document');

            $table->string('notification_id')->nullable()->index(); // ID of the notification instance to track events
            $table->string('channel'); // email, whatsapp
            $table->string('status')->default('queued'); // queued, sent, failed
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
