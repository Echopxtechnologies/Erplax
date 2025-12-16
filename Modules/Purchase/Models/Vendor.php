<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vendor_code', 'name', 'display_name', 'contact_person', 'email', 'phone', 'mobile',
        'website', 'gst_type', 'gst_number', 'pan_number', 'billing_address', 'billing_city',
        'billing_state', 'billing_pincode', 'billing_country', 'shipping_address', 'shipping_city',
        'shipping_state', 'shipping_pincode', 'payment_terms', 'credit_days', 'credit_limit',
        'opening_balance', 'status', 'notes', 'created_by'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'opening_balance' => 'decimal:2',
    ];

    // Relationships
    public function purchaseOrders() { return $this->hasMany(PurchaseOrder::class); }
    public function purchaseBills() { return $this->hasMany(PurchaseBill::class); }
    public function creator() { return $this->belongsTo(\App\Models\Admin::class, 'created_by'); }
    
    // Bank Details (polymorphic via holder_type/holder_id in bank_details table)
    public function bankDetails()
    {
        return $this->hasMany(\App\Models\BankDetail::class, 'holder_id')
            ->where('holder_type', 'vendor');
    }
    
    public function primaryBank()
    {
        return $this->hasOne(\App\Models\BankDetail::class, 'holder_id')
            ->where('holder_type', 'vendor')
            ->where('is_primary', true);
    }

    // Accessors
    public function getDisplayNameOrNameAttribute()
    {
        return $this->display_name ?: $this->name;
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->billing_address,
            $this->billing_city,
            $this->billing_state,
            $this->billing_pincode
        ]);
        return implode(', ', $parts);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'ACTIVE');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('vendor_code', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%")
              ->orWhere('contact_person', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Generate unique vendor code
    public static function generateCode(): string
    {
        $prefix = PurchaseSetting::getValue('vendor_prefix', 'VND-');
        $last = static::where('vendor_code', 'like', "{$prefix}%")
            ->orderBy('vendor_code', 'desc')->first();
        
        if ($last) {
            $lastNum = (int) preg_replace('/[^0-9]/', '', substr($last->vendor_code, strlen($prefix)));
            $newNum = str_pad($lastNum + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNum = '00001';
        }
        
        return "{$prefix}{$newNum}";
    }
}
