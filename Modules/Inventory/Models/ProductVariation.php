<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'barcode',
        'variation_name',
        'purchase_price',
        'sale_price',
        'mrp',
        'image_path',
        'stock_qty',
        'is_active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'mrp' => 'decimal:2',
        'stock_qty' => 'decimal:3',
        'is_active' => 'boolean',
    ];

    /**
     * Get variation image URL
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        
        // Check product images linked to this variation
        $varImage = ProductImage::where('product_id', $this->product_id)
            ->where('variation_id', $this->id)
            ->first();
        if ($varImage) return $varImage->url;
        
        // Fallback to parent product image
        return $this->product?->primary_image_url ?? asset('images/no-image.png');
    }

    // ==================== RELATIONSHIPS ====================

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'variation_attribute_values',
            'variation_id',
            'attribute_value_id'
        )->with('attribute');
    }

    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class, 'variation_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'variation_id');
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class, 'variation_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'variation_id');
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get effective purchase price (variation price or fallback to product price)
     */
    public function getEffectivePurchasePriceAttribute(): float
    {
        return $this->purchase_price ?? $this->product->purchase_price ?? 0;
    }

    /**
     * Get effective sale price
     */
    public function getEffectiveSalePriceAttribute(): float
    {
        return $this->sale_price ?? $this->product->sale_price ?? 0;
    }

    /**
     * Get effective MRP
     */
    public function getEffectiveMrpAttribute(): ?float
    {
        return $this->mrp ?? $this->product->mrp;
    }

    /**
     * Get current stock across all warehouses
     */
    public function getCurrentStockAttribute(): float
    {
        return $this->stockLevels()->sum('qty') ?? $this->stock_qty ?? 0;
    }

    /**
     * Get display name with attributes
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->variation_name) {
            return $this->variation_name;
        }

        $parts = [];
        foreach ($this->attributeValues as $attrValue) {
            $parts[] = $attrValue->value;
        }
        
        return implode(' / ', $parts);
    }

    /**
     * Full name including product name
     */
    public function getFullNameAttribute(): string
    {
        return $this->product->name . ' - ' . $this->display_name;
    }

    // ==================== HELPERS ====================

    /**
     * Generate variation name from attribute values
     */
    public function generateVariationName(): string
    {
        $parts = [];
        foreach ($this->attributeValues()->orderBy('attribute_id')->get() as $attrValue) {
            $parts[] = $attrValue->value;
        }
        
        return implode(' / ', $parts);
    }

    /**
     * Update variation name from attribute values
     */
    public function updateVariationName(): self
    {
        $this->variation_name = $this->generateVariationName();
        $this->save();
        return $this;
    }

    /**
     * Generate SKU from product SKU and attribute values
     */
    public static function generateSku(Product $product, array $attributeValueIds): string
    {
        $values = AttributeValue::whereIn('id', $attributeValueIds)
            ->orderBy('attribute_id')
            ->pluck('slug')
            ->toArray();
        
        $suffix = strtoupper(implode('-', $values));
        return $product->sku . '-' . $suffix;
    }

    /**
     * Check if variation has specific attribute value
     */
    public function hasAttributeValue(int $attributeValueId): bool
    {
        return $this->attributeValues()->where('attribute_value_id', $attributeValueId)->exists();
    }



    /**
     * Sync attribute values
     */
    public function syncAttributeValues(array $attributeValueIds): self
    {
        $this->attributeValues()->sync($attributeValueIds);
        $this->updateVariationName();
        return $this;
    }
}