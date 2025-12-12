<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AttributeValue extends Model
{
    protected $fillable = [
        'attribute_id',
        'value',
        'slug',
        'color_code',
        'sort_order',
    ];

    // ==================== RELATIONSHIPS ====================

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    }

    public function variations()
    {
        return $this->belongsToMany(
            ProductVariation::class,
            'variation_attribute_values',
            'attribute_value_id',
            'variation_id'
        );
    }

    // ==================== BOOT ====================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($value) {
            if (empty($value->slug)) {
                $value->slug = Str::slug($value->value);
            }
        });
    }

    // ==================== ACCESSORS ====================

    public function getDisplayNameAttribute(): string
    {
        return $this->value;
    }

    public function getFullNameAttribute(): string
    {
        return $this->attribute->name . ': ' . $this->value;
    }

    // ==================== HELPERS ====================

    public static function findOrCreateByName(int $attributeId, string $value): self
    {
        $slug = Str::slug($value);
        
        return static::firstOrCreate(
            ['attribute_id' => $attributeId, 'slug' => $slug],
            ['value' => $value]
        );
    }
}