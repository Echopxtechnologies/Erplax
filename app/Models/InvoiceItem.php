<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'item_type',
        'description',
        'long_description',
        'quantity',
        'rate',
        'amount',
        'tax_ids',      // Multiple tax IDs stored as JSON
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the invoice that owns this item
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the product associated with this item
     */
    public function product()
    {
        return $this->belongsTo(\Modules\Inventory\Models\Product::class);
    }

    /**
     * Get parsed tax IDs as array
     */
    public function getTaxIdsArrayAttribute(): array
    {
        if (empty($this->tax_ids)) {
            return [];
        }

        // If already array
        if (is_array($this->tax_ids)) {
            return array_map('intval', $this->tax_ids);
        }

        // Try JSON decode
        $decoded = json_decode($this->tax_ids, true);
        if (is_array($decoded)) {
            return array_map('intval', $decoded);
        }

        // Try comma separated
        if (strpos($this->tax_ids, ',') !== false) {
            return array_map('intval', array_filter(explode(',', $this->tax_ids)));
        }

        // Single value
        return $this->tax_ids ? [intval($this->tax_ids)] : [];
    }

    /**
     * Calculate total tax amount for this item
     */
    public function calculateTaxAmount(array $taxRatesMap = []): float
    {
        $taxIds = $this->tax_ids_array;
        if (empty($taxIds) || empty($taxRatesMap)) {
            return 0;
        }

        $totalTax = 0;
        foreach ($taxIds as $taxId) {
            $rate = $taxRatesMap[$taxId] ?? 0;
            $totalTax += ($this->amount * $rate) / 100;
        }

        return $totalTax;
    }
}