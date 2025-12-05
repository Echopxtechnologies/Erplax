<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable = [
        'name',
        'alias',
        'description',
        'version',
        'is_active',
        'is_installed',
        'is_core',
        'sort_order',
        'installed_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_installed' => 'boolean',
        'is_core' => 'boolean',
        'sort_order' => 'integer',
        'installed_at' => 'datetime',
    ];

    /**
     * Get all menus for this module
     */
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'module_id')->orderBy('sort_order');
    }

    /**
     * Get only parent menus (no parent_id)
     */
    public function parentMenus(): HasMany
    {
        return $this->hasMany(Menu::class, 'module_id')
            ->whereNull('parent_id')
            ->orderBy('sort_order');
    }

    /**
     * Get active menus
     */
    public function activeMenus(): HasMany
    {
        return $this->hasMany(Menu::class, 'module_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Scope for active modules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for installed modules
     */
    public function scopeInstalled($query)
    {
        return $query->where('is_installed', true);
    }

    /**
     * Scope for ordered modules
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get all permissions for this module
     */
    public function getPermissions(): array
    {
        $permissions = [];

        foreach ($this->menus as $menu) {
            foreach ($menu->actions as $action) {
                $permissions[] = "{$this->alias}.{$menu->slug}.{$action->action_slug}";
            }
        }

        return $permissions;
    }

    /**
     * Get module icon based on alias
     */
    public function getIconAttribute(): string
    {
        $icons = [
            'todo' => 'clipboard',
            'book' => 'book',
            'attendance' => 'calendar',
            'users' => 'users',
            'settings' => 'settings',
        ];

        return $icons[$this->alias] ?? 'folder';
    }
}