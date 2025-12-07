<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable, HasRoles;
    
    protected $table = 'admins';
    protected $guard = 'admin';
    protected $guard_name = 'admin';  // For Spatie permissions
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_active',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the guard name for Spatie permissions
     */
    public function guardName(): string
    {
        return 'admin';
    }
}