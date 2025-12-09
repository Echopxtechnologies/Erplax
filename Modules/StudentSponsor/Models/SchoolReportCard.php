<?php

namespace Modules\StudentSponsor\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolReportCard extends Model
{
    protected $table = 'tblschool_report_card';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'student_school_id',
        'filename',
        'term',
        'upload_date',
        'report_card_file',
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
        return $this->belongsTo(SchoolStudent::class, 'student_school_id');
    }
}
