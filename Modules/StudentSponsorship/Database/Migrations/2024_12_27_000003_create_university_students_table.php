<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('university_students', function (Blueprint $table) {
            $table->id();
            
            // Basic Information
            $table->string('name', 255);
            $table->string('email', 255)->nullable();
            $table->string('contact_no', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('zip', 20)->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            
            // University Information
            $table->string('university_internal_id', 100)->unique()->nullable();
            $table->string('university_id', 100)->nullable()->comment('Student registration number');
            $table->unsignedBigInteger('university_name_id')->nullable();
            $table->unsignedBigInteger('university_program_id')->nullable();
            $table->enum('university_year_of_study', [
                '1Y1S', '1Y2S', '2Y1S', '2Y2S', '3Y1S', '3Y2S', '4Y1S', '4Y2S', '5Y1S', '5Y2S'
            ])->nullable();
            $table->date('university_student_dob')->nullable();
            $table->unsignedTinyInteger('university_age')->nullable();
            
            // Bank Information
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('university_bank_branch_info', 255)->nullable();
            $table->string('university_bank_branch_number', 100)->nullable();
            $table->string('university_bank_account_no', 100)->nullable();
            
            // Sponsorship
            $table->date('university_sponsorship_start_date')->nullable();
            $table->date('university_sponsorship_end_date')->nullable();
            
            // Introduction
            $table->string('university_introducedby', 255)->nullable();
            $table->string('university_introducedph', 50)->nullable();
            
            // Family Information
            $table->string('university_father_name', 255)->nullable();
            $table->string('university_mother_name', 255)->nullable();
            $table->decimal('university_father_income', 12, 2)->nullable();
            $table->decimal('university_mother_income', 12, 2)->nullable();
            $table->string('university_guardian_name', 255)->nullable();
            $table->decimal('university_guardian_income', 12, 2)->nullable();
            
            // Comments
            $table->text('background_info')->nullable();
            $table->text('internal_comment')->nullable();
            $table->text('external_comment')->nullable();
            
            // Status
            $table->boolean('active')->default(true);
            
            // Staff Access
            $table->unsignedBigInteger('staff_id')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes (short names for MySQL 64-char limit)
            $table->index('name', 'us_name_idx');
            $table->index('email', 'us_email_idx');
            $table->index('university_internal_id', 'us_internal_id_idx');
            $table->index('university_name_id', 'us_uni_name_idx');
            $table->index('university_program_id', 'us_program_idx');
            $table->index('active', 'us_active_idx');
            $table->index('country_id', 'us_country_idx');
            $table->index('bank_id', 'us_bank_idx');
            
            // Foreign Keys (short names for MySQL limit)
            $table->foreign('university_name_id', 'us_uni_name_fk')
                  ->references('id')->on('university_names')
                  ->onDelete('set null');
                  
            $table->foreign('university_program_id', 'us_program_fk')
                  ->references('id')->on('university_programs')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_students');
    }
};
