<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblsponsor_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id');
            $table->integer('sponsor_id');
            $table->integer('student_id')->nullable();
            $table->date('payment_date');
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->string('currency', 10)->nullable()->default('INR');
            $table->text('note')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable();
            
            $table->index('transaction_id', 'transaction_id');
            $table->index('sponsor_id', 'sponsor_id');
            $table->index('student_id', 'student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblsponsor_payments');
    }
};
