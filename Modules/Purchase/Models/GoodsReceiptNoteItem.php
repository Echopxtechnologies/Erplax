<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsReceiptNoteItem extends Model
{
    protected $fillable = [
        'goods_receipt_note_id',
        'purchase_order_item_id',
        'product_id',
        'variation_id',
        'unit_id',
        'ordered_qty',
        'received_qty',
        'accepted_qty',
        'rejected_qty',
        'rate',
        'discount_percent',
        // Tax 1
        'tax_1_id', 'tax_1_name', 'tax_1_rate',
        // Tax 2
        'tax_2_id', 'tax_2_name', 'tax_2_rate',
        'rejection_reason',
        'lot_no',
        'batch_no',
        'manufacturing_date',
        'expiry_date',
        'stock_movement_id',
        'lot_id',
        'notes',
    ];

    protected $casts = [
        'ordered_qty' => 'decimal:3',
        'received_qty' => 'decimal:3',
        'accepted_qty' => 'decimal:3',
        'rejected_qty' => 'decimal:3',
        'rate' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'tax_1_rate' => 'decimal:2',
        'tax_2_rate' => 'decimal:2',
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
    ];

    // ==================== RELATIONSHIPS ====================

    public function goodsReceiptNote(): BelongsTo
    {
        return $this->belongsTo(GoodsReceiptNote::class);
    }

    public function purchaseOrderItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\Modules\Inventory\Models\Product::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(\Modules\Inventory\Models\ProductVariation::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(\Modules\Inventory\Models\Unit::class);
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(\Modules\Inventory\Models\Lot::class);
    }

    public function stockMovement(): BelongsTo
    {
        return $this->belongsTo(\Modules\Inventory\Models\StockMovement::class);
    }

    // ==================== ACCESSORS ====================

    public function getPendingQtyAttribute(): float
    {
        return max(0, $this->ordered_qty - $this->received_qty);
    }

    public function getTotalValueAttribute(): float
    {
        return $this->accepted_qty * $this->rate;
    }

    public function getIsFullyReceivedAttribute(): bool
    {
        return $this->received_qty >= $this->ordered_qty;
    }
    
    public function getTotalTaxRateAttribute(): float
    {
        return ($this->tax_1_rate ?? 0) + ($this->tax_2_rate ?? 0);
    }
}
