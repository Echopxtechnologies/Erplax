<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadsSource extends Model
{
    protected $table = 'leads_sources';

    protected $fillable = ['name'];

    // Relationship with leads
    public function leads()
    {
        return $this->hasMany(Lead::class, 'source', 'id');
    }
}