<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

 protected $fillable = [
    'invoice_number',
    'customer_id',
    'estimation_id',
    'subject',
    'date',
    'due_date',
    'currency',
    'subtotal',
    'discount',           // Make sure this is here
    'discount_type',      // And this
    'tax',
    'total',
    'amount_paid',        // And this
    'amount_due',         // And this
    'status',
    'payment_status',     // And this
    'content',
    'terms_conditions',
    'admin_note',
    'email',
    'phone',
    'address',
    'city',
    'state',
    'zip_code',
    'country',
    'assigned_to',
    'created_by',
];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'amount_due' => 'decimal:2',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function estimation()
    {
        return $this->belongsTo(Estimation::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class)->orderBy('payment_date', 'desc');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Generate invoice number
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV-';
        $year = date('Y');
        $lastInvoice = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -6));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . '-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    // Record a payment and create Payment record
    public function recordPayment($amount, $paymentDate = null, $paymentMethod = 'cash', $notes = null, $transactionId = null)
    {
        $amount = min($amount, $this->amount_due); // Can't pay more than due
        
        if ($amount <= 0) {
            return null;
        }

        // Create payment record
        $payment = Payment::create([
            'payment_number' => Payment::generatePaymentNumber(),
            'invoice_id' => $this->id,
            'customer_id' => $this->customer_id,
            'amount' => $amount,
            'payment_date' => $paymentDate ?? now(),
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
            'notes' => $notes,
            'status' => 'completed',
            'created_by' => auth()->user()->name ?? 'System',
        ]);

        // Update invoice amounts
        $this->amount_paid += $amount;
        $this->amount_due = $this->total - $this->amount_paid;

        // Update payment status
        if ($this->amount_due <= 0) {
            $this->payment_status = 'paid';
            $this->amount_due = 0;
        } else {
            $this->payment_status = 'partial';
        }

        $this->save();

        return $payment;
    }

    // Recalculate totals from items
    public function recalculateTotals()
    {
        $subtotal = $this->items->sum('amount');
        $tax = $this->items->sum(function ($item) {
            return ($item->amount * $item->tax_rate) / 100;
        });

        $this->subtotal = $subtotal;
        $this->tax = $tax;
        $this->total = $subtotal + $tax - $this->discount;
        $this->amount_due = $this->total - $this->amount_paid;

        // Update payment status
        if ($this->amount_paid <= 0) {
            $this->payment_status = 'unpaid';
        } elseif ($this->amount_due <= 0) {
            $this->payment_status = 'paid';
        } else {
            $this->payment_status = 'partial';
        }

        $this->save();
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    public function scopePartial($query)
    {
        return $query->where('payment_status', 'partial');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->where('payment_status', '!=', 'paid');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

public function taxes()
{
    return $this->hasMany(InvoiceTax::class);
}

}