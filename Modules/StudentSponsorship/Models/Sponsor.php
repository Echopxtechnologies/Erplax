<?php

namespace Modules\StudentSponsorship\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Sponsor extends Model
{
    protected $table = 'sponsors';

    protected $fillable = [
        // Basic Info
        'sponsor_internal_id',
        'name',
        'sponsor_type',
        'sponsor_occupation',
        'email',
        'country_id',
        'contact_no',
        'city',
        'address',
        'zip',
        
        // Banking
        'bank_id',
        'sponsor_bank_branch_info',
        'sponsor_bank_branch_number',
        'sponsor_bank_account_no',
        
        // Sponsorship
        'membership_start_date',
        'membership_end_date',
        'sponsor_frequency',
        
        // Comments
        'background_info',
        'internal_comment',
        'external_comment',
        
        // Status
        'active',
        'user_id',
        'staff_id',  // For portal access via staff portal
    ];

    protected $casts = [
        'active' => 'boolean',
        'membership_start_date' => 'date',
        'membership_end_date' => 'date',
    ];

    // =========================================
    // RELATIONSHIPS
    // =========================================

    public function country(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Country::class, 'country_id', 'country_id');
    }

    /**
     * Staff relationship for portal access
     * Staff uses admin guard for authentication
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Admin\Staff::class, 'staff_id', 'id');
    }

    // Note: Bank relationship uses DB query since Bank model may not exist
    // Use getBankNameAttribute() accessor instead

    // =========================================
    // ACCESSORS
    // =========================================

    public function getCountryNameAttribute(): ?string
    {
        return $this->country?->short_name;
    }

    /**
     * Get bank name via DB query (Bank model may not exist)
     */
    public function getBankNameAttribute(): ?string
    {
        if (!$this->bank_id) {
            return null;
        }
        
        $bank = DB::table('banks')->where('id', $this->bank_id)->first();
        return $bank?->name;
    }

    /**
     * Get bank name for display
     */
    public function getBankNameDisplayAttribute(): ?string
    {
        return $this->bank_name;
    }

    public function getSponsorTypeDisplayAttribute(): string
    {
        return $this->sponsor_type === 'company' ? 'Company' : 'Individual';
    }

    public function getFrequencyDisplayAttribute(): ?string
    {
        $frequencies = [
            'one_time' => 'One-time',
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'half_yearly' => 'Half-yearly',
            'yearly' => 'Yearly',
        ];
        
        return $frequencies[$this->sponsor_frequency] ?? null;
    }

    public function getStatusDisplayAttribute(): string
    {
        return $this->active ? 'Active' : 'Inactive';
    }

    /**
     * Check if sponsor has portal access
     */
    public function getHasPortalAccessAttribute(): bool
    {
        return !empty($this->staff_id);
    }

    /**
     * Get portal login email (from staff's admin record)
     */
    public function getPortalEmailAttribute(): ?string
    {
        if (!$this->staff_id) {
            return null;
        }
        
        $staff = $this->staff;
        if ($staff && $staff->admin) {
            return $staff->admin->email;
        }
        
        return $staff?->email;
    }

    // =========================================
    // SPONSORSHIP RELATIONSHIPS (via transactions with ANY payment)
    // =========================================

    /**
     * Get all transactions for this sponsor
     */
    public function transactions()
    {
        return $this->hasMany(SponsorTransaction::class, 'sponsor_id');
    }

    /**
     * Get active transactions (with at least one payment - partial or completed)
     */
    public function activeTransactions()
    {
        return $this->hasMany(SponsorTransaction::class, 'sponsor_id')
            ->whereIn('status', ['partial', 'completed']);
    }

    /**
     * Get sponsored school students (via transactions with ANY payment)
     */
    public function sponsoredSchoolStudents()
    {
        return SchoolStudent::whereIn('id', function ($query) {
            $query->select('school_student_id')
                ->from('sponsor_transactions')
                ->where('sponsor_id', $this->id)
                ->whereIn('status', ['partial', 'completed']) // ANY payment creates relation
                ->whereNotNull('school_student_id');
        })->get();
    }

    /**
     * Get sponsored university students (via transactions with ANY payment)
     */
    public function sponsoredUniversityStudents()
    {
        return UniversityStudent::whereIn('id', function ($query) {
            $query->select('university_student_id')
                ->from('sponsor_transactions')
                ->where('sponsor_id', $this->id)
                ->whereIn('status', ['partial', 'completed']) // ANY payment creates relation
                ->whereNotNull('university_student_id');
        })->get();
    }

    /**
     * Get all sponsored students count (partial + completed)
     */
    public function getSponsoredStudentsCountAttribute(): int
    {
        return SponsorTransaction::where('sponsor_id', $this->id)
            ->whereIn('status', ['partial', 'completed']) // ANY payment counts
            ->where(function ($q) {
                $q->whereNotNull('school_student_id')
                  ->orWhereNotNull('university_student_id');
            })
            ->count();
    }

    /**
     * Get total amount paid by this sponsor
     */
    public function getTotalPaidAttribute(): float
    {
        return SponsorTransaction::where('sponsor_id', $this->id)
            ->sum('amount_paid');
    }

    /**
     * Get total balance remaining
     */
    public function getTotalBalanceAttribute(): float
    {
        $transactions = SponsorTransaction::where('sponsor_id', $this->id)
            ->whereIn('status', ['pending', 'partial'])
            ->get();
        
        return $transactions->sum(function($t) {
            return max(0, $t->total_amount - $t->amount_paid);
        });
    }

    /**
     * Get sponsored students names as array (partial + completed)
     */
    public function getSponsoredStudentsListAttribute(): array
    {
        $students = [];
        
        // School students with payment info
        $schoolTransactions = SponsorTransaction::where('sponsor_id', $this->id)
            ->whereIn('status', ['partial', 'completed'])
            ->whereNotNull('school_student_id')
            ->with('schoolStudent')
            ->get();
        
        foreach ($schoolTransactions as $txn) {
            if ($txn->schoolStudent) {
                $students[] = [
                    'name' => $txn->schoolStudent->full_name,
                    'type' => 'School',
                    'id' => $txn->schoolStudent->school_student_id,
                    'student_id' => $txn->schoolStudent->id,
                    'hash_id' => $txn->schoolStudent->hash_id ?? null,
                    'amount_paid' => $txn->amount_paid,
                    'total_amount' => $txn->total_amount,
                    'balance' => max(0, $txn->total_amount - $txn->amount_paid),
                    'status' => $txn->status,
                    'currency' => $txn->currency,
                    'currency_symbol' => $txn->currency_symbol,
                    'transaction_id' => $txn->id,
                ];
            }
        }
        
        // University students with payment info
        $uniTransactions = SponsorTransaction::where('sponsor_id', $this->id)
            ->whereIn('status', ['partial', 'completed'])
            ->whereNotNull('university_student_id')
            ->with('universityStudent')
            ->get();
        
        foreach ($uniTransactions as $txn) {
            if ($txn->universityStudent) {
                $students[] = [
                    'name' => $txn->universityStudent->full_name,
                    'type' => 'University',
                    'id' => $txn->universityStudent->university_internal_id,
                    'student_id' => $txn->universityStudent->id,
                    'hash_id' => $txn->universityStudent->hash_id ?? null,
                    'amount_paid' => $txn->amount_paid,
                    'total_amount' => $txn->total_amount,
                    'balance' => max(0, $txn->total_amount - $txn->amount_paid),
                    'status' => $txn->status,
                    'currency' => $txn->currency,
                    'currency_symbol' => $txn->currency_symbol,
                    'transaction_id' => $txn->id,
                ];
            }
        }
        
        return $students;
    }

    // =========================================
    // SCOPES
    // =========================================

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }

    public function scopeIndividual($query)
    {
        return $query->where('sponsor_type', 'individual');
    }

    public function scopeCompany($query)
    {
        return $query->where('sponsor_type', 'company');
    }

    public function scopeWithPortalAccess($query)
    {
        return $query->whereNotNull('staff_id');
    }

    public function scopeWithoutPortalAccess($query)
    {
        return $query->whereNull('staff_id');
    }
}
