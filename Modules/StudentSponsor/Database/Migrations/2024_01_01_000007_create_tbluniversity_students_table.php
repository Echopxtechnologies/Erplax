<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbluniversity_students', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('entity_type', ['university'])->default('university');
            $table->string('name', 255)->nullable();
            $table->binary('profile_photo')->nullable();
            $table->string('contact_no', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('zip', 20)->nullable();
            $table->integer('country_id')->nullable();
            $table->string('university_internal_id', 100)->nullable();
            $table->string('university_id', 100)->nullable();
            $table->integer('university_name_id')->nullable();
            $table->integer('university_program_id')->nullable();
            $table->enum('university_year_of_study', ['1Y1S','1Y2S','2Y1S','2Y2S','3Y1S','3Y2S','4Y1S','4Y2S','5Y1S','5Y2S'])->nullable();
            $table->date('university_student_dob')->nullable();
            $table->integer('university_age')->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('university_bank_branch_info', 255)->nullable();
            $table->string('university_bank_branch_number', 100)->nullable();
            $table->string('university_bank_account_no', 100)->nullable();
            $table->date('university_sponsorship_start_date')->nullable();
            $table->date('university_sponsorship_end_date')->nullable();
            $table->string('university_introducedby', 255)->nullable();
            $table->string('university_introducedph', 50)->nullable();
            $table->string('university_father_name', 255)->nullable();
            $table->string('university_mother_name', 255)->nullable();
            $table->float('university_father_income')->nullable();
            $table->float('university_mother_income')->nullable();
            $table->string('university_guardian_name', 255)->nullable();
            $table->float('university_guardian_income')->nullable();
            $table->integer('sponsor_id')->nullable();
            $table->text('background_info')->nullable();
            $table->text('internal_comment')->nullable();
            $table->text('external_comment')->nullable();
            $table->dateTime('created_on')->nullable()->useCurrent();
            
            $table->unique('email', 'email');
            $table->unique('university_internal_id', 'university_internal_id');
            $table->unique('university_bank_account_no', 'university_bank_account_no');
            $table->index('university_name_id', 'university_name_id');
            $table->index('university_program_id', 'university_program_id');
            $table->index('bank_id', 'bank_id');
            $table->index('sponsor_id', 'sponsor_id');
            $table->index('country_id', 'fk_university_students_country');
        });

        // Change profile_photo to LONGBLOB
        DB::statement('ALTER TABLE tbluniversity_students MODIFY profile_photo LONGBLOB NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('tbluniversity_students');
    }
};
