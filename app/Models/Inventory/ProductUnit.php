<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductUnit extends Model
{
    protected $fillable = [
        'product_id',
        'unit_id',
        'conversion_factor',
        'purchase_price',
        'sale_price',
        'barcode',
        'is_purchase_unit',
        'is_sale_unit',
        'is_default',
    ];

    protected $casts = [
        'conversion_factor' => 'decimal:4',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_purchase_unit' => 'boolean',
        'is_sale_unit' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}