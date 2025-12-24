<?php

namespace Modules\Website\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'brand_id',
        'unit_id',
        'name',
        'sku',
        'barcode',
        'hsn_code',
        'description',
        'short_description',
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
        'is_active' => 'boolean',
        'can_be_sold' => 'boolean',
        'has_variants' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id')->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->where('is_primary', 1);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id');
    }

    public function activeVariations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id')->where('is_active', true);
    }

    public function attributes()
    {
        return $this->belongsToMany(
            ProductAttribute::class,
            'product_attribute_map',
            'product_id',
            'attribute_id'
        );
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id')->where('status', 'approved')->orderBy('created_at', 'desc');
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    /**
     * Get review count
     */
    public function getReviewCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Get rating breakdown (count per star)
     */
    public function getRatingBreakdown(): array
    {
        $breakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $breakdown[$i] = $this->approvedReviews()->where('rating', $i)->count();
        }
        return $breakdown;
    }

    // ==================== IMAGE METHODS ====================

    public function getPrimaryImageUrl(): ?string
    {
        try {
            if (!Schema::hasTable('product_images')) {
                return null;
            }
            
            $image = $this->primaryImage;
            if ($image && $image->image_path) {
                return asset('storage/' . $image->image_path);
            }
            
            $firstImage = $this->images()->first();
            if ($firstImage && $firstImage->image_path) {
                return asset('storage/' . $firstImage->image_path);
            }
        } catch (\Exception $e) {
            return null;
        }
        
        return null;
    }

    public function getAllImages(): array
    {
        try {
            return $this->images->map(function($img) {
                return [
                    'id' => $img->id,
                    'url' => asset('storage/' . $img->image_path),
                    'is_primary' => $img->is_primary,
                    'variation_id' => $img->variation_id,
                ];
            })->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get images for a specific variation (by color)
     */
    public function getImagesForVariation(?int $variationId): array
    {
        try {
            if (!$variationId) {
                return $this->getAllImages();
            }
            
            // Get variation-specific images
            $varImages = $this->images()->where('variation_id', $variationId)->get();
            
            if ($varImages->isEmpty()) {
                // Fallback to product images
                return $this->getAllImages();
            }
            
            return $varImages->map(function($img) {
                return [
                    'id' => $img->id,
                    'url' => asset('storage/' . $img->image_path),
                    'is_primary' => $img->is_primary,
                    'variation_id' => $img->variation_id,
                ];
            })->toArray();
        } catch (\Exception $e) {
            return $this->getAllImages();
        }
    }

    // ==================== PRICING METHODS ====================

    public function getDiscountPercent(): ?int
    {
        if ($this->mrp && $this->sale_price && $this->mrp > $this->sale_price) {
            return round((($this->mrp - $this->sale_price) / $this->mrp) * 100);
        }
        return null;
    }

    /**
     * Get price range for variant products
     */
    public function getPriceRange(): array
    {
        if (!$this->has_variants) {
            return [
                'min' => $this->sale_price,
                'max' => $this->sale_price,
                'has_range' => false,
            ];
        }

        try {
            $variations = $this->activeVariations;
            
            if ($variations->isEmpty()) {
                return [
                    'min' => $this->sale_price,
                    'max' => $this->sale_price,
                    'has_range' => false,
                ];
            }

            $prices = $variations->map(function($v) {
                return $v->sale_price ?? $this->sale_price;
            })->filter();

            $min = $prices->min() ?? $this->sale_price;
            $max = $prices->max() ?? $this->sale_price;

            return [
                'min' => $min,
                'max' => $max,
                'has_range' => $min != $max,
            ];
        } catch (\Exception $e) {
            return [
                'min' => $this->sale_price,
                'max' => $this->sale_price,
                'has_range' => false,
            ];
        }
    }

    // ==================== STOCK METHODS ====================

    /**
     * Get current stock from stock_levels table
     */
    public function getCurrentStock(): float
    {
        try {
            // Try stock_levels first (new system)
            if (Schema::hasTable('stock_levels')) {
                if ($this->has_variants) {
                    // For variant products: sum all variation stocks
                    return (float) DB::table('stock_levels')
                        ->where('product_id', $this->id)
                        ->whereNotNull('variation_id')
                        ->sum('qty');
                }
                
                // For non-variant products
                $stock = DB::table('stock_levels')
                    ->where('product_id', $this->id)
                    ->whereNull('variation_id')
                    ->sum('qty');
                
                if ($stock > 0) {
                    return (float) $stock;
                }
            }
            
            // Fallback to stock_movements
            if (Schema::hasTable('stock_movements')) {
                $lastMovement = DB::table('stock_movements')
                    ->where('product_id', $this->id)
                    ->whereNull('variation_id')
                    ->orderBy('id', 'desc')
                    ->first();
                
                return $lastMovement ? (float) $lastMovement->stock_after : 0;
            }
            
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get stock for a specific variation
     */
    public function getVariationStock(int $variationId): float
    {
        try {
            if (Schema::hasTable('stock_levels')) {
                return (float) DB::table('stock_levels')
                    ->where('variation_id', $variationId)
                    ->sum('qty');
            }
            
            // Fallback to variation's stock_qty
            $variation = $this->variations()->find($variationId);
            return $variation ? (float) ($variation->stock_qty ?? 0) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get stock status
     */
    public function getStockStatus(): string
    {
        $stock = $this->getCurrentStock();
        $minLevel = $this->min_stock_level ?? 5;
        
        if ($stock <= 0) {
            return 'out_of_stock';
        } elseif ($stock <= $minLevel) {
            return 'low_stock';
        }
        
        return 'in_stock';
    }

    /**
     * Get stock label
     */
    public function getStockLabel(): string
    {
        $status = $this->getStockStatus();
        $stock = $this->getCurrentStock();
        
        switch ($status) {
            case 'out_of_stock':
                return 'Out of Stock';
            case 'low_stock':
                return 'Low Stock (' . $this->formatQty($stock) . ' left)';
            default:
                return 'In Stock';
        }
    }

    public function isInStock(): bool
    {
        return $this->getCurrentStock() > 0;
    }

    /**
     * Check if any variation is in stock
     */
    public function hasAnyVariationInStock(): bool
    {
        if (!$this->has_variants) {
            return $this->isInStock();
        }

        foreach ($this->activeVariations as $variation) {
            if ($variation->getCurrentStock() > 0) {
                return true;
            }
        }
        
        return false;
    }

    // ==================== UNIT METHODS ====================

    public function getUnitName(): string
    {
        try {
            if ($this->unit_id && Schema::hasTable('units')) {
                $unit = DB::table('units')->where('id', $this->unit_id)->first();
                return $unit ? ($unit->short_name ?? $unit->name ?? 'pcs') : 'pcs';
            }
        } catch (\Exception $e) {
            // ignore
        }
        return 'pcs';
    }

    /**
     * Check if unit allows decimals
     */
    public function allowsDecimal(): bool
    {
        $unit = strtolower($this->getUnitName());
        $noDecimalUnits = [
            'pcs', 'pc', 'piece', 'pieces', 
            'box', 'boxes', 
            'pack', 'packs', 
            'unit', 'units', 
            'nos', 'no', 
            'ea', 'each', 
            'pair', 'pairs', 
            'set', 'sets', 
            'dozen', 'doz', 'dzn',
            'bottle', 'bottles', 
            'can', 'cans',
            'bag', 'bags', 
            'roll', 'rolls', 
            'sheet', 'sheets', 
            'bundle', 'bundles'
        ];
        return !in_array($unit, $noDecimalUnits);
    }

    /**
     * Format quantity based on unit
     */
    public function formatQty($qty): string
    {
        if ($this->allowsDecimal()) {
            return rtrim(rtrim(number_format((float)$qty, 2), '0'), '.');
        }
        return (int) $qty . '';
    }

    /**
     * Format price (no decimals if whole number)
     */
    public static function formatPrice($price): string
    {
        $price = (float) $price;
        if ($price == floor($price)) {
            return number_format($price, 0);
        }
        return number_format($price, 2);
    }

    // ==================== VARIATION METHODS ====================

    /**
     * Get grouped attributes with values for this product
     */
    public function getAttributesWithValues(): array
    {
        if (!$this->has_variants) {
            return [];
        }

        try {
            $result = [];
            
            // Get all attribute values used by this product's variations
            $attributeIds = DB::table('product_attribute_map')
                ->where('product_id', $this->id)
                ->pluck('attribute_id')
                ->toArray();
            
            if (empty($attributeIds)) {
                return [];
            }

            $attributes = ProductAttribute::whereIn('id', $attributeIds)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            foreach ($attributes as $attribute) {
                // Get values used by active variations
                $valueIds = DB::table('variation_attribute_values as vav')
                    ->join('product_variations as pv', 'pv.id', '=', 'vav.variation_id')
                    ->where('pv.product_id', $this->id)
                    ->where('pv.is_active', true)
                    ->join('attribute_values as av', 'av.id', '=', 'vav.attribute_value_id')
                    ->where('av.attribute_id', $attribute->id)
                    ->pluck('av.id')
                    ->unique()
                    ->toArray();

                $values = AttributeValue::whereIn('id', $valueIds)
                    ->orderBy('sort_order')
                    ->get();

                if ($values->isNotEmpty()) {
                    $result[] = [
                        'attribute' => $attribute,
                        'values' => $values,
                        'is_color' => $attribute->isColor(),
                        'is_size' => $attribute->isSize(),
                    ];
                }
            }

            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Find variation by selected attribute values
     */
    public function findVariation(array $selectedValues): ?ProductVariation
    {
        try {
            $valueIds = array_values($selectedValues);
            sort($valueIds);
            
            foreach ($this->activeVariations as $variation) {
                $varValueIds = $variation->attributeValues()->pluck('attribute_values.id')->toArray();
                sort($varValueIds);
                
                if ($varValueIds === $valueIds) {
                    return $variation;
                }
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get variations data as JSON for frontend
     */
    public function getVariationsJson(): string
    {
        if (!$this->has_variants) {
            return '[]';
        }

        try {
            $data = $this->activeVariations->map(function($v) {
                return [
                    'id' => $v->id,
                    'sku' => $v->sku,
                    'name' => $v->getDisplayName(),
                    'price' => (float) ($v->sale_price ?? $this->sale_price),
                    'mrp' => (float) ($v->mrp ?? $this->mrp),
                    'stock' => $v->getCurrentStock(),
                    'in_stock' => $v->isInStock(),
                    'image' => $v->getImageUrl(),
                    'attributes' => $v->getAttributeValuesArray(),
                ];
            });

            return $data->toJson();
        } catch (\Exception $e) {
            return '[]';
        }
    }
}
