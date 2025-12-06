<?php

namespace Modules\Todo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Notification;

class Todo extends Model
{
    protected $fillable = [
        'user_id',
        'assigned_to',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'completed_at',
        'overdue_notified',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'overdue_notified' => 'boolean',
    ];

    /**
     * Get the user that created the todo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user assigned to this todo
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Scope for pending todos
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed todos
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for overdue todos
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString())
                     ->where('status', '!=', 'completed');
    }

    /**
     * Scope for user's todos (created by or assigned to)
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('assigned_to', $userId);
        });
    }

    /**
     * Check if todo is overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && 
               $this->due_date->isPast() && 
               $this->status !== 'completed';
    }

    /**
     * Get user name (creator) for display
     */
    public function getUserNameAttribute(): string
    {
        return $this->user->name ?? 'Unknown';
    }

    /**
     * Get assignee name for display
     */
    public function getAssigneeNameAttribute(): string
    {
        return $this->assignee->name ?? 'Unassigned';
    }

    /**
     * Send notification to assigned user
     */
    public function notifyAssignee(string $title, string $message, User $fromUser = null): void
    {
        if (!$this->assigned_to) return;

        Notification::create([
            'user_id' => $this->assigned_to,
            'from_user_id' => $fromUser?->id,
            'title' => $title,
            'message' => $message,
            'type' => 'info',
            'url' => route('admin.todo.show', $this->id),
        ]);
    }

    /**
     * Send overdue notification
     */
    public function sendOverdueNotification(): void
    {
        if ($this->overdue_notified) return;

        // Notify the assignee (or creator if not assigned)
        $notifyUserId = $this->assigned_to ?? $this->user_id;

        Notification::create([
            'user_id' => $notifyUserId,
            'from_user_id' => null, // System notification
            'title' => 'Task Overdue!',
            'message' => "Task \"{$this->title}\" is overdue. Due: {$this->due_date->format('M d, Y')}",
            'type' => 'error',
            'url' => route('admin.todo.show', $this->id),
        ]);

        $this->update(['overdue_notified' => true]);
    }
}
