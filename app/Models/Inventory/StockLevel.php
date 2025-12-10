<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockLevel extends Model
{
    protected $fillable = [
        'product_id',
        'warehouse_id',
        'rack_id',
        'lot_id',
        'unit_id',
        'qty',
        'reserved_qty',
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'reserved_qty' => 'decimal:3',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function rack(): BelongsTo
    {
        return $this->belongsTo(Rack::class);
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get available quantity
     */
    public function getAvailableQtyAttribute(): float
    {
        return $this->qty - $this->reserved_qty;
    }

    /**
     * Update or create stock level
     */
    public static function updateStock($productId, $warehouseId, $rackId, $lotId, $unitId, $qty, $type = 'add'): self
    {
        $stockLevel = self::firstOrCreate([
            'product_id' => $productId,
            'warehouse_id' => $warehouseId,
            'rack_id' => $rackId,
            'lot_id' => $lotId,
        ], [
            'unit_id' => $unitId,
            'qty' => 0,
            'reserved_qty' => 0,
        ]);

        if ($type === 'add') {
            $stockLevel->qty += $qty;
        } else {
            $stockLevel->qty -= $qty;
        }

        // Prevent negative stock
        if ($stockLevel->qty < 0) {
            $stockLevel->qty = 0;
        }

        $stockLevel->save();
        
        return $stockLevel;
    }

    /**
     * Get available stock for a product in a warehouse
     */
    public static function getAvailableStock($productId, $warehouseId, $rackId = null, $lotId = null): float
    {
        $query = self::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId);

        if ($rackId) {
            $query->where('rack_id', $rackId);
        }

        if ($lotId) {
            $query->where('lot_id', $lotId);
        }

        return $query->sum(\DB::raw('qty - reserved_qty')) ?? 0;
    }
}