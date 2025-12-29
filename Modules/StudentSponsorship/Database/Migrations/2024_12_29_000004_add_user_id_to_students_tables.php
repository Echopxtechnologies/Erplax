<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds user_id to school_students and university_students for portal access
     */
    public function up(): void
    {
        // Add user_id to school_students
        Schema::table('school_students', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id')->comment('Links to users table for portal access');
            $table->index('user_id', 'idx_school_user_id');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });

        // Add user_id to university_students
        Schema::table('university_students', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id')->comment('Links to users table for portal access');
            $table->index('user_id', 'idx_uni_user_id');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_students', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex('idx_school_user_id');
            $table->dropColumn('user_id');
        });

        Schema::table('university_students', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex('idx_uni_user_id');
            $table->dropColumn('user_id');
        });
    }
};
