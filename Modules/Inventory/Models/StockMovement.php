<?php

namespace Modules\Inventory\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * StockMovement Model - Records all stock changes
 * 
 * Every stock change creates a movement record for audit trail
 * 
 * Movement Types:
 * - IN: Stock received (purchase, opening)
 * - OUT: Stock delivered (sale, issue)
 * - TRANSFER: Stock moved between locations
 * - ADJUSTMENT: Manual stock correction
 * - RETURN: Customer return / supplier return
 * 
 * IMPORTANT:
 * - qty = quantity in TRANSACTION unit (e.g., 10 bags)
 * - base_qty = quantity in BASE unit (e.g., 50 KG if bag = 5 KG)
 */
class StockMovement extends Model
{
    protected $fillable = [
        'reference_no',
        'product_id',
        'variation_id',
        'warehouse_id',
        'rack_id',
        'lot_id',
        'unit_id',            // Transaction unit (could be "5 KG Bag")
        'qty',                // Quantity in transaction unit
        'base_qty',           // Quantity in base unit
        'stock_before',       // Stock before this movement (in base unit)
        'stock_after',        // Stock after this movement (in base unit)
        'purchase_price',     // Price at time of movement
        'movement_type',      // IN, OUT, TRANSFER, ADJUSTMENT, RETURN
        'reference_type',     // PURCHASE, SALE, TRANSFER, ADJUSTMENT, RETURN, OPENING
        'reference_id',       // Related record ID (invoice, PO, etc.)
        'reason',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'base_qty' => 'decimal:3',
        'stock_before' => 'decimal:3',
        'stock_after' => 'decimal:3',
        'purchase_price' => 'decimal:2',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Get related stock transfer (for TRANSFER movements)
     */
    public function stockTransfer(): BelongsTo
    {
        return $this->belongsTo(StockTransfer::class, 'reference_no', 'transfer_no');
    }

    // ==================== ACCESSORS ====================
    
    /**
     * Check if movement is positive (increases stock)
     */
    public function getIsPositiveAttribute(): bool
    {
        return in_array($this->movement_type, ['IN', 'RETURN']);
    }

    /**
     * Get signed quantity display
     */
    public function getSignedQtyAttribute(): string
    {
        $sign = $this->is_positive ? '+' : '-';
        return $sign . number_format($this->base_qty ?? $this->qty, 2);
    }

    /**
     * Get full quantity display with unit conversion info
     */
    public function getQtyDisplayAttribute(): string
    {
        $unitName = $this->unit->short_name ?? 'PCS';
        $baseUnitName = $this->product->unit->short_name ?? 'PCS';
        
        $display = number_format($this->qty, 2) . ' ' . $unitName;
        
        // Show base qty if different
        if ($this->qty != $this->base_qty && $this->base_qty) {
            $display .= ' (= ' . number_format($this->base_qty, 2) . ' ' . $baseUnitName . ')';
        }
        
        return $display;
    }

    /**
     * Get movement type badge color
     */
    public function getTypeBadgeColorAttribute(): string
    {
        return match($this->movement_type) {
            'IN' => 'success',
            'OUT' => 'danger',
            'TRANSFER' => 'info',
            'ADJUSTMENT' => 'warning',
            'RETURN' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get location display
     */
    public function getLocationDisplayAttribute(): string
    {
        $location = $this->warehouse->name ?? 'Unknown';
        
        if ($this->rack) {
            $location .= ' (' . $this->rack->code . ')';
        }
        
        return $location;
    }

    // ==================== STATIC METHODS ====================
    
    /**
     * Generate reference number
     * 
     * @param string $type Prefix (RCV, DLV, TRF, ADJ, RET, STK)
     * @return string
     */
    public static function generateReferenceNo(string $type = 'STK'): string
    {
        $prefix = $type . '-' . date('Ymd') . '-';
        
        $last = self::where('reference_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();
        
        $num = $last ? ((int) substr($last->reference_no, -4) + 1) : 1;
        
        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create a stock IN movement
     */
    public static function createIn(array $data): self
    {
        return self::create(array_merge($data, [
            'movement_type' => 'IN',
        ]));
    }

    /**
     * Create a stock OUT movement
     */
    public static function createOut(array $data): self
    {
        return self::create(array_merge($data, [
            'movement_type' => 'OUT',
        ]));
    }

    // ==================== SCOPES ====================
    
    public function scopeInMovements($query)
    {
        return $query->where('movement_type', 'IN');
    }

    public function scopeOutMovements($query)
    {
        return $query->where('movement_type', 'OUT');
    }

    public function scopeTransfers($query)
    {
        return $query->where('movement_type', 'TRANSFER');
    }

    public function scopeAdjustments($query)
    {
        return $query->where('movement_type', 'ADJUSTMENT');
    }

    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeForWarehouse($query, int $warehouseId)
    {
        return $query->where('warehouse_id', $warehouseId);
    }

    public function scopeForLot($query, int $lotId)
    {
        return $query->where('lot_id', $lotId);
    }

    public function scopeDateRange($query, string $from, string $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}