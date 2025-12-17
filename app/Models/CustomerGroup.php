<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customer_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS (MODIFIED FOR STRING-BASED GROUP)
    |--------------------------------------------------------------------------
    */

    /**
     * Get all customers in this group
     * NOTE: Uses group_name (string) instead of foreign key
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customers()
    {
        // CHANGED: Match by group_name instead of customer_group_id
        return $this->hasMany(Customer::class, 'group_name', 'name');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Get customer count for this group
     *
     * @return int
     */
    public function getCustomerCountAttribute()
    {
        return $this->customers()->count();
    }

    /**
     * Check if group has customers
     *
     * @return bool
     */
    public function hasCustomers()
    {
        return $this->customers()->exists();
    }

    /**
     * Check if group can be deleted
     * (Can only delete if no customers assigned)
     *
     * @return bool
     */
    public function isDeletable()
    {
        return !$this->hasCustomers();
    }
}