<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'cron_job_id',
        'status',
        'message',
        'execution_time',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the cron job
     */
    public function cronJob()
    {
        return $this->belongsTo(CronJob::class);
    }

    /**
     * Scope for successful runs
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed runs
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Get execution time in human readable format
     */
    public function getExecutionTimeFormattedAttribute(): string
    {
        if (!$this->execution_time) {
            return '-';
        }

        if ($this->execution_time < 1000) {
            return $this->execution_time . 'ms';
        }

        return round($this->execution_time / 1000, 2) . 's';
    }
}