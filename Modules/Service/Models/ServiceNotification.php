<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceNotification extends Model
{
    protected $fillable = [
        'service_id',
        'type',
        'email_to',
        'subject',
        'message',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Get the service contract
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Scope for sent notifications
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for pending notifications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        $labels = [
            'reminder' => 'Service Reminder',
            'overdue' => 'Overdue Notice',
            'completed' => 'Service Completed',
            'scheduled' => 'Service Scheduled',
        ];
        return $labels[$this->type] ?? ucfirst($this->type);
    }
}
