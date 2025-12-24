<?php

namespace Modules\Website\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteOrder extends Model
{
    protected $table = 'website_orders';

    protected $fillable = [
        'order_no',
        'customer_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_pincode',
        'shipping_country',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_pincode',
        'billing_country',
        'subtotal',
        'tax_amount',
        'shipping_fee',
        'cod_fee',
        'discount_amount',
        'discount_code',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'transaction_id',
        'payment_gateway',
        'paid_at',
        'tracking_number',
        'carrier',
        'shipped_at',
        'delivered_at',
        'customer_notes',
        'admin_notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'cod_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_RETURNED = 'returned';

    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_REFUNDED = 'refunded';

    // =====================
    // RELATIONSHIPS
    // =====================

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(WebsiteOrderItem::class, 'order_id');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(WebsiteOrderStatusHistory::class, 'order_id')->orderBy('created_at', 'desc');
    }

    // =====================
    // SCOPES
    // =====================

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function scopeShipped($query)
    {
        return $query->where('status', self::STATUS_SHIPPED);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function scopePaymentPending($query)
    {
        return $query->where('payment_status', self::PAYMENT_PENDING);
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_PAID);
    }

    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    // =====================
    // ACCESSORS
    // =====================

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'returned' => 'Returned',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get status color (for badges)
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'shipped' => 'purple',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'returned' => 'secondary',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get payment status label
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
            'partial_refund' => 'Partial Refund',
        ];

        return $labels[$this->payment_status] ?? ucfirst($this->payment_status);
    }

    /**
     * Get payment status color
     */
    public function getPaymentStatusColorAttribute(): string
    {
        $colors = [
            'pending' => 'warning',
            'paid' => 'success',
            'failed' => 'danger',
            'refunded' => 'info',
            'partial_refund' => 'warning',
        ];

        return $colors[$this->payment_status] ?? 'secondary';
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        $labels = [
            'cod' => 'Cash on Delivery',
            'online' => 'Online Payment',
            'cash' => 'Cash',
        ];
        
        return $labels[$this->payment_method] ?? ucfirst(str_replace('_', ' ', $this->payment_method));
    }

    /**
     * Get full shipping address
     */
    public function getFullShippingAddressAttribute(): string
    {
        $parts = array_filter([
            $this->shipping_address,
            $this->shipping_city,
            $this->shipping_state,
            $this->shipping_pincode,
            $this->shipping_country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get full billing address
     */
    public function getFullBillingAddressAttribute(): string
    {
        // If no billing address, use shipping
        if (empty($this->billing_address)) {
            return $this->full_shipping_address;
        }

        $parts = array_filter([
            $this->billing_address,
            $this->billing_city,
            $this->billing_state,
            $this->billing_pincode,
            $this->billing_country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get item count
     */
    public function getItemCountAttribute(): int
    {
        return $this->items->sum('qty');
    }

    // =====================
    // METHODS
    // =====================

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    /**
     * Check if order is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Check if order is paid
     */
    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    /**
     * Update order status with history
     */
    public function updateStatus(string $status, ?string $comment = null, ?int $changedBy = null): bool
    {
        $oldStatus = $this->status;
        $this->status = $status;
        
        // Set timestamps
        if ($status === self::STATUS_SHIPPED && !$this->shipped_at) {
            $this->shipped_at = now();
        }
        if ($status === self::STATUS_DELIVERED && !$this->delivered_at) {
            $this->delivered_at = now();
        }

        $saved = $this->save();

        if ($saved) {
            WebsiteOrderStatusHistory::create([
                'order_id' => $this->id,
                'status' => $status,
                'comment' => $comment ?? "Status changed from {$oldStatus} to {$status}",
                'changed_by' => $changedBy,
            ]);
        }

        return $saved;
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(?string $transactionId = null, ?string $gateway = null): bool
    {
        $this->payment_status = self::PAYMENT_PAID;
        $this->paid_at = now();
        
        if ($transactionId) {
            $this->transaction_id = $transactionId;
        }
        if ($gateway) {
            $this->payment_gateway = $gateway;
        }

        return $this->save();
    }

    /**
     * Calculate totals from items
     */
    public function recalculateTotals(): void
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->tax_amount = $this->items->sum('tax_amount');
        $this->total = $this->subtotal + $this->tax_amount + $this->shipping_fee + $this->cod_fee - $this->discount_amount;
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotal(): string
    {
        return '₹' . number_format($this->total, 2);
    }
}
