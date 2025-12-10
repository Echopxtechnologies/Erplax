<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==================== 1. BRANDS ====================
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191);
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ==================== 2. PRODUCT CATEGORIES ====================
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

        // ==================== 3. UNITS (NEW) ====================
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);                      // Pieces, Kilograms, Liters
            $table->string('short_name', 20);                // PCS, KG, LTR
            $table->foreignId('base_unit_id')->nullable()->constrained('units')->nullOnDelete(); // For conversions
            $table->decimal('conversion_factor', 15, 4)->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique('short_name');
        });

        // ==================== 4. PRODUCTS ====================
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete(); // Primary unit
            $table->string('name', 191);
            $table->string('sku', 100)->unique();
            $table->string('barcode', 100)->nullable();
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('sale_price', 12, 2)->default(0);
            $table->string('hsn_code', 50)->nullable();
            $table->decimal('min_stock_level', 12, 3)->default(0);
            $table->decimal('max_stock_level', 12, 3)->default(0);
            $table->boolean('is_batch_managed')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ==================== 5. PRODUCT UNITS (Multiple units per product) ====================
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->decimal('conversion_factor', 10, 4)->default(1); // How many base units
            $table->decimal('purchase_price', 12, 2)->nullable();    // Price for this unit
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->string('barcode', 100)->nullable();              // Different barcode per unit
            $table->boolean('is_purchase_unit')->default(false);     // Can purchase in this unit
            $table->boolean('is_sale_unit')->default(false);         // Can sell in this unit
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            $table->unique(['product_id', 'unit_id']);
        });

        // ==================== 6. WAREHOUSES ====================
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

        // ==================== 7. RACKS ====================
        Schema::create('racks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->string('code', 50);                     // A1, B2, C3
            $table->string('name', 191);                    // Rack A - Shelf 1
            $table->string('zone', 50)->nullable();         // Zone A, Cold Storage
            $table->string('aisle', 50)->nullable();        // Aisle 1
            $table->string('level', 50)->nullable();        // Ground, Level 1
            $table->decimal('capacity', 12, 3)->nullable(); // Max capacity
            $table->foreignId('capacity_unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['warehouse_id', 'code']);
        });

        // ==================== 8. LOTS (Batches) ====================
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('lot_no', 100);
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('sale_price', 12, 2)->default(0);
            $table->decimal('initial_qty', 12, 3)->default(0);
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->enum('status', ['AVAILABLE', 'RESERVED', 'EXPIRED', 'CONSUMED'])->default('AVAILABLE');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->unique(['product_id', 'lot_no']);
        });

        // ==================== 9. STOCK LEVELS (Current stock per location) ====================
        Schema::create('stock_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('rack_id')->nullable()->constrained('racks')->nullOnDelete();
            $table->foreignId('lot_id')->nullable()->constrained('lots')->nullOnDelete();
            $table->foreignId('unit_id')->constrained('units');
            $table->decimal('qty', 12, 3)->default(0);
            $table->decimal('reserved_qty', 12, 3)->default(0);
            $table->timestamps();
            
            $table->unique(['product_id', 'warehouse_id', 'rack_id', 'lot_id'], 'stock_unique');
            $table->index(['product_id', 'warehouse_id']);
        });

        // ==================== 10. STOCK MOVEMENTS ====================
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 50)->nullable();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->foreignId('rack_id')->nullable()->constrained('racks')->nullOnDelete();
            $table->foreignId('lot_id')->nullable()->constrained('lots')->nullOnDelete();
            $table->foreignId('unit_id')->constrained('units');
            $table->decimal('qty', 12, 3);
            $table->decimal('base_qty', 12, 3);              // Converted to base unit
            $table->decimal('stock_before', 12, 3)->default(0);
            $table->decimal('stock_after', 12, 3)->default(0);
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

        // ==================== 11. STOCK TRANSFERS ====================
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_no', 50)->unique();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('lot_id')->nullable()->constrained('lots')->nullOnDelete();
            $table->foreignId('unit_id')->constrained('units');
            $table->foreignId('from_warehouse_id')->constrained('warehouses');
            $table->foreignId('to_warehouse_id')->constrained('warehouses');
            $table->foreignId('from_rack_id')->nullable()->constrained('racks')->nullOnDelete();
            $table->foreignId('to_rack_id')->nullable()->constrained('racks')->nullOnDelete();
            $table->decimal('qty', 12, 3);
            $table->decimal('base_qty', 12, 3);
            $table->enum('status', ['PENDING', 'IN_TRANSIT', 'COMPLETED', 'CANCELLED'])->default('COMPLETED');
            $table->string('reason', 255)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->index(['from_warehouse_id', 'to_warehouse_id']);
            $table->index('status');
        });

        // ==================== SEED DEFAULT UNITS ====================
        $this->seedUnits();
    }

    /**
     * Seed default units
     */
    private function seedUnits(): void
    {
        $units = [
            // Base units (no conversion)
            ['name' => 'Pieces', 'short_name' => 'PCS', 'base_unit_id' => null, 'conversion_factor' => 1],
            ['name' => 'Grams', 'short_name' => 'G', 'base_unit_id' => null, 'conversion_factor' => 1],
            ['name' => 'Milliliters', 'short_name' => 'ML', 'base_unit_id' => null, 'conversion_factor' => 1],
            ['name' => 'Meters', 'short_name' => 'M', 'base_unit_id' => null, 'conversion_factor' => 1],
        ];

        foreach ($units as $unit) {
            \DB::table('units')->insert(array_merge($unit, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Get base unit IDs
        $gId = \DB::table('units')->where('short_name', 'G')->value('id');
        $mlId = \DB::table('units')->where('short_name', 'ML')->value('id');
        $mId = \DB::table('units')->where('short_name', 'M')->value('id');
        $pcsId = \DB::table('units')->where('short_name', 'PCS')->value('id');

        // Derived units (with conversions)
        $derivedUnits = [
            // Weight
            ['name' => 'Kilograms', 'short_name' => 'KG', 'base_unit_id' => $gId, 'conversion_factor' => 1000],
            ['name' => 'Milligrams', 'short_name' => 'MG', 'base_unit_id' => $gId, 'conversion_factor' => 0.001],
            ['name' => 'Quintal', 'short_name' => 'QTL', 'base_unit_id' => $gId, 'conversion_factor' => 100000],
            ['name' => 'Ton', 'short_name' => 'TON', 'base_unit_id' => $gId, 'conversion_factor' => 1000000],
            
            // Volume
            ['name' => 'Liters', 'short_name' => 'LTR', 'base_unit_id' => $mlId, 'conversion_factor' => 1000],
            
            // Length
            ['name' => 'Centimeters', 'short_name' => 'CM', 'base_unit_id' => $mId, 'conversion_factor' => 0.01],
            ['name' => 'Feet', 'short_name' => 'FT', 'base_unit_id' => $mId, 'conversion_factor' => 0.3048],
            ['name' => 'Inches', 'short_name' => 'IN', 'base_unit_id' => $mId, 'conversion_factor' => 0.0254],
            
            // Packing
            ['name' => 'Box', 'short_name' => 'BOX', 'base_unit_id' => $pcsId, 'conversion_factor' => 1],
            ['name' => 'Carton', 'short_name' => 'CTN', 'base_unit_id' => $pcsId, 'conversion_factor' => 1],
            ['name' => 'Pack', 'short_name' => 'PACK', 'base_unit_id' => $pcsId, 'conversion_factor' => 1],
            ['name' => 'Dozen', 'short_name' => 'DZN', 'base_unit_id' => $pcsId, 'conversion_factor' => 12],
            ['name' => 'Pair', 'short_name' => 'PAIR', 'base_unit_id' => $pcsId, 'conversion_factor' => 2],
            ['name' => 'Set', 'short_name' => 'SET', 'base_unit_id' => $pcsId, 'conversion_factor' => 1],
            ['name' => 'Roll', 'short_name' => 'ROLL', 'base_unit_id' => $pcsId, 'conversion_factor' => 1],
            ['name' => 'Bundle', 'short_name' => 'BDL', 'base_unit_id' => $pcsId, 'conversion_factor' => 1],
            ['name' => 'Bag', 'short_name' => 'BAG', 'base_unit_id' => $pcsId, 'conversion_factor' => 1],
        ];

        foreach ($derivedUnits as $unit) {
            \DB::table('units')->insert(array_merge($unit, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('stock_levels');
        Schema::dropIfExists('lots');
        Schema::dropIfExists('racks');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('product_units');
        Schema::dropIfExists('products');
        Schema::dropIfExists('units');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('brands');
    }
};