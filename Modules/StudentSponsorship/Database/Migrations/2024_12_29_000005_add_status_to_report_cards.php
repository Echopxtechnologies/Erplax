<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add status to school_report_cards
        Schema::table('school_report_cards', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('upload_date');
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
        });

        // Add status to university_report_cards
        Schema::table('university_report_cards', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('upload_date');
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('school_report_cards', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_at', 'approved_by']);
        });

        Schema::table('university_report_cards', function (Blueprint $table) {
            $table->dropColumn(['status', 'approved_at', 'approved_by']);
        });
    }
};
