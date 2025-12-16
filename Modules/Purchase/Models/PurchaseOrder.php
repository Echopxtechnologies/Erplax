<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'po_number', 'vendor_id', 'purchase_request_id', 'po_date', 'expected_date', 'delivery_date',
        'status', 'shipping_address', 'shipping_city', 'shipping_state', 'shipping_pincode',
        'subtotal', 'tax_amount', 'discount_amount', 'shipping_charge', 'total_amount',
        'payment_terms', 'terms_conditions', 'notes', 'sent_at', 'confirmed_at', 'created_by'
    ];

    protected $casts = [
        'po_date' => 'date',
        'expected_date' => 'date',
        'delivery_date' => 'date',
        'sent_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_charge' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function purchaseRequest() { return $this->belongsTo(PurchaseRequest::class); }
    public function items() { return $this->hasMany(PurchaseOrderItem::class); }
    public function creator() { return $this->belongsTo(\App\Models\Admin::class, 'created_by'); }
    public function goodsReceiptNotes() { return $this->hasMany(GoodsReceiptNote::class); }

    // Accessors
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'DRAFT' => 'Draft',
            'SENT' => 'Sent to Vendor',
            'CONFIRMED' => 'Confirmed',
            'PARTIALLY_RECEIVED' => 'Partially Received',
            'RECEIVED' => 'Fully Received',
            'CANCELLED' => 'Cancelled',
            default => $this->status
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'DRAFT' => 'secondary',
            'SENT' => 'info',
            'CONFIRMED' => 'primary',
            'PARTIALLY_RECEIVED' => 'warning',
            'RECEIVED' => 'success',
            'CANCELLED' => 'dark',
            default => 'secondary'
        };
    }

    // Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('po_number', 'like', "%{$search}%")
              ->orWhereHas('vendor', fn($v) => $v->where('name', 'like', "%{$search}%"));
        });
    }

    // Helpers
    public function canEdit() { return in_array($this->status, ['DRAFT']); }
    public function canSend() { return $this->status === 'DRAFT' && $this->items->count() > 0; }
    public function canConfirm() { return $this->status === 'SENT'; }
    public function canCancel() { return in_array($this->status, ['DRAFT', 'SENT']); }
    public function canReceive() { return in_array($this->status, ['CONFIRMED', 'PARTIALLY_RECEIVED']); }

    public function calculateTotals()
    {
        $subtotal = $this->items->sum('total');
        $taxAmount = $this->items->sum('tax_amount');
        
        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $subtotal + $taxAmount + $this->shipping_charge - $this->discount_amount,
        ]);
    }

    public static function generateNumber(): string
    {
        $prefix = PurchaseSetting::getValue('po_prefix', 'PO-');
        $yearMonth = date('Ym');
        $last = static::where('po_number', 'like', "{$prefix}{$yearMonth}%")
            ->orderBy('po_number', 'desc')->first();
        
        if ($last) {
            $lastNum = (int) substr($last->po_number, -4);
            $newNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNum = '0001';
        }
        
        return "{$prefix}{$yearMonth}-{$newNum}";
    }
}
