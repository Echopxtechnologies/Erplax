<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminSession extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'admin_sessions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'admin_id',
        'session_id',
        'fingerprint',
        'device_name',
        'ip_address',
        'user_agent',
        'last_activity',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'last_activity' => 'datetime',
    ];

    /**
     * Get the admin that owns the session.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Scope: Active sessions (not expired)
     */
    public function scopeActive($query, int $idleMinutes = 30)
    {
        return $query->where('last_activity', '>', now()->subMinutes($idleMinutes));
    }

    /**
     * Scope: Sessions for a specific admin
     */
    public function scopeForAdmin($query, int $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * Get all active sessions for an admin
     */
    public static function getActiveSessions(int $adminId, int $idleMinutes = 30)
    {
        return static::forAdmin($adminId)
            ->active($idleMinutes)
            ->orderBy('last_activity', 'desc')
            ->get();
    }

    /**
     * Terminate all sessions for an admin (force logout everywhere)
     */
    public static function terminateAllSessions(int $adminId): int
    {
        return static::forAdmin($adminId)->delete();
    }

    /**
     * Terminate all other sessions except current
     */
    public static function terminateOtherSessions(int $adminId, string $currentSessionId): int
    {
        return static::forAdmin($adminId)
            ->where('session_id', '!=', $currentSessionId)
            ->delete();
    }

    /**
     * Check if admin has too many active sessions
     */
    public static function hasExcessiveSessions(int $adminId, int $maxSessions = 5, int $idleMinutes = 30): bool
    {
        return static::forAdmin($adminId)
            ->active($idleMinutes)
            ->count() > $maxSessions;
    }

    /**
     * Get session count for an admin
     */
    public static function getSessionCount(int $adminId, int $idleMinutes = 30): int
    {
        return static::forAdmin($adminId)
            ->active($idleMinutes)
            ->count();
    }

    /**
     * Clean up expired sessions globally
     */
    public static function cleanupExpired(int $idleMinutes = 30): int
    {
        return static::where('last_activity', '<', now()->subMinutes($idleMinutes))->delete();
    }
}