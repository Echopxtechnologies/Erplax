<?php
namespace Modules\Pos\Models;
use Illuminate\Database\Eloquent\Model;

class PosSettings extends Model
{
    protected $table = 'pos_settings';
    protected $guarded = [];
    protected $casts = ['tax_inclusive' => 'boolean', 'default_tax_rate' => 'decimal:2'];
}
