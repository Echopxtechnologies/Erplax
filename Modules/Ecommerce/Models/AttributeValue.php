<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $table = 'attribute_values';

    protected $fillable = [
        'attribute_id',
        'value',
        'slug',
        'color_code',
        'sort_order',
    ];

    /**
     * Get parent attribute
     */
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    }

    /**
     * Check if this value has a color code
     */
    public function hasColorCode(): bool
    {
        return !empty($this->color_code);
    }

    /**
     * Get display color (for swatches)
     */
    public function getDisplayColor(): string
    {
        if ($this->color_code) {
            return $this->color_code;
        }
        
        // Fallback colors for common color names
        $colors = [
            'black' => '#000000',
            'white' => '#FFFFFF',
            'red' => '#EF4444',
            'blue' => '#3B82F6',
            'green' => '#22C55E',
            'yellow' => '#EAB308',
            'orange' => '#F97316',
            'purple' => '#A855F7',
            'pink' => '#EC4899',
            'gray' => '#6B7280',
            'grey' => '#6B7280',
            'brown' => '#92400E',
            'navy' => '#1E3A5F',
            'beige' => '#F5F5DC',
            'maroon' => '#800000',
        ];
        
        $slug = strtolower($this->slug);
        return $colors[$slug] ?? '#CBD5E1';
    }
}
