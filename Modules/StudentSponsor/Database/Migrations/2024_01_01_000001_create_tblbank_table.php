<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblbank', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->dateTime('created_on')->nullable()->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblbank');
    }
};
