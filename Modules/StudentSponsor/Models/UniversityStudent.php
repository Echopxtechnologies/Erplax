<?php

namespace Modules\StudentSponsor\Models;

use Illuminate\Database\Eloquent\Model;

class UniversityStudent extends Model
{
    protected $table = 'tbluniversity_students';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'entity_type',
        'name',
        'profile_photo',
        'contact_no',
        'email',
        'address',
        'city',
        'zip',
        'country_id',
        'university_internal_id',
        'university_id',
        'university_name_id',
        'university_program_id',
        'university_year_of_study',
        'university_semester',
        'university_student_dob',
        'university_age',
        'bank_id',
        'university_bank_branch_info',
        'university_bank_branch_number',
        'university_bank_account_no',
        'university_sponsorship_start_date',
        'university_sponsorship_end_date',
        'university_introducedby',
        'university_introducedph',
        'university_father_name',
        'university_mother_name',
        'university_father_income',
        'university_mother_income',
        'university_guardian_name',
        'university_guardian_income',
        'sponsor_id',
        'background_info',
        'internal_comment',
        'external_comment',
        'created_on',
        'staff_id',
        'active',
    ];

    protected $casts = [
        'created_on' => 'datetime',
        'university_student_dob' => 'date',
        'university_sponsorship_start_date' => 'date',
        'university_sponsorship_end_date' => 'date',
        'university_father_income' => 'float',
        'university_mother_income' => 'float',
        'university_guardian_income' => 'float',
        'university_age' => 'integer',
        'active' => 'boolean',
    ];

    /**
     * Hidden attributes (for JSON)
     */
    protected $hidden = [
        'profile_photo', // Don't include BLOB in JSON responses
    ];

    /**
     * University name relationship
     */
    public function universityName()
    {
        return $this->belongsTo(UniversityName::class, 'university_name_id');
    }

    /**
     * Program relationship
     */
    public function program()
    {
        return $this->belongsTo(UniversityProgram::class, 'university_program_id');
    }

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
     * Sponsor relationship
     */
    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class, 'sponsor_id');
    }

    /**
     * Transactions relationship
     */
    public function transactions()
    {
        return $this->hasMany(SponsorTransaction::class, 'university_student_id');
    }

    /**
     * Report cards relationship
     */
    public function reportCards()
    {
        return $this->hasMany(UniversityReportCard::class, 'student_university_id');
    }

    /**
     * Check if student is sponsored
     */
    public function isSponsored()
    {
        return !is_null($this->sponsor_id);
    }

    /**
     * Check if student has active sponsorship
     */
    public function hasActiveSponsor()
    {
        if (!$this->sponsor_id) {
            return false;
        }

        $now = now();
        $startDate = $this->university_sponsorship_start_date;
        $endDate = $this->university_sponsorship_end_date;

        if ($startDate && $endDate) {
            return $now->between($startDate, $endDate);
        }

        if ($startDate && !$endDate) {
            return $now->gte($startDate);
        }

        return true;
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
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
     * Get sponsorship status
     */
    public function getSponsorshipStatusAttribute()
    {
        if (!$this->sponsor_id) {
            return 'Not Sponsored';
        }

        if ($this->hasActiveSponsor()) {
            return 'Active Sponsorship';
        }

        return 'Sponsorship Expired';
    }

    /**
     * Get year and semester display
     */
    public function getYearSemesterDisplayAttribute()
    {
        $parts = array_filter([
            $this->university_year_of_study,
            $this->university_semester,
        ]);

        return implode(' - ', $parts) ?: '-';
    }

    /**
     * Scope: Active students
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Scope: Sponsored students
     */
    public function scopeSponsored($query)
    {
        return $query->whereNotNull('sponsor_id');
    }

    /**
     * Scope: Unsponsored students
     */
    public function scopeUnsponsored($query)
    {
        return $query->whereNull('sponsor_id');
    }

    /**
     * Scope: By university
     */
    public function scopeByUniversity($query, $universityId)
    {
        return $query->where('university_name_id', $universityId);
    }

    /**
     * Scope: By program
     */
    public function scopeByProgram($query, $programId)
    {
        return $query->where('university_program_id', $programId);
    }

    /**
     * Scope: By year of study
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('university_year_of_study', $year);
    }
}
