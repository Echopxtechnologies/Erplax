<?php

namespace Modules\StudentSponsorship\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UniversityReportCard extends Model
{
    protected $fillable = [
        'university_student_id',
        'filename',
        'upload_date',
        'report_card_term',
        'current_term',
        'semester_end_month',
        'semester_end_year',
        'file_path',
        'file_data',
        'mime_type',
        'file_size',
        'status',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'upload_date' => 'date',
        'semester_end_month' => 'integer',
        'semester_end_year' => 'integer',
        'file_size' => 'integer',
        'approved_at' => 'datetime',
    ];

    /**
     * Year/Semester options
     */
    public const TERMS = [
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
     * Get the student this report card belongs to
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(UniversityStudent::class, 'university_student_id');
    }

    /**
     * Get term display name
     */
    public function getTermDisplayAttribute(): string
    {
        return self::TERMS[$this->report_card_term] ?? $this->report_card_term;
    }

    /**
     * Get semester end display
     */
    public function getSemesterEndDisplayAttribute(): string
    {
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March',
            4 => 'April', 5 => 'May', 6 => 'June',
            7 => 'July', 8 => 'August', 9 => 'September',
            10 => 'October', 11 => 'November', 12 => 'December'
        ];
        
        $month = $months[$this->semester_end_month] ?? '';
        return "{$month} {$this->semester_end_year}";
    }

    /**
     * Check if file is stored as BLOB
     */
    public function hasFileData(): bool
    {
        return !empty($this->file_data);
    }

    /**
     * Get file extension from mime type
     */
    public function getFileExtensionAttribute(): string
    {
        $extensions = [
            'application/pdf' => 'pdf',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
        ];
        
        return $extensions[$this->mime_type] ?? 'bin';
    }
}
