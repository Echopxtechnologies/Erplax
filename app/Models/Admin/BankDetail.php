<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    protected $fillable = [
        'holder_type', 'holder_id', 'account_holder_name', 'bank_name',
        'account_number', 'ifsc_code', 'branch_name', 'upi_id',
        'account_type', 'is_primary', 'is_active'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopePrimary($q) { return $q->where('is_primary', true); }
    public function scopeForHolder($q, $type, $id) { return $q->where('holder_type', $type)->where('holder_id', $id); }
}