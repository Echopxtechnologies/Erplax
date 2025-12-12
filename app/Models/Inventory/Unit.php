<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'base_unit_id',
        'conversion_factor',
        'is_active',
    ];

    protected $casts = [
        'conversion_factor' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function baseUnit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'base_unit_id');
    }

    public function derivedUnits(): HasMany
    {
        return $this->hasMany(Unit::class, 'base_unit_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function productUnits(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    /**
     * Convert quantity to base unit
     */
    public function toBaseUnit($qty): float
    {
        return $qty * $this->conversion_factor;
    }

    /**
     * Convert quantity from base unit
     */
    public function fromBaseUnit($qty): float
    {
        return $this->conversion_factor > 0 ? $qty / $this->conversion_factor : $qty;
    }

    /**
     * Get display name
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ' (' . $this->short_name . ')';
    }

    /**
     * Scope for active units
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if this is a base unit
     */
    public function getIsBaseUnitAttribute(): bool
    {
        return is_null($this->base_unit_id);
    }

    /**
     * Get all base units
     */
    public static function getBaseUnits()
    {
        return self::whereNull('base_unit_id')->active()->get();
    }
}