<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Admin\Staff;
/**
 * Admin Model
 * 
 * SECURITY ARCHITECTURE:
 * - This model handles ONLY authentication and authorization
 * - Personal/HR data is stored in the Staff model
 * - One-to-one relationship with Staff (1 Admin = 1 Staff profile)
 * - Uses Spatie Permission for role-based access control
 * 
 * WHY THIS SEPARATION?
 * 1. Security: Auth credentials isolated from general HR data
 * 2. Compliance: Password hashes stored separately from PII
 * 3. Flexibility: Staff can exist without system access
 * 4. Audit: Separate logs for auth changes vs profile changes
 * 5. Performance: Lighter auth queries (no HR data loaded)
 */
class Admin extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'admins';
    protected $guard = 'admin';
    protected $guard_name = 'admin'; // For Spatie permissions

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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the staff profile associated with this admin login
     * This is the ONE-TO-ONE relationship
     */
    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class, 'admin_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope for active admins
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for admins with staff profiles
     */
    public function scopeWithStaffProfile(Builder $query): Builder
    {
        return $query->whereHas('staff');
    }

    /**
     * Scope for admins by role with staff data
     */
    public function scopeWithStaffAndRole(Builder $query, string $role): Builder
    {
        return $query->with('staff')->role($role);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Get the guard name for Spatie permissions
     */
    public function guardName(): string
    {
        return 'admin';
    }

    /**
     * Check if admin has a staff profile
     */
    public function hasStaffProfile(): bool
    {
        return $this->staff()->exists();
    }

    /**
     * Get full name from staff profile or fallback to admin name
     */
    public function getFullName(): string
    {
        return $this->staff?->full_name ?? $this->name;
    }

    /**
     * Get display name (convenience method)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->getFullName();
    }

    /**
     * Get staff ID if exists
     */
    public function getStaffIdAttribute(): ?int
    {
        return $this->staff?->id;
    }

    /**
     * Get employee code if exists
     */
    public function getEmployeeCodeAttribute(): ?string
    {
        return $this->staff?->employee_code;
    }

    /**
     * Check if this admin can be deleted
     * (Prevents deleting last super-admin or self)
     */
    public function canBeDeleted(): bool
    {
        // Can't delete yourself
        if ($this->id === auth('admin')->id()) {
            return false;
        }

        // Can't delete last super-admin
        if ($this->hasRole('super-admin')) {
            return Admin::role('super-admin')->count() > 1;
        }

        return true;
    }

    /**
     * Get deletion restriction reason
     */
    public function getDeletionRestriction(): ?string
    {
        if ($this->id === auth('admin')->id()) {
            return 'You cannot delete your own account';
        }

        if ($this->hasRole('super-admin') && Admin::role('super-admin')->count() <= 1) {
            return 'Cannot delete the last super-admin';
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Get all admins with their staff data and roles
     * Useful for user management listings
     */
    public static function getUsersWithStaffAndRoles(int $perPage = 10)
    {
        return self::with(['staff', 'roles'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get users by role with staff details
     */
    public static function getUsersByRole(string $role)
    {
        return self::with('staff')
            ->role($role)
            ->get();
    }

    /**
     * Search users by name or email
     */
    public static function searchUsers(string $search)
    {
        return self::with(['staff', 'roles'])
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhereHas('staff', function ($q) use ($search) {
                          $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('employee_code', 'like', "%{$search}%");
                      });
            })
            ->get();
    }
}