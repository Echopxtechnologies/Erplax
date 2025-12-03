<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('menu_name', 150);
            $table->string('icon', 100)->nullable();
            $table->string('route', 191)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('permission_name', 100)->nullable();
            $table->string('menu_visibility', 100)->default('Admin');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('menus')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menus');
    }
};
