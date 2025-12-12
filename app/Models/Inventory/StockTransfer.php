<?php

namespace App\Models\Inventory;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * StockTransfer Model - Records stock transfers between warehouses/racks
 * 
 * Transfer creates TWO stock movements:
 * 1. OUT movement from source location
 * 2. IN movement to destination location
 * 
 * Both movements share the same transfer_no
 */
class StockTransfer extends Model
{
    protected $fillable = [
        'transfer_no',
        'product_id',
        'variation_id',
        'lot_id',
        'unit_id',              // Transaction unit
        'from_warehouse_id',
        'to_warehouse_id',
        'from_rack_id',
        'to_rack_id',
        'qty',                  // Quantity in transaction unit
        'base_qty',             // Quantity in base unit
        'status',               // PENDING, IN_TRANSIT, COMPLETED, CANCELLED
        'reason',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'base_qty' => 'decimal:3',
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

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function fromRack(): BelongsTo
    {
        return $this->belongsTo(Rack::class, 'from_rack_id');
    }

    public function toRack(): BelongsTo
    {
        return $this->belongsTo(Rack::class, 'to_rack_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    // ==================== ACCESSORS ====================
    
    /**
     * Get from location display
     */
    public function getFromLocationAttribute(): string
    {
        $location = $this->fromWarehouse->name ?? 'Unknown';
        
        if ($this->fromRack) {
            $location .= ' (' . $this->fromRack->code . ')';
        }
        
        return $location;
    }

    /**
     * Get to location display
     */
    public function getToLocationAttribute(): string
    {
        $location = $this->toWarehouse->name ?? 'Unknown';
        
        if ($this->toRack) {
            $location .= ' (' . $this->toRack->code . ')';
        }
        
        return $location;
    }

    /**
     * Get full transfer description
     */
    public function getTransferDescriptionAttribute(): string
    {
        return "{$this->from_location} → {$this->to_location}";
    }

    /**
     * Check if transfer is between same warehouse (rack transfer)
     */
    public function getIsSameWarehouseAttribute(): bool
    {
        return $this->from_warehouse_id === $this->to_warehouse_id;
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'COMPLETED' => 'success',
            'IN_TRANSIT' => 'warning',
            'PENDING' => 'info',
            'CANCELLED' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get quantity display with unit
     */
    public function getQtyDisplayAttribute(): string
    {
        $unitName = $this->unit->short_name ?? 'PCS';
        $baseUnitName = $this->product->unit->short_name ?? 'PCS';
        
        $display = number_format($this->qty, 2) . ' ' . $unitName;
        
        if ($this->qty != $this->base_qty && $this->base_qty) {
            $display .= ' (= ' . number_format($this->base_qty, 2) . ' ' . $baseUnitName . ')';
        }
        
        return $display;
    }

    // ==================== STATIC METHODS ====================
    
    /**
     * Generate transfer number
     */
    public static function generateTransferNo(): string
    {
        $prefix = 'TRF-' . date('Ymd') . '-';
        
        $last = self::where('transfer_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();
        
        $num = $last ? ((int) substr($last->transfer_no, -3) + 1) : 1;
        
        return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    // ==================== SCOPES ====================
    
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'IN_TRANSIT');
    }

    public function scopeFromWarehouse($query, int $warehouseId)
    {
        return $query->where('from_warehouse_id', $warehouseId);
    }

    public function scopeToWarehouse($query, int $warehouseId)
    {
        return $query->where('to_warehouse_id', $warehouseId);
    }
}