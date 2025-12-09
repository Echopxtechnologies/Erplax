<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // add columns only if they don't already exist
            if (!Schema::hasColumn('customers', 'name')) {
                $table->string('name')->after('id');
            }

            if (!Schema::hasColumn('customers', 'email')) {
                $table->string('email')->nullable()->after('name');
            }

            if (!Schema::hasColumn('customers', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }

            if (!Schema::hasColumn('customers', 'company')) {
                $table->string('company')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('customers', 'active')) {
                $table->boolean('active')->default(true)->after('company');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // rollback (optional)
            if (Schema::hasColumn('customers', 'active')) {
                $table->dropColumn('active');
            }
            if (Schema::hasColumn('customers', 'company')) {
                $table->dropColumn('company');
            }
            if (Schema::hasColumn('customers', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('customers', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('customers', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
    