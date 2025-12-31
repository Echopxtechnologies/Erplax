<?php

namespace Modules\StudentSponsorship\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SponsorTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'sponsor_transactions';

    protected $fillable = [
        'transaction_number',
        'sponsor_id',
        'school_student_id',
        'university_student_id',
        'total_amount',
        'amount_paid',
        'currency',
        'payment_type',
        'status',
        'last_payment_date',
        'next_payment_due',
        'due_reminder_active',
        'days_before_due',
        'x_days_email_sent',
        'due_day_email_sent',
        'description',
        'internal_note',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'last_payment_date' => 'date',
        'next_payment_due' => 'date',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_reminder_active' => 'boolean',
        'x_days_email_sent' => 'boolean',
        'due_day_email_sent' => 'boolean',
    ];

    // Currency options
    public const CURRENCIES = [
        'LKR' => 'Sri Lankan Rupees (LKR)',
        'USD' => 'US Dollars (USD)',
        'CAD' => 'Canadian Dollars (CAD)',
        'GBP' => 'UK Pounds (GBP)',
        'AUD' => 'Australian Dollars (AUD)',
    ];

    // Payment type options
    public const PAYMENT_TYPES = [
        'one_time' => 'One time',
        'monthly' => 'Monthly',
        'quarterly' => 'Quarterly',
        'yearly' => 'Yearly',
        'custom' => 'Custom',
    ];

    // =========================================
    // RELATIONSHIPS
    // =========================================

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(Sponsor::class, 'sponsor_id');
    }

    public function schoolStudent(): BelongsTo
    {
        return $this->belongsTo(SchoolStudent::class, 'school_student_id');
    }

    public function universityStudent(): BelongsTo
    {
        return $this->belongsTo(UniversityStudent::class, 'university_student_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SponsorPayment::class, 'transaction_id');
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * Get the student (either school or university)
     */
    public function getStudentAttribute()
    {
        if ($this->school_student_id) {
            return $this->schoolStudent;
        } elseif ($this->university_student_id) {
            return $this->universityStudent;
        }
        return null;
    }

    public function getStudentTypeAttribute(): ?string
    {
        if ($this->school_student_id) return 'school';
        if ($this->university_student_id) return 'university';
        return null;
    }

    public function getStudentNameAttribute(): ?string
    {
        $student = $this->student;
        return $student ? $student->full_name : null;
    }

    public function getStudentIdDisplayAttribute(): ?string
    {
        if ($this->school_student_id && $this->schoolStudent) {
            return $this->schoolStudent->school_student_id ?? $this->schoolStudent->school_internal_id;
        }
        if ($this->university_student_id && $this->universityStudent) {
            return $this->universityStudent->university_internal_id;
        }
        return null;
    }

    public function getCurrencySymbolAttribute(): string
    {
        // Return currency code with space for display like "LKR ", "USD ", etc.
        return $this->currency . ' ';
    }

    public function getFormattedTotalAttribute(): string
    {
        return $this->currency . ' ' . number_format($this->total_amount, 2);
    }

    public function getFormattedPaidAttribute(): string
    {
        return $this->currency . ' ' . number_format($this->amount_paid, 2);
    }

    public function getFormattedBalanceAttribute(): string
    {
        $balance = $this->total_amount - $this->amount_paid;
        if ($balance < 0) {
            // Overpaid - show as extra contribution
            return $this->currency . ' ' . number_format(abs($balance), 2) . ' (Extra)';
        }
        return $this->currency . ' ' . number_format($balance, 2);
    }

    public function getBalanceAttribute(): float
    {
        return $this->total_amount - $this->amount_paid;
    }

    /**
     * Get extra amount paid beyond total
     */
    public function getExtraAmountAttribute(): float
    {
        $balance = $this->total_amount - $this->amount_paid;
        return $balance < 0 ? abs($balance) : 0;
    }

    /**
     * Check if overpaid
     */
    public function getIsOverpaidAttribute(): bool
    {
        return $this->amount_paid > $this->total_amount;
    }

    public function getPaymentProgressAttribute(): float
    {
        if ($this->total_amount <= 0) return 0;
        // Can exceed 100% for overpayments
        return ($this->amount_paid / $this->total_amount) * 100;
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'warning',
            'partial' => 'info',
            'completed' => 'success',
            'cancelled' => 'secondary',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusDisplayAttribute(): string
    {
        $statuses = [
            'pending' => 'Pending',
            'partial' => 'Partial',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];
        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    public function getPaymentTypeDisplayAttribute(): string
    {
        return self::PAYMENT_TYPES[$this->payment_type] ?? ucfirst($this->payment_type);
    }

    public function getCurrencyDisplayAttribute(): string
    {
        return self::CURRENCIES[$this->currency] ?? $this->currency;
    }

    // =========================================
    // SCOPES
    // =========================================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeBySponsor($query, $sponsorId)
    {
        return $query->where('sponsor_id', $sponsorId);
    }

    public function scopeDueWithin($query, $days = 7)
    {
        return $query->where('next_payment_due', '<=', now()->addDays($days))
                     ->whereIn('status', ['pending', 'partial']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('next_payment_due', '<', now())
                     ->whereIn('status', ['pending', 'partial']);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }

    // =========================================
    // METHODS
    // =========================================

    /**
     * Generate unique transaction number
     */
    public static function generateTransactionNumber(): string
    {
        $prefix = 'TXN';
        $year = date('Y');
        $month = date('m');
        
        $last = self::where('transaction_number', 'like', "{$prefix}{$year}{$month}%")
            ->orderBy('transaction_number', 'desc')
            ->first();
        
        if ($last && preg_match('/' . $prefix . $year . $month . '(\d+)/', $last->transaction_number, $matches)) {
            $sequence = intval($matches[1]) + 1;
        } else {
            $sequence = 1;
        }
        
        return $prefix . $year . $month . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate next payment due date based on payment type
     */
    public function calculateNextDueDate(): ?string
    {
        if (!$this->last_payment_date) {
            return now()->format('Y-m-d');
        }

        $lastDate = $this->last_payment_date;

        switch ($this->payment_type) {
            case 'monthly':
                return $lastDate->addMonth()->format('Y-m-d');
            case 'quarterly':
                return $lastDate->addMonths(3)->format('Y-m-d');
            case 'yearly':
                return $lastDate->addYear()->format('Y-m-d');
            default:
                return null;
        }
    }

    /**
     * Check if payment is due
     */
    public function isDue(): bool
    {
        if (!$this->next_payment_due) return false;
        return $this->next_payment_due <= now() && $this->status !== 'completed';
    }

    /**
     * Check if reminder should be sent
     */
    public function shouldSendReminder(): bool
    {
        if (!$this->due_reminder_active) return false;
        if (!$this->next_payment_due) return false;
        if ($this->status === 'completed') return false;

        $daysUntilDue = now()->diffInDays($this->next_payment_due, false);
        
        return $daysUntilDue <= $this->days_before_due && !$this->x_days_email_sent;
    }

    /**
     * Mark as cancelled
     */
    public function markCancelled(): bool
    {
        $this->status = 'cancelled';
        return $this->save();
    }

    /**
     * Recalculate totals from payments
     */
    public function recalculateTotals(): void
    {
        $this->amount_paid = $this->payments()->sum('amount');
        
        $lastPayment = $this->payments()->orderBy('payment_date', 'desc')->first();
        $this->last_payment_date = $lastPayment?->payment_date;

        if ($this->amount_paid >= $this->total_amount) {
            $this->status = 'completed';
        } elseif ($this->amount_paid > 0) {
            $this->status = 'partial';
        }

        $this->save();
    }
}
