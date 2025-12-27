<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteOrderItem extends Model
{
    protected $table = 'website_order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'variation_id',
        'product_name',
        'variation_name',
        'sku',
        'hsn_code',
        'unit_id',
        'unit_name',
        'qty',
        'unit_price',
        'mrp',
        'tax_rate',
        'tax_amount',
        'subtotal',
        'total',
    ];

    protected $casts = [
        'qty' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'mrp' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // =====================
    // RELATIONSHIPS
    // =====================

    public function order(): BelongsTo
    {
        return $this->belongsTo(WebsiteOrder::class, 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    // =====================
    // ACCESSORS
    // =====================

    /**
     * Get full product name with variation
     */
    public function getFullNameAttribute(): string
    {
        if ($this->variation_name) {
            return $this->product_name . ' - ' . $this->variation_name;
        }
        return $this->product_name;
    }

    /**
     * Get formatted unit price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rs. ' . number_format($this->unit_price, 2);
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rs. ' . number_format($this->total, 2);
    }

    /**
     * Get discount percentage from MRP
     */
    public function getDiscountPercentAttribute(): float
    {
        if (!$this->mrp || $this->mrp <= $this->unit_price) {
            return 0;
        }
        
        return round((($this->mrp - $this->unit_price) / $this->mrp) * 100, 1);
    }

    /**
     * Get product image URL
     */
    public function getImageUrlAttribute(): ?string
    {
        // Try variation image first
        if ($this->variation_id && $this->variation) {
            $varImage = $this->variation->getImageUrl();
            if ($varImage) {
                return $varImage;
            }
        }

        // Fallback to product image
        if ($this->product) {
            return $this->product->getPrimaryImageUrl();
        }

        return null;
    }

    // =====================
    // METHODS
    // =====================

    /**
     * Create order item from cart data
     */
    public static function createFromCart(array $cartItem, WebsiteOrder $order): self
    {
        $product = Product::find($cartItem['product_id']);
        $variation = null;
        
        if (!empty($cartItem['variation_id'])) {
            $variation = ProductVariation::find($cartItem['variation_id']);
        }

        // Get price using proper methods (handles null/inheritance)
        $unitPrice = $variation 
            ? $variation->getEffectiveSalePrice() 
            : ($product->sale_price ?? 0);
            
        $mrp = $variation 
            ? $variation->getEffectiveMrp() 
            : ($product->mrp ?? $unitPrice);
        
        // Calculate tax (from product's tax_1 + tax_2)
        $taxRate = 0;
        if ($product->tax_1_id) {
            $tax1 = \DB::table('taxes')->where('id', $product->tax_1_id)->where('is_active', 1)->first();
            if ($tax1) $taxRate += $tax1->rate;
        }
        if ($product->tax_2_id) {
            $tax2 = \DB::table('taxes')->where('id', $product->tax_2_id)->where('is_active', 1)->first();
            if ($tax2) $taxRate += $tax2->rate;
        }

        $qty = $cartItem['qty'];
        $subtotal = $unitPrice * $qty;
        
        // Calculate tax amount (assuming prices include tax)
        $settings = \Modules\Ecommerce\Models\WebsiteSetting::instance();
        if ($settings->tax_included_in_price) {
            // Extract tax from inclusive price: tax = price - (price / (1 + rate/100))
            $taxAmount = $taxRate > 0 ? ($subtotal - ($subtotal / (1 + $taxRate / 100))) : 0;
        } else {
            // Add tax on top: tax = price * rate/100
            $taxAmount = $subtotal * ($taxRate / 100);
        }

        $total = $settings->tax_included_in_price ? $subtotal : ($subtotal + $taxAmount);

        return self::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'variation_id' => $variation?->id,
            'product_name' => $product->name,
            'variation_name' => $variation ? $variation->getDisplayName() : null,
            'sku' => $variation?->sku ?? $product->sku,
            'hsn_code' => $product->hsn_code,
            'unit_id' => $product->unit_id,
            'unit_name' => $product->unit?->short_name ?? 'PCS',
            'qty' => $qty,
            'unit_price' => $unitPrice,
            'mrp' => $mrp,
            'tax_rate' => $taxRate,
            'tax_amount' => round($taxAmount, 2),
            'subtotal' => round($subtotal, 2),
            'total' => round($total, 2),
        ]);
    }

    /**
     * Check if product still exists
     */
    public function productExists(): bool
    {
        return Product::where('id', $this->product_id)->exists();
    }

    /**
     * Check if can be re-ordered (product in stock)
     */
    public function canReorder(): bool
    {
        if (!$this->productExists()) {
            return false;
        }

        $product = $this->product;
        
        if (!$product->is_active || !$product->can_be_sold) {
            return false;
        }

        if ($this->variation_id) {
            $variation = $this->variation;
            if (!$variation || !$variation->is_active) {
                return false;
            }
            return $variation->getCurrentStock() > 0;
        }

        return $product->getCurrentStock() > 0;
    }
}
