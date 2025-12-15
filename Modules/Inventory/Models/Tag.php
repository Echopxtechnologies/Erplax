<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
            if (empty($tag->color)) {
                $tag->color = '#6366f1';
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && !$tag->isDirty('slug')) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    // ==================== RELATIONSHIPS ====================

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_tags')->withTimestamps();
    }

    // ==================== SCOPES ====================

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    // ==================== HELPERS ====================

    /**
     * Get or create tag by name
     */
    public static function findOrCreateByName(string $name): self
    {
        $slug = Str::slug($name);
        
        return self::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name]
        );
    }

    /**
     * Sync tags for a product from comma-separated string or array
     */
    public static function syncProductTags(Product $product, $tags): void
    {
        if (is_string($tags)) {
            $tags = array_filter(array_map('trim', explode(',', $tags)));
        }

        if (empty($tags)) {
            $product->tags()->detach();
            return;
        }

        $tagIds = [];
        foreach ($tags as $tagName) {
            if (!empty($tagName)) {
                $tag = self::findOrCreateByName($tagName);
                $tagIds[] = $tag->id;
            }
        }

        $product->tags()->sync($tagIds);
    }
}