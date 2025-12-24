<?php
namespace Modules\Pos\Models;
use Illuminate\Database\Eloquent\Model;

class PosSaleItem extends Model
{
    protected $guarded = [];
    public function sale() { return $this->belongsTo(PosSale::class, 'sale_id'); }
}
