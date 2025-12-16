<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseBillItem extends Model
{
    protected $table = 'purchase_bill_items';

    protected $fillable = [
        'purchase_bill_id',
        'grn_item_id',
        'product_id',
        'variation_id',
        'unit_id',
        'description',
        'qty',
        'rate',
        'tax_percent',
        'tax_amount',
        'discount_percent',
        'discount_amount',
        'total',
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'rate' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function bill()
    {
        return $this->belongsTo(PurchaseBill::class, 'purchase_bill_id');
    }

    public function grnItem()
    {
        return $this->belongsTo(GoodsReceiptNoteItem::class, 'grn_item_id');
    }

    public function product()
    {
        if (class_exists('\Modules\Inventory\Models\Product')) {
            return $this->belongsTo(\Modules\Inventory\Models\Product::class);
        }
        return $this->belongsTo(Vendor::class, 'product_id');
    }

    public function variation()
    {
        if (class_exists('\Modules\Inventory\Models\ProductVariation')) {
            return $this->belongsTo(\Modules\Inventory\Models\ProductVariation::class);
        }
        return $this->belongsTo(Vendor::class, 'variation_id');
    }

    public function unit()
    {
        if (class_exists('\Modules\Inventory\Models\Unit')) {
            return $this->belongsTo(\Modules\Inventory\Models\Unit::class);
        }
        return $this->belongsTo(Vendor::class, 'unit_id');
    }

    // Calculate item total
    public function calculateTotal(): void
    {
        $lineTotal = $this->qty * $this->rate;
        $this->discount_amount = $lineTotal * ($this->discount_percent / 100);
        $afterDiscount = $lineTotal - $this->discount_amount;
        $this->tax_amount = $afterDiscount * ($this->tax_percent / 100);
        $this->total = $afterDiscount + $this->tax_amount;
        $this->save();
    }
}
