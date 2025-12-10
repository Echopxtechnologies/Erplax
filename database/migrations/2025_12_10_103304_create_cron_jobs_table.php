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
        Schema::create('cron_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('method'); // Format: ClassName/methodName
            $table->string('schedule')->default('daily'); // hourly, daily, weekly, monthly
            $table->text('description')->nullable();
            $table->timestamp('last_run')->nullable();
            $table->integer('last_duration')->nullable(); // in milliseconds
            $table->enum('last_status', ['success', 'failed', 'running'])->nullable();
            $table->text('last_message')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Cron execution logs
        Schema::create('cron_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cron_job_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['success', 'failed', 'running'])->default('running');
            $table->text('message')->nullable();
            $table->integer('execution_time')->nullable(); // in milliseconds
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['cron_job_id', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cron_logs');
        Schema::dropIfExists('cron_jobs');
    }
};