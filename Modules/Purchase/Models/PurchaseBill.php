<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseBill extends Model
{
    use SoftDeletes;

    protected $table = 'purchase_bills';

    protected $fillable = [
        'bill_number',
        'vendor_id',
        'purchase_order_id',
        'grn_id',
        'vendor_invoice_no',
        'vendor_invoice_date',
        'bill_date',
        'due_date',
        'warehouse_id',
        'status',
        'payment_status',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'shipping_charge',
        'adjustment',
        'grand_total',
        'paid_amount',
        'balance_due',
        'notes',
        'terms_conditions',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'created_by',
    ];

    protected $casts = [
        'vendor_invoice_date' => 'date',
        'bill_date' => 'date',
        'due_date' => 'date',
        'approved_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_charge' => 'decimal:2',
        'adjustment' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_due' => 'decimal:2',
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function grn()
    {
        return $this->belongsTo(GoodsReceiptNote::class, 'grn_id');
    }

    public function warehouse()
    {
        if (class_exists('\Modules\Inventory\Models\Warehouse')) {
            return $this->belongsTo(\Modules\Inventory\Models\Warehouse::class);
        }
        return $this->belongsTo(Vendor::class, 'warehouse_id'); // Fallback
    }

    public function items()
    {
        return $this->hasMany(PurchaseBillItem::class);
    }

    public function payments()
    {
        return $this->hasMany(PurchasePayment::class);
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'approved_by');
    }

    // Generate Bill Number
    public static function generateNumber(): string
    {
        $prefix = 'BILL';
        $year = date('Ym');
        
        $last = self::withTrashed()
            ->where('bill_number', 'like', "{$prefix}-{$year}-%")
            ->orderByRaw('CAST(SUBSTRING(bill_number, -4) AS UNSIGNED) DESC')
            ->first();
        
        $nextNum = 1;
        if ($last) {
            $parts = explode('-', $last->bill_number);
            $nextNum = (int)end($parts) + 1;
        }
        
        return sprintf('%s-%s-%04d', $prefix, $year, $nextNum);
    }

    // Status helpers
    public function canEdit(): bool
    {
        return in_array($this->status, ['DRAFT', 'REJECTED']);
    }

    public function canSubmit(): bool
    {
        return $this->status === 'DRAFT' && $this->items()->count() > 0;
    }

    public function canApprove(): bool
    {
        return $this->status === 'PENDING';
    }

    public function canPay(): bool
    {
        return $this->status === 'APPROVED' && $this->balance_due > 0;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'DRAFT' => 'badge-secondary',
            'PENDING' => 'badge-warning',
            'APPROVED' => 'badge-success',
            'REJECTED' => 'badge-danger',
            'CANCELLED' => 'badge-dark',
            default => 'badge-secondary',
        };
    }

    public function getPaymentBadgeAttribute(): string
    {
        return match($this->payment_status) {
            'UNPAID' => 'badge-danger',
            'PARTIALLY_PAID' => 'badge-warning',
            'PAID' => 'badge-success',
            default => 'badge-secondary',
        };
    }

    // Calculate totals
    public function calculateTotals(): void
    {
        $subtotal = 0;
        $taxAmount = 0;
        $discountAmount = 0;

        foreach ($this->items as $item) {
            $lineTotal = $item->qty * $item->rate;
            $itemDiscount = $lineTotal * ($item->discount_percent / 100);
            $afterDiscount = $lineTotal - $itemDiscount;
            $itemTax = $afterDiscount * ($item->tax_percent / 100);
            
            $subtotal += $lineTotal;
            $discountAmount += $itemDiscount;
            $taxAmount += $itemTax;
        }

        $this->subtotal = $subtotal;
        $this->tax_amount = $taxAmount;
        $this->discount_amount = $discountAmount;
        $this->grand_total = $subtotal - $discountAmount + $taxAmount + $this->shipping_charge + $this->adjustment;
        $this->balance_due = $this->grand_total - $this->paid_amount;
        $this->save();
    }

    // Update payment status
    public function updatePaymentStatus(): void
    {
        $this->paid_amount = $this->payments()->where('status', 'COMPLETED')->sum('amount');
        $this->balance_due = $this->grand_total - $this->paid_amount;
        
        if ($this->paid_amount <= 0) {
            $this->payment_status = 'UNPAID';
        } elseif ($this->balance_due <= 0) {
            $this->payment_status = 'PAID';
        } else {
            $this->payment_status = 'PARTIALLY_PAID';
        }
        
        $this->save();
    }

    // Is overdue
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->balance_due > 0;
    }

    // Days overdue
    public function getDaysOverdueAttribute(): int
    {
        if (!$this->is_overdue) return 0;
        return $this->due_date->diffInDays(now());
    }
}
