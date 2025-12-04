<?php

namespace Modules\Todo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends Model
{
    use HasFactory;

    protected $table = 'todos';

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'assigned_to',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public static function getPriorities()
    {
        return [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_URGENT => 'Urgent',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getPriorityLabelAttribute()
    {
        return self::getPriorities()[$this->priority] ?? $this->priority;
    }

    public function getStatusBadgeColorAttribute()
    {
        return match ($this->status) {
            self::STATUS_PENDING => '#FFA500',
            self::STATUS_IN_PROGRESS => '#3498DB',
            self::STATUS_COMPLETED => '#27AE60',
            self::STATUS_CANCELLED => '#E74C3C',
            default => '#95A5A6',
        };
    }

    public function getPriorityBadgeColorAttribute()
    {
        return match ($this->priority) {
            self::PRIORITY_LOW => '#27AE60',
            self::PRIORITY_MEDIUM => '#F39C12',
            self::PRIORITY_HIGH => '#E67E22',
            self::PRIORITY_URGENT => '#C0392B',
            default => '#95A5A6',
        };
    }
}
