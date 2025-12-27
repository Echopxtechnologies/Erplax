<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class ServiceRecord extends Model
{
    protected $fillable = [
        'service_id',
        'reference_no',
        'engineer_id',
        'service_type',
        'service_date',
        'service_time',
        'time_taken',
        'status',
        'remarks',
        'work_done',
        'labor_cost',
        'total_cost',
        'is_paid',
        'service_charge',
        'invoice_id',
        'service_reference',
        'dates_updated',
        'created_by',
    ];

    protected $casts = [
        'service_date' => 'date',
        'labor_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'dates_updated' => 'boolean',
        'is_paid' => 'boolean',
    ];

    /**
     * Get the service contract
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the engineer
     */
    public function engineer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'engineer_id');
    }

    /**
     * Get the creator
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get materials used
     */
    public function materials(): HasMany
    {
        return $this->hasMany(ServiceRecordMaterial::class);
    }

    /**
     * Get linked invoice (if exists)
     */
    public function invoice(): BelongsTo
    {
        // Check if Invoice model exists in the system
        if (class_exists(\App\Models\Invoice::class)) {
            return $this->belongsTo(\App\Models\Invoice::class, 'invoice_id');
        }
        return $this->belongsTo(self::class, 'invoice_id'); // Fallback
    }

    /**
     * Get engineer name
     */
    public function getEngineerNameAttribute(): string
    {
        return $this->engineer->name ?? 'Not Assigned';
    }

    /**
     * Get materials total
     */
    public function getMaterialsTotalAttribute(): float
    {
        return $this->materials->sum('total');
    }

    /**
     * Get formatted time taken
     */
    public function getTimeTakenFormattedAttribute(): string
    {
        if (!$this->time_taken) return '-';
        $hours = floor($this->time_taken / 60);
        $mins = $this->time_taken % 60;
        if ($hours > 0) {
            return $hours . 'h ' . ($mins > 0 ? $mins . 'm' : '');
        }
        return $mins . ' min';
    }

    /**
     * Calculate and update total cost
     */
    public function updateTotalCost(): void
    {
        $this->total_cost = $this->labor_cost + $this->materials_total + ($this->service_charge ?? 0);
        $this->save();
    }

    /**
     * Generate reference number
     */
    public static function generateReference(): string
    {
        $prefix = 'SR';
        $year = date('y');
        $month = date('m');
        $lastRecord = self::whereYear('created_at', date('Y'))
                         ->whereMonth('created_at', date('m'))
                         ->latest()
                         ->first();
        $nextNumber = $lastRecord ? (intval(substr($lastRecord->reference_no, -4)) + 1) : 1;
        return $prefix . '/' . $year . $month . '/' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate service reference for invoice tracking
     */
    public static function generateServiceReference(): string
    {
        $prefix = 'SVC';
        $year = date('Y');
        
        // Get max number from valid references this year
        // Valid format: SVC202500001 (SVC + 4 year + 5 number = 12 chars)
        $lastRef = self::whereNotNull('service_reference')
                      ->where('service_reference', 'LIKE', $prefix . $year . '%')
                      ->orderBy('service_reference', 'desc')
                      ->value('service_reference');
        
        $nextNumber = 1;
        if ($lastRef && strlen($lastRef) >= 12) {
            // Extract last 5 digits
            $lastNumber = (int) substr($lastRef, -5);
            $nextNumber = $lastNumber + 1;
        }
        
        // Format: SVC202500001
        return $prefix . $year . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($record) {
            if (empty($record->reference_no)) {
                $record->reference_no = self::generateReference();
            }
            // Generate service reference if paid service
            if ($record->is_paid && empty($record->service_reference)) {
                $record->service_reference = self::generateServiceReference();
            }
        });

        static::saved(function ($record) {
            // Update service dates if marked as completed
            if ($record->status === 'completed' && !$record->dates_updated) {
                $record->service->updateServiceDates($record->service_date);
                $record->dates_updated = true;
                $record->saveQuietly();
            }
        });
    }
}