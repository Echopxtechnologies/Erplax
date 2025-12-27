<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

class Notification extends Model
{
    protected $table = 'notifications';
    
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'user_type',
        'from_user_id',
        'from_user_type',
        'title',
        'message',
        'type',
        'url',
        'is_read',
        'created_at',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    protected $attributes = [
        'type' => 'info',
        'user_type' => 'user',
        'is_read' => false,
    ];

    // ========== RELATIONSHIPS ==========

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function sender()
    {
        if ($this->from_user_type === 'admin') {
            return $this->belongsTo(Admin::class, 'from_user_id');
        }
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // ========== SCOPES ==========

    public function scopeForAdmin($query, $adminId)
    {
        return $query->where('user_id', $adminId)->where('user_type', 'admin');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId)->where('user_type', 'user');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // ========== ACCESSORS ==========

    public function getSenderNameAttribute(): string
    {
        if (!$this->from_user_id) {
            return 'System';
        }
        $sender = $this->sender;
        return $sender->name ?? 'Unknown';
    }

    // ========== METHODS ==========

    public function markAsRead(): bool
    {
        return $this->update(['is_read' => true]);
    }

    public function markAsUnread(): bool
    {
        return $this->update(['is_read' => false]);
    }

    // ========== STATIC HELPERS ==========

    /**
     * Send notification to an admin
     */
    public static function toAdmin(int $adminId, string $title, ?string $message = null, array $options = []): self
    {
        return self::create([
            'user_id' => $adminId,
            'user_type' => 'admin',
            'from_user_id' => $options['from_user_id'] ?? null,
            'from_user_type' => $options['from_user_type'] ?? null,
            'title' => $title,
            'message' => $message,
            'type' => $options['type'] ?? 'info',
            'url' => $options['url'] ?? null,
            'created_at' => now(),
        ]);
    }

    /**
     * Send notification to a user
     */
    public static function toUser(int $userId, string $title, ?string $message = null, array $options = []): self
    {
        return self::create([
            'user_id' => $userId,
            'user_type' => 'user',
            'from_user_id' => $options['from_user_id'] ?? null,
            'from_user_type' => $options['from_user_type'] ?? null,
            'title' => $title,
            'message' => $message,
            'type' => $options['type'] ?? 'info',
            'url' => $options['url'] ?? null,
            'created_at' => now(),
        ]);
    }

    /**
     * Send notification to multiple admins
     */
    public static function toAdmins(array $adminIds, string $title, ?string $message = null, array $options = []): int
    {
        $count = 0;
        foreach ($adminIds as $adminId) {
            self::toAdmin($adminId, $title, $message, $options);
            $count++;
        }
        return $count;
    }

    /**
     * Send notification to all active admins
     */
    public static function toAllAdmins(string $title, ?string $message = null, array $options = []): int
    {
        $adminIds = Admin::where('is_active', true)->pluck('id')->toArray();
        return self::toAdmins($adminIds, $title, $message, $options);
    }

    /**
     * Send notification to multiple users
     */
    public static function toUsers(array $userIds, string $title, ?string $message = null, array $options = []): int
    {
        $count = 0;
        foreach ($userIds as $userId) {
            self::toUser($userId, $title, $message, $options);
            $count++;
        }
        return $count;
    }
}