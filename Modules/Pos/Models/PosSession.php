<?php
namespace Modules\Pos\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin;

class PosSession extends Model
{
    protected $guarded = [];
    protected $casts = ['opened_at' => 'datetime', 'closed_at' => 'datetime'];
    
    public function admin() { return $this->belongsTo(Admin::class); }
    public function sales() { return $this->hasMany(PosSale::class, 'session_id'); }
}
