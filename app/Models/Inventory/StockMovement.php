<?php

namespace App\Models\Inventory;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'reference_no',
        'product_id',
        'warehouse_id',
        'rack_id',
        'lot_id',
        'unit_id',
        'qty',
        'base_qty',
        'stock_before',
        'stock_after',
        'movement_type',
        'reference_type',
        'reference_id',
        'reason',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'base_qty' => 'decimal:3',
        'stock_before' => 'decimal:3',
        'stock_after' => 'decimal:3',
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
    /**
     * Get related stock transfer (for TRANSFER movements)
     */
    public function stockTransfer(): BelongsTo
    {
        return $this->belongsTo(StockTransfer::class, 'reference_no', 'transfer_no');
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
     * Generate reference number
     */
    public static function generateReferenceNo($type = 'STK'): string
    {
        $prefix = $type . '-' . date('Ymd') . '-';
        $last = self::where('reference_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();
        
        $num = $last ? ((int) substr($last->reference_no, -4) + 1) : 1;
        
        return $prefix . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}