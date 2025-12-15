<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProductUnit Model - Multi-unit support for products
 * 
 * Allows a product to be sold/purchased in different units with different prices
 * 
 * Example: Product "Rice" (base unit: KG)
 * - 1 KG Packet (conversion: 1, sale_price: 50)
 * - 5 KG Bag (conversion: 5, sale_price: 240)
 * - 25 KG Sack (conversion: 25, sale_price: 1150)
 * 
 * When selling "2 × 5 KG Bag":
 * - qty = 2 (in transaction unit)
 * - base_qty = 10 (2 × 5 = 10 KG deducted from stock)
 */
class ProductUnit extends Model
{
    protected $fillable = [
        'product_id',
        'unit_id',
        'unit_name',           // Custom name like "5 KG Bag", "Box of 12"
        'conversion_factor',   // How many base units (5 for "5 KG Bag")
        'purchase_price',      // Price when purchasing in this unit
        'sale_price',          // Price when selling in this unit
        'barcode',             // Unique barcode for this unit
        'is_purchase_unit',    // Can purchase in this unit
        'is_sale_unit',        // Can sell in this unit
        'is_default',          // Default unit for transactions
    ];

    protected $casts = [
        'conversion_factor' => 'decimal:4',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_purchase_unit' => 'boolean',
        'is_sale_unit' => 'boolean',
        'is_default' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================
    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    // ==================== ACCESSORS ====================
    
    /**
     * Get display name for this unit
     * Returns custom name if set, otherwise unit short name with conversion
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->unit_name) {
            return $this->unit_name;
        }
        
        $unitName = $this->unit->short_name ?? 'PCS';
        
        if ($this->conversion_factor == 1) {
            return $unitName;
        }
        
        return "{$this->conversion_factor} {$unitName}";
    }

    /**
     * Get full display name with conversion info
     */
    public function getFullDisplayNameAttribute(): string
    {
        $name = $this->display_name;
        $baseUnit = $this->product->unit->short_name ?? 'PCS';
        
        if ($this->conversion_factor != 1) {
            $name .= " (= {$this->conversion_factor} {$baseUnit})";
        }
        
        return $name;
    }

    /**
     * Get effective purchase price (own or calculated from base)
     */
    public function getEffectivePurchasePriceAttribute(): float
    {
        if ($this->purchase_price !== null) {
            return $this->purchase_price;
        }
        
        // Calculate from base product price
        return ($this->product->purchase_price ?? 0) * $this->conversion_factor;
    }

    /**
     * Get effective sale price (own or calculated from base)
     */
    public function getEffectiveSalePriceAttribute(): float
    {
        if ($this->sale_price !== null) {
            return $this->sale_price;
        }
        
        // Calculate from base product price
        return ($this->product->sale_price ?? 0) * $this->conversion_factor;
    }

    // ==================== SCOPES ====================
    
    /**
     * Scope to get purchase units
     */
    public function scopePurchaseUnits($query)
    {
        return $query->where('is_purchase_unit', true);
    }

    /**
     * Scope to get sale units
     */
    public function scopeSaleUnits($query)
    {
        return $query->where('is_sale_unit', true);
    }

    /**
     * Scope to find by barcode
     */
    public function scopeByBarcode($query, string $barcode)
    {
        return $query->where('barcode', $barcode);
    }

    // ==================== METHODS ====================
    
    /**
     * Convert quantity from this unit to base unit
     * 
     * @param float $qty Quantity in this unit
     * @return float Quantity in base unit
     */
    public function toBaseQty(float $qty): float
    {
        return $qty * $this->conversion_factor;
    }

    /**
     * Convert quantity from base unit to this unit
     * 
     * @param float $baseQty Quantity in base unit
     * @return float Quantity in this unit
     */
    public function fromBaseQty(float $baseQty): float
    {
        if ($this->conversion_factor == 0) {
            return 0;
        }
        
        return $baseQty / $this->conversion_factor;
    }

    /**
     * Check if stock is sufficient in base units
     * 
     * @param float $qty Quantity in this unit
     * @param float $availableBaseStock Available stock in base units
     * @return bool
     */
    public function hasEnoughStock(float $qty, float $availableBaseStock): bool
    {
        $requiredBaseQty = $this->toBaseQty($qty);
        return $availableBaseStock >= $requiredBaseQty;
    }

    /**
     * Get max quantity that can be sold in this unit
     * 
     * @param float $availableBaseStock Available stock in base units
     * @return float
     */
    public function getMaxQty(float $availableBaseStock): float
    {
        return $this->fromBaseQty($availableBaseStock);
    }
}