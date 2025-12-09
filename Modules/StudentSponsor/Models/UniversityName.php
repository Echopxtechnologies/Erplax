<?php

namespace Modules\StudentSponsor\Models;

use Illuminate\Database\Eloquent\Model;

class UniversityName extends Model
{
    protected $table = 'tbluniversity_name';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'created_on',
    ];

    protected $casts = [
        'created_on' => 'datetime',
    ];

    public function students()
    {
        return $this->hasMany(UniversityStudent::class, 'university_name_id');
    }

    public function programs()
    {
        return $this->hasMany(UniversityProgram::class, 'university_id');
    }
}
