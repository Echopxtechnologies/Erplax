<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletedYearToStudents extends Migration
{
    public function up(): void
    {
        // Add completed_year to school_students
        Schema::table('school_students', function (Blueprint $table) {
            $table->year('completed_year')->nullable()->after('current_state')->comment('Academic year when student was marked complete');
        });
        
        // Add completed_year to university_students
        Schema::table('university_students', function (Blueprint $table) {
            $table->year('completed_year')->nullable()->after('current_state')->comment('Academic year when student was marked complete');
        });
    }

    public function down(): void
    {
        Schema::table('school_students', function (Blueprint $table) {
            $table->dropColumn('completed_year');
        });
        
        Schema::table('university_students', function (Blueprint $table) {
            $table->dropColumn('completed_year');
        });
    }
}
