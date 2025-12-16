<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Purchase Bills (Vendor Invoices)
        if (!Schema::hasTable('purchase_bills')) {
            Schema::create('purchase_bills', function (Blueprint $table) {
                $table->id();
                $table->string('bill_number', 50)->unique();
                $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
                $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->nullOnDelete();
                $table->foreignId('grn_id')->nullable()->constrained('goods_receipt_notes')->nullOnDelete();
                
                // Vendor Invoice Details
                $table->string('vendor_invoice_no', 100)->nullable();
                $table->date('vendor_invoice_date')->nullable();
                $table->date('bill_date');
                $table->date('due_date')->nullable();
                
                // Warehouse (from GRN)
                $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete();
                
                // Status
                $table->enum('status', ['DRAFT', 'PENDING', 'APPROVED', 'REJECTED', 'CANCELLED'])->default('DRAFT');
                $table->enum('payment_status', ['UNPAID', 'PARTIALLY_PAID', 'PAID'])->default('UNPAID');
                
                // Amounts
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('tax_amount', 15, 2)->default(0);
                $table->decimal('discount_amount', 15, 2)->default(0);
                $table->decimal('shipping_charge', 15, 2)->default(0);
                $table->decimal('adjustment', 15, 2)->default(0);
                $table->decimal('grand_total', 15, 2)->default(0);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->decimal('balance_due', 15, 2)->default(0);
                
                // Notes
                $table->text('notes')->nullable();
                $table->text('terms_conditions')->nullable();
                
                // Approval
                $table->timestamp('approved_at')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->text('rejection_reason')->nullable();
                
                // Audit
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                $table->index(['vendor_id', 'status']);
                $table->index('payment_status');
            });
        }

        // Purchase Bill Items
        if (!Schema::hasTable('purchase_bill_items')) {
            Schema::create('purchase_bill_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('purchase_bill_id')->constrained('purchase_bills')->cascadeOnDelete();
                $table->foreignId('grn_item_id')->nullable()->constrained('goods_receipt_note_items')->nullOnDelete();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('variation_id')->nullable()->constrained('product_variations')->nullOnDelete();
                $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
                
                $table->string('description')->nullable();
                $table->decimal('qty', 12, 3);
                $table->decimal('rate', 12, 2);
                $table->decimal('tax_percent', 5, 2)->default(0);
                $table->decimal('tax_amount', 12, 2)->default(0);
                $table->decimal('discount_percent', 5, 2)->default(0);
                $table->decimal('discount_amount', 12, 2)->default(0);
                $table->decimal('total', 15, 2)->default(0);
                
                $table->timestamps();
                
                $table->index('purchase_bill_id');
            });
        }

        // Purchase Payments
        if (!Schema::hasTable('purchase_payments')) {
            Schema::create('purchase_payments', function (Blueprint $table) {
                $table->id();
                $table->string('payment_number', 50)->unique();
                $table->foreignId('purchase_bill_id')->constrained('purchase_bills')->cascadeOnDelete();
                $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
                
                $table->date('payment_date');
                $table->decimal('amount', 15, 2);
                $table->unsignedBigInteger('payment_method_id')->nullable(); // References payment_methods table
                $table->string('reference_no', 100)->nullable();
                $table->string('bank_name', 100)->nullable();
                $table->string('cheque_no', 50)->nullable();
                $table->date('cheque_date')->nullable();
                
                $table->text('notes')->nullable();
                $table->enum('status', ['PENDING', 'COMPLETED', 'CANCELLED'])->default('COMPLETED');
                
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                $table->index(['purchase_bill_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_payments');
        Schema::dropIfExists('purchase_bill_items');
        Schema::dropIfExists('purchase_bills');
    }
};
