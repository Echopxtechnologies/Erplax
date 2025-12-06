<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'from_user_id',
        'title',
        'message',
        'type',
        'url',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that receives the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that sent the notification
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->latest('created_at');
    }

    /**
     * Get sender name
     */
    public function getSenderNameAttribute(): string
    {
        return $this->sender->name ?? 'System';
    }
}