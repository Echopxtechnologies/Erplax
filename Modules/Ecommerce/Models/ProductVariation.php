<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductVariation extends Model
{
    protected $table = 'product_variations';

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
        'is_active' => 'boolean',
    ];

    /**
     * Get parent product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get attribute values for this variation
     */
    public function attributeValues()
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'variation_attribute_values',
            'variation_id',
            'attribute_value_id'
        );
    }

    /**
     * Get variation images
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'variation_id');
    }

    /**
     * Scope: Active variations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get effective sale price (variation or parent)
     */
    public function getEffectiveSalePrice(): float
    {
        return $this->sale_price ?? $this->product->sale_price ?? 0;
    }

    /**
     * Get effective MRP (variation or parent)
     */
    public function getEffectiveMrp(): ?float
    {
        return $this->mrp ?? $this->product->mrp;
    }

    /**
     * Get current stock from stock_levels table
     */
    public function getCurrentStock(): float
    {
        try {
            if (!Schema::hasTable('stock_levels')) {
                return $this->stock_qty ?? 0;
            }
            
            return (float) DB::table('stock_levels')
                ->where('variation_id', $this->id)
                ->sum('qty') ?: ($this->stock_qty ?? 0);
        } catch (\Exception $e) {
            return $this->stock_qty ?? 0;
        }
    }

    /**
     * Check if variation is in stock
     */
    public function isInStock(): bool
    {
        return $this->getCurrentStock() > 0;
    }

    /**
     * Get image URL (variation specific or fallback to product)
     */
    public function getImageUrl(): ?string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        
        // Check for variation-specific images
        $varImage = $this->images()->first();
        if ($varImage && $varImage->image_path) {
            return asset('storage/' . $varImage->image_path);
        }
        
        // Fallback to product image
        return $this->product->getPrimaryImageUrl();
    }

    /**
     * Get display name (e.g., "Black / XL")
     */
    public function getDisplayName(): string
    {
        if ($this->variation_name) {
            return $this->variation_name;
        }

        try {
            $values = $this->attributeValues()
                ->orderBy('attribute_id')
                ->pluck('value')
                ->toArray();
            
            return implode(' / ', $values) ?: 'Default';
        } catch (\Exception $e) {
            return 'Default';
        }
    }

    /**
     * Get attribute values as array [attribute_id => value_id]
     */
    public function getAttributeValuesArray(): array
    {
        try {
            return $this->attributeValues()
                ->get()
                ->pluck('id', 'attribute_id')
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Check if variation has specific attribute value
     */
    public function hasAttributeValue(int $valueId): bool
    {
        return $this->attributeValues()->where('attribute_value_id', $valueId)->exists();
    }
}
