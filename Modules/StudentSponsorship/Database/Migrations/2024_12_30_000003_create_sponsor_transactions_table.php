<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sponsor Transactions Table
     * Based on Perfex CRM transaction model
     */
    public function up(): void
    {
        Schema::create('sponsor_transactions', function (Blueprint $table) {
            $table->id();
            
            // Transaction Info
            $table->string('transaction_number', 50)->unique();
            
            // Relationships
            $table->unsignedBigInteger('sponsor_id');
            $table->unsignedBigInteger('school_student_id')->nullable();
            $table->unsignedBigInteger('university_student_id')->nullable();
            
            // Amount
            $table->decimal('total_amount', 15, 2);
            $table->decimal('amount_paid', 15, 2)->default(0); // Auto-updated from payments
            $table->string('currency', 3)->default('LKR'); // LKR, USD, CAD, GBP, AUD
            
            // Payment Type
            $table->enum('payment_type', ['one_time', 'monthly', 'quarterly', 'yearly', 'custom'])->default('one_time');
            
            // Status
            $table->enum('status', ['pending', 'partial', 'completed', 'cancelled'])->default('pending');
            
            // Payment Tracking Dates
            $table->date('last_payment_date')->nullable();
            $table->date('next_payment_due')->nullable();
            
            // Due Reminder Settings
            $table->boolean('due_reminder_active')->default(false);
            $table->integer('days_before_due')->nullable()->default(7);
            $table->boolean('x_days_email_sent')->default(false);
            $table->boolean('due_day_email_sent')->default(false);
            
            // Notes
            $table->text('description')->nullable();
            $table->text('internal_note')->nullable();
            
            // Admin
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Keys
            $table->foreign('sponsor_id')->references('id')->on('sponsors')->onDelete('cascade');
            $table->foreign('school_student_id')->references('id')->on('school_students')->onDelete('set null');
            $table->foreign('university_student_id')->references('id')->on('university_students')->onDelete('set null');
            
            // Indexes
            $table->index('status');
            $table->index('sponsor_id');
            $table->index('next_payment_due');
        });

        // Payments table - individual payment records
        Schema::create('sponsor_payments', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('transaction_id');
            
            // Payment Details
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->string('payment_method', 50)->nullable(); // cash, bank_transfer, cheque, upi, online, card
            $table->string('reference_number', 100)->nullable(); // UTR, Cheque no, etc
            
            // Receipt
            $table->string('receipt_number', 50)->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            
            // Admin
            $table->unsignedBigInteger('created_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Key
            $table->foreign('transaction_id')->references('id')->on('sponsor_transactions')->onDelete('cascade');
            
            // Index
            $table->index('transaction_id');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsor_payments');
        Schema::dropIfExists('sponsor_transactions');
    }
};
