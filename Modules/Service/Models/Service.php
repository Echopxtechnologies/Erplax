<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Customer;
use Carbon\Carbon;

class Service extends Model
{
    protected $fillable = [
        'client_id',
        'machine_name',
        'equipment_no',
        'model_no',
        'serial_number',
        'service_frequency',
        'custom_days',
        'first_service_date',
        'last_service_date',
        'next_service_date',
        'status',
        'service_status',
        'notes',
        'reminder_days',
        'auto_reminder',
        'last_reminder_sent',
        'created_by',
    ];

    protected $casts = [
        'first_service_date' => 'date',
        'last_service_date' => 'date',
        'next_service_date' => 'date',
        'last_reminder_sent' => 'datetime',
        'auto_reminder' => 'boolean',
    ];

    /**
     * Get the client (customer) for this service
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }

    /**
     * Get the user who created this service
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get service records (history)
     */
    public function serviceRecords(): HasMany
    {
        return $this->hasMany(ServiceRecord::class)->orderBy('service_date', 'desc');
    }

    /**
     * Get service visits
     */
    public function visits(): HasMany
    {
        return $this->hasMany(ServiceVisit::class)->orderBy('visit_date', 'desc');
    }

    /**
     * Get notifications
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(ServiceNotification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Scope for active services
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for overdue services
     */
    public function scopeOverdue($query)
    {
        return $query->where('next_service_date', '<', now()->toDateString())
                     ->where('service_status', '!=', 'completed')
                     ->where('service_status', '!=', 'canceled')
                     ->where('status', 'active');
    }

    /**
     * Scope for due soon (within reminder days)
     */
    public function scopeDueSoon($query)
    {
        return $query->where('status', 'active')
                     ->where('service_status', '!=', 'completed')
                     ->where('service_status', '!=', 'canceled')
                     ->whereRaw('next_service_date <= DATE_ADD(CURDATE(), INTERVAL reminder_days DAY)')
                     ->where('next_service_date', '>=', now()->toDateString());
    }

    /**
     * Check if service is overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->next_service_date && 
               $this->next_service_date->isPast() && 
               !in_array($this->service_status, ['completed', 'canceled']) &&
               $this->status === 'active';
    }

    /**
     * Get days until next service
     */
    public function getDaysLeftAttribute(): ?int
    {
        if (!$this->next_service_date) return null;
        return now()->startOfDay()->diffInDays($this->next_service_date, false);
    }

    /**
     * Get client name for display
     */
    public function getClientNameAttribute(): string
    {
        return $this->client->company ?? $this->client->name ?? 'Unknown';
    }

    /**
     * Get formatted service frequency
     */
    public function getFrequencyLabelAttribute(): string
    {
        $labels = [
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'half_yearly' => 'Half Yearly',
            'yearly' => 'Yearly',
            'custom' => 'Custom (' . ($this->custom_days ?? 0) . ' days)',
        ];
        return $labels[$this->service_frequency] ?? $this->service_frequency;
    }

    /**
     * Get frequency in days
     */
    public function getFrequencyDaysAttribute(): int
    {
        switch ($this->service_frequency) {
            case 'monthly': return 30;
            case 'quarterly': return 90;
            case 'half_yearly': return 180;
            case 'yearly': return 365;
            case 'custom': return $this->custom_days ?? 30;
            default: return 30;
        }
    }

    /**
     * Calculate next service date based on frequency
     */
    public function calculateNextServiceDate(?Carbon $fromDate = null): Carbon
    {
        $baseDate = $fromDate ?? $this->last_service_date ?? $this->first_service_date ?? now();
        
        if (!$baseDate instanceof Carbon) {
            $baseDate = Carbon::parse($baseDate);
        }

        switch ($this->service_frequency) {
            case 'monthly':
                return $baseDate->copy()->addMonth();
            case 'quarterly':
                return $baseDate->copy()->addMonths(3);
            case 'half_yearly':
                return $baseDate->copy()->addMonths(6);
            case 'yearly':
                return $baseDate->copy()->addYear();
            case 'custom':
                return $baseDate->copy()->addDays($this->custom_days ?? 30);
            default:
                return $baseDate->copy()->addMonth();
        }
    }

    /**
     * Update dates after service completion
     */
    public function updateServiceDates(Carbon $completedDate): void
    {
        $this->last_service_date = $completedDate;
        $this->next_service_date = $this->calculateNextServiceDate($completedDate);
        $this->service_status = 'pending'; // Reset to pending for next service
        $this->save();
    }

    /**
     * Generate contract reference number
     */
    public static function generateReference(): string
    {
        $prefix = 'SVC';
        $year = date('y');
        $lastService = self::whereYear('created_at', date('Y'))->latest()->first();
        $nextNumber = $lastService ? (intval(substr($lastService->id, -5)) + 1) : 1;
        return $prefix . '/' . $year . '/' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            // Set next service date if not provided
            if (empty($service->next_service_date) && $service->first_service_date) {
                $service->next_service_date = $service->first_service_date;
            }
        });
    }
}