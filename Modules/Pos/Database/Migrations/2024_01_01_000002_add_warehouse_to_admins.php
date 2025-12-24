<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('admins', 'warehouse_id')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->unsignedBigInteger('warehouse_id')->nullable()->after('is_admin');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('admins', 'warehouse_id')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->dropColumn('warehouse_id');
            });
        }
    }
};
