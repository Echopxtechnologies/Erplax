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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->char('code', 3)->unique();              // ISO 4217 code (USD, INR, EUR)
            $table->string('symbol', 10);                   // $, ₹, €
            $table->string('symbol_native', 10)->nullable(); // Native symbol
            $table->tinyInteger('decimal_digits')->default(2);
            $table->string('decimal_separator', 1)->default('.');
            $table->string('thousand_separator', 1)->default(',');
            $table->enum('symbol_position', ['before', 'after'])->default('before');
            $table->boolean('space_between')->default(false);
            $table->decimal('exchange_rate', 15, 6)->default(1.000000);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};