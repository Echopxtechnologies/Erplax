<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('alias')->unique();
            $table->string('description')->nullable();
            $table->string('version')->default('1.0.0');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_installed')->default(false);
            $table->boolean('is_core')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamp('installed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};