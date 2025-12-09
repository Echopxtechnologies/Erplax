<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'sku',
        'barcode',
        'unit',
        'purchase_price',
        'sale_price',
        'hsn_code',
        'min_stock_level',
        'max_stock_level',
        'is_batch_managed',
        'is_active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'min_stock_level' => 'integer',
        'max_stock_level' => 'integer',
        'is_batch_managed' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function getCurrentStockAttribute(): float
    {
        return $this->stockMovements()
            ->selectRaw("SUM(CASE WHEN movement_type IN ('IN', 'RETURN') THEN qty WHEN movement_type = 'OUT' THEN -qty ELSE qty END) as total")
            ->value('total') ?? 0;
    }
}