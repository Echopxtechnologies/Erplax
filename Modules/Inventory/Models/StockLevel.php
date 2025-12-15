<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * StockLevel Model - Tracks stock quantities
 * 
 * IMPORTANT: Stock is ALWAYS stored in BASE UNITS
 * 
 * Example: Product "Rice" (base unit: KG)
 * - If stock shows 100, it means 100 KG
 * - If user receives "20 × 5 KG Bag", qty increases by 100 (20 × 5)
 * 
 * Stock is tracked per:
 * - Product (required)
 * - Variation (optional - for products with variants like Size/Color)
 * - Warehouse (required)
 * - Rack (optional)
 * - Lot (optional - for batch-managed products)
 */
class StockLevel extends Model
{
    protected $fillable = [
        'product_id',
        'variation_id',
        'warehouse_id',
        'rack_id',
        'lot_id',
        'unit_id',        // Base unit ID
        'qty',            // Quantity in base units
        'reserved_qty',   // Reserved for pending orders
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'reserved_qty' => 'decimal:3',
    ];

    // ==================== RELATIONSHIPS ====================
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class);
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

    // ==================== ACCESSORS ====================
    
    /**
     * Get available quantity (total - reserved)
     */
    public function getAvailableQtyAttribute(): float
    {
        return max(0, ($this->qty ?? 0) - ($this->reserved_qty ?? 0));
    }

    /**
     * Get formatted quantity with unit
     */
    public function getQtyDisplayAttribute(): string
    {
        $unitName = $this->unit->short_name ?? $this->product->unit->short_name ?? 'PCS';
        return number_format($this->qty, 2) . ' ' . $unitName;
    }

    /**
     * Get location display
     */
    public function getLocationDisplayAttribute(): string
    {
        $location = $this->warehouse->name ?? 'Unknown';
        
        if ($this->rack) {
            $location .= ' → ' . $this->rack->code;
        }
        
        return $location;
    }

    // ==================== STATIC METHODS ====================
    
    /**
     * Update or create stock level (add or subtract)
     * 
     * @param int $productId
     * @param int $warehouseId
     * @param int|null $rackId
     * @param int|null $lotId
     * @param int $unitId Base unit ID
     * @param float $qty Quantity to add/subtract (in base units)
     * @param string $type 'add' or 'subtract'
     * @param int|null $variationId
     * @return self
     */
    public static function updateStock(
        int $productId,
        int $warehouseId,
        ?int $rackId,
        ?int $lotId,
        int $unitId,
        float $qty,
        string $type = 'add',
        ?int $variationId = null
    ): self {
        $stockLevel = self::firstOrCreate([
            'product_id' => $productId,
            'variation_id' => $variationId,
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
     * Get available stock for a product
     * 
     * @param int $productId
     * @param int|null $warehouseId
     * @param int|null $rackId
     * @param int|null $lotId
     * @param int|null $variationId
     * @return float Stock in base units
     */
    public static function getAvailableStock(
        int $productId,
        ?int $warehouseId = null,
        ?int $rackId = null,
        ?int $lotId = null,
        ?int $variationId = null
    ): float {
        $query = self::where('product_id', $productId);

        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }

        if ($rackId) {
            $query->where('rack_id', $rackId);
        }

        if ($lotId) {
            $query->where('lot_id', $lotId);
        }

        if ($variationId) {
            $query->where('variation_id', $variationId);
        }

        return (float) ($query->sum(DB::raw('qty - reserved_qty')) ?? 0);
    }

    /**
     * Get total stock (including reserved)
     */
    public static function getTotalStock(
        int $productId,
        ?int $warehouseId = null,
        ?int $rackId = null,
        ?int $lotId = null,
        ?int $variationId = null
    ): float {
        $query = self::where('product_id', $productId);

        if ($warehouseId) {
            $query->where('warehouse_id', $warehouseId);
        }

        if ($rackId) {
            $query->where('rack_id', $rackId);
        }

        if ($lotId) {
            $query->where('lot_id', $lotId);
        }

        if ($variationId) {
            $query->where('variation_id', $variationId);
        }

        return (float) ($query->sum('qty') ?? 0);
    }

    /**
     * Reserve stock for an order
     */
    public static function reserveStock(
        int $productId,
        int $warehouseId,
        float $qty,
        ?int $rackId = null,
        ?int $lotId = null,
        ?int $variationId = null
    ): bool {
        $stockLevel = self::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->when($rackId, fn($q) => $q->where('rack_id', $rackId))
            ->when($lotId, fn($q) => $q->where('lot_id', $lotId))
            ->when($variationId, fn($q) => $q->where('variation_id', $variationId))
            ->first();

        if (!$stockLevel || $stockLevel->available_qty < $qty) {
            return false;
        }

        $stockLevel->reserved_qty += $qty;
        $stockLevel->save();
        
        return true;
    }

    /**
     * Release reserved stock
     */
    public static function releaseReservedStock(
        int $productId,
        int $warehouseId,
        float $qty,
        ?int $rackId = null,
        ?int $lotId = null,
        ?int $variationId = null
    ): bool {
        $stockLevel = self::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->when($rackId, fn($q) => $q->where('rack_id', $rackId))
            ->when($lotId, fn($q) => $q->where('lot_id', $lotId))
            ->when($variationId, fn($q) => $q->where('variation_id', $variationId))
            ->first();

        if (!$stockLevel) {
            return false;
        }

        $stockLevel->reserved_qty = max(0, $stockLevel->reserved_qty - $qty);
        $stockLevel->save();
        
        return true;
    }

    /**
     * Get stock by warehouse (summary)
     */
    public static function getStockByWarehouse(int $productId, ?int $variationId = null): array
    {
        $query = self::where('product_id', $productId)
            ->when($variationId, fn($q) => $q->where('variation_id', $variationId))
            ->select('warehouse_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(reserved_qty) as total_reserved'))
            ->groupBy('warehouse_id')
            ->with('warehouse');

        return $query->get()->map(function ($item) {
            return [
                'warehouse_id' => $item->warehouse_id,
                'warehouse_name' => $item->warehouse->name ?? 'Unknown',
                'warehouse_code' => $item->warehouse->code ?? '',
                'qty' => (float) $item->total_qty,
                'reserved_qty' => (float) $item->total_reserved,
                'available_qty' => (float) ($item->total_qty - $item->total_reserved),
            ];
        })->toArray();
    }

    /**
     * Get stock by lot (for batch-managed products)
     */
    public static function getStockByLot(int $productId, ?int $warehouseId = null): array
    {
        $query = self::where('product_id', $productId)
            ->whereNotNull('lot_id')
            ->when($warehouseId, fn($q) => $q->where('warehouse_id', $warehouseId))
            ->select('lot_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(reserved_qty) as total_reserved'))
            ->groupBy('lot_id')
            ->with('lot');

        return $query->get()->map(function ($item) {
            return [
                'lot_id' => $item->lot_id,
                'lot_no' => $item->lot->lot_no ?? 'Unknown',
                'batch_no' => $item->lot->batch_no ?? '',
                'expiry_date' => $item->lot->expiry_date?->format('Y-m-d'),
                'qty' => (float) $item->total_qty,
                'reserved_qty' => (float) $item->total_reserved,
                'available_qty' => (float) ($item->total_qty - $item->total_reserved),
            ];
        })->toArray();
    }

    /**
     * Check if product is low on stock
     */
    public static function isLowStock(int $productId): bool
    {
        $product = Product::find($productId);
        if (!$product) return false;
        
        $totalStock = self::getTotalStock($productId);
        return $totalStock <= $product->min_stock_level;
    }

    /**
     * Get low stock products
     */
    public static function getLowStockProducts(): \Illuminate\Support\Collection
    {
        return Product::where('is_active', true)
            ->where('track_inventory', true)
            ->where('min_stock_level', '>', 0)
            ->get()
            ->filter(function ($product) {
                $stock = self::getTotalStock($product->id);
                return $stock <= $product->min_stock_level;
            });
    }

    /**
     * Convert stock to different unit
     * 
     * @param float $baseQty Quantity in base units
     * @param Product $product
     * @param int $targetUnitId
     * @return array ['qty' => float, 'unit_name' => string]
     */
    public static function convertToUnit(float $baseQty, Product $product, int $targetUnitId): array
    {
        // If target is base unit
        if ($targetUnitId == $product->unit_id) {
            return [
                'qty' => $baseQty,
                'unit_name' => $product->unit->short_name ?? 'PCS',
            ];
        }

        // Check product units
        $productUnit = ProductUnit::where('product_id', $product->id)
            ->where('unit_id', $targetUnitId)
            ->first();

        if ($productUnit && $productUnit->conversion_factor > 0) {
            return [
                'qty' => $baseQty / $productUnit->conversion_factor,
                'unit_name' => $productUnit->unit_name ?: ($productUnit->unit->short_name ?? 'PCS'),
            ];
        }

        // Fallback to base unit
        return [
            'qty' => $baseQty,
            'unit_name' => $product->unit->short_name ?? 'PCS',
        ];
    }
}