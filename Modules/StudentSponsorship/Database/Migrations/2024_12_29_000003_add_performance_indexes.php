<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Add indexes for better query performance
     */
    public function up(): void
    {
        // School Students indexes
        Schema::table('school_students', function (Blueprint $table) {
            $table->index('current_state', 'idx_school_current_state');
            $table->index(['current_state', 'status'], 'idx_school_state_status');
            $table->index('created_at', 'idx_school_created_at');
        });

        // University Students indexes
        Schema::table('university_students', function (Blueprint $table) {
            $table->index('current_state', 'idx_uni_current_state');
            $table->index('active', 'idx_uni_active');
            $table->index('university_name_id', 'idx_uni_name_id');
            $table->index('university_program_id', 'idx_uni_program_id');
            $table->index('university_year_of_study', 'idx_uni_year');
            $table->index(['current_state', 'active'], 'idx_uni_state_active');
            $table->index('created_at', 'idx_uni_created_at');
        });

        // School Report Cards index
        if (Schema::hasTable('school_report_cards') && Schema::hasColumn('school_report_cards', 'student_school_id')) {
            Schema::table('school_report_cards', function (Blueprint $table) {
                $table->index('student_school_id', 'idx_report_school_student');
            });
        }

        // University Report Cards index
        if (Schema::hasTable('university_report_cards') && Schema::hasColumn('university_report_cards', 'university_student_id')) {
            Schema::table('university_report_cards', function (Blueprint $table) {
                $table->index('university_student_id', 'idx_report_uni_student');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_students', function (Blueprint $table) {
            $table->dropIndex('idx_school_current_state');
            $table->dropIndex('idx_school_state_status');
            $table->dropIndex('idx_school_created_at');
        });

        Schema::table('university_students', function (Blueprint $table) {
            $table->dropIndex('idx_uni_current_state');
            $table->dropIndex('idx_uni_active');
            $table->dropIndex('idx_uni_name_id');
            $table->dropIndex('idx_uni_program_id');
            $table->dropIndex('idx_uni_year');
            $table->dropIndex('idx_uni_state_active');
            $table->dropIndex('idx_uni_created_at');
        });

        if (Schema::hasTable('school_report_cards')) {
            Schema::table('school_report_cards', function (Blueprint $table) {
                $table->dropIndex('idx_report_school_student');
            });
        }

        if (Schema::hasTable('university_report_cards')) {
            Schema::table('university_report_cards', function (Blueprint $table) {
                $table->dropIndex('idx_report_uni_student');
            });
        }
    }
};
