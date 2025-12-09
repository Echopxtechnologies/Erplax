<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        // Brands (ADD THIS - before products)
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Product Categories
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('code', 50)->unique();
            $table->string('name', 191);
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('name', 191);
            $table->string('sku', 100)->unique();
            $table->string('barcode', 100)->nullable();
            $table->string('unit', 50)->default('PCS');
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->default(0);
            $table->string('hsn_code', 50)->nullable();
            $table->integer('min_stock_level')->default(0);
            $table->integer('max_stock_level')->default(0);
            $table->boolean('is_batch_managed')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Warehouses
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->string('code', 50)->unique();
            $table->string('address', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->default('India');
            $table->string('contact_person', 191)->nullable();
            $table->string('phone', 50)->nullable();
            $table->enum('type', ['STORAGE', 'SHOP', 'RETURN_CENTER'])->default('STORAGE');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Lots (Batches)
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('lot_no', 100);
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->default(0);
            $table->decimal('initial_qty', 12, 3)->default(0);
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->enum('status', ['AVAILABLE', 'RESERVED', 'EXPIRED', 'CONSUMED'])->default('AVAILABLE');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->unique(['product_id', 'lot_no']);
        });

        // Stock Movements
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('lot_id')->nullable()->constrained('lots')->nullOnDelete();
            $table->decimal('qty', 12, 3);
            $table->string('uom', 50)->default('PCS');
            $table->enum('movement_type', ['IN', 'OUT', 'TRANSFER', 'ADJUSTMENT', 'RETURN']);
            $table->enum('reference_type', ['PURCHASE', 'SALE', 'TRANSFER', 'ADJUSTMENT', 'RETURN', 'OPENING'])->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('reason', 255)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->index(['product_id', 'warehouse_id']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('lots');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
};