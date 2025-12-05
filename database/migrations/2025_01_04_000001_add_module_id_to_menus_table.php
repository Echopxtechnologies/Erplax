<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            // Add module_id to link menus to modules
            $table->unsignedBigInteger('module_id')->nullable()->after('id');
            
            // Add slug for permission naming
            $table->string('slug', 100)->nullable()->after('menu_name');
            
            // Add foreign key
            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign(['module_id']);
            $table->dropColumn(['module_id', 'slug']);
        });
    }
};