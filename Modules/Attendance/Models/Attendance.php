<?php

namespace Modules\Attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status Constants
    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_LATE = 'late';
    const STATUS_HALF_DAY = 'half-day';
    const STATUS_ON_LEAVE = 'on-leave';

    /**
     * Get all available statuses with their display labels
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PRESENT => 'Present',
            self::STATUS_ABSENT => 'Absent',
            self::STATUS_LATE => 'Late',
            self::STATUS_HALF_DAY => 'Half Day',
            self::STATUS_ON_LEAVE => 'On Leave',
        ];
    }

    /**
     * Get status label for the current record
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    /**
     * Get badge color for status (hex format for better flexibility)
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PRESENT => '#27AE60',    // Green
            self::STATUS_ABSENT => '#E74C3C',     // Red
            self::STATUS_LATE => '#F39C12',       // Orange
            self::STATUS_HALF_DAY => '#3498DB',   // Blue
            self::STATUS_ON_LEAVE => '#9B59B6',   // Purple
            default => '#95A5A6',                  // Gray
        };
    }

    /**
     * Get CSS class for status badge (Bootstrap style)
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PRESENT => 'success',
            self::STATUS_ABSENT => 'danger',
            self::STATUS_LATE => 'warning',
            self::STATUS_HALF_DAY => 'info',
            self::STATUS_ON_LEAVE => 'secondary',
            default => 'secondary',
        };
    }

    // Old method kept for backward compatibility (deprecated)
    public function getStatusColorAttribute(): string
    {
        return $this->statusBadgeClass;
    }

    /**
     * User relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filter by date
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    /**
     * Scope: Filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter by month and year
     */
    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('attendance_date', $month)
                     ->whereYear('attendance_date', $year);
    }

    /**
     * Scope: Filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filter by status (multiple)
     */
    public function scopeByStatuses($query, array $statuses)
    {
        return $query->whereIn('status', $statuses);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeByDateRange($query, $fromDate, $toDate)
    {
        return $query->whereDate('attendance_date', '>=', $fromDate)
                     ->whereDate('attendance_date', '<=', $toDate);
    }
}
