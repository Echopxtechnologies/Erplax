<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'item_type',
        'product_id',
        'description',
        'long_description',
        'quantity',
        'unit',
        'rate',
        'tax_ids',       // Multiple tax IDs stored as JSON array
        'tax_rate',
        'tax_name',
        'tax_amount',
        'amount',
        'total',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'rate' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

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

        if (is_array($this->tax_ids)) {
            return array_map('intval', $this->tax_ids);
        }

        $decoded = json_decode($this->tax_ids, true);
        if (is_array($decoded)) {
            return array_map('intval', $decoded);
        }

        if (strpos($this->tax_ids, ',') !== false) {
            return array_map('intval', array_filter(explode(',', $this->tax_ids)));
        }

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