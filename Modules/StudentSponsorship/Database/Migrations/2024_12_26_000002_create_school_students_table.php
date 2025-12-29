<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_students', function (Blueprint $table) {
            $table->id();
            $table->string('school_internal_id')->unique()->comment('Internal tracking ID');
            $table->integer('school_student_id')->unique()->comment('School Student ID - links to report cards');
            
            // ========== STUDENT INFO TAB ==========
            // Basic Information
            $table->string('full_name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code', 20)->nullable();
            
            // School Information
            $table->string('grade')->nullable()->comment('Grade 1-14, O/L, A/L');
            $table->string('grade_mismatch_reason')->nullable()->comment('Required if age doesnt match grade');
            $table->string('current_state')->default('inprogress')->comment('inprogress or complete');
            $table->string('school_type')->nullable()->comment('Type 1AB, 1C, 2, 3');
            $table->foreignId('school_id')->nullable()->constrained('school_names')->nullOnDelete();
            
            // ========== SPONSORSHIP TAB ==========
            $table->date('sponsorship_start_date')->nullable();
            $table->date('sponsorship_end_date')->nullable();
            $table->string('introduced_by')->nullable();
            $table->string('introducer_phone')->nullable();
            
            // ========== BANK INFO TAB ==========
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_branch_number')->nullable();
            $table->text('bank_branch_info')->nullable();
            
            // ========== FAMILY INFO TAB ==========
            $table->string('father_name')->nullable();
            $table->decimal('father_income', 12, 2)->nullable();
            $table->string('mother_name')->nullable();
            $table->decimal('mother_income', 12, 2)->nullable();
            $table->string('guardian_name')->nullable();
            $table->decimal('guardian_income', 12, 2)->nullable();
            $table->text('background_info')->nullable();
            
            // ========== ADDITIONAL INFO TAB ==========
            $table->text('internal_comment')->nullable()->comment('Staff only');
            $table->text('external_comment')->nullable()->comment('Visible to students/sponsors');
            
            // Status
            $table->boolean('status')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('school_internal_id');
            $table->index('email');
            $table->index('status');
            $table->index('grade');
            $table->index('country_id');
            $table->index('bank_id');
            
            // Foreign keys to existing tables
            $table->foreign('country_id')->references('country_id')->on('countries')->nullOnDelete();
            $table->foreign('bank_id')->references('id')->on('banks')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_students');
    }
};
