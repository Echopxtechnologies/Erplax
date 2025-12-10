<?php

namespace App\Models\Inventory;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransfer extends Model
{
    protected $fillable = [
        'transfer_no',
        'product_id',
        'lot_id',
        'unit_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'from_rack_id',
        'to_rack_id',
        'qty',
        'base_qty',
        'status',
        'reason',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'base_qty' => 'decimal:3',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
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
}