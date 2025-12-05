<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role;

class Menu extends Model
{
    protected $fillable = [
        'parent_id',
        'module_id',
        'menu_name',
        'slug',
        'icon',
        'route',
        'category',
        'permission_name',
        'menu_visibility',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the module that owns this menu
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    /**
     * Get the parent menu
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get child menus
     */
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get active children
     */
    public function activeChildren(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Get all actions for this menu
     */
    public function actions(): HasMany
    {
        return $this->hasMany(MenuAction::class, 'menu_id')->orderBy('sort_order');
    }

    /**
     * Get active actions
     */
    public function activeActions(): HasMany
    {
        return $this->hasMany(MenuAction::class, 'menu_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Get roles that have access to this menu
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'menu_role', 'menu_id', 'role_id')
            ->withTimestamps();
    }

    /**
     * Scope for active menus
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for parent menus only
     */
    public function scopeParentsOnly($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for ordered menus
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get all permissions for this menu
     */
    public function getPermissions(): array
    {
        $permissions = [];
        $moduleAlias = $this->module?->alias ?? 'system';

        foreach ($this->actions as $action) {
            $permissions[] = "{$moduleAlias}.{$this->slug}.{$action->action_slug}";
        }

        return $permissions;
    }

    /**
     * Get permission name for a specific action
     */
    public function getPermissionName(string $actionSlug): string
    {
        $moduleAlias = $this->module?->alias ?? 'system';
        return "{$moduleAlias}.{$this->slug}.{$actionSlug}";
    }

    /**
     * Check if menu has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get full permission for read action
     */
    public function getReadPermissionAttribute(): string
    {
        return $this->getPermissionName('read');
    }
}