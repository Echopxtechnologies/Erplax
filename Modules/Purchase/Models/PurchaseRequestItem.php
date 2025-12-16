<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    protected $fillable = [
        'purchase_request_id', 'product_id', 'variation_id', 'unit_id',
        'qty', 'ordered_qty', 'estimated_price', 'specifications', 'remarks'
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'ordered_qty' => 'decimal:3',
        'estimated_price' => 'decimal:2',
    ];

    // Relationships
    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function product()
    {
        return $this->belongsTo(\Modules\Inventory\Models\Product::class, 'product_id');
    }

    public function variation()
    {
        return $this->belongsTo(\Modules\Inventory\Models\ProductVariation::class, 'variation_id');
    }

    public function unit()
    {
        return $this->belongsTo(\Modules\Inventory\Models\Unit::class, 'unit_id');
    }

    // Accessors
    public function getPendingQtyAttribute(): float
    {
        return max(0, $this->qty - $this->ordered_qty);
    }

    public function getEstimatedTotalAttribute(): float
    {
        return $this->qty * ($this->estimated_price ?? 0);
    }

    public function getProductNameAttribute(): string
    {
        $name = $this->product->name ?? 'Unknown Product';
        if ($this->variation) {
            $name .= ' - ' . $this->variation->variation_name;
        }
        return $name;
    }

    public function getUnitNameAttribute(): string
    {
        return $this->unit->short_name ?? '-';
    }
}
