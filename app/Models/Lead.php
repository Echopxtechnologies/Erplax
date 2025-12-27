<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lead extends Model
{
    protected $table = 'leads';

    protected $fillable = [
        'hash',
        'name',
        'title',
        'company',
        'description',
        'country',
        'zip',
        'city',
        'state',
        'address',
        'assigned',
        'source',
        'status',
        'dateadded',
        'from_form_id',
        'leadorder',
        'email',
        'website',
        'phonenumber',
        'date_converted',
        'lost',
        'junk',
        'is_imported_from_email_integration',
        'email_integration_uid',
        'is_public',
        'default_language',
        'client_id',
        'lead_value',
        'vat',
    ];

    protected $casts = [
        'dateadded' => 'datetime',
        'date_converted' => 'datetime',
        'lost' => 'boolean',
        'junk' => 'boolean',
        'is_imported_from_email_integration' => 'boolean',
        'is_public' => 'boolean',
        'lead_value' => 'decimal:2',
        'vat' => 'decimal:2',
    ];

    public $timestamps = true; // Uses created_at, updated_at

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($lead) {
            // Auto-generate hash if not provided
            if (empty($lead->hash)) {
                $lead->hash = Str::random(32);
            }
            // dateadded has default value in DB, no need to set
        });
    }

    // Relationships
    public function leadStatus()
    {
        return $this->belongsTo(LeadsStatus::class, 'status', 'id');
    }

    public function leadSource()
    {
        return $this->belongsTo(LeadsSource::class, 'source', 'id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'assigned', 'id');
    }
}