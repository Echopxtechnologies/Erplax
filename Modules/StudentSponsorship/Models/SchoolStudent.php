<?php

namespace Modules\StudentSponsorship\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SchoolStudent extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'school_internal_id',
        
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
        
        $lastStudent = self::withTrashed()
            ->where('school_internal_id', 'LIKE', "{$prefix}{$year}%")
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
            ->singleFile()
            ->useFallbackUrl('/images/default-avatar.png')
            ->useFallbackPath(public_path('/images/default-avatar.png'));
        
        // For report cards
        $this->addMediaCollection('report_cards');
        
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
    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('profile_photo', 'medium') 
            ?: $this->getFirstMediaUrl('profile_photo') 
            ?: asset('images/default-avatar.png');
    }

    /**
     * Get profile photo thumbnail URL
     */
    public function getProfilePhotoThumbAttribute(): string
    {
        return $this->getFirstMediaUrl('profile_photo', 'thumb') 
            ?: $this->getFirstMediaUrl('profile_photo') 
            ?: asset('images/default-avatar.png');
    }

    /**
     * Check if has profile photo
     */
    public function hasProfilePhoto(): bool
    {
        return $this->hasMedia('profile_photo');
    }

    /**
     * Get report cards
     */
    public function getReportCardsAttribute()
    {
        return $this->getMedia('report_cards');
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
