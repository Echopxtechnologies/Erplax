<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbluniversity_program', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('university_id')->nullable();
            $table->string('name', 255);
            $table->dateTime('created_on')->nullable()->useCurrent();
            
            $table->index('university_id', 'idx_university_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbluniversity_program');
    }
};
