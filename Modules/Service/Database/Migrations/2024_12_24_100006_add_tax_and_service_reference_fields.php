<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add tax_ids to service_record_materials
        Schema::table('service_record_materials', function (Blueprint $table) {
            if (!Schema::hasColumn('service_record_materials', 'tax_ids')) {
                $table->json('tax_ids')->nullable()->after('total');
            }
            if (!Schema::hasColumn('service_record_materials', 'tax_amount')) {
                $table->decimal('tax_amount', 15, 2)->default(0)->after('total');
            }
        });

        // Add service_reference to invoices for tracking (only if columns don't exist)
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'service_reference')) {
                $table->string('service_reference')->nullable();
            }
            if (!Schema::hasColumn('invoices', 'service_id')) {
                $table->unsignedBigInteger('service_id')->nullable();
            }
            if (!Schema::hasColumn('invoices', 'service_record_id')) {
                $table->unsignedBigInteger('service_record_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_record_materials', function (Blueprint $table) {
            if (Schema::hasColumn('service_record_materials', 'tax_ids')) {
                $table->dropColumn('tax_ids');
            }
            if (Schema::hasColumn('service_record_materials', 'tax_amount')) {
                $table->dropColumn('tax_amount');
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'service_reference')) {
                $table->dropColumn('service_reference');
            }
            if (Schema::hasColumn('invoices', 'service_id')) {
                $table->dropColumn('service_id');
            }
            if (Schema::hasColumn('invoices', 'service_record_id')) {
                $table->dropColumn('service_record_id');
            }
        });
    }
};