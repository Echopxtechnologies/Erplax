<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'state',
        'country',
        'contact_person',
        'phone',
        'type',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function racks(): HasMany
    {
        return $this->hasMany(Rack::class);
    }

    public function activeRacks(): HasMany
    {
        return $this->hasMany(Rack::class)->where('is_active', true);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function stockLevels(): HasMany
    {
        return $this->hasMany(StockLevel::class);
    }

    public static function getDefault(): ?self
    {
        return static::where('is_default', true)->where('is_active', true)->first();
    }

    /**
     * Get total stock value in this warehouse
     */
    public function getTotalStockValue(): float
    {
        return $this->stockLevels()
            ->join('products', 'stock_levels.product_id', '=', 'products.id')
            ->selectRaw('SUM(stock_levels.qty * products.purchase_price) as total')
            ->value('total') ?? 0;
    }
}