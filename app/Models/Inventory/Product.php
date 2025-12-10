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
        'unit_id',
        'name',
        'sku',
        'barcode',
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
        'min_stock_level' => 'decimal:3',
        'max_stock_level' => 'decimal:3',
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

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function productUnits(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class);
    }

    /**
     * Get unit short name for display
     */
    public function getUnitNameAttribute(): string
    {
        return $this->unit->short_name ?? 'PCS';
    }

    /**
     * Get current stock (calculated from stock_levels table)
     */
    public function getCurrentStockAttribute(): float
    {
        return $this->stockLevels()->sum('qty') ?? 0;
    }

    /**
     * Get stock by warehouse
     */
    public function getStockByWarehouse($warehouseId): float
    {
        return $this->stockLevels()
            ->where('warehouse_id', $warehouseId)
            ->sum('qty') ?? 0;
    }

    /**
     * Get stock by warehouse and rack
     */
    public function getStockByRack($warehouseId, $rackId): float
    {
        return $this->stockLevels()
            ->where('warehouse_id', $warehouseId)
            ->where('rack_id', $rackId)
            ->sum('qty') ?? 0;
    }

    /**
     * Check if stock is low
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->current_stock <= $this->min_stock_level;
    }
}