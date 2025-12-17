<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Goods Receipt Notes (GRN)
        Schema::create('goods_receipt_notes', function (Blueprint $table) {
            $table->id();
            $table->string('grn_number', 50)->unique();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders');
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->date('grn_date');
            $table->string('invoice_number', 100)->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('lr_number', 100)->nullable(); // Lorry Receipt / Transport
            $table->string('vehicle_number', 50)->nullable();
            $table->enum('status', ['DRAFT', 'INSPECTING', 'APPROVED', 'REJECTED', 'CANCELLED'])->default('DRAFT');
            $table->foreignId('warehouse_id')->nullable();
            $table->foreignId('rack_id')->nullable();
            $table->decimal('total_qty', 15, 3)->default(0);
            $table->decimal('accepted_qty', 15, 3)->default(0);
            $table->decimal('rejected_qty', 15, 3)->default(0);
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('stock_updated')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('received_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['purchase_order_id', 'status']);
            $table->index('grn_date');
        });

        // GRN Items
        Schema::create('goods_receipt_note_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_receipt_note_id')->constrained()->cascadeOnDelete();
            $table->foreignId('purchase_order_item_id')->nullable();
            $table->foreignId('product_id')->nullable();
            $table->foreignId('variation_id')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->decimal('ordered_qty', 15, 3)->default(0);
            $table->decimal('received_qty', 15, 3)->default(0);
            $table->decimal('accepted_qty', 15, 3)->default(0);
            $table->decimal('rejected_qty', 15, 3)->default(0);
            $table->decimal('rate', 15, 2)->default(0);
            $table->decimal('discount_percent', 8, 2)->default(0);
            // Tax 1
            $table->unsignedBigInteger('tax_1_id')->nullable();
            $table->string('tax_1_name', 100)->nullable();
            $table->decimal('tax_1_rate', 8, 2)->default(0);
            // Tax 2
            $table->unsignedBigInteger('tax_2_id')->nullable();
            $table->string('tax_2_name', 100)->nullable();
            $table->decimal('tax_2_rate', 8, 2)->default(0);
            // Other fields
            $table->string('rejection_reason', 255)->nullable();
            // Lot/Batch fields
            $table->string('lot_no', 100)->nullable();
            $table->string('batch_no', 100)->nullable();
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();
            // Stock movement reference
            $table->unsignedBigInteger('stock_movement_id')->nullable();
            $table->foreignId('lot_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['goods_receipt_note_id', 'product_id']);
        });

        // Add GRN prefix setting
        \DB::table('purchase_settings')->insert([
            'key' => 'grn_prefix',
            'value' => 'GRN-',
            'group' => 'grn',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('goods_receipt_note_items');
        Schema::dropIfExists('goods_receipt_notes');
        \DB::table('purchase_settings')->where('key', 'grn_prefix')->delete();
    }
};
