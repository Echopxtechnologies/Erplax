<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. BRANDS
        if (!Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $table) {
                $table->id();
                $table->string('name', 191);
                $table->string('logo', 255)->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. PRODUCT CATEGORIES
        if (!Schema::hasTable('product_categories')) {
            Schema::create('product_categories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('code', 50)->nullable();
                $table->string('name', 191);
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. UNITS
        if (!Schema::hasTable('units')) {
            Schema::create('units', function (Blueprint $table) {
                $table->id();
                $table->string('name', 50);
                $table->string('short_name', 20);
                $table->unsignedBigInteger('base_unit_id')->nullable();
                $table->decimal('conversion_factor', 15, 4)->default(1);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. TAGS
        if (!Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 100);
                $table->string('color', 7)->default('#6366f1');
                $table->timestamps();
            });
        }

        // 5. PRODUCT ATTRIBUTES
        if (!Schema::hasTable('product_attributes')) {
            Schema::create('product_attributes', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 100);
                $table->string('type', 20)->default('select');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 6. ATTRIBUTE VALUES
        if (!Schema::hasTable('attribute_values')) {
            Schema::create('attribute_values', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('attribute_id');
                $table->string('value', 100);
                $table->string('slug', 100);
                $table->string('color_code', 7)->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        // 7. PRODUCTS
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id')->nullable();
                $table->unsignedBigInteger('brand_id')->nullable();
                $table->unsignedBigInteger('unit_id')->nullable();
                $table->string('name', 191);
                $table->string('sku', 100);
                $table->string('barcode', 100)->nullable();
                $table->string('hsn_code', 50)->nullable();
                $table->text('description')->nullable();
                $table->text('short_description')->nullable();
                $table->decimal('purchase_price', 12, 2)->default(0);
                $table->decimal('sale_price', 12, 2)->default(0);
                $table->decimal('mrp', 12, 2)->nullable();
                $table->decimal('default_profit_rate', 5, 2)->default(0);
                $table->unsignedBigInteger('tax_1_id')->nullable();
                $table->unsignedBigInteger('tax_2_id')->nullable();
                $table->decimal('min_stock_level', 12, 3)->default(0);
                $table->decimal('max_stock_level', 12, 3)->default(0);
                $table->boolean('is_batch_managed')->default(false);
                $table->boolean('can_be_sold')->default(true);
                $table->boolean('can_be_purchased')->default(true);
                $table->boolean('track_inventory')->default(true);
                $table->boolean('has_variants')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 8. PRODUCT ATTRIBUTE MAP
        if (!Schema::hasTable('product_attribute_map')) {
            Schema::create('product_attribute_map', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('attribute_id');
                $table->timestamps();
            });
        }

        // 9. PRODUCT VARIATIONS
        if (!Schema::hasTable('product_variations')) {
            Schema::create('product_variations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->string('sku', 100);
                $table->string('barcode', 100)->nullable();
                $table->string('variation_name', 191)->nullable();
                $table->decimal('purchase_price', 12, 2)->nullable();
                $table->decimal('sale_price', 12, 2)->nullable();
                $table->decimal('mrp', 12, 2)->nullable();
                $table->string('image_path', 255)->nullable();
                $table->decimal('stock_qty', 12, 3)->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 10. VARIATION ATTRIBUTE VALUES
        if (!Schema::hasTable('variation_attribute_values')) {
            Schema::create('variation_attribute_values', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('variation_id');
                $table->unsignedBigInteger('attribute_value_id');
                $table->timestamps();
            });
        }

        // 11. PRODUCT IMAGES
        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('variation_id')->nullable();
                $table->string('image_path', 255);
                $table->string('alt_text', 255)->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
            });
        }

        // 12. PRODUCT TAGS
        if (!Schema::hasTable('product_tags')) {
            Schema::create('product_tags', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('tag_id');
                $table->timestamps();
            });
        }

        // 13. PRODUCT UNITS
        if (!Schema::hasTable('product_units')) {
            Schema::create('product_units', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('unit_id');
                $table->string('unit_name', 100)->nullable();
                $table->decimal('conversion_factor', 10, 4)->default(1);
                $table->decimal('purchase_price', 12, 2)->nullable();
                $table->decimal('sale_price', 12, 2)->nullable();
                $table->string('barcode', 100)->nullable();
                $table->boolean('is_purchase_unit')->default(false);
                $table->boolean('is_sale_unit')->default(false);
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }

        // 14. WAREHOUSES
        if (!Schema::hasTable('warehouses')) {
            Schema::create('warehouses', function (Blueprint $table) {
                $table->id();
                $table->string('name', 191);
                $table->string('code', 50);
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
        }

        // 15. RACKS
        if (!Schema::hasTable('racks')) {
            Schema::create('racks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('warehouse_id');
                $table->string('code', 50);
                $table->string('name', 191);
                $table->string('zone', 50)->nullable();
                $table->string('aisle', 50)->nullable();
                $table->string('level', 50)->nullable();
                $table->decimal('max_capacity', 15, 2)->nullable();
                $table->unsignedBigInteger('capacity_unit_id')->nullable();
                $table->decimal('max_weight', 15, 2)->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 16. LOTS
        if (!Schema::hasTable('lots')) {
            Schema::create('lots', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('variation_id')->nullable();
                $table->string('lot_no', 100);
                $table->string('batch_no', 100)->nullable();
                $table->date('manufacturing_date')->nullable();
                $table->date('expiry_date')->nullable();
                $table->decimal('initial_qty', 12, 3)->nullable();
                $table->decimal('purchase_price', 12, 2)->nullable();
                $table->decimal('sale_price', 12, 2)->nullable();
                $table->enum('status', ['ACTIVE', 'EXPIRED', 'RECALLED', 'CONSUMED'])->default('ACTIVE');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 17. STOCK LEVELS
        if (!Schema::hasTable('stock_levels')) {
            Schema::create('stock_levels', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('variation_id')->nullable();
                $table->unsignedBigInteger('warehouse_id');
                $table->unsignedBigInteger('rack_id')->nullable();
                $table->unsignedBigInteger('lot_id')->nullable();
                $table->unsignedBigInteger('unit_id');
                $table->decimal('qty', 12, 3)->default(0);
                $table->decimal('reserved_qty', 12, 3)->default(0);
                $table->timestamps();
            });
        }

        // 18. STOCK MOVEMENTS
        if (!Schema::hasTable('stock_movements')) {
            Schema::create('stock_movements', function (Blueprint $table) {
                $table->id();
                $table->string('reference_no', 50)->nullable();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('variation_id')->nullable();
                $table->unsignedBigInteger('warehouse_id');
                $table->unsignedBigInteger('rack_id')->nullable();
                $table->unsignedBigInteger('lot_id')->nullable();
                $table->unsignedBigInteger('unit_id');
                $table->decimal('qty', 12, 3);
                $table->decimal('base_qty', 12, 3);
                $table->decimal('stock_before', 12, 3)->default(0);
                $table->decimal('stock_after', 12, 3)->default(0);
                $table->decimal('purchase_price', 12, 2)->nullable();
                $table->enum('movement_type', ['IN', 'OUT', 'TRANSFER', 'ADJUSTMENT', 'RETURN']);
                $table->enum('reference_type', ['PURCHASE', 'SALE', 'TRANSFER', 'ADJUSTMENT', 'RETURN', 'OPENING'])->nullable();
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->string('reason', 255)->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 19. STOCK TRANSFERS
        if (!Schema::hasTable('stock_transfers')) {
            Schema::create('stock_transfers', function (Blueprint $table) {
                $table->id();
                $table->string('transfer_no', 50);
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('variation_id')->nullable();
                $table->unsignedBigInteger('lot_id')->nullable();
                $table->unsignedBigInteger('unit_id');
                $table->unsignedBigInteger('from_warehouse_id');
                $table->unsignedBigInteger('to_warehouse_id');
                $table->unsignedBigInteger('from_rack_id')->nullable();
                $table->unsignedBigInteger('to_rack_id')->nullable();
                $table->decimal('qty', 12, 3);
                $table->decimal('base_qty', 12, 3);
                $table->enum('status', ['PENDING', 'IN_TRANSIT', 'COMPLETED', 'CANCELLED'])->default('COMPLETED');
                $table->string('reason', 255)->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // SEED DATA
        $this->seedData();
    }

    private function seedData(): void
    {
        // Units
        if (Schema::hasTable('units') && DB::table('units')->count() == 0) {
            DB::table('units')->insert([
                ['name' => 'Pieces', 'short_name' => 'PCS', 'base_unit_id' => null, 'conversion_factor' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Grams', 'short_name' => 'G', 'base_unit_id' => null, 'conversion_factor' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Kilograms', 'short_name' => 'KG', 'base_unit_id' => null, 'conversion_factor' => 1000, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Milliliters', 'short_name' => 'ML', 'base_unit_id' => null, 'conversion_factor' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Liters', 'short_name' => 'LTR', 'base_unit_id' => null, 'conversion_factor' => 1000, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Meters', 'short_name' => 'M', 'base_unit_id' => null, 'conversion_factor' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Dozen', 'short_name' => 'DZN', 'base_unit_id' => null, 'conversion_factor' => 12, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Box', 'short_name' => 'BOX', 'base_unit_id' => null, 'conversion_factor' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // Warehouse
        if (Schema::hasTable('warehouses') && DB::table('warehouses')->count() == 0) {
            DB::table('warehouses')->insert([
                'code' => 'WH-MAIN',
                'name' => 'Main Warehouse',
                'type' => 'STORAGE',
                'is_default' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Category
        if (Schema::hasTable('product_categories') && DB::table('product_categories')->count() == 0) {
            DB::table('product_categories')->insert([
                'code' => 'GEN',
                'name' => 'General',
                'description' => 'General Products',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Attributes
        if (Schema::hasTable('product_attributes') && DB::table('product_attributes')->count() == 0) {
            DB::table('product_attributes')->insert([
                ['name' => 'Color', 'slug' => 'color', 'type' => 'color', 'sort_order' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'Size', 'slug' => 'size', 'type' => 'select', 'sort_order' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ]);

            $colorId = DB::table('product_attributes')->where('slug', 'color')->value('id');
            $sizeId = DB::table('product_attributes')->where('slug', 'size')->value('id');

            if ($colorId && $sizeId) {
                DB::table('attribute_values')->insert([
                    ['attribute_id' => $colorId, 'value' => 'Black', 'slug' => 'black', 'color_code' => '#000000', 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
                    ['attribute_id' => $colorId, 'value' => 'White', 'slug' => 'white', 'color_code' => '#FFFFFF', 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
                    ['attribute_id' => $colorId, 'value' => 'Red', 'slug' => 'red', 'color_code' => '#EF4444', 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
                    ['attribute_id' => $sizeId, 'value' => 'S', 'slug' => 's', 'color_code' => null, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
                    ['attribute_id' => $sizeId, 'value' => 'M', 'slug' => 'm', 'color_code' => null, 'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
                    ['attribute_id' => $sizeId, 'value' => 'L', 'slug' => 'l', 'color_code' => null, 'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
                    ['attribute_id' => $sizeId, 'value' => 'XL', 'slug' => 'xl', 'color_code' => null, 'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
                ]);
            }
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
        Schema::dropIfExists('product_tags');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('variation_attribute_values');
        Schema::dropIfExists('product_variations');
        Schema::dropIfExists('product_attribute_map');
        Schema::dropIfExists('products');
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('product_attributes');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('units');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('brands');
    }
};
