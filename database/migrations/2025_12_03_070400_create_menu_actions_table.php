<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->string('action_name', 100);
            $table->string('action_slug', 100);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('menu_id')
                ->references('id')
                ->on('menus')
                ->onDelete('cascade');

            $table->unique(['menu_id', 'action_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_actions');
    }
};