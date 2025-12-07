<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'module_id',
        'action_name',
        'sort_order',
    ];

    /**
     * Default guard for new permissions
     */
    protected $attributes = [
        'guard_name' => 'admin',  // ← Default to admin guard
    ];

    /**
     * Get the module this permission belongs to
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    /**
     * Get module name from permission name
     */
    public function getModuleSlugAttribute(): string
    {
        $parts = explode('.', $this->name);
        return $parts[0] ?? 'system';
    }

    /**
     * Get menu slug from permission name
     */
    public function getMenuSlugAttribute(): string
    {
        $parts = explode('.', $this->name);
        return $parts[1] ?? '';
    }

    /**
     * Get action slug from permission name
     */
    public function getActionSlugAttribute(): string
    {
        $parts = explode('.', $this->name);
        return $parts[2] ?? $parts[1] ?? $parts[0];
    }

    /**
     * Scope to get permissions by module
     */
    public function scopeByModule($query, $moduleId)
    {
        return $query->where('module_id', $moduleId);
    }

    /**
     * Scope for admin guard permissions
     */
    public function scopeAdmin($query)
    {
        return $query->where('guard_name', 'admin');
    }

    /**
     * Scope for orphan permissions (no module)
     */
    public function scopeOrphan($query)
    {
        return $query->whereNull('module_id');
    }
}