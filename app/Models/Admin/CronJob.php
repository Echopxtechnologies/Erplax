<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'method',
        'schedule',
        'description',
        'last_run',
        'last_duration',
        'last_status',
        'last_message',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'last_run' => 'datetime',
        'status' => 'boolean',
    ];

    /**
     * Get logs for this cron job
     */
    public function logs()
    {
        return $this->hasMany(CronLog::class);
    }

    /**
     * Scope for active crons
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Check if cron should run based on schedule
     */
    public function shouldRun(): bool
    {
        if (!$this->status) {
            return false;
        }

        if (!$this->last_run) {
            return true;
        }

        return match ($this->schedule) {
            'minutely' => $this->last_run->addMinute()->isPast(),
            'every_5_minutes' => $this->last_run->addMinutes(5)->isPast(),
            'every_10_minutes' => $this->last_run->addMinutes(10)->isPast(),
            'every_15_minutes' => $this->last_run->addMinutes(15)->isPast(),
            'every_30_minutes' => $this->last_run->addMinutes(30)->isPast(),
            'hourly' => $this->last_run->addHour()->isPast(),
            'daily' => $this->last_run->addDay()->isPast(),
            'weekly' => $this->last_run->addWeek()->isPast(),
            'monthly' => $this->last_run->addMonth()->isPast(),
            default => true,
        };
    }

    /**
     * Get the class name from method field
     */
    public function getClassName(): string
    {
        $parts = explode('/', $this->method);
        return $parts[0] ?? '';
    }

    /**
     * Get the method name from method field
     */
    public function getMethodName(): string
    {
        $parts = explode('/', $this->method);
        return $parts[1] ?? 'handle';
    }

    /**
     * Get full class path
     */
    public function getFullClass(): string
    {
        return "App\\Crons\\" . $this->getClassName();
    }

    /**
     * Get execution time in human readable format
     */
    public function getLastDurationFormattedAttribute(): string
    {
        if (!$this->last_duration) {
            return '-';
        }

        if ($this->last_duration < 1000) {
            return $this->last_duration . 'ms';
        }

        return round($this->last_duration / 1000, 2) . 's';
    }

    /**
     * Get schedule options
     */
    public static function scheduleOptions(): array
    {
        return [
            'minutely' => 'Every Minute',
            'every_5_minutes' => 'Every 5 Minutes',
            'every_10_minutes' => 'Every 10 Minutes',
            'every_15_minutes' => 'Every 15 Minutes',
            'every_30_minutes' => 'Every 30 Minutes',
            'hourly' => 'Hourly',
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
        ];
    }

    /**
     * Get recent logs
     */
    public function recentLogs($limit = 5)
    {
        return $this->logs()->latest()->limit($limit)->get();
    }
}