<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add tax_1 and tax_2 columns to purchase_order_items
        if (Schema::hasTable('purchase_order_items')) {
            Schema::table('purchase_order_items', function (Blueprint $table) {
                if (!Schema::hasColumn('purchase_order_items', 'tax_1_id')) {
                    $table->unsignedBigInteger('tax_1_id')->nullable()->after('tax_amount');
                    $table->string('tax_1_name', 100)->nullable()->after('tax_1_id');
                    $table->decimal('tax_1_rate', 8, 2)->default(0)->after('tax_1_name');
                    $table->decimal('tax_1_amount', 15, 2)->default(0)->after('tax_1_rate');
                    $table->unsignedBigInteger('tax_2_id')->nullable()->after('tax_1_amount');
                    $table->string('tax_2_name', 100)->nullable()->after('tax_2_id');
                    $table->decimal('tax_2_rate', 8, 2)->default(0)->after('tax_2_name');
                    $table->decimal('tax_2_amount', 15, 2)->default(0)->after('tax_2_rate');
                }
            });
        }

        // Add tax_1 and tax_2 columns to purchase_bill_items
        if (Schema::hasTable('purchase_bill_items')) {
            Schema::table('purchase_bill_items', function (Blueprint $table) {
                if (!Schema::hasColumn('purchase_bill_items', 'tax_1_id')) {
                    $table->unsignedBigInteger('tax_1_id')->nullable()->after('tax_amount');
                    $table->string('tax_1_name', 100)->nullable()->after('tax_1_id');
                    $table->decimal('tax_1_rate', 8, 2)->default(0)->after('tax_1_name');
                    $table->decimal('tax_1_amount', 15, 2)->default(0)->after('tax_1_rate');
                    $table->unsignedBigInteger('tax_2_id')->nullable()->after('tax_1_amount');
                    $table->string('tax_2_name', 100)->nullable()->after('tax_2_id');
                    $table->decimal('tax_2_rate', 8, 2)->default(0)->after('tax_2_name');
                    $table->decimal('tax_2_amount', 15, 2)->default(0)->after('tax_2_rate');
                }
            });
        }
        
        // Add tax_1 and tax_2 columns to goods_receipt_note_items
        if (Schema::hasTable('goods_receipt_note_items')) {
            Schema::table('goods_receipt_note_items', function (Blueprint $table) {
                if (!Schema::hasColumn('goods_receipt_note_items', 'discount_percent')) {
                    $table->decimal('discount_percent', 8, 2)->default(0)->after('rate');
                }
                if (!Schema::hasColumn('goods_receipt_note_items', 'tax_1_id')) {
                    $table->unsignedBigInteger('tax_1_id')->nullable()->after('discount_percent');
                    $table->string('tax_1_name', 100)->nullable()->after('tax_1_id');
                    $table->decimal('tax_1_rate', 8, 2)->default(0)->after('tax_1_name');
                    $table->unsignedBigInteger('tax_2_id')->nullable()->after('tax_1_rate');
                    $table->string('tax_2_name', 100)->nullable()->after('tax_2_id');
                    $table->decimal('tax_2_rate', 8, 2)->default(0)->after('tax_2_name');
                }
            });
        }
    }

    public function down(): void
    {
        // Remove columns from purchase_order_items
        if (Schema::hasTable('purchase_order_items') && Schema::hasColumn('purchase_order_items', 'tax_1_id')) {
            Schema::table('purchase_order_items', function (Blueprint $table) {
                $table->dropColumn(['tax_1_id', 'tax_1_name', 'tax_1_rate', 'tax_1_amount', 
                                   'tax_2_id', 'tax_2_name', 'tax_2_rate', 'tax_2_amount']);
            });
        }

        // Remove columns from purchase_bill_items
        if (Schema::hasTable('purchase_bill_items') && Schema::hasColumn('purchase_bill_items', 'tax_1_id')) {
            Schema::table('purchase_bill_items', function (Blueprint $table) {
                $table->dropColumn(['tax_1_id', 'tax_1_name', 'tax_1_rate', 'tax_1_amount', 
                                   'tax_2_id', 'tax_2_name', 'tax_2_rate', 'tax_2_amount']);
            });
        }
        
        // Remove columns from goods_receipt_note_items
        if (Schema::hasTable('goods_receipt_note_items') && Schema::hasColumn('goods_receipt_note_items', 'tax_1_id')) {
            Schema::table('goods_receipt_note_items', function (Blueprint $table) {
                $table->dropColumn(['tax_1_id', 'tax_1_name', 'tax_1_rate', 
                                   'tax_2_id', 'tax_2_name', 'tax_2_rate']);
            });
        }
    }
};
