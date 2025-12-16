<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id', 'purchase_request_item_id', 'product_id', 'variation_id', 
        'unit_id', 'qty', 'received_qty', 'rate', 'discount_percent', 'discount_amount',
        'tax_percent', 'tax_amount', 'total', 'description'
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'received_qty' => 'decimal:3',
        'rate' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relationships
    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class); }
    public function purchaseRequestItem() { return $this->belongsTo(PurchaseRequestItem::class); }
    public function product() { return $this->belongsTo(\Modules\Inventory\Models\Product::class); }
    public function variation() { return $this->belongsTo(\Modules\Inventory\Models\ProductVariation::class); }
    public function unit() { return $this->belongsTo(\Modules\Inventory\Models\Unit::class); }

    // Accessors
    public function getPendingQtyAttribute()
    {
        return max(0, $this->qty - $this->received_qty);
    }

    public function getProductNameAttribute()
    {
        if ($this->variation) {
            return $this->product->name . ' - ' . $this->variation->name;
        }
        return $this->product->name ?? $this->description ?? 'N/A';
    }

    public function getUnitNameAttribute()
    {
        return $this->unit->short_name ?? $this->unit->name ?? '-';
    }

    public function getSubtotalAttribute()
    {
        return $this->qty * $this->rate;
    }

    public function calculateTotal()
    {
        $subtotal = $this->qty * $this->rate;
        
        // Calculate discount - ensure never null
        $discountPercent = $this->discount_percent ?? 0;
        $discountAmount = $discountPercent > 0 
            ? ($subtotal * $discountPercent / 100) 
            : ($this->discount_amount ?? 0);
        
        $afterDiscount = $subtotal - $discountAmount;
        
        // Calculate tax - ensure never null
        $taxPercent = $this->tax_percent ?? 0;
        $taxAmount = $taxPercent > 0 ? ($afterDiscount * $taxPercent / 100) : 0;
        
        $this->discount_amount = round($discountAmount, 2);
        $this->tax_amount = round($taxAmount, 2);
        $this->total = round($afterDiscount + $taxAmount, 2);
        $this->save();
    }
}
