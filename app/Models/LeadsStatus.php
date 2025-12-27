<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadsStatus extends Model
{
    protected $table = 'leads_status';

    protected $fillable = ['name', 'statusorder', 'color', 'isdefault'];

    protected $casts = [
        'isdefault' => 'boolean',
        'statusorder' => 'integer',
    ];

    // Relationship with leads
    public function leads()
    {
        return $this->hasMany(Lead::class, 'status', 'id');
    }

    // Scope for default status
    public function scopeDefault($query)
    {
        return $query->where('isdefault', 1);
    }

    // Scope ordered by statusorder
    public function scopeOrdered($query)
    {
        return $query->orderBy('statusorder');
    }
}