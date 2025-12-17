<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MERGED SALES MIGRATION
     * 
     * This single migration creates all sales-related tables:
     * 1. payment_methods
     * 2. proposals
     * 3. proposal_items
     * 4. proposal_taxes
     * 5. estimations
     * 6. estimation_items
     * 7. invoices
     * 8. invoice_items
     * 9. payments
     * 
     * Merged from:
     * - 2024_01_01_000001_create_proposals_table.php
     * - 2024_01_01_000002_create_proposal_items_table.php
     * - 2025_12_12_052858_create_estimations_table.php
     * - 2025_12_12_052905_create_estimation_items_table.php
     * - 2025_12_12_160330_create_invoices_table.php
     * - 2025_12_12_161703_create_invoice_items_table.php
     * - 2025_12_13_065640_create_payment_methods_table.php
     * - 2025_12_13_121638_create_payments_table.php
     * - 2025_12_15_104011_add_missing_columns_to_invoices_table.php
     * - 2025_12_15_104252_add_missing_columns_to_invoice_items_table.php
     * - 2025_12_16_095522_add_missing_columns_to_invoice_items_table.php
     * - 2025_12_16_103336_add_tax_ids_to_estimation_items_table.php
     * - 2025_12_16_110520_add_tax_ids_to_proposal_items_table.php
     */
    public function up(): void
    {
        // =====================================================
        // 1. PAYMENT METHODS TABLE
        // =====================================================
     

        // =====================================================
        // 2. PROPOSALS TABLE
        // =====================================================
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('proposal_number')->unique();
            $table->string('subject');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('status')->default('draft'); // draft, sent, open, revised, declined, accepted
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->date('date');
            $table->date('open_till')->nullable();
            
            // Contact & Address
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            
            // Financial
            $table->string('currency')->default('INR');
            $table->string('discount_type')->default('no_discount'); // no_discount, before_tax, after_tax
            $table->decimal('discount_percent', 10, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('adjustment', 15, 2)->default(0);
            
            // Content
            $table->text('content')->nullable();
            $table->text('tags')->nullable();
            $table->boolean('allow_comments')->default(true);
            $table->text('admin_note')->nullable();
            
            // Timestamps
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('status');
            $table->index('date');
        });

        // =====================================================
        // 3. PROPOSAL ITEMS TABLE
        // =====================================================
        Schema::create('proposal_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->string('item_type')->default('product'); // product, section, note
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('description')->nullable();
            $table->text('long_description')->nullable();
            $table->decimal('quantity', 15, 4)->default(1);
            $table->string('unit')->nullable();
            $table->decimal('rate', 15, 2)->default(0);
            $table->text('tax_ids')->nullable(); // JSON array of tax IDs for multi-tax
            $table->string('tax_name')->nullable();
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Foreign Key (only to proposals, not products to avoid constraint issues)
            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');
            
            // Indexes
            $table->index('proposal_id');
            $table->index('product_id');
        });

        // =====================================================
        // 4. PROPOSAL TAXES TABLE (Aggregated tax breakdown)
        // =====================================================
        Schema::create('proposal_taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->string('name');
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();

            // Foreign Key
            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');
            
            // Indexes
            $table->index('proposal_id');
            $table->index('tax_id');
        });

        // =====================================================
        // 5. ESTIMATIONS TABLE
        // =====================================================
        Schema::create('estimations', function (Blueprint $table) {
            $table->id();
            $table->string('estimation_number')->unique();
            $table->string('subject');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('proposal_id')->nullable(); // Link to proposal
            $table->string('status')->default('draft'); // draft, sent, approved, rejected, revised
            $table->string('assigned_to')->nullable(); // Store name directly
            $table->date('date');
            $table->date('valid_until')->nullable();
            
            // Contact & Address
            $table->text('address')->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 50)->nullable();
            
            // Financial
            $table->string('currency', 10)->default('INR');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->string('discount_type')->nullable(); // no_discount, before_tax, after_tax
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('adjustment', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            
            // Content
            $table->text('content')->nullable(); // Notes visible to customer
            $table->string('tags')->nullable();
            $table->boolean('allow_comments')->default(true);
            $table->text('admin_note')->nullable(); // Internal notes
            $table->text('terms_conditions')->nullable();
            $table->integer('validity_days')->nullable();
            
            // Tracking
            $table->string('created_by')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('customer_id');
            $table->index('proposal_id');
            $table->index('status');
            $table->index('date');
        });

        // =====================================================
        // 6. ESTIMATION ITEMS TABLE
        // =====================================================
        Schema::create('estimation_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estimation_id');
            $table->string('item_type')->default('product'); // product, section, note
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('description')->nullable();
            $table->text('long_description')->nullable();
            $table->decimal('quantity', 15, 2)->default(1);
            $table->string('unit', 50)->nullable();
            $table->decimal('rate', 15, 2)->default(0);
            $table->text('tax_ids')->nullable(); // JSON array of tax IDs for multi-tax
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->string('tax_name')->nullable();
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Foreign Key
            $table->foreign('estimation_id')->references('id')->on('estimations')->onDelete('cascade');
            
            // Indexes
            $table->index('estimation_id');
            $table->index('product_id');
        });

        // =====================================================
        // 7. INVOICES TABLE
        // =====================================================
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('estimation_id')->nullable();
            $table->string('subject');
            $table->date('date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->default('draft'); // draft, sent, paid, partially_paid, overdue, cancelled
            $table->string('payment_status')->default('unpaid'); // unpaid, partial, paid
            
            // Contact & Address
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            
            // Financial
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->string('discount_type')->default('no_discount'); // no_discount, before_tax, after_tax
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('adjustment', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('amount_due', 15, 2)->default(0);
            
            // Content
            $table->text('content')->nullable(); // Customer notes
            $table->string('tags')->nullable();
            $table->boolean('allow_comments')->default(true);
            $table->text('admin_note')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->string('currency')->default('INR');
            $table->string('assigned_to')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('estimation_id')->references('id')->on('estimations')->onDelete('set null');
            
            // Indexes
            $table->index('status');
            $table->index('payment_status');
            $table->index('date');
            $table->index('due_date');
        });

        // =====================================================
        // 8. INVOICE ITEMS TABLE
        // =====================================================
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('item_type')->default('product'); // product, section, note
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('description')->nullable();
            $table->text('long_description')->nullable();
            $table->decimal('quantity', 15, 2)->default(1);
            $table->string('unit', 50)->nullable();
            $table->decimal('rate', 15, 2)->default(0);
            $table->text('tax_ids')->nullable(); // JSON array of tax IDs for multi-tax
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Foreign Key
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            
            // Indexes
            $table->index('invoice_id');
            $table->index('product_id');
        });

        // =====================================================
        // 9. PAYMENTS TABLE
        // =====================================================
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->decimal('amount', 15, 2);
            $table->date('payment_date');
            $table->string('payment_method')->default('cash'); // cash, bank_transfer, upi, card, cheque, other
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('completed'); // completed, pending, failed, refunded
            $table->string('created_by')->nullable();
            $table->timestamps();
            
            // Foreign Keys
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            
            // Indexes
            $table->index(['invoice_id', 'payment_date']);
            $table->index('payment_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order (respecting foreign key constraints)
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('estimation_items');
        Schema::dropIfExists('estimations');
        Schema::dropIfExists('proposal_taxes');
        Schema::dropIfExists('proposal_items');
        Schema::dropIfExists('proposals');
        Schema::dropIfExists('payment_methods');
    }
};
