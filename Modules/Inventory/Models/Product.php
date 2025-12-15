<?php

namespace Modules\Inventory\Models;
use App\Models\Admin\Tax;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'unit_id',
        'name',
        'description',
        'short_description',
        'sku',
        'barcode',
        'hsn_code',
        'purchase_price',
        'sale_price',
        'mrp',
        'default_profit_rate',
        'tax_1_id',
        'tax_2_id',
        'min_stock_level',
        'max_stock_level',
        'is_batch_managed',
        'can_be_sold',
        'can_be_purchased',
        'track_inventory',
        'has_variants',
        'is_active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'mrp' => 'decimal:2',
        'default_profit_rate' => 'decimal:2',
        'min_stock_level' => 'decimal:3',
        'max_stock_level' => 'decimal:3',
        'is_batch_managed' => 'boolean',
        'can_be_sold' => 'boolean',
        'can_be_purchased' => 'boolean',
        'track_inventory' => 'boolean',
        'has_variants' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    // Tax Relationships
    public function tax1(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_1_id');
    }

    public function tax2(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_2_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function productUnits(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }

    // ==================== VARIATION RELATIONSHIPS ====================

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductAttribute::class,
            'product_attribute_map',
            'product_id',
            'attribute_id'
        );
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function activeVariations(): HasMany
    {
        return $this->hasMany(ProductVariation::class)->where('is_active', true);
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSellable($query)
    {
        return $query->where('can_be_sold', true)->where('is_active', true);
    }

    public function scopePurchasable($query)
    {
        return $query->where('can_be_purchased', true)->where('is_active', true);
    }

    public function scopeWithVariants($query)
    {
        return $query->where('has_variants', true);
    }

    public function scopeWithoutVariants($query)
    {
        return $query->where('has_variants', false);
    }

    public function scopeTrackingInventory($query)
    {
        return $query->where('track_inventory', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('(SELECT COALESCE(SUM(qty), 0) FROM stock_levels WHERE stock_levels.product_id = products.id) < products.min_stock_level');
    }

    // ==================== ACCESSORS ====================

    public function getUnitShortNameAttribute(): string
    {
        return $this->unit?->short_name ?? 'PCS';
    }

    /**
     * Get current stock - FIXED VERSION
     * Only uses stock_levels table, no double counting
     */
    public function getCurrentStockAttribute(): float
    {
        if ($this->has_variants) {
            // For variant products: sum stock where variation_id is set
            return (float) $this->stockLevels()->whereNotNull('variation_id')->sum('qty');
        }
        
        // For non-variant products: sum stock where variation_id is null
        return (float) $this->stockLevels()->whereNull('variation_id')->sum('qty');
    }

    /**
     * Get stock at specific warehouse
     */
    public function getStockAtWarehouse(int $warehouseId): float
    {
        return (float) $this->stockLevels()
            ->where('warehouse_id', $warehouseId)
            ->when(!$this->has_variants, fn($q) => $q->whereNull('variation_id'))
            ->sum('qty');
    }

    /**
     * Get stock breakdown by warehouse
     */
    public function getStockByWarehouse(): array
    {
        return $this->stockLevels()
            ->selectRaw('warehouse_id, SUM(qty) as total_qty')
            ->when(!$this->has_variants, fn($q) => $q->whereNull('variation_id'))
            ->groupBy('warehouse_id')
            ->with('warehouse')
            ->get()
            ->mapWithKeys(fn($item) => [$item->warehouse_id => [
                'warehouse' => $item->warehouse,
                'qty' => (float) $item->total_qty,
            ]])
            ->toArray();
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->min_stock_level > 0 && $this->current_stock < $this->min_stock_level;
    }

    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first() 
            ?? $this->images()->first();
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        $image = $this->primary_image;
        return $image ? asset('storage/' . $image->image_path) : null;
    }

    // Tax Accessors
    public function getTax1NameAttribute(): ?string
    {
        return $this->tax1?->name;
    }

    public function getTax1RateAttribute(): float
    {
        return $this->tax1?->rate ?? 0;
    }

    public function getTax2NameAttribute(): ?string
    {
        return $this->tax2?->name;
    }

    public function getTax2RateAttribute(): float
    {
        return $this->tax2?->rate ?? 0;
    }

    public function getTotalTaxRateAttribute(): float
    {
        return $this->tax_1_rate + $this->tax_2_rate;
    }

    public function getCalculatedSalePriceAttribute(): float
    {
        if ($this->default_profit_rate > 0) {
            return $this->purchase_price * (1 + $this->default_profit_rate / 100);
        }
        return $this->sale_price;
    }

    // ==================== TAX METHODS ====================

    public function getTaxAmount(float $price): array
    {
        $tax1Rate = $this->tax_1_rate;
        $tax2Rate = $this->tax_2_rate;
        
        $tax1 = $tax1Rate > 0 ? $price * ($tax1Rate / 100) : 0;
        $tax2 = $tax2Rate > 0 ? $price * ($tax2Rate / 100) : 0;
        
        return [
            'tax_1' => [
                'name' => $this->tax_1_name,
                'rate' => $tax1Rate,
                'amount' => round($tax1, 2),
            ],
            'tax_2' => [
                'name' => $this->tax_2_name,
                'rate' => $tax2Rate,
                'amount' => round($tax2, 2),
            ],
            'total' => round($tax1 + $tax2, 2),
        ];
    }

    public function getPriceWithTax(float $price): float
    {
        $taxAmount = $this->getTaxAmount($price);
        return round($price + $taxAmount['total'], 2);
    }


    // ==================== UNIT METHODS ====================

    public function getAvailableUnits()
    {
        $units = collect([
            (object)[
                'id' => $this->unit_id,
                'unit_id' => $this->unit_id,
                'name' => $this->unit?->name,
                'short_name' => $this->unit?->short_name,
                'conversion_factor' => 1,
                'purchase_price' => $this->purchase_price,
                'sale_price' => $this->sale_price,
                'is_base' => true,
            ]
        ]);

        foreach ($this->productUnits as $pu) {
            $units->push((object)[
                'id' => $pu->id,
                'unit_id' => $pu->unit_id,
                'name' => $pu->unit_name ?? $pu->unit?->name,
                'short_name' => $pu->unit?->short_name,
                'conversion_factor' => $pu->conversion_factor,
                'purchase_price' => $pu->purchase_price ?? ($this->purchase_price * $pu->conversion_factor),
                'sale_price' => $pu->sale_price ?? ($this->sale_price * $pu->conversion_factor),
                'is_purchase_unit' => $pu->is_purchase_unit,
                'is_sale_unit' => $pu->is_sale_unit,
                'barcode' => $pu->barcode,
                'is_base' => false,
            ]);
        }

        return $units;
    }

    public function getPurchaseUnits()
    {
        return $this->getAvailableUnits()->filter(fn($u) => $u->is_base || $u->is_purchase_unit);
    }

    public function getSaleUnits()
    {
        return $this->getAvailableUnits()->filter(fn($u) => $u->is_base || $u->is_sale_unit);
    }

    public function convertToBaseUnit(float $qty, int $unitId): float
    {
        if ($unitId == $this->unit_id) {
            return $qty;
        }

        $productUnit = $this->productUnits()->where('unit_id', $unitId)->first();
        return $productUnit ? $qty * $productUnit->conversion_factor : $qty;
    }

    // ==================== VARIATION METHODS ====================

    /**
     * Get all attribute values available for this product
     */
    public function getAttributeValuesGrouped(): array
    {
        $result = [];
        
        foreach ($this->attributes()->with('values')->get() as $attribute) {
            $result[$attribute->id] = [
                'attribute' => $attribute,
                'values' => $attribute->values,
            ];
        }
        
        return $result;
    }

    /**
     * Generate all possible variation combinations
     */
    public function generateVariationCombinations(): array
    {
        $attributes = $this->getAttributeValuesGrouped();
        
        if (empty($attributes)) {
            return [];
        }

        $combinations = [[]];
        
        foreach ($attributes as $attrId => $data) {
            $newCombinations = [];
            foreach ($combinations as $combination) {
                foreach ($data['values'] as $value) {
                    $newCombinations[] = array_merge($combination, [$attrId => $value->id]);
                }
            }
            $combinations = $newCombinations;
        }
        
        return $combinations;
    }

    /**
     * Create variations from combinations
     */
    public function createVariationsFromCombinations(array $combinations = null): array
    {
        $combinations = $combinations ?? $this->generateVariationCombinations();
        $created = [];
        
        foreach ($combinations as $combo) {
            $valueIds = array_values($combo);
            $sku = ProductVariation::generateSku($this, $valueIds);
            
            // Skip if variation already exists
            if ($this->variations()->where('sku', $sku)->exists()) {
                continue;
            }
            
            $variation = $this->variations()->create([
                'sku' => $sku,
                'purchase_price' => null, // Use parent price
                'sale_price' => null,
                'is_active' => true,
            ]);
            
            $variation->syncAttributeValues($valueIds);
            $created[] = $variation;
        }
        
        return $created;
    }

    /**
     * Find variation by attribute values
     */
    public function findVariation(array $attributeValueIds): ?ProductVariation
    {
        $valueIds = collect($attributeValueIds)->sort()->values()->toArray();
        
        foreach ($this->variations as $variation) {
            $varValueIds = $variation->attributeValues()->pluck('attribute_values.id')->sort()->values()->toArray();
            if ($varValueIds === $valueIds) {
                return $variation;
            }
        }
        
        return null;
    }

    /**
     * Get sellable items (product itself if no variants, or variations)
     */
    public function getSellableItems()
    {
        if ($this->has_variants) {
            return $this->activeVariations;
        }
        return collect([$this]);
    }

    // ==================== IMAGE METHODS ====================

    /**
     * Get all image URLs
     */
    public function getImageUrls(): array
    {
        return $this->images->map(fn($img) => $img->url)->toArray();
    }

    /**
     * Get image count
     */
    public function getImageCountAttribute(): int
    {
        return $this->images()->count();
    }

    /**
     * Ensure product has a primary image set
     */
    public function ensurePrimaryImage(): void
    {
        ProductImage::ensurePrimaryImage($this->id);
    }

    // ==================== TAG METHODS ====================

    public static function syncProductTags(Product $product, $tags): void
    {
        if (is_string($tags)) {
            $tags = array_map('trim', explode(',', $tags));
        }
        
        $tagIds = [];
        foreach ($tags as $tagName) {
            if (empty($tagName)) continue;
            $tag = Tag::findOrCreateByName($tagName);
            $tagIds[] = $tag->id;
        }
        
        $product->tags()->sync($tagIds);
    }

    // ==================== BOOT ====================

    protected static function boot()
    {
        parent::boot();

        // After deleting, clean up related data
        static::deleting(function ($product) {
            // Delete images with files
            foreach ($product->images as $image) {
                $image->deleteWithFile();
            }
        });
    }
}