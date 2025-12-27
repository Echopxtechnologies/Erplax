<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'tblbank';
    
    protected $fillable = ['name'];
    
    // tblbank uses created_on instead of created_at
    public $timestamps = false;
    
    const CREATED_AT = 'created_on';
    const UPDATED_AT = null;
    
    /**
     * Boot method to set created_on on create
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->created_on = now();
        });
    }
}