<?php

// app/Models/Customer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
   protected $fillable = [
    'name',
    'customer_type', 
    'email',
    'phone',
    'company',
    'designation',
    'gst_number',
    'website',
    'group_name',
    'currency',
    'address',
    'city',
    'state',
    'zip_code',
    'country',
    'notes',
    'shipping_address', // Add these fields
    'shipping_city',
    'shipping_zip_code',
    'shipping_state',
    'shipping_country'


    
];

}
