<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblsponsor_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sponsor_id');
            $table->integer('school_student_id')->nullable();
            $table->integer('university_student_id')->nullable();
            $table->decimal('total_amount', 12, 2)->nullable()->default(0.00)->comment('Total sponsorship agreed');
            $table->decimal('amount_paid', 12, 2)->nullable()->default(0.00)->comment('Sum of payments made');
            $table->decimal('balance_amount', 12, 2)->nullable()->storedAs('total_amount - amount_paid');
            $table->string('currency', 10)->nullable()->default('INR');
            $table->date('last_payment_date')->nullable();
            $table->date('next_payment_due')->nullable();
            $table->enum('payment_type', ['one_time','monthly','quarterly','yearly','custom'])->nullable()->default('one_time');
            $table->tinyInteger('due_reminder_active')->nullable()->default(0);
            $table->smallInteger('due_reminder_days_before')->nullable()->default(15);
            $table->date('scheduled_due_reminder_date')->nullable();
            $table->tinyInteger('due_reminder_sent')->nullable()->default(0);
            $table->date('sponsorship_start')->nullable();
            $table->date('sponsorship_end')->nullable();
            $table->tinyInteger('renewal_reminder_active')->nullable()->default(0);
            $table->smallInteger('renewal_reminder_days_before')->nullable()->default(15);
            $table->date('scheduled_renewal_reminder')->nullable();
            $table->tinyInteger('renewal_reminder_sent')->nullable()->default(0);
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            $table->dateTime('last_xday_reminder_sent_at')->nullable();
            $table->tinyInteger('due_day_email_sent')->default(0);
            $table->dateTime('last_due_day_email_sent_at')->nullable();
            $table->dateTime('due_reminder_sent_at')->nullable();
            $table->dateTime('due_day_email_sent_at')->nullable();
            
            $table->index('sponsor_id', 'sponsor_id');
            $table->index('school_student_id', 'school_student_id');
            $table->index('university_student_id', 'university_student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblsponsor_transactions');
    }
};
