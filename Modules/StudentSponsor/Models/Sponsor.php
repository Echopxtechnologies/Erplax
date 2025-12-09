<?php

namespace Modules\StudentSponsor\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $table = 'tblsponsor_records';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'entity_type',
        'name',
        'sponsor_type',
        'sponsor_occupation',
        'contact_no',
        'email',
        'address',
        'city',
        'state',
        'zip',
        'country_id',
        'bank_id',
        'sponsor_bank_branch_info',
        'sponsor_bank_branch_number',
        'sponsor_bank_account_no',
        'sponsor_frequency',
        'product_id',
        'membership_start_date',
        'membership_end_date',
        'school_internal_ids',
        'university_internal_ids',
        'created_on',
        'staff_id',
        'active',
    ];

    protected $casts = [
        'created_on' => 'datetime',
        'membership_start_date' => 'date',
        'membership_end_date' => 'date',
        'active' => 'boolean',
        'school_internal_ids' => 'array',
        'university_internal_ids' => 'array',
    ];

    /**
     * Bank relationship
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    /**
     * Country relationship
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }

    /**
     * School students relationship
     */
    public function schoolStudents()
    {
        return $this->hasMany(SchoolStudent::class, 'sponsor_id');
    }

    /**
     * University students relationship
     */
    public function universityStudents()
    {
        return $this->hasMany(UniversityStudent::class, 'sponsor_id');
    }

    /**
     * Transactions relationship
     */
    public function transactions()
    {
        return $this->hasMany(SponsorTransaction::class, 'sponsor_id');
    }

    /**
     * Payments relationship
     */
    public function payments()
    {
        return $this->hasMany(SponsorPayment::class, 'sponsor_id');
    }

    /**
     * Get all students (combined)
     */
    public function getAllStudentsAttribute()
    {
        return $this->schoolStudents->merge($this->universityStudents);
    }

    /**
     * Get total students count
     */
    public function getTotalStudentsCountAttribute()
    {
        return $this->schoolStudents()->count() + $this->universityStudents()->count();
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->zip,
            $this->country?->short_name,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return $this->active ? 'Active' : 'Inactive';
    }

    /**
     * Get sponsor type label
     */
    public function getSponsorTypeLabelAttribute()
    {
        $types = [
            'individual' => 'Individual',
            'company' => 'Company',
            'organization' => 'Organization',
            'trust' => 'Trust/Foundation',
            'charity' => 'Charity',
        ];

        return $types[$this->sponsor_type] ?? ucfirst($this->sponsor_type ?? 'Unknown');
    }

    /**
     * Get frequency label
     */
    public function getFrequencyLabelAttribute()
    {
        $frequencies = [
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'bi_annually' => 'Bi-Annually',
            'yearly' => 'Yearly',
            'one_time' => 'One Time',
        ];

        return $frequencies[$this->sponsor_frequency] ?? ucfirst(str_replace('_', ' ', $this->sponsor_frequency ?? 'Not Set'));
    }

    /**
     * Check if membership is active
     */
    public function hasMembershipActive()
    {
        $now = now();
        $startDate = $this->membership_start_date;
        $endDate = $this->membership_end_date;

        if (!$startDate) {
            return true; // No start date means always active
        }

        if ($startDate && $endDate) {
            return $now->between($startDate, $endDate);
        }

        if ($startDate && !$endDate) {
            return $now->gte($startDate);
        }

        return true;
    }

    /**
     * Get total committed amount
     */
    public function getTotalCommittedAttribute()
    {
        return $this->transactions()->sum('total_amount');
    }

    /**
     * Get total paid amount
     */
    public function getTotalPaidAttribute()
    {
        return $this->transactions()->sum('amount_paid');
    }

    /**
     * Get outstanding amount
     */
    public function getOutstandingAmountAttribute()
    {
        return $this->total_committed - $this->total_paid;
    }

    /**
     * Scope: Active sponsors
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Scope: Inactive sponsors
     */
    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }

    /**
     * Scope: By type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('sponsor_type', $type);
    }

    /**
     * Scope: Actively sponsoring (has students)
     */
    public function scopeActivelySponsoring($query)
    {
        return $query->where(function($q) {
            $q->whereHas('schoolStudents')
              ->orWhereHas('universityStudents');
        });
    }

    /**
     * Scope: With membership expiring soon
     */
    public function scopeExpiringWithin($query, $days = 30)
    {
        return $query->whereNotNull('membership_end_date')
            ->where('membership_end_date', '<=', now()->addDays($days))
            ->where('membership_end_date', '>=', now());
    }
}
