<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuAction extends Model
{
    protected $fillable = [
        'menu_id',
        'action_name',
        'action_slug',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the menu that owns this action
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Scope for active actions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered actions
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the full permission name
     */
    public function getPermissionName(): string
    {
        $moduleAlias = $this->menu->module?->alias ?? 'system';
        return "{$moduleAlias}.{$this->menu->slug}.{$this->action_slug}";
    }

    /**
     * Get permission name attribute
     */
    public function getPermissionAttribute(): string
    {
        return $this->getPermissionName();
    }
}