<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_role', function (Blueprint $table) {
            $table->unsignedBigInteger('menu_id')->after('id');
            $table->unsignedBigInteger('role_id')->after('menu_id');
            
            $table->foreign('menu_id')
                ->references('id')
                ->on('menus')
                ->onDelete('cascade');
            
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->unique(['menu_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::table('menu_role', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);
            $table->dropForeign(['role_id']);
            $table->dropColumn(['menu_id', 'role_id']);
        });
    }
};