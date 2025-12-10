<?php

namespace App\Models\Inventory;

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
        'capacity',
        'capacity_unit_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'capacity' => 'decimal:3',
        'is_active' => 'boolean',
    ];

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

    /**
     * Get full location path
     */
    public function getFullLocationAttribute(): string
    {
        $parts = array_filter([
            $this->zone,
            $this->aisle,
            $this->name,
            $this->level
        ]);
        return implode(' › ', $parts);
    }

    /**
     * Get full name with warehouse
     */
    public function getFullNameAttribute(): string
    {
        return $this->warehouse->name . ' > ' . $this->name . ' (' . $this->code . ')';
    }

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
     * Scope for active racks
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}