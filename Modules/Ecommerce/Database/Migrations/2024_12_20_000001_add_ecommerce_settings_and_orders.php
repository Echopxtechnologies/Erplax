<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. ADD ECOMMERCE COLUMNS TO WEBSITE_SETTINGS
        Schema::table('website_settings', function (Blueprint $table) {
            // Shipping Settings
            $table->decimal('shipping_fee', 10, 2)->default(0)->after('meta_description');
            $table->decimal('free_shipping_min', 10, 2)->default(0)->after('shipping_fee'); // 0 = no free shipping
            $table->string('delivery_days', 50)->nullable()->after('free_shipping_min'); // "3-5 business days"
            
            // COD Settings
            $table->boolean('cod_enabled')->default(true)->after('delivery_days');
            $table->decimal('cod_fee', 10, 2)->default(0)->after('cod_enabled');
            $table->decimal('cod_max_amount', 12, 2)->default(0)->after('cod_fee'); // 0 = no limit
            
            // Online Payment Settings
            $table->boolean('online_payment_enabled')->default(false)->after('cod_max_amount');
            $table->string('online_payment_label', 100)->default('Pay Online (UPI/Card/NetBanking)')->after('online_payment_enabled');
            
            // Order Settings
            $table->string('order_prefix', 20)->default('ORD-')->after('online_payment_label');
            $table->decimal('min_order_amount', 10, 2)->default(0)->after('order_prefix');
            $table->boolean('guest_checkout')->default(false)->after('min_order_amount');
            
            // Tax Settings
            $table->boolean('tax_included_in_price')->default(true)->after('guest_checkout'); // prices already include tax
            $table->boolean('show_tax_breakup')->default(true)->after('tax_included_in_price');
            
            // Store Info (for invoices)
            $table->string('store_address')->nullable()->after('show_tax_breakup');
            $table->string('store_city', 100)->nullable()->after('store_address');
            $table->string('store_state', 100)->nullable()->after('store_city');
            $table->string('store_pincode', 20)->nullable()->after('store_state');
            $table->string('store_gstin', 20)->nullable()->after('store_pincode');
            
            // Invoice Settings
            $table->string('invoice_prefix', 20)->default('INV-')->after('store_gstin');
            $table->text('invoice_footer')->nullable()->after('invoice_prefix');
            
            // Notification Settings
            $table->string('order_notification_email')->nullable()->after('invoice_footer');
            $table->boolean('send_order_email')->default(true)->after('order_notification_email');
        });

        // Update default record
        DB::table('website_settings')->where('id', 1)->update([
            'shipping_fee' => 50,
            'free_shipping_min' => 500,
            'delivery_days' => '3-5 business days',
            'cod_enabled' => true,
            'cod_fee' => 0,
            'order_prefix' => 'ORD-',
            'invoice_prefix' => 'INV-',
            'tax_included_in_price' => true,
            'show_tax_breakup' => true,
            'guest_checkout' => false,
        ]);

        // 2. CREATE WEBSITE_ORDERS TABLE
        Schema::create('website_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no', 50)->unique();
            $table->unsignedBigInteger('customer_id')->nullable(); // links to customers table
            $table->unsignedBigInteger('user_id')->nullable(); // links to users table
            
            // Customer Info (snapshot at order time)
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone', 20);
            
            // Shipping Address
            $table->text('shipping_address');
            $table->string('shipping_city', 100);
            $table->string('shipping_state', 100);
            $table->string('shipping_pincode', 20);
            $table->string('shipping_country', 100)->default('India');
            
            // Billing Address (optional - same as shipping if null)
            $table->text('billing_address')->nullable();
            $table->string('billing_city', 100)->nullable();
            $table->string('billing_state', 100)->nullable();
            $table->string('billing_pincode', 20)->nullable();
            $table->string('billing_country', 100)->nullable();
            
            // Amounts
            $table->decimal('subtotal', 12, 2)->default(0); // sum of items
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('cod_fee', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('discount_code', 50)->nullable();
            $table->decimal('total', 12, 2)->default(0); // final amount
            
            // Status
            $table->enum('status', [
                'pending',      // Just placed
                'confirmed',    // Confirmed by admin
                'processing',   // Being prepared
                'shipped',      // Dispatched
                'delivered',    // Completed
                'cancelled',    // Cancelled
                'returned'      // Returned
            ])->default('pending');
            
            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded',
                'partial_refund'
            ])->default('pending');
            
            // Payment method slug from payment_methods table
            $table->string('payment_method', 50)->default('cash');
            
            // Payment Details
            $table->string('transaction_id', 100)->nullable();
            $table->string('payment_gateway', 50)->nullable(); // razorpay, phonepe etc
            $table->timestamp('paid_at')->nullable();
            
            // Shipping Details
            $table->string('tracking_number', 100)->nullable();
            $table->string('carrier', 100)->nullable(); // Courier/carrier name
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // Notes
            $table->text('customer_notes')->nullable(); // Notes from customer
            $table->text('admin_notes')->nullable(); // Internal notes
            
            // IP & Device (for fraud detection)
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('customer_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
        });

        // 3. CREATE WEBSITE_ORDER_ITEMS TABLE
        Schema::create('website_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('variation_id')->nullable();
            
            // Product snapshot (in case product is edited/deleted later)
            $table->string('product_name');
            $table->string('variation_name')->nullable(); // "Black / XL"
            $table->string('sku', 100);
            $table->string('hsn_code', 50)->nullable();
            
            // Unit
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->string('unit_name', 50)->default('PCS');
            
            // Quantity & Pricing
            $table->decimal('qty', 12, 3);
            $table->decimal('unit_price', 12, 2); // Price per unit
            $table->decimal('mrp', 12, 2)->nullable();
            
            // Tax (from product's tax_1_id, tax_2_id)
            $table->decimal('tax_rate', 5, 2)->default(0); // Combined tax %
            $table->decimal('tax_amount', 10, 2)->default(0);
            
            // Totals
            $table->decimal('subtotal', 12, 2); // qty * unit_price
            $table->decimal('total', 12, 2); // subtotal + tax (or just subtotal if tax included)
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('order_id')->references('id')->on('website_orders')->onDelete('cascade');
            $table->index('product_id');
            $table->index('variation_id');
        });

        // 4. CREATE WEBSITE_ORDER_STATUS_HISTORY TABLE (for tracking)
        Schema::create('website_order_status_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('status', 50);
            $table->string('comment')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable(); // user_id who changed
            $table->timestamps();
            
            $table->foreign('order_id')->references('id')->on('website_orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_order_status_history');
        Schema::dropIfExists('website_order_items');
        Schema::dropIfExists('website_orders');
        
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_fee',
                'free_shipping_min',
                'delivery_days',
                'cod_enabled',
                'cod_fee',
                'cod_max_amount',
                'online_payment_enabled',
                'online_payment_label',
                'order_prefix',
                'min_order_amount',
                'guest_checkout',
                'tax_included_in_price',
                'show_tax_breakup',
                'store_address',
                'store_city',
                'store_state',
                'store_pincode',
                'store_gstin',
                'invoice_prefix',
                'invoice_footer',
                'order_notification_email',
                'send_order_email',
            ]);
        });
    }
};
