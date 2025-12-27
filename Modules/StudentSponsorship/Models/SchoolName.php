<?php

namespace Modules\StudentSponsorship\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolName extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get all students in this school
     */
    public function students(): HasMany
    {
        return $this->hasMany(SchoolStudent::class, 'school_id');
    }

    /**
     * Scope for active schools
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
