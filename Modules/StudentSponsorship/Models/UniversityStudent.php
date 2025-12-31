<?php

namespace Modules\StudentSponsorship\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\StudentSponsorship\Helpers\HashId;
use Carbon\Carbon;

class UniversityStudent extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * The table associated with the model.
     */
    protected $table = 'university_students';

    protected $fillable = [
        // Portal Access
        'user_id',
        
        // Basic Information
        'name',
        'email',
        'contact_no',
        'address',
        'city',
        'zip',
        'country_id',
        
        // University Information
        'university_internal_id',
        'university_id',
        'university_name_id',
        'university_program_id',
        'university_year_of_study',
        'university_student_dob',
        'university_age',
        
        // Bank Information
        'bank_id',
        'university_bank_branch_info',
        'university_bank_branch_number',
        'university_bank_account_no',
        
        // Sponsorship
        'university_sponsorship_start_date',
        'university_sponsorship_end_date',
        
        // Introduction
        'university_introducedby',
        'university_introducedph',
        
        // Family Information
        'university_father_name',
        'university_mother_name',
        'university_father_income',
        'university_mother_income',
        'university_guardian_name',
        'university_guardian_income',
        
        // Comments
        'background_info',
        'internal_comment',
        'external_comment',
        
        // Status
        'active',
        'current_state',
        'staff_id',
    ];

    protected $casts = [
        'university_student_dob' => 'date',
        'university_sponsorship_start_date' => 'date',
        'university_sponsorship_end_date' => 'date',
        'university_father_income' => 'decimal:2',
        'university_mother_income' => 'decimal:2',
        'university_guardian_income' => 'decimal:2',
        'university_age' => 'integer',
        'active' => 'boolean',
    ];

    /**
     * Year/Semester options
     */
    public const YEAR_OF_STUDY = [
        '1Y1S' => 'Year 1 - Semester 1',
        '1Y2S' => 'Year 1 - Semester 2',
        '2Y1S' => 'Year 2 - Semester 1',
        '2Y2S' => 'Year 2 - Semester 2',
        '3Y1S' => 'Year 3 - Semester 1',
        '3Y2S' => 'Year 3 - Semester 2',
        '4Y1S' => 'Year 4 - Semester 1',
        '4Y2S' => 'Year 4 - Semester 2',
        '5Y1S' => 'Year 5 - Semester 1',
        '5Y2S' => 'Year 5 - Semester 2',
    ];

    /**
     * Current state options
     */
    public const CURRENT_STATE = [
        'inprogress' => 'In Progress',
        'complete' => 'Complete',
    ];

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            // Calculate age from DOB
            if ($student->university_student_dob && !$student->university_age) {
                $student->university_age = Carbon::parse($student->university_student_dob)->age;
            }
        });

        static::updating(function ($student) {
            // Recalculate age if DOB changed
            if ($student->isDirty('university_student_dob') && $student->university_student_dob) {
                $student->university_age = Carbon::parse($student->university_student_dob)->age;
            }
        });
    }

    /**
     * Generate unique internal ID
     */
    public static function generateInternalId(): string
    {
        $year = date('Y');
        $prefix = "UNI-{$year}-";
        
        $lastStudent = self::where('university_internal_id', 'like', $prefix . '%')
            ->orderByRaw('CAST(SUBSTRING(university_internal_id, -4) AS UNSIGNED) DESC')
            ->first();

        if ($lastStudent && preg_match('/(\d+)$/', $lastStudent->university_internal_id, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_photo')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    /**
     * Register media conversions
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(400)
            ->height(400)
            ->sharpen(10)
            ->nonQueued();
    }

    // ==================== HASH ID METHODS ====================

    /**
     * Get hash_id attribute
     */
    public function getHashIdAttribute(): string
    {
        return HashId::encode($this->id, 'university_student');
    }

    /**
     * Get full_name attribute (alias for name for consistency with SchoolStudent)
     */
    public function getFullNameAttribute(): ?string
    {
        return $this->name;
    }

    /**
     * Find by hash ID
     */
    public static function findByHash(string $hash): ?self
    {
        $id = HashId::decode($hash, 'university_student');
        return $id ? self::find($id) : null;
    }

    /**
     * Find by hash ID or fail
     */
    public static function findByHashOrFail(string $hash): self
    {
        $student = self::findByHash($hash);
        
        if (!$student) {
            abort(404, 'University student not found');
        }
        
        return $student;
    }

    /**
     * Resolve ID from hash or numeric
     */
    public static function resolveId($hashOrId): ?int
    {
        if (is_numeric($hashOrId)) {
            return (int) $hashOrId;
        }
        
        return HashId::decode($hashOrId, 'university_student');
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the university
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(UniversityName::class, 'university_name_id');
    }

    /**
     * Get the program
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(UniversityProgram::class, 'university_program_id');
    }

    /**
     * Get sponsors (via transactions with ANY payment - partial or completed)
     */
    public function getSponsorsAttribute()
    {
        return Sponsor::whereIn('id', function ($query) {
            $query->select('sponsor_id')
                ->from('sponsor_transactions')
                ->where('university_student_id', $this->id)
                ->whereIn('status', ['partial', 'completed']); // ANY payment creates relation
        })->get();
    }

    /**
     * Get sponsors list with payment details for display
     */
    public function getSponsorsListAttribute(): array
    {
        $sponsors = [];
        
        $transactions = SponsorTransaction::where('university_student_id', $this->id)
            ->whereIn('status', ['partial', 'completed']) // ANY payment
            ->with('sponsor')
            ->get();
        
        foreach ($transactions as $txn) {
            if ($txn->sponsor) {
                $sponsors[] = [
                    'name' => $txn->sponsor->name,
                    'id' => $txn->sponsor->sponsor_internal_id,
                    'sponsor_id' => $txn->sponsor->id,
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
        
        return $sponsors;
    }

    /**
     * Get sponsors count (partial + completed)
     */
    public function getSponsorsCountAttribute(): int
    {
        return \Illuminate\Support\Facades\DB::table('sponsor_transactions')
            ->where('university_student_id', $this->id)
            ->whereIn('status', ['partial', 'completed']) // ANY payment
            ->distinct('sponsor_id')
            ->count('sponsor_id');
    }

    /**
     * Get total amount received from all sponsors
     */
    public function getTotalSponsorshipReceivedAttribute(): float
    {
        return SponsorTransaction::where('university_student_id', $this->id)
            ->sum('amount_paid');
    }

    /**
     * Get total balance pending from all sponsors
     */
    public function getTotalSponsorshipBalanceAttribute(): float
    {
        $transactions = SponsorTransaction::where('university_student_id', $this->id)
            ->whereIn('status', ['pending', 'partial'])
            ->get();
        
        return $transactions->sum(function($t) {
            return max(0, $t->total_amount - $t->amount_paid);
        });
    }

    /**
     * Get the country name (using DB query since Country model may not exist)
     */
    public function getCountryNameAttribute(): ?string
    {
        if (!$this->country_id) {
            return null;
        }
        
        try {
            $country = \DB::table('countries')->where('country_id', $this->country_id)->first();
            return $country ? $country->short_name : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the bank name (using DB query since Bank model may not exist)
     */
    public function getBankNameAttribute(): ?string
    {
        if (!$this->bank_id) {
            return null;
        }
        
        try {
            $bank = \DB::table('banks')->where('id', $this->bank_id)->first();
            return $bank ? $bank->name : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get report cards
     */
    public function reportCards(): HasMany
    {
        return $this->hasMany(UniversityReportCard::class, 'university_student_id');
    }

    // ==================== ACCESSORS ====================

    /**
     * Get university name display
     */
    public function getUniversityNameDisplayAttribute(): string
    {
        return $this->university?->name ?? 'N/A';
    }

    /**
     * Get program name display
     */
    public function getProgramNameDisplayAttribute(): string
    {
        return $this->program?->name ?? 'N/A';
    }

    /**
     * Get year of study display
     */
    public function getYearOfStudyDisplayAttribute(): string
    {
        return self::YEAR_OF_STUDY[$this->university_year_of_study] ?? $this->university_year_of_study ?? 'N/A';
    }

    /**
     * Get status display
     */
    public function getStatusDisplayAttribute(): string
    {
        return $this->active ? 'Active' : 'Inactive';
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->active 
            ? '<span class="badge bg-success">Active</span>'
            : '<span class="badge bg-secondary">Inactive</span>';
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->hasMedia('profile_photo')) {
            return $this->getFirstMediaUrl('profile_photo', 'medium');
        }
        
        return $this->defaultAvatarSvg;
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->hasMedia('profile_photo')) {
            return $this->getFirstMediaUrl('profile_photo', 'thumb');
        }
        
        return $this->defaultAvatarSvg;
    }

    /**
     * Get default avatar SVG
     */
    public function getDefaultAvatarSvgAttribute(): string
    {
        $name = $this->name ?? 'U';
        $initials = collect(explode(' ', $name))
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->join('');

        return "data:image/svg+xml," . rawurlencode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" viewBox="0 0 150 150">' .
            '<rect width="150" height="150" fill="#6366f1"/>' .
            '<text x="50%" y="50%" dominant-baseline="central" text-anchor="middle" ' .
            'font-family="Arial, sans-serif" font-size="48" font-weight="bold" fill="white">' .
            htmlspecialchars($initials) .
            '</text></svg>'
        );
    }

    /**
     * Get age (calculated or stored)
     */
    public function getAgeAttribute(): ?int
    {
        if ($this->university_age) {
            return $this->university_age;
        }
        
        if ($this->university_student_dob) {
            return Carbon::parse($this->university_student_dob)->age;
        }
        
        return null;
    }

    // ==================== SCOPES ====================

    /**
     * Scope for active students
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope for inactive students
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    /**
     * Scope for filtering by university
     */
    public function scopeByUniversity($query, $universityId)
    {
        return $query->where('university_name_id', $universityId);
    }

    /**
     * Scope for filtering by program
     */
    public function scopeByProgram($query, $programId)
    {
        return $query->where('university_program_id', $programId);
    }

    /**
     * Scope for filtering by year of study
     */
    public function scopeByYearOfStudy($query, $year)
    {
        return $query->where('university_year_of_study', $year);
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('contact_no', 'like', "%{$search}%")
              ->orWhere('university_internal_id', 'like', "%{$search}%")
              ->orWhere('university_id', 'like', "%{$search}%");
        });
    }
}
