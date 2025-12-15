<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rack extends Model
{
    protected $fillable = [
        'warehouse_id',
        'code',
        'name',
        'zone',
        'aisle',
        'level',
        'max_capacity',        // Changed from 'capacity'
        'capacity_unit_id',
        'max_weight',          // Added
        'description',
        'is_active',
    ];

    protected $casts = [
        'max_capacity' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function capacityUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'capacity_unit_id');
    }

    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get full location path (Zone › Aisle › Level)
     */
    public function getFullLocationAttribute(): string
    {
        $parts = array_filter([
            $this->zone,
            $this->aisle ? "Aisle {$this->aisle}" : null,
            $this->level ? "Level {$this->level}" : null,
        ]);
        return $parts ? implode(' › ', $parts) : '-';
    }

    /**
     * Get full name with warehouse
     */
    public function getFullNameAttribute(): string
    {
        return ($this->warehouse?->name ?? 'Unknown') . ' > ' . $this->name . ' (' . $this->code . ')';
    }

    /**
     * Get capacity display with unit
     */
    public function getCapacityDisplayAttribute(): ?string
    {
        if (!$this->max_capacity) return null;
        
        $unit = $this->capacityUnit?->short_name ?? '';
        return number_format($this->max_capacity, 2) . ($unit ? ' ' . $unit : '');
    }

    /**
     * Get weight display
     */
    public function getWeightDisplayAttribute(): ?string
    {
        if (!$this->max_weight) return null;
        return number_format($this->max_weight, 2) . ' kg';
    }

    // ==================== METHODS ====================

    /**
     * Get current stock in this rack
     */
    public function getCurrentStock($productId = null): float
    {
        $query = $this->stockLevels();
        
        if ($productId) {
            $query->where('product_id', $productId);
        }
        
        return $query->sum('qty') ?? 0;
    }

    /**
     * Check if rack has any stock
     */
    public function hasStock(): bool
    {
        return $this->stockLevels()->where('qty', '>', 0)->exists();
    }

    /**
     * Get stock count (number of different products)
     */
    public function getStockCountAttribute(): int
    {
        return $this->stockLevels()->where('qty', '>', 0)->count();
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInWarehouse($query, $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeInZone($query, $zone)
    {
        return $query->where('zone', $zone);
    }
}