<?php

namespace Modules\Website\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebsiteOrderStatusHistory extends Model
{
    protected $table = 'website_order_status_history';

    protected $fillable = [
        'order_id',
        'status',
        'comment',
        'changed_by',
    ];

    // =====================
    // RELATIONSHIPS
    // =====================

    public function order(): BelongsTo
    {
        return $this->belongsTo(WebsiteOrder::class, 'order_id');
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'changed_by');
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
            'pending' => 'Order Placed',
            'confirmed' => 'Order Confirmed',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
            'returned' => 'Returned',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get status icon
     */
    public function getStatusIconAttribute(): string
    {
        $icons = [
            'pending' => 'clock',
            'confirmed' => 'check-circle',
            'processing' => 'cog',
            'shipped' => 'truck',
            'delivered' => 'check-double',
            'cancelled' => 'times-circle',
            'returned' => 'undo',
        ];

        return $icons[$this->status] ?? 'circle';
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'pending' => '#f59e0b',
            'confirmed' => '#3b82f6',
            'processing' => '#8b5cf6',
            'shipped' => '#6366f1',
            'delivered' => '#10b981',
            'cancelled' => '#ef4444',
            'returned' => '#6b7280',
        ];

        return $colors[$this->status] ?? '#6b7280';
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d M Y, h:i A');
    }

    /**
     * Get changed by name
     */
    public function getChangedByNameAttribute(): string
    {
        if ($this->changed_by && $this->changedByUser) {
            return $this->changedByUser->name ?? 'Admin';
        }
        return 'System';
    }
}
