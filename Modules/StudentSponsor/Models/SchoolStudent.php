<?php

namespace Modules\StudentSponsor\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SchoolStudent extends Model
{
    protected $table = 'tblschool_students';
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
        'school_internal_id',
        'school_id',
        'school_type',
        'school_name_id',
        'school_grade_year',
        'school_grade',
        'grade_mismatch_reason',
        'school_student_dob',
        'school_age',
        'bank_id',
        'school_bank_branch_number',
        'school_bank_branch_info',
        'school_bank_account_no',
        'school_sponsorship_start_date',
        'school_sponsorship_end_date',
        'school_introducedby',
        'school_introducedph',
        'school_father_name',
        'school_mother_name',
        'school_father_income',
        'school_mother_income',
        'school_guardian_name',
        'school_guardian_income',
        'sponsor_id',
        'background_info',
        'internal_comment',
        'external_comment',
        'created_on',
    ];

    protected $casts = [
        'school_student_dob' => 'date',
        'school_sponsorship_start_date' => 'date',
        'school_sponsorship_end_date' => 'date',
        'school_father_income' => 'decimal:2',
        'school_mother_income' => 'decimal:2',
        'school_guardian_income' => 'decimal:2',
        'school_age' => 'integer',
        'country_id' => 'integer',
        'school_name_id' => 'integer',
        'bank_id' => 'integer',
        'sponsor_id' => 'integer',
        'created_on' => 'datetime',
    ];

    // Grade to age mapping for Sri Lankan education system
    public static $gradeAgeMapping = [
        '1' => ['min' => 5, 'max' => 6, 'name' => 'Grade 1'],
        '2' => ['min' => 6, 'max' => 7, 'name' => 'Grade 2'],
        '3' => ['min' => 7, 'max' => 8, 'name' => 'Grade 3'],
        '4' => ['min' => 8, 'max' => 9, 'name' => 'Grade 4'],
        '5' => ['min' => 9, 'max' => 10, 'name' => 'Grade 5'],
        '6' => ['min' => 10, 'max' => 11, 'name' => 'Grade 6'],
        '7' => ['min' => 11, 'max' => 12, 'name' => 'Grade 7'],
        '8' => ['min' => 12, 'max' => 13, 'name' => 'Grade 8'],
        '9' => ['min' => 13, 'max' => 14, 'name' => 'Grade 9'],
        '10' => ['min' => 14, 'max' => 15, 'name' => 'Grade 10'],
        'O/L' => ['min' => 15, 'max' => 16, 'name' => 'O/L (Grade 11)'],
        'A/L1' => ['min' => 16, 'max' => 17, 'name' => 'A/L1 (Grade 12)'],
        'A/L2' => ['min' => 17, 'max' => 18, 'name' => 'A/L2 (Grade 13)'],
        'A/L Final' => ['min' => 18, 'max' => 19, 'name' => 'A/L Final (Grade 14)'],
    ];

    // Relationships
    public function schoolName()
    {
        return $this->belongsTo(SchoolName::class, 'school_name_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class, 'sponsor_id');
    }

    public function reportCards()
    {
        return $this->hasMany(SchoolReportCard::class, 'student_school_id');
    }

    // Accessors
    public function getAgeAttribute()
    {
        if ($this->school_student_dob) {
            return Carbon::parse($this->school_student_dob)->age;
        }
        return $this->school_age;
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return route('admin.studentsponsor.school.photo', $this->id);
        }
        return null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('staff_active', 1);
    }

    public function scopeSponsored($query)
    {
        return $query->whereNotNull('sponsor_id');
    }

    public function scopeUnsponsored($query)
    {
        return $query->whereNull('sponsor_id');
    }

    // Helper Methods
    public static function generateInternalId()
    {
        $maxId = self::max('id') ?? 0;
        return 'SCH-' . date('Y') . '-' . str_pad($maxId + 1, 5, '0', STR_PAD_LEFT);
    }

    public function calculateAge()
    {
        if ($this->school_student_dob) {
            return Carbon::parse($this->school_student_dob)->age;
        }
        return null;
    }

    public function validateAgeGrade()
    {
        $grade = $this->school_grade;
        $age = $this->calculateAge();

        if (!$grade || !$age || !isset(self::$gradeAgeMapping[$grade])) {
            return ['valid' => true];
        }

        $expected = self::$gradeAgeMapping[$grade];

        if ($age >= $expected['min'] && $age <= $expected['max']) {
            return ['valid' => true];
        }

        if ($age < $expected['min']) {
            return [
                'valid' => false,
                'message' => "Student is too young for {$expected['name']}. Age {$age} is below minimum required age of {$expected['min']} years.",
                'requires_reason' => false
            ];
        }

        if ($age === ($expected['max'] + 1)) {
            return [
                'valid' => empty($this->grade_mismatch_reason) ? false : true,
                'message' => "Age {$age} is one year older than typical for {$expected['name']}. Please provide a grade mismatch reason.",
                'requires_reason' => true
            ];
        }

        if ($age > ($expected['max'] + 1)) {
            return [
                'valid' => false,
                'message' => "Student is too old for {$expected['name']}. Age {$age} exceeds maximum allowed age.",
                'requires_reason' => false
            ];
        }

        return ['valid' => true];
    }

    public function getSponsorHistory()
    {
        $history = [];

        if ($this->sponsor) {
            $history[] = [
                'sponsor_id' => $this->sponsor->id,
                'sponsor_name' => $this->sponsor->name,
                'sponsor_email' => $this->sponsor->email ?? null,
                'sponsor_type' => $this->sponsor->sponsor_type ?? null,
                'relationship_type' => 'direct',
                'sponsorship_start' => $this->school_sponsorship_start_date,
                'sponsorship_end' => $this->school_sponsorship_end_date,
            ];
        }

        return $history;
    }
}