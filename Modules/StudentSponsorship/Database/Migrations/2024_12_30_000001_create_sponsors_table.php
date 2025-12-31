<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsors', function (Blueprint $table) {
            $table->id();
            
            // Basic Info
            $table->string('sponsor_internal_id', 50)->nullable()->unique();
            $table->string('name', 255);
            $table->enum('sponsor_type', ['individual', 'company'])->default('individual');
            $table->string('sponsor_occupation', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('contact_no', 50)->nullable();
            $table->string('city', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('zip', 20)->nullable();
            
            // Banking
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('sponsor_bank_branch_info', 255)->nullable();
            $table->string('sponsor_bank_branch_number', 50)->nullable();
            $table->string('sponsor_bank_account_no', 50)->nullable();
            
            // Sponsorship
            $table->date('membership_start_date')->nullable();
            $table->date('membership_end_date')->nullable();
            $table->enum('sponsor_frequency', ['one_time', 'monthly', 'quarterly', 'half_yearly', 'yearly'])->nullable();
            
            // Comments
            $table->text('background_info')->nullable();
            $table->text('internal_comment')->nullable();
            $table->text('external_comment')->nullable();
            
            // Status
            $table->boolean('active')->default(1);
            
            // Staff Access (for later)
            $table->unsignedBigInteger('user_id')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('name');
            $table->index('email');
            $table->index('sponsor_type');
            $table->index('active');
            $table->index('country_id');
            $table->index('bank_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsors');
    }
};
