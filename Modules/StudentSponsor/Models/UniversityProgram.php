<?php

namespace Modules\StudentSponsor\Models;

use Illuminate\Database\Eloquent\Model;

class UniversityProgram extends Model
{
    protected $table = 'tbluniversity_program';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'university_id',
        'name',
        'created_on',
    ];

    protected $casts = [
        'created_on' => 'datetime',
    ];

    public function university()
    {
        return $this->belongsTo(UniversityName::class, 'university_id');
    }

    public function students()
    {
        return $this->hasMany(UniversityStudent::class, 'university_program_id');
    }
}
