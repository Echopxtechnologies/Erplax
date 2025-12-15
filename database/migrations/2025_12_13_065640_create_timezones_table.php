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
        Schema::create('timezones', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);                    // Asia/Kolkata
            $table->string('label', 150);                   // (UTC+05:30) Chennai, Kolkata, Mumbai
            $table->string('offset', 10);                   // +05:30
            $table->integer('offset_minutes');              // 330
            $table->string('country_code', 2)->nullable();  // IN
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timezones');
    }
};