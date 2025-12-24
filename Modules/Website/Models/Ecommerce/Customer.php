<?php

namespace Modules\Website\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

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
        'is_website_user',
        'user_id',
        'leadid',
        'added_by',
        'notes',
    ];

    protected $casts = [
        'active' => 'boolean',
        'invoice_emails' => 'boolean',
        'estimate_emails' => 'boolean',
        'credit_note_emails' => 'boolean',
        'contract_emails' => 'boolean',
        'task_emails' => 'boolean',
        'project_emails' => 'boolean',
        'ticket_emails' => 'boolean',
        'is_supplier' => 'boolean',
        'is_website_user' => 'boolean',
    ];

    /**
     * Get linked user
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Scope: Website users only
     */
    public function scopeWebsiteUsers($query)
    {
        return $query->where('is_website_user', 1);
    }

    /**
     * Scope: Active customers
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Get display name
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->customer_type === 'company' && $this->company) {
            return $this->company;
        }
        return $this->name ?? '';
    }

    /**
     * Get full shipping address
     */
    public function getFullShippingAddressAttribute(): string
    {
        $parts = array_filter([
            $this->shipping_address,
            $this->shipping_city,
            $this->shipping_state,
            $this->shipping_zip_code,
            $this->shipping_country,
        ]);
        return implode(', ', $parts);
    }

    /**
     * Get full billing address
     */
    public function getFullBillingAddressAttribute(): string
    {
        $parts = array_filter([
            $this->billing_street,
            $this->billing_city,
            $this->billing_state,
            $this->billing_zip_code,
            $this->billing_country,
        ]);
        return implode(', ', $parts);
    }
}
