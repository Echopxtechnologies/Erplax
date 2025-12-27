<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ServiceVisit extends Model
{
    protected $fillable = [
        'service_id',
        'engineer_id',
        'visit_date',
        'visit_time',
        'check_in_time',
        'check_out_time',
        'status',
        'purpose',
        'notes',
        'client_signature',
        'created_by',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    /**
     * Get the service contract
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the engineer
     */
    public function engineer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'engineer_id');
    }

    /**
     * Get the creator
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get engineer name
     */
    public function getEngineerNameAttribute(): string
    {
        return $this->engineer->name ?? 'Not Assigned';
    }

    /**
     * Get duration in minutes
     */
    public function getDurationAttribute(): ?int
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return null;
        }
        
        $checkIn = \Carbon\Carbon::parse($this->check_in_time);
        $checkOut = \Carbon\Carbon::parse($this->check_out_time);
        
        return $checkOut->diffInMinutes($checkIn);
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        $minutes = $this->duration;
        if ($minutes === null) return '-';
        
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return $hours . 'h ' . $mins . 'm';
        }
        return $mins . ' min';
    }
}
