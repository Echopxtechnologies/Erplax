<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pos_sales', 'invoice_id')) {
            Schema::table('pos_sales', function (Blueprint $table) {
                $table->unsignedBigInteger('invoice_id')->nullable()->after('change_amount');
            });
        }
    }

    public function down(): void
    {
        Schema::table('pos_sales', function (Blueprint $table) {
            $table->dropColumn('invoice_id');
        });
    }
};
