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
     * Get the module this permission belongs to
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

    /**
     * Get module name from permission name
     * e.g., "book.list.read" returns "book"
     */
    public function getModuleSlugAttribute(): string
    {
        $parts = explode('.', $this->name);
        return $parts[0] ?? 'system';
    }

    /**
     * Get menu slug from permission name
     * e.g., "book.list.read" returns "list"
     */
    public function getMenuSlugAttribute(): string
    {
        $parts = explode('.', $this->name);
        return $parts[1] ?? '';
    }

    /**
     * Get action slug from permission name
     * e.g., "book.list.read" returns "read"
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
     * Scope for orphan permissions (no module)
     */
    public function scopeOrphan($query)
    {
        return $query->whereNull('module_id');
    }
}