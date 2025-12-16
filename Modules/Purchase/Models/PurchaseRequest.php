<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'pr_number', 'pr_date', 'required_date', 'department', 'priority', 'status',
        'purpose', 'notes', 'rejection_reason', 'requested_by', 'approved_by', 
        'approved_at', 'created_by'
    ];

    protected $casts = [
        'pr_date' => 'date',
        'required_date' => 'date',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function items() { return $this->hasMany(PurchaseRequestItem::class); }
    public function requester() { return $this->belongsTo(\App\Models\Admin::class, 'requested_by'); }
    public function approver() { return $this->belongsTo(\App\Models\Admin::class, 'approved_by'); }
    public function creator() { return $this->belongsTo(\App\Models\Admin::class, 'created_by'); }
    public function purchaseOrders() { return $this->hasMany(PurchaseOrder::class); }

    // Accessors
    public function getTotalEstimatedAttribute()
    {
        return $this->items->sum(fn($item) => $item->estimated_total);
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'DRAFT' => 'secondary',
            'PENDING' => 'warning',
            'APPROVED' => 'success',
            'REJECTED' => 'danger',
            'CANCELLED' => 'dark',
            'CONVERTED' => 'info',
            default => 'secondary'
        };
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('pr_number', 'like', "%{$search}%")
              ->orWhere('department', 'like', "%{$search}%")
              ->orWhere('purpose', 'like', "%{$search}%");
        });
    }

    // Workflow helpers
    public function canEdit() { return in_array($this->status, ['DRAFT']); }
    public function canSubmit() { return $this->status === 'DRAFT' && $this->items->count() > 0; }
    public function canApprove() { return $this->status === 'PENDING'; }
    public function canCancel() { return in_array($this->status, ['DRAFT', 'PENDING']); }
    public function canConvert() { return $this->status === 'APPROVED'; }

    public static function generateNumber(): string
    {
        $prefix = PurchaseSetting::getValue('pr_prefix', 'PR-');
        $yearMonth = date('Ym');
        $last = static::where('pr_number', 'like', "{$prefix}{$yearMonth}%")
            ->orderBy('pr_number', 'desc')->first();
        
        if ($last) {
            $lastNum = (int) substr($last->pr_number, -4);
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNum = '0001';
        }
        
        return "{$prefix}{$yearMonth}-{$newNum}";
    }
}
