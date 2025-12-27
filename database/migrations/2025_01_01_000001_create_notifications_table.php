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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            
            // Recipient (who receives the notification)
            $table->unsignedBigInteger('user_id');
            $table->enum('user_type', ['admin', 'user'])->default('user');
            
            // Sender (who triggered the notification) - nullable for system notifications
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->enum('from_user_type', ['admin', 'user'])->nullable();
            
            // Notification content
            $table->string('title', 255);
            $table->text('message')->nullable();
            $table->enum('type', ['info', 'success', 'warning', 'error'])->default('info');
            $table->string('url', 500)->nullable();
            
            // Status
            $table->boolean('is_read')->default(false);
            
            // Timestamp
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes for fast queries
            $table->index(['user_id', 'user_type'], 'notif_recipient_idx');
            $table->index(['user_id', 'user_type', 'is_read'], 'notif_recipient_read_idx');
            $table->index(['user_id', 'user_type', 'created_at'], 'notif_recipient_date_idx');
            $table->index('created_at', 'notif_created_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};