<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Fixes the service_notifications.type column to accept longer values
     * like 'service_completed', 'invoice_created', 'reminder_sent'
     */
    public function up(): void
    {
        // Fix service_notifications.type column
        if (Schema::hasTable('service_notifications')) {
            Schema::table('service_notifications', function (Blueprint $table) {
                $table->string('type', 50)->change();
            });
            
            \Log::info('Fixed service_notifications.type column to VARCHAR(50)');
        }
        
        // Also ensure service_records has all required columns for invoice creation
        if (Schema::hasTable('service_records')) {
            if (!Schema::hasColumn('service_records', 'service_reference')) {
                Schema::table('service_records', function (Blueprint $table) {
                    $table->string('service_reference', 20)->nullable()->unique()->after('service_charge');
                });
                \Log::info('Added service_reference column to service_records');
            }
            
            if (!Schema::hasColumn('service_records', 'invoice_id')) {
                Schema::table('service_records', function (Blueprint $table) {
                    $table->unsignedBigInteger('invoice_id')->nullable()->after('service_reference');
                });
                \Log::info('Added invoice_id column to service_records');
            }
        }
        
        // Ensure service_record_materials has tax columns
        if (Schema::hasTable('service_record_materials')) {
            if (!Schema::hasColumn('service_record_materials', 'tax_ids')) {
                Schema::table('service_record_materials', function (Blueprint $table) {
                    $table->json('tax_ids')->nullable()->after('total');
                });
                \Log::info('Added tax_ids column to service_record_materials');
            }
            
            if (!Schema::hasColumn('service_record_materials', 'tax_amount')) {
                Schema::table('service_record_materials', function (Blueprint $table) {
                    $table->decimal('tax_amount', 15, 2)->default(0)->after('tax_ids');
                });
                \Log::info('Added tax_amount column to service_record_materials');
            }
        }
        
        // Ensure invoices has service tracking columns
        if (Schema::hasTable('invoices')) {
            if (!Schema::hasColumn('invoices', 'service_reference')) {
                Schema::table('invoices', function (Blueprint $table) {
                    $table->string('service_reference', 255)->nullable();
                });
                \Log::info('Added service_reference column to invoices');
            }
            
            if (!Schema::hasColumn('invoices', 'service_id')) {
                Schema::table('invoices', function (Blueprint $table) {
                    $table->unsignedBigInteger('service_id')->nullable();
                });
                \Log::info('Added service_id column to invoices');
            }
            
            if (!Schema::hasColumn('invoices', 'service_record_id')) {
                Schema::table('invoices', function (Blueprint $table) {
                    $table->unsignedBigInteger('service_record_id')->nullable();
                });
                \Log::info('Added service_record_id column to invoices');
            }
        }
        
        \Log::info('Service module migration completed successfully');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert service_notifications.type column
        if (Schema::hasTable('service_notifications')) {
            Schema::table('service_notifications', function (Blueprint $table) {
                $table->string('type', 20)->change();
            });
        }
        
        // Note: We don't remove the other columns as they may contain data
    }
};
