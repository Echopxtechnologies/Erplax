<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ==================== 1. BRANDS ====================
        if (!Schema::hasTable('brands')) {
            Schema::create('brands', function (Blueprint $table) {
                $table->id();
                $table->string('name', 191);
                $table->string('logo')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // ==================== 2. PRODUCT CATEGORIES ====================
        if (!Schema::hasTable('product_categories')) {
            Schema::create('product_categories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('parent_id')->nullable()->constrained('product_categories')->nullOnDelete();
                $table->string('code', 50)->nullable()->unique();
                $table->string('name', 191);
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // ==================== 3. UNITS ====================
        if (!Schema::hasTable('units')) {
            Schema::create('units', function (Blueprint $table) {
                $table->id();
                $table->string('name', 50);
                $table->string('short_name', 20);
                $table->foreignId('base_unit_id')->nullable()->constrained('units')->nullOnDelete();
                $table->decimal('conversion_factor', 15, 4)->default(1);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->unique('short_name');
            });
        }

        // ==================== 4. TAGS ====================
        if (!Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100)->unique();
                $table->string('slug', 100)->unique();
                $table->string('color', 7)->default('#6366f1');
                $table->timestamps();
            });
        }

        // ==================== 5. PRODUCT ATTRIBUTES ====================
        if (!Schema::hasTable('product_attributes')) {
            Schema::create('product_attributes', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('slug', 100)->unique();
                $table->string('type', 20)->default('select');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // ==================== 6. ATTRIBUTE VALUES ====================
        if (!Schema::hasTable('attribute_values')) {
            Schema::create('attribute_values', function (Blueprint $table) {
                $table->id();
                $table->foreignId('attribute_id')->constrained('product_attributes')->cascadeOnDelete();
                $table->string('value', 100);
                $table->string('slug', 100);
                $table->string('color_code', 7)->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
                
                $table->unique(['attribute_id', 'slug']);
            });
        }

        // ==================== 7. PRODUCTS ====================
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name', 191);
                $table->string('sku', 100)->unique();
                $table->text('description')->nullable();
                $table->decimal('purchase_price', 12, 2)->default(0);
                $table->decimal('sale_price', 12, 2)->default(0);
                $table->decimal('mrp', 12, 2)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Add missing columns to existing products table
        if (!Schema::hasColumn('products', 'category_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable()->after('id');
            });
        }
        if (!Schema::hasColumn('products', 'brand_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('brand_id')->nullable()->after('category_id');
            });
        }
        if (!Schema::hasColumn('products', 'unit_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('unit_id')->nullable()->after('brand_id');
            });
        }
        if (!Schema::hasColumn('products', 'short_description')) {
            Schema::table('products', function (Blueprint $table) {
                $table->text('short_description')->nullable()->after('description');
            });
        }
        if (!Schema::hasColumn('products', 'barcode')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('barcode', 100)->nullable()->after('sku');
            });
        }
        if (!Schema::hasColumn('products', 'hsn_code')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('hsn_code', 50)->nullable()->after('barcode');
            });
        }
        if (!Schema::hasColumn('products', 'default_profit_rate')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('default_profit_rate', 5, 2)->default(0)->after('mrp');
            });
        }
        if (!Schema::hasColumn('products', 'tax_1_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('tax_1_id')->nullable()->after('default_profit_rate');
            });
        }
        if (!Schema::hasColumn('products', 'tax_2_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('tax_2_id')->nullable()->after('tax_1_id');
            });
        }
        if (!Schema::hasColumn('products', 'min_stock_level')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('min_stock_level', 12, 3)->default(0)->after('tax_2_id');
            });
        }
        if (!Schema::hasColumn('products', 'max_stock_level')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('max_stock_level', 12, 3)->default(0)->after('min_stock_level');
            });
        }
        if (!Schema::hasColumn('products', 'is_batch_managed')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_batch_managed')->default(false)->after('max_stock_level');
            });
        }
        if (!Schema::hasColumn('products', 'can_be_sold')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('can_be_sold')->default(true)->after('is_batch_managed');
            });
        }
        if (!Schema::hasColumn('products', 'can_be_purchased')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('can_be_purchased')->default(true)->after('can_be_sold');
            });
        }
        if (!Schema::hasColumn('products', 'track_inventory')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('track_inventory')->default(true)->after('can_be_purchased');
            });
        }
        if (!Schema::hasColumn('products', 'has_variants')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('has_variants')->default(false)->after('track_inventory');
            });
        }

        // ==================== 8. PRODUCT ATTRIBUTE MAPPING ====================
        if (!Schema::hasTable('product_attribute_map')) {
            Schema::create('product_attribute_map', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('attribute_id')->constrained('product_attributes')->cascadeOnDelete();
                $table->timestamps();
                
                $table->unique(['product_id', 'attribute_id']);
            });
        }

        // ==================== 9. PRODUCT VARIATIONS ====================
        if (!Schema::hasTable('product_variations')) {
            Schema::create('product_variations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->string('sku', 100)->unique();
                $table->string('barcode', 100)->nullable();
                $table->string('variation_name', 191)->nullable();
                $table->decimal('purchase_price', 12, 2)->nullable();
                $table->decimal('sale_price', 12, 2)->nullable();
                $table->decimal('mrp', 12, 2)->nullable();
                $table->string('image_path')->nullable();
                $table->decimal('stock_qty', 12, 3)->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index('product_id');
            });
        }

        // ==================== 10. VARIATION ATTRIBUTE VALUES ====================
        if (!Schema::hasTable('variation_attribute_values')) {
            Schema::create('variation_attribute_values', function (Blueprint $table) {
                $table->id();
                $table->foreignId('variation_id')->constrained('product_variations')->cascadeOnDelete();
                $table->foreignId('attribute_value_id')->constrained('attribute_values')->cascadeOnDelete();
                $table->timestamps();
                
                $table->unique(['variation_id', 'attribute_value_id'], 'var_attr_val_unique');
            });
        }

        // ==================== 11. PRODUCT IMAGES ====================
        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('variation_id')->nullable()->constrained('product_variations')->cascadeOnDelete();
                $table->string('image_path');
                $table->string('alt_text')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
                
                $table->index('product_id');
            });
        }

        // ==================== 12. PRODUCT TAGS ====================
        if (!Schema::hasTable('product_tags')) {
            Schema::create('product_tags', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
                $table->timestamps();
                
                $table->unique(['product_id', 'tag_id']);
            });
        }

        // ==================== 13. PRODUCT UNITS ====================
        if (!Schema::hasTable('product_units')) {
            Schema::create('product_units', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
                $table->string('unit_name', 100)->nullable();
                $table->decimal('conversion_factor', 10, 4)->default(1);
                $table->decimal('purchase_price', 12, 2)->nullable();
                $table->decimal('sale_price', 12, 2)->nullable();
                $table->string('barcode', 100)->nullable();
                $table->boolean('is_purchase_unit')->default(false);
                $table->boolean('is_sale_unit')->default(false);
                $table->boolean('is_default')->default(false);
                $table->timestamps();
                
                $table->unique(['product_id', 'unit_id']);
            });
        }

        // ==================== 14. WAREHOUSES ====================
        if (!Schema::hasTable('warehouses')) {
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
        }

        // ==================== 15. RACKS ====================
        if (!Schema::hasTable('racks')) {
            Schema::create('racks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
                $table->string('code', 50);
                $table->string('name', 191);
                $table->string('zone', 50)->nullable();
                $table->string('aisle', 50)->nullable();
                $table->string('level', 50)->nullable();
                $table->decimal('max_capacity', 15, 2)->nullable();
                $table->foreignId('capacity_unit_id')->nullable()->constrained('units')->nullOnDelete();
                $table->decimal('max_weight', 15, 2)->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->unique(['warehouse_id', 'code']);
            });
        }

        // ==================== 16. LOTS ====================
        if (!Schema::hasTable('lots')) {
            Schema::create('lots', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('variation_id')->nullable()->constrained('product_variations')->nullOnDelete();
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
                
                $table->unique(['product_id', 'lot_no']);
            });
        }

        // ==================== 17. STOCK LEVELS ====================
        if (!Schema::hasTable('stock_levels')) {
            Schema::create('stock_levels', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('variation_id')->nullable()->constrained('product_variations')->nullOnDelete();
                $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
                $table->foreignId('rack_id')->nullable()->constrained('racks')->nullOnDelete();
                $table->foreignId('lot_id')->nullable()->constrained('lots')->nullOnDelete();
                $table->foreignId('unit_id')->constrained('units');
                $table->decimal('qty', 12, 3)->default(0);
                $table->decimal('reserved_qty', 12, 3)->default(0);
                $table->timestamps();
                
                $table->unique(['product_id', 'variation_id', 'warehouse_id', 'rack_id', 'lot_id'], 'stock_unique');
                $table->index(['product_id', 'warehouse_id']);
            });
        }

        // ==================== 18. STOCK MOVEMENTS ====================
        if (!Schema::hasTable('stock_movements')) {
            Schema::create('stock_movements', function (Blueprint $table) {
                $table->id();
                $table->string('reference_no', 50)->nullable();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('variation_id')->nullable()->constrained('product_variations')->nullOnDelete();
                $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
                $table->foreignId('rack_id')->nullable()->constrained('racks')->nullOnDelete();
                $table->foreignId('lot_id')->nullable()->constrained('lots')->nullOnDelete();
                $table->foreignId('unit_id')->constrained('units');
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
                
                $table->index(['product_id', 'warehouse_id']);
                $table->index(['reference_type', 'reference_id']);
            });
        }

        // ==================== 19. STOCK TRANSFERS ====================
        if (!Schema::hasTable('stock_transfers')) {
            Schema::create('stock_transfers', function (Blueprint $table) {
                $table->id();
                $table->string('transfer_no', 50)->unique();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->foreignId('variation_id')->nullable()->constrained('product_variations')->nullOnDelete();
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
        }

        // ==================== SEED DEFAULT DATA ====================
        $this->seedUnits();
        $this->seedWarehouses();
        $this->seedAttributes();
    }

    private function seedUnits(): void
    {
        if (\DB::table('units')->count() > 0) return;

        $units = [
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

        $gId = \DB::table('units')->where('short_name', 'G')->value('id');
        $mlId = \DB::table('units')->where('short_name', 'ML')->value('id');
        $mId = \DB::table('units')->where('short_name', 'M')->value('id');
        $pcsId = \DB::table('units')->where('short_name', 'PCS')->value('id');

        $derivedUnits = [
            ['name' => 'Kilograms', 'short_name' => 'KG', 'base_unit_id' => $gId, 'conversion_factor' => 1000],
            ['name' => 'Milligrams', 'short_name' => 'MG', 'base_unit_id' => $gId, 'conversion_factor' => 0.001],
            ['name' => 'Quintal', 'short_name' => 'QTL', 'base_unit_id' => $gId, 'conversion_factor' => 100000],
            ['name' => 'Ton', 'short_name' => 'TON', 'base_unit_id' => $gId, 'conversion_factor' => 1000000],
            ['name' => 'Liters', 'short_name' => 'LTR', 'base_unit_id' => $mlId, 'conversion_factor' => 1000],
            ['name' => 'Centimeters', 'short_name' => 'CM', 'base_unit_id' => $mId, 'conversion_factor' => 0.01],
            ['name' => 'Feet', 'short_name' => 'FT', 'base_unit_id' => $mId, 'conversion_factor' => 0.3048],
            ['name' => 'Inches', 'short_name' => 'IN', 'base_unit_id' => $mId, 'conversion_factor' => 0.0254],
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

    private function seedWarehouses(): void
    {
        if (\DB::table('warehouses')->count() > 0) return;

        \DB::table('warehouses')->insert([
            'code' => 'WH-MAIN',
            'name' => 'Main Warehouse',
            'type' => 'STORAGE',
            'is_default' => true,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function seedAttributes(): void
    {
        if (\DB::table('product_attributes')->count() > 0) return;

        $attributes = [
            [
                'name' => 'Color',
                'slug' => 'color',
                'type' => 'color',
                'sort_order' => 1,
                'values' => [
                    ['value' => 'Black', 'slug' => 'black', 'color_code' => '#000000'],
                    ['value' => 'White', 'slug' => 'white', 'color_code' => '#FFFFFF'],
                    ['value' => 'Red', 'slug' => 'red', 'color_code' => '#EF4444'],
                    ['value' => 'Blue', 'slug' => 'blue', 'color_code' => '#3B82F6'],
                    ['value' => 'Green', 'slug' => 'green', 'color_code' => '#22C55E'],
                    ['value' => 'Yellow', 'slug' => 'yellow', 'color_code' => '#EAB308'],
                    ['value' => 'Orange', 'slug' => 'orange', 'color_code' => '#F97316'],
                    ['value' => 'Purple', 'slug' => 'purple', 'color_code' => '#A855F7'],
                    ['value' => 'Pink', 'slug' => 'pink', 'color_code' => '#EC4899'],
                    ['value' => 'Gray', 'slug' => 'gray', 'color_code' => '#6B7280'],
                    ['value' => 'Brown', 'slug' => 'brown', 'color_code' => '#92400E'],
                    ['value' => 'Navy', 'slug' => 'navy', 'color_code' => '#1E3A8A'],
                ],
            ],
            [
                'name' => 'Size',
                'slug' => 'size',
                'type' => 'select',
                'sort_order' => 2,
                'values' => [
                    ['value' => 'XS', 'slug' => 'xs', 'color_code' => null],
                    ['value' => 'S', 'slug' => 's', 'color_code' => null],
                    ['value' => 'M', 'slug' => 'm', 'color_code' => null],
                    ['value' => 'L', 'slug' => 'l', 'color_code' => null],
                    ['value' => 'XL', 'slug' => 'xl', 'color_code' => null],
                    ['value' => 'XXL', 'slug' => 'xxl', 'color_code' => null],
                    ['value' => 'XXXL', 'slug' => 'xxxl', 'color_code' => null],
                ],
            ],
            [
                'name' => 'Material',
                'slug' => 'material',
                'type' => 'select',
                'sort_order' => 3,
                'values' => [
                    ['value' => 'Cotton', 'slug' => 'cotton', 'color_code' => null],
                    ['value' => 'Polyester', 'slug' => 'polyester', 'color_code' => null],
                    ['value' => 'Silk', 'slug' => 'silk', 'color_code' => null],
                    ['value' => 'Wool', 'slug' => 'wool', 'color_code' => null],
                    ['value' => 'Linen', 'slug' => 'linen', 'color_code' => null],
                    ['value' => 'Leather', 'slug' => 'leather', 'color_code' => null],
                    ['value' => 'Denim', 'slug' => 'denim', 'color_code' => null],
                ],
            ],
            [
                'name' => 'Style',
                'slug' => 'style',
                'type' => 'select',
                'sort_order' => 4,
                'values' => [
                    ['value' => 'Regular', 'slug' => 'regular', 'color_code' => null],
                    ['value' => 'Slim Fit', 'slug' => 'slim-fit', 'color_code' => null],
                    ['value' => 'Loose Fit', 'slug' => 'loose-fit', 'color_code' => null],
                    ['value' => 'Round Neck', 'slug' => 'round-neck', 'color_code' => null],
                    ['value' => 'V-Neck', 'slug' => 'v-neck', 'color_code' => null],
                    ['value' => 'Collar', 'slug' => 'collar', 'color_code' => null],
                ],
            ],
        ];

        foreach ($attributes as $attr) {
            $values = $attr['values'];
            unset($attr['values']);
            
            \DB::table('product_attributes')->insert(array_merge($attr, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
            
            $attrId = \DB::table('product_attributes')->where('slug', $attr['slug'])->value('id');
            
            foreach ($values as $index => $value) {
                \DB::table('attribute_values')->insert(array_merge($value, [
                    'attribute_id' => $attrId,
                    'sort_order' => $index + 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
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