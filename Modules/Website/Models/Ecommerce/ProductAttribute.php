<?php

namespace Modules\Website\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $table = 'product_attributes';

    protected $fillable = [
        'name',
        'slug',
        'type', // select, color
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get attribute values
     */
    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id')->orderBy('sort_order');
    }

    /**
     * Check if this is a color attribute
     */
    public function isColor(): bool
    {
        return $this->type === 'color' || strtolower($this->slug) === 'color';
    }

    /**
     * Check if this is a size attribute
     */
    public function isSize(): bool
    {
        return strtolower($this->slug) === 'size';
    }
}
