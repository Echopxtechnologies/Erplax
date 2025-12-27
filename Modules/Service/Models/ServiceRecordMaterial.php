<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ServiceRecordMaterial extends Model
{
    protected $fillable = [
        'service_record_id',
        'product_id',
        'material_name',
        'quantity',
        'unit_price',
        'total',
        'tax_ids',
        'tax_amount',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
        'tax_amount' => 'decimal:2',
    ];

    /**
     * Get the service record
     */
    public function serviceRecord(): BelongsTo
    {
        return $this->belongsTo(ServiceRecord::class);
    }

    /**
     * Get the product (with safety check)
     */
    public function product(): BelongsTo
    {
        if (class_exists(\App\Models\Product::class)) {
            return $this->belongsTo(\App\Models\Product::class, 'product_id');
        }
        // Return empty relation if Product class doesn't exist
        return $this->belongsTo(ServiceRecordMaterial::class, 'product_id')->whereRaw('1=0');
    }

    /**
     * Get product name safely
     */
    public function getProductNameAttribute(): ?string
    {
        if ($this->material_name) {
            return $this->material_name;
        }
        
        if ($this->product_id) {
            // Try to get from products table directly
            $product = DB::table('products')->where('id', $this->product_id)->first();
            return $product->name ?? null;
        }
        
        return null;
    }

    /**
     * Get tax_ids as array
     */
    public function getTaxIdsArrayAttribute(): array
    {
        $taxIds = $this->tax_ids;
        
        if (empty($taxIds)) {
            return [];
        }
        
        if (is_array($taxIds)) {
            return $taxIds;
        }
        
        if (is_numeric($taxIds)) {
            return [intval($taxIds)];
        }
        
        if (is_string($taxIds)) {
            $decoded = json_decode($taxIds, true);
            if (is_array($decoded)) {
                return $decoded;
            }
            if (is_numeric($decoded)) {
                return [intval($decoded)];
            }
        }
        
        return [];
    }

    /**
     * Get subtotal (without tax)
     */
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Get total with tax
     */
    public function getTotalWithTaxAttribute(): float
    {
        return $this->subtotal + ($this->tax_amount ?? 0);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($material) {
            // Calculate total (including tax)
            $subtotal = $material->quantity * $material->unit_price;
            $material->total = $subtotal + ($material->tax_amount ?? 0);
            
            // Store product name if product selected and material_name is empty
            if ($material->product_id && empty($material->material_name)) {
                // Get product name from database directly (avoid model dependency)
                $product = DB::table('products')->where('id', $material->product_id)->first();
                if ($product) {
                    $material->material_name = $product->name;
                }
            }
        });

        static::saved(function ($material) {
            // Update service record total
            if ($material->serviceRecord) {
                $material->serviceRecord->updateTotalCost();
            }
        });

        static::deleted(function ($material) {
            // Update service record total
            if ($material->serviceRecord) {
                $material->serviceRecord->updateTotalCost();
            }
        });
    }
}
