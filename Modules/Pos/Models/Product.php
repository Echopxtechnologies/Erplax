<?php

namespace Modules\Pos\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    
    protected $casts = [
        'is_active' => 'boolean',
        'has_variants' => 'boolean',
        'sale_price' => 'decimal:2',
    ];
}
