<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Exception;

class Customer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'customer_type',
        'active',
        'email',
        'phone',
        'company',
        'vat',
        'website',
        'group_name',
        'currency',
        'designation',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'latitude',
        'longitude',
        'billing_street',
        'billing_city',
        'billing_state',
        'billing_zip_code',
        'billing_country',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_zip_code',
        'shipping_country',
        'default_language',
        'profile_image',
        'invoice_emails',
        'estimate_emails',
        'credit_note_emails',
        'contract_emails',
        'task_emails',
        'project_emails',
        'ticket_emails',
        'is_supplier',
        'leadid',
        'added_by',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'active' => 'boolean',
        'is_supplier' => 'boolean',
        'invoice_emails' => 'boolean',
        'estimate_emails' => 'boolean',
        'credit_note_emails' => 'boolean',
        'contract_emails' => 'boolean',
        'task_emails' => 'boolean',
        'project_emails' => 'boolean',
        'ticket_emails' => 'boolean',
        'currency' => 'integer',
        'leadid' => 'integer',
        'added_by' => 'integer',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            // Set defaults
            if (!isset($customer->active)) {
                $customer->active = true;
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & MUTATORS
    |--------------------------------------------------------------------------
    */

    /**
     * Get display name (company name or person name)
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        if ($this->isCompany() && !empty($this->company)) {
            return $this->company;
        }
        return $this->name ?? 'Unnamed Customer';
    }

    /**
     * Get full name (alias for name - for backward compatibility)
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Get formatted address string
     *
     * @return string
     */
    public function getFormattedAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->zip_code,
        ]);

        return !empty($parts) ? implode(', ', $parts) : 'No address';
    }

    /**
     * Get status badge HTML
     *
     * @return string
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->isActive()) {
            return '<span class="badge badge-success">Active</span>';
        }
        return '<span class="badge badge-secondary">Inactive</span>';
    }

    /**
     * Get type badge HTML
     *
     * @return string
     */
    public function getTypeBadgeAttribute()
    {
        if ($this->isIndividual()) {
            return '<span class="badge badge-info">👤 Individual</span>';
        }
        return '<span class="badge badge-primary">🏢 Company</span>';
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Check if customer is individual type
     *
     * @return bool
     */
    public function isIndividual()
    {
        return $this->customer_type === 'individual';
    }

    /**
     * Check if customer is company type
     *
     * @return bool
     */
    public function isCompany()
    {
        return $this->customer_type === 'company';
    }

    /**
     * Check if customer is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active == 1 || $this->active === true;
    }

    /**
     * Check if customer is a supplier
     *
     * @return bool
     */
    public function isSupplier()
    {
        return $this->is_supplier == 1 || $this->is_supplier === true;
    }

    /**
     * Check if customer can be deleted
     *
     * @return bool
     */
    public function isDeletable()
    {
        // Can always delete in single-table structure
        return true;
    }

    /**
     * Check if contact has specific email notification enabled
     *
     * @param string $type
     * @return bool
     */
    public function hasEmailNotification($type)
    {
        $field = $type . '_emails';
        
        if (property_exists($this, $field) || array_key_exists($field, $this->attributes)) {
            return $this->{$field} == 1 || $this->{$field} === true;
        }

        return false;
    }

    /**
     * Get all enabled email notifications
     *
     * @return array
     */
    public function getEnabledNotificationsAttribute()
    {
        $notifications = [];
        
        $types = [
            'invoice',
            'estimate',
            'credit_note',
            'contract',
            'task',
            'project',
            'ticket',
        ];

        foreach ($types as $type) {
            if ($this->hasEmailNotification($type)) {
                $notifications[] = $type;
            }
        }

        return $notifications;
    }

    /*
    |--------------------------------------------------------------------------
    | BACKWARD COMPATIBILITY PROPERTIES
    | (To maintain compatibility with existing code)
    |--------------------------------------------------------------------------
    */

    /**
     * Get customer_id (alias for id - backward compatibility)
     *
     * @return int
     */
    public function getCustomerIdAttribute()
    {
        return $this->id;
    }

    /**
     * Get phonenumber (alias for phone - backward compatibility)
     *
     * @return string|null
     */
    public function getPhonenumberAttribute()
    {
        return $this->phone;
    }

    /**
     * Set phonenumber (alias for phone - backward compatibility)
     *
     * @param string|null $value
     */
    public function setPhonenumberAttribute($value)
    {
        $this->attributes['phone'] = $value;
    }

    /**
     * Get zip (alias for zip_code - backward compatibility)
     *
     * @return string|null
     */
    public function getZipAttribute()
    {
        return $this->zip_code;
    }

    /**
     * Set zip (alias for zip_code - backward compatibility)
     *
     * @param string|null $value
     */
    public function setZipAttribute($value)
    {
        $this->attributes['zip_code'] = $value;
    }

    /**
     * Get datecreated (alias for created_at - backward compatibility)
     *
     * @return \Carbon\Carbon|null
     */
    public function getDatecreatedAttribute()
    {
        return $this->created_at;
    }

    /*
    |--------------------------------------------------------------------------
    | MOCK METHODS FOR CONTACT COMPATIBILITY
    | (To work with existing controllers that expect Contact methods)
    |--------------------------------------------------------------------------
    */

    /**
     * Check if is primary (always true in single table)
     *
     * @return bool
     */
    public function isPrimary()
    {
        return true;
    }

    /**
     * Get is_primary attribute (always 1 in single table)
     *
     * @return int
     */
    public function getIsPrimaryAttribute()
    {
        return 1;
    }

    /**
     * Get primary badge HTML
     *
     * @return string
     */
    public function getPrimaryBadgeAttribute()
    {
        return '<span class="badge badge-primary">Primary</span>';
    }

    /**
     * Mock customer relationship (returns self)
     * For backward compatibility with code that does $contact->customer
     *
     * @return $this
     */
    public function customer()
    {
        return $this;
    }

    /**
     * Get customer attribute (returns self)
     *
     * @return $this
     */
    public function getCustomerAttribute()
    {
        return $this;
    }

    /**
     * Mock contact_count (always 1 in single table)
     *
     * @return int
     */
    public function getContactCountAttribute()
    {
        return 1;
    }

    /**
     * Mock canAddContact (always false in single table)
     *
     * @return bool
     */
    public function canAddContact()
    {
        return false;
    }

    /**
     * Mock primaryContact (returns self)
     *
     * @return $this
     */
    public function primaryContact()
    {
        return $this;
    }

    /**
     * Get primaryContact attribute (returns self)
     *
     * @return $this
     */
    public function getPrimaryContactAttribute()
    {
        return $this;
    }

    /**
     * Mock contacts relationship (returns collection with self)
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function contacts()
    {
        return collect([$this]);
    }

    /**
     * Mock belongsToIndividual
     *
     * @return bool
     */
    public function belongsToIndividual()
    {
        return $this->isIndividual();
    }

    /**
     * Mock belongsToCompany
     *
     * @return bool
     */
    public function belongsToCompany()
    {
        return $this->isCompany();
    }

    /*
    |--------------------------------------------------------------------------
    | SPLIT NAME METHODS
    | (For forms that still use firstname/lastname)
    |--------------------------------------------------------------------------
    */

    /**
     * Get firstname from name (first word)
     *
     * @return string
     */
    public function getFirstnameAttribute()
    {
        $parts = explode(' ', $this->name ?? '', 2);
        return $parts[0] ?? '';
    }

    /**
     * Get lastname from name (remaining words)
     *
     * @return string
     */
    public function getLastnameAttribute()
    {
        $parts = explode(' ', $this->name ?? '', 2);
        return $parts[1] ?? '';
    }

    /**
     * Set firstname (updates name)
     *
     * @param string $value
     */
    public function setFirstnameAttribute($value)
    {
        $lastname = $this->lastname ?? '';
        $this->attributes['name'] = trim($value . ' ' . $lastname);
    }

    /**
     * Set lastname (updates name)
     *
     * @param string $value
     */
    public function setLastnameAttribute($value)
    {
        $firstname = $this->firstname ?? '';
        $this->attributes['name'] = trim($firstname . ' ' . $value);
    }
}