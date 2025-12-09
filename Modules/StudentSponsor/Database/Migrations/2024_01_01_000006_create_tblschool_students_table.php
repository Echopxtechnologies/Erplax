<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblschool_students', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('entity_type', ['school'])->default('school');
            $table->string('name', 255)->nullable();
            $table->binary('profile_photo')->nullable();
            $table->string('contact_no', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('zip', 20)->nullable();
            $table->integer('country_id')->nullable();
            $table->string('school_internal_id', 100)->nullable();
            $table->string('school_id', 100)->nullable();
            $table->string('school_type', 20)->nullable();
            $table->integer('school_name_id')->nullable();
            $table->integer('school_grade_year')->nullable();
            $table->string('school_grade', 20)->nullable();
            $table->text('grade_mismatch_reason')->nullable();
            $table->date('school_student_dob')->nullable();
            $table->integer('school_age')->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('school_bank_branch_number', 100)->nullable();
            $table->string('school_bank_branch_info', 255)->nullable();
            $table->string('school_bank_account_no', 100)->nullable();
            $table->date('school_sponsorship_start_date')->nullable();
            $table->date('school_sponsorship_end_date')->nullable();
            $table->string('school_introducedby', 255)->nullable();
            $table->string('school_introducedph', 50)->nullable();
            $table->string('school_father_name', 255)->nullable();
            $table->string('school_mother_name', 255)->nullable();
            $table->float('school_father_income')->nullable();
            $table->float('school_mother_income')->nullable();
            $table->string('school_guardian_name', 255)->nullable();
            $table->float('school_guardian_income')->nullable();
            $table->integer('sponsor_id')->nullable();
            $table->text('background_info')->nullable();
            $table->text('internal_comment')->nullable();
            $table->text('external_comment')->nullable();
            $table->dateTime('created_on')->nullable()->useCurrent();
            
            $table->unique('email', 'email');
            $table->unique('school_internal_id', 'school_internal_id');
            $table->unique('school_bank_account_no', 'school_bank_account_no');
            $table->index('school_name_id', 'school_name_id');
            $table->index('bank_id', 'bank_id');
            $table->index('sponsor_id', 'sponsor_id');
            $table->index('country_id', 'tblschool_students_ibfk_3');
        });

        // Change profile_photo to LONGBLOB
        DB::statement('ALTER TABLE tblschool_students MODIFY profile_photo LONGBLOB NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('tblschool_students');
    }
};
