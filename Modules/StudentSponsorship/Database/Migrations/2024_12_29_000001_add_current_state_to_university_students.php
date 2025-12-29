<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('university_students', function (Blueprint $table) {
            if (!Schema::hasColumn('university_students', 'current_state')) {
                $table->string('current_state', 20)->default('inprogress')->after('active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('university_students', function (Blueprint $table) {
            if (Schema::hasColumn('university_students', 'current_state')) {
                $table->dropColumn('current_state');
            }
        });
    }
};
