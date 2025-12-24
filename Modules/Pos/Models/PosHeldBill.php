<?php
namespace Modules\Pos\Models;
use Illuminate\Database\Eloquent\Model;

class PosHeldBill extends Model
{
    protected $guarded = [];
    protected $casts = ['cart_items' => 'array'];
}
