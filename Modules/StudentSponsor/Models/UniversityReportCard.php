<?php

namespace Modules\StudentSponsor\Models;

use Illuminate\Database\Eloquent\Model;

class UniversityReportCard extends Model
{
    protected $table = 'tbluniversity_report_card';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'student_university_id',
        'filename',
        'report_card_file',
        'upload_date',
        'report_card_term',
        'current_term',
        'semester_end_month',
        'semester_end_year',
        'file_blob',
        'mime_type',
        'file_size',
        'sha256',
        'created_on',
    ];

    protected $casts = [
        'upload_date' => 'date',
        'created_on' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(UniversityStudent::class, 'student_university_id');
    }
}
