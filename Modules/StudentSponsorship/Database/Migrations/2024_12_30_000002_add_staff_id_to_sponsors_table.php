<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds staff_id to sponsors table for portal access via staff portal
     */
    public function up(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            // Link to staff record for portal access (staff uses admin guard)
            $table->unsignedBigInteger('staff_id')->nullable()->after('user_id');
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('set null');
            
            // Index for quick lookups
            $table->index('staff_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropIndex(['staff_id']);
            $table->dropColumn('staff_id');
        });
    }
};
