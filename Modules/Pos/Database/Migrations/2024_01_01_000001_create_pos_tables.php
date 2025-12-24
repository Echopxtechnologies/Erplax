<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->default('My Store');
            $table->string('store_phone')->nullable();
            $table->string('store_address')->nullable();
            $table->string('store_gstin')->nullable();
            $table->string('invoice_prefix')->default('INV-');
            $table->decimal('default_tax_rate', 5, 2)->default(18);
            $table->boolean('tax_inclusive')->default(false);
            $table->string('receipt_footer')->default('Thank you!');
            $table->unsignedBigInteger('default_warehouse_id')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_code')->unique();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->decimal('opening_cash', 12, 2)->default(0);
            $table->decimal('closing_cash', 12, 2)->nullable();
            $table->decimal('total_sales', 12, 2)->default(0);
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('session_id')->nullable()->constrained('pos_sessions')->nullOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->enum('payment_method', ['cash', 'card', 'upi'])->default('cash');
            $table->decimal('cash_received', 12, 2)->nullable();
            $table->decimal('change_amount', 12, 2)->default(0);
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->enum('status', ['completed', 'voided'])->default('completed');
            $table->timestamps();
        });

        Schema::create('pos_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('pos_sales')->cascadeOnDelete();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->string('product_name');
            $table->string('variant_name')->nullable();
            $table->integer('qty');
            $table->decimal('price', 12, 2);
            $table->decimal('line_total', 12, 2);
            $table->timestamps();
        });

        Schema::create('pos_held_bills', function (Blueprint $table) {
            $table->id();
            $table->string('hold_ref')->unique();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->json('cart_items');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->timestamps();
        });

        if (!Schema::hasColumn('admins', 'warehouse_id')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->unsignedBigInteger('warehouse_id')->nullable()->after('is_active');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_held_bills');
        Schema::dropIfExists('pos_sale_items');
        Schema::dropIfExists('pos_sales');
        Schema::dropIfExists('pos_sessions');
        Schema::dropIfExists('pos_settings');
    }
};
