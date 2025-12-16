<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchasePayment extends Model
{
    use SoftDeletes;

    protected $table = 'purchase_payments';

    protected $fillable = [
        'payment_number',
        'purchase_bill_id',
        'vendor_id',
        'payment_date',
        'amount',
        'payment_method_id',
        'reference_no',
        'bank_name',
        'cheque_no',
        'cheque_date',
        'notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'cheque_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function bill()
    {
        return $this->belongsTo(PurchaseBill::class, 'purchase_bill_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by');
    }
    
    public function paymentMethod()
    {
        if (\Schema::hasTable('payment_methods')) {
            return $this->belongsTo(\App\Models\PaymentMethod::class, 'payment_method_id');
        }
        return $this->belongsTo(Vendor::class, 'payment_method_id'); // Fallback
    }

    // Generate Payment Number
    public static function generateNumber(): string
    {
        $prefix = 'PAY';
        $year = date('Ym');
        
        $last = self::withTrashed()
            ->where('payment_number', 'like', "{$prefix}-{$year}-%")
            ->orderByRaw('CAST(SUBSTRING(payment_number, -4) AS UNSIGNED) DESC')
            ->first();
        
        $nextNum = 1;
        if ($last) {
            $parts = explode('-', $last->payment_number);
            $nextNum = (int)end($parts) + 1;
        }
        
        return sprintf('%s-%s-%04d', $prefix, $year, $nextNum);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        if ($this->paymentMethod) {
            return $this->paymentMethod->name;
        }
        return 'N/A';
    }
}
