<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Main services/contracts table
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('customers')->onDelete('cascade');
            $table->string('machine_name');
            $table->string('equipment_no')->nullable();
            $table->string('model_no')->nullable();
            $table->string('serial_number')->nullable();
            $table->enum('service_frequency', ['monthly', 'quarterly', 'half_yearly', 'yearly', 'custom'])->default('monthly');
            $table->integer('custom_days')->nullable(); // For custom frequency
            $table->date('first_service_date');
            $table->date('last_service_date')->nullable();
            $table->date('next_service_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('service_status', ['draft', 'pending', 'completed', 'overdue', 'canceled'])->default('draft');
            $table->text('notes')->nullable();
            $table->integer('reminder_days')->default(15); // Days before to send reminder
            $table->timestamp('last_reminder_sent')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['client_id', 'status']);
            $table->index('service_status');
            $table->index('next_service_date');
        });

        // Service history/records table
        Schema::create('service_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->string('reference_no')->unique(); // Auto-generated reference
            $table->foreignId('engineer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('service_type')->default('Maintenance'); // Maintenance, Repair, Installation, etc.
            $table->date('service_date');
            $table->time('service_time')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'canceled'])->default('scheduled');
            $table->text('remarks')->nullable();
            $table->text('work_done')->nullable();
            $table->decimal('labor_cost', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->boolean('dates_updated')->default(false); // Track if contract dates were updated
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['service_id', 'status']);
            $table->index('service_date');
            $table->index('engineer_id');
        });

        // Materials used in service records
        Schema::create('service_record_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_record_id')->constrained('service_records')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('material_name'); // Store name in case product is deleted
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('service_record_id');
        });

        // Engineer visits/schedule
        Schema::create('service_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('engineer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('visit_date');
            $table->time('visit_time')->nullable();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'canceled', 'rescheduled'])->default('scheduled');
            $table->text('purpose')->nullable();
            $table->text('notes')->nullable();
            $table->string('client_signature')->nullable(); // Path to signature image
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['service_id', 'visit_date']);
            $table->index('engineer_id');
        });

        // Email notification logs
        Schema::create('service_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->enum('type', ['reminder', 'overdue', 'completed', 'scheduled'])->default('reminder');
            $table->string('email_to');
            $table->string('subject');
            $table->text('message')->nullable();
            $table->enum('status', ['sent', 'failed', 'pending'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->index(['service_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_notifications');
        Schema::dropIfExists('service_visits');
        Schema::dropIfExists('service_record_materials');
        Schema::dropIfExists('service_records');
        Schema::dropIfExists('services');
    }
};
