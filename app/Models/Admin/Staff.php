<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'staffs';

    protected $fillable = [
        'admin_id',
        'employee_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'gender',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'pincode',
        'country',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'department',
        'designation',
        'join_date',
        'confirmation_date',
        'exit_date',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'dob' => 'date',
        'join_date' => 'date',
        'confirmation_date' => 'date',
        'exit_date' => 'date',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship to Admin (for authentication)
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get initials for avatar
     */
    public function getInitialsAttribute()
    {
        return strtoupper(
            substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1)
        );
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->state,
            $this->pincode,
            $this->country,
        ]);
        
        return implode(', ', $parts);
    }

    /**
     * Get age from DOB
     */
    public function getAgeAttribute()
    {
        return $this->dob ? $this->dob->age : null;
    }

    /**
     * Check if staff has system access
     */
    public function hasSystemAccess()
    {
        return !is_null($this->admin_id) && $this->admin;
    }

    /**
     * Check if staff is active
     */
    public function isActive()
    {
        return $this->status === true;
    }

    /**
     * Get roles through admin relationship
     */
    public function getRoles()
    {
        return $this->admin ? $this->admin->roles->pluck('name')->toArray() : [];
    }

    /**
     * Scope: Active staff only
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope: Staff with system access
     */
    public function scopeWithSystemAccess($query)
    {
        return $query->whereNotNull('admin_id');
    }

    /**
     * Scope: Search by name, email, employee code, department
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('employee_code', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('department', 'like', "%{$search}%")
              ->orWhere('designation', 'like', "%{$search}%");
        });
    }

    /**
     * Generate unique employee code
     */
    public static function generateEmployeeCode($prefix = 'EMP')
    {
        $lastStaff = self::withTrashed()->orderBy('id', 'desc')->first();
        $nextNumber = $lastStaff ? ($lastStaff->id + 1) : 1;
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}