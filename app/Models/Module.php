<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'installed_at' => 'datetime',
    ];

    // Scopes 
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInstalled($query)
    {
        return $query->where('is_installed', true);
    }
}