<?php

namespace Modules\StudentSponsor\Models;

use Illuminate\Database\Eloquent\Model;

class SponsorTransaction extends Model
{
    protected $table = 'tblsponsor_transactions';
    protected $primaryKey = 'id';
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'sponsor_id',
        'school_student_id',
        'university_student_id',
        'total_amount',
        'amount_paid',
        'currency',
        'last_payment_date',
        'next_payment_due',
        'payment_type',
        'due_reminder_active',
        'due_reminder_days_before',
        'scheduled_due_reminder_date',
        'due_reminder_sent',
        'sponsorship_start',
        'sponsorship_end',
        'renewal_reminder_active',
        'renewal_reminder_days_before',
        'scheduled_renewal_reminder',
        'renewal_reminder_sent',
        'created_at',
        'updated_at',
        'last_xday_reminder_sent_at',
        'due_day_email_sent',
        'last_due_day_email_sent_at',
        'due_reminder_sent_at',
        'due_day_email_sent_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'last_payment_date' => 'date',
        'next_payment_due' => 'date',
        'sponsorship_start' => 'date',
        'sponsorship_end' => 'date',
        'scheduled_due_reminder_date' => 'date',
        'scheduled_renewal_reminder' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_xday_reminder_sent_at' => 'datetime',
        'last_due_day_email_sent_at' => 'datetime',
        'due_reminder_sent_at' => 'datetime',
        'due_day_email_sent_at' => 'datetime',
        'due_reminder_active' => 'boolean',
        'due_reminder_sent' => 'boolean',
        'renewal_reminder_active' => 'boolean',
        'renewal_reminder_sent' => 'boolean',
        'due_day_email_sent' => 'boolean',
    ];

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class, 'sponsor_id');
    }

    public function schoolStudent()
    {
        return $this->belongsTo(SchoolStudent::class, 'school_student_id');
    }

    public function universityStudent()
    {
        return $this->belongsTo(UniversityStudent::class, 'university_student_id');
    }

    public function payments()
    {
        return $this->hasMany(SponsorPayment::class, 'transaction_id');
    }

    // Get student (either school or university)
    public function getStudentAttribute()
    {
        return $this->schoolStudent ?? $this->universityStudent;
    }

    // Get student type
    public function getStudentTypeAttribute()
    {
        if ($this->school_student_id) return 'school';
        if ($this->university_student_id) return 'university';
        return null;
    }

    /**
     * Compute next payment due date based on payment type and last payment
     */
    public function computeNextPaymentDue()
    {
        $anchor = $this->last_payment_date ?? $this->sponsorship_start ?? now();
        
        if ($anchor instanceof \Carbon\Carbon) {
            $anchorDate = $anchor;
        } else {
            $anchorDate = \Carbon\Carbon::parse($anchor);
        }

        switch ($this->payment_type) {
            case 'monthly':
                $this->next_payment_due = $anchorDate->copy()->addMonth();
                break;
            case 'quarterly':
                $this->next_payment_due = $anchorDate->copy()->addMonths(3);
                break;
            case 'yearly':
                $this->next_payment_due = $anchorDate->copy()->addYear();
                break;
            case 'one_time':
            case 'custom':
            default:
                // Don't auto-calculate for one_time or custom
                break;
        }

        $this->save();
    }

    /**
     * Get balance (remaining amount)
     */
    public function getBalanceAttribute()
    {
        return $this->total_amount - $this->amount_paid;
    }
}
