<?php

namespace Modules\StudentSponsor\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolName extends Model
{
    protected $table = 'tblschool_name';
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
        return $this->hasMany(SchoolStudent::class, 'school_name_id');
    }
}
