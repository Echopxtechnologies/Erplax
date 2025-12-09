<?php

namespace Modules\StudentSponsor\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'tblbank';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    const CREATED_AT = 'created_on';

    protected $fillable = [
        'name',
        'created_on',
    ];

    protected $casts = [
        'created_on' => 'datetime',
    ];
}
