<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('university_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('code', 50)->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index('name');
            $table->index('code');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_programs');
    }
};
