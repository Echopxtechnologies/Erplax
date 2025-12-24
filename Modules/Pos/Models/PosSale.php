<?php
namespace Modules\Pos\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

class PosSale extends Model
{
    protected $guarded = [];
    
    public function admin() { return $this->belongsTo(Admin::class); }
    public function session() { return $this->belongsTo(PosSession::class, 'session_id'); }
    public function items() { return $this->hasMany(PosSaleItem::class, 'sale_id'); }
    public function customer() { return $this->belongsTo(\App\Models\Customer::class); }
    public function invoice() { return $this->belongsTo(\App\Models\Invoice::class); }
}
