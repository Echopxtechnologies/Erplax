<?php

namespace Modules\StudentSponsorship\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Modules\StudentSponsorship\Helpers\HashId;

class SchoolStudent extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * The table associated with the model.
     */
    protected $table = 'school_students';

    /**
     * Get hashed ID for URLs
     */
    public function getHashIdAttribute(): string
    {
        return HashId::encode($this->id);
    }

    /**
     * Find by hashed ID
     */
    public static function findByHash($hash)
    {
        $id = HashId::decode($hash);
        
        if (!$id) {
            return null;
        }
        
        return static::find($id);
    }

    /**
     * Find by hashed ID or fail
     */
    public static function findByHashOrFail($hash)
    {
        $id = HashId::decode($hash);
        
        if (!$id) {
            abort(404, 'Student not found');
        }
        
        return static::findOrFail($id);
    }

    /**
     * Encode an ID to hash
     */
    public static function encodeId($id): string
    {
        return HashId::encode($id);
    }

    /**
     * Decode hash to ID
     */
    public static function decodeHash($hash): ?int
    {
        return HashId::decode($hash);
    }

    protected $fillable = [
        'school_internal_id',
        'school_student_id',
        'user_id',
        
        // Student Info - Basic
        'full_name',
        'email',
        'phone',
        'dob',
        'age',
        'country_id',
        'address',
        'city',
        'postal_code',
        
        // Student Info - School
        'grade',
        'grade_mismatch_reason',
        'current_state',
        'school_type',
        'school_id',
        
        // Sponsorship
        'sponsorship_start_date',
        'sponsorship_end_date',
        'introduced_by',
        'introducer_phone',
        
        // Bank Info
        'bank_id',
        'bank_account_number',
        'bank_branch_number',
        'bank_branch_info',
        
        // Family Info
        'father_name',
        'father_income',
        'mother_name',
        'mother_income',
        'guardian_name',
        'guardian_income',
        'background_info',
        
        // Additional Info
        'internal_comment',
        'external_comment',
        
        'status',
    ];

    protected $casts = [
        'dob' => 'date',
        'sponsorship_start_date' => 'date',
        'sponsorship_end_date' => 'date',
        'status' => 'boolean',
        'father_income' => 'decimal:2',
        'mother_income' => 'decimal:2',
        'guardian_income' => 'decimal:2',
        'age' => 'integer',
    ];

    /**
     * Get the school
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(SchoolName::class, 'school_id');
    }

    /**
     * Get sponsors (via transactions with ANY payment - partial or completed)
     */
    public function getSponsorsAttribute()
    {
        return Sponsor::whereIn('id', function ($query) {
            $query->select('sponsor_id')
                ->from('sponsor_transactions')
                ->where('school_student_id', $this->id)
                ->whereIn('status', ['partial', 'completed']); // ANY payment creates relation
        })->get();
    }

    /**
     * Get sponsors list with payment details for display
     */
    public function getSponsorsListAttribute(): array
    {
        $sponsors = [];
        
        $transactions = SponsorTransaction::where('school_student_id', $this->id)
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
            ->where('school_student_id', $this->id)
            ->whereIn('status', ['partial', 'completed']) // ANY payment
            ->distinct('sponsor_id')
            ->count('sponsor_id');
    }

    /**
     * Get total amount received from all sponsors
     */
    public function getTotalSponsorshipReceivedAttribute(): float
    {
        return SponsorTransaction::where('school_student_id', $this->id)
            ->sum('amount_paid');
    }

    /**
     * Get total balance pending from all sponsors
     */
    public function getTotalSponsorshipBalanceAttribute(): float
    {
        $transactions = SponsorTransaction::where('school_student_id', $this->id)
            ->whereIn('status', ['pending', 'partial'])
            ->get();
        
        return $transactions->sum(function($t) {
            return max(0, $t->total_amount - $t->amount_paid);
        });
    }

    /**
     * Get the country - using DB query since model may not exist
     */
    public function country(): BelongsTo
    {
        // Try to use model if exists, otherwise return empty relation
        if (class_exists('\App\Models\Country')) {
            return $this->belongsTo(\App\Models\Country::class, 'country_id', 'country_id');
        }
        return $this->belongsTo(SchoolName::class, 'country_id', 'id')->withDefault();
    }

    /**
     * Get the bank - using DB query since model may not exist
     */
    public function bank(): BelongsTo
    {
        if (class_exists('\App\Models\Bank')) {
            return $this->belongsTo(\App\Models\Bank::class, 'bank_id', 'id');
        }
        return $this->belongsTo(SchoolName::class, 'bank_id', 'id')->withDefault();
    }

    /**
     * Get school name for display
     */
    public function getSchoolNameDisplayAttribute(): string
    {
        return $this->school->name ?? 'N/A';
    }

    /**
     * Get country name for display
     */
    public function getCountryNameAttribute(): string
    {
        if ($this->country_id) {
            $country = \DB::table('countries')->where('country_id', $this->country_id)->first();
            return $country->short_name ?? 'N/A';
        }
        return 'N/A';
    }

    /**
     * Get bank name for display
     */
    public function getBankNameDisplayAttribute(): string
    {
        if ($this->bank_id) {
            $bank = \DB::table('banks')->where('id', $this->bank_id)->first();
            return $bank->name ?? 'N/A';
        }
        return 'N/A';
    }

    /**
     * Scope for active students
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope for search
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('full_name', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('school_internal_id', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Generate next school internal ID
     */
    public static function generateInternalId(): string
    {
        $prefix = 'SCH';
        $year = date('Y');
        
        $lastStudent = self::where('school_internal_id', 'LIKE', "{$prefix}{$year}%")
            ->orderBy('school_internal_id', 'desc')
            ->first();
        
        if ($lastStudent) {
            $lastNum = (int) substr($lastStudent->school_internal_id, -5);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }
        
        return $prefix . $year . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Register media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_photo')
            ->singleFile();
        
        // Other documents
        $this->addMediaCollection('documents');
    }

    /**
     * Register media conversions (thumbnails)
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

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('profile_photo', 'medium') 
            ?: $this->getFirstMediaUrl('profile_photo') 
            ?: null;
    }

    /**
     * Get profile photo thumbnail URL
     */
    public function getProfilePhotoThumbAttribute(): ?string
    {
        return $this->getFirstMediaUrl('profile_photo', 'thumb') 
            ?: $this->getFirstMediaUrl('profile_photo') 
            ?: null;
    }

    /**
     * Check if has profile photo
     */
    public function hasProfilePhoto(): bool
    {
        return $this->hasMedia('profile_photo');
    }

    /**
     * Get report cards from database table
     */
    public function getReportCardsFromDb()
    {
        return \DB::table('school_report_cards')
            ->where('student_school_id', $this->id)
            ->orderBy('created_on', 'desc')
            ->get();
    }

    /**
     * Calculate age from DOB
     */
    public function calculateAge(): ?int
    {
        if (!$this->dob) return null;
        return $this->dob->age;
    }
}
