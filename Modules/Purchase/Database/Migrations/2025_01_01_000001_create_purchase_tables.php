<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vendors
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_code', 50)->unique();
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('website')->nullable();
            $table->enum('gst_type', ['REGISTERED', 'UNREGISTERED', 'COMPOSITION', 'SEZ'])->default('REGISTERED');
            $table->string('gst_number', 50)->nullable();
            $table->string('pan_number', 20)->nullable();
            $table->text('billing_address')->nullable();
            $table->string('billing_city', 100)->nullable();
            $table->string('billing_state', 100)->nullable();
            $table->string('billing_pincode', 10)->nullable();
            $table->string('billing_country', 100)->default('India');
            $table->text('shipping_address')->nullable();
            $table->string('shipping_city', 100)->nullable();
            $table->string('shipping_state', 100)->nullable();
            $table->string('shipping_pincode', 10)->nullable();
            $table->string('payment_terms', 100)->nullable();
            $table->integer('credit_days')->default(30);
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'BLOCKED'])->default('ACTIVE');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Purchase Requests
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('pr_number', 50)->unique();
            $table->date('pr_date');
            $table->date('required_date')->nullable();
            $table->string('department', 100)->nullable();
            $table->enum('priority', ['LOW', 'NORMAL', 'HIGH', 'URGENT'])->default('NORMAL');
            $table->enum('status', ['DRAFT', 'PENDING', 'APPROVED', 'REJECTED', 'CANCELLED', 'CONVERTED'])->default('DRAFT');
            $table->string('purpose', 500)->nullable();
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('requested_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Purchase Request Items
        Schema::create('purchase_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable();
            $table->foreignId('variation_id')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->decimal('qty', 15, 3);
            $table->decimal('ordered_qty', 15, 3)->default(0);
            $table->decimal('estimated_price', 15, 2)->nullable();
            $table->text('specifications')->nullable();
            $table->timestamps();
        });

        // Purchase Orders
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number', 50)->unique();
            $table->foreignId('vendor_id')->constrained('vendors');
            $table->foreignId('purchase_request_id')->nullable()->constrained('purchase_requests')->nullOnDelete();
            $table->date('po_date');
            $table->date('expected_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->enum('status', ['DRAFT', 'SENT', 'CONFIRMED', 'PARTIALLY_RECEIVED', 'RECEIVED', 'CANCELLED'])->default('DRAFT');
            $table->text('shipping_address')->nullable();
            $table->string('shipping_city', 100)->nullable();
            $table->string('shipping_state', 100)->nullable();
            $table->string('shipping_pincode', 10)->nullable();
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('shipping_charge', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('payment_terms', 100)->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Purchase Order Items
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('purchase_request_item_id')->nullable();
            $table->foreignId('product_id')->nullable();
            $table->foreignId('variation_id')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->decimal('qty', 15, 3);
            $table->decimal('received_qty', 15, 3)->default(0);
            $table->decimal('rate', 15, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Purchase Settings
        Schema::create('purchase_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group', 50)->default('general');
            $table->timestamps();
        });

        // Default settings
        \DB::table('purchase_settings')->insert([
            ['key' => 'vendor_prefix', 'value' => 'VND-', 'group' => 'vendor', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pr_prefix', 'value' => 'PR-', 'group' => 'pr', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'po_prefix', 'value' => 'PO-', 'group' => 'po', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pr_approval_required', 'value' => '1', 'group' => 'pr', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_payment_terms', 'value' => 'Net 30', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_tax_percent', 'value' => '18', 'group' => 'general', 'created_at' => now(), 'updated_at' => now()],
            // PDF Settings
            ['key' => 'pdf_primary_color', 'value' => '#1e40af', 'group' => 'pdf', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pdf_secondary_color', 'value' => '#f3f4f6', 'group' => 'pdf', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pdf_show_logo', 'value' => '1', 'group' => 'pdf', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pdf_show_gst', 'value' => '1', 'group' => 'pdf', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pdf_show_terms', 'value' => '1', 'group' => 'pdf', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pdf_show_signature', 'value' => '1', 'group' => 'pdf', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pdf_show_notes', 'value' => '1', 'group' => 'pdf', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pdf_compact_mode', 'value' => '1', 'group' => 'pdf', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'pdf_font_size', 'value' => '9', 'group' => 'pdf', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'po_terms', 'value' => "1. Goods once sold will not be taken back.\n2. Delivery within specified time.\n3. Payment as per agreed terms.", 'group' => 'po', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('purchase_request_items');
        Schema::dropIfExists('purchase_requests');
        Schema::dropIfExists('purchase_settings');
        Schema::dropIfExists('vendors');
    }
};
