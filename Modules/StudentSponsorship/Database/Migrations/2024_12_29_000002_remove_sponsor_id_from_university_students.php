<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if column exists before trying to drop
        if (Schema::hasColumn('university_students', 'sponsor_id')) {
            // Try to drop index first (may not exist)
            try {
                $indexExists = DB::select("SHOW INDEX FROM university_students WHERE Key_name = 'us_sponsor_idx'");
                if (!empty($indexExists)) {
                    Schema::table('university_students', function (Blueprint $table) {
                        $table->dropIndex('us_sponsor_idx');
                    });
                }
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
            
            // Drop the column
            Schema::table('university_students', function (Blueprint $table) {
                $table->dropColumn('sponsor_id');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('university_students', 'sponsor_id')) {
            Schema::table('university_students', function (Blueprint $table) {
                $table->unsignedBigInteger('sponsor_id')->nullable()->after('university_sponsorship_end_date');
                $table->index('sponsor_id', 'us_sponsor_idx');
            });
        }
    }
};
