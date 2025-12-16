<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoodsReceiptNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'grn_number',
        'purchase_order_id',
        'vendor_id',
        'grn_date',
        'invoice_number',
        'invoice_date',
        'lr_number',
        'vehicle_number',
        'status',
        'warehouse_id',
        'rack_id',
        'total_qty',
        'accepted_qty',
        'rejected_qty',
        'rejection_reason',
        'notes',
        'stock_updated',
        'approved_at',
        'approved_by',
        'received_by',
        'created_by',
    ];

    protected $casts = [
        'grn_date' => 'date',
        'invoice_date' => 'date',
        'approved_at' => 'datetime',
        'stock_updated' => 'boolean',
        'total_qty' => 'decimal:3',
        'accepted_qty' => 'decimal:3',
        'rejected_qty' => 'decimal:3',
    ];

    // ==================== RELATIONSHIPS ====================

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(\Modules\Inventory\Models\Warehouse::class);
    }

    public function rack(): BelongsTo
    {
        return $this->belongsTo(\Modules\Inventory\Models\Rack::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(GoodsReceiptNoteItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Admin::class, 'received_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Admin::class, 'approved_by');
    }

    // ==================== ACCESSORS ====================

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'DRAFT' => 'secondary',
            'INSPECTING' => 'warning',
            'APPROVED' => 'success',
            'REJECTED' => 'danger',
            'CANCELLED' => 'dark',
            default => 'secondary',
        };
    }

    public function getCanEditAttribute(): bool
    {
        return in_array($this->status, ['DRAFT', 'INSPECTING']);
    }

    public function getCanApproveAttribute(): bool
    {
        return $this->status === 'INSPECTING' && !$this->stock_updated;
    }

    // ==================== STATIC METHODS ====================

    public static function generateGrnNumber(): string
    {
        $prefix = PurchaseSetting::getValue('grn_prefix', 'GRN-');
        $yearMonth = date('Ym');
        
        $last = self::withTrashed()
            ->where('grn_number', 'like', $prefix . $yearMonth . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNum = (int) substr($last->grn_number, -4);
            $newNum = $lastNum + 1;
        } else {
            $newNum = 1;
        }

        return $prefix . $yearMonth . '-' . str_pad($newNum, 4, '0', STR_PAD_LEFT);
    }

    // ==================== METHODS ====================

    public function calculateTotals(): void
    {
        $this->total_qty = $this->items()->sum('received_qty');
        $this->accepted_qty = $this->items()->sum('accepted_qty');
        $this->rejected_qty = $this->items()->sum('rejected_qty');
        $this->save();
    }

    public function updatePurchaseOrder(): void
    {
        $po = $this->purchaseOrder;
        if (!$po) return;

        // Update received qty for each PO item
        foreach ($this->items as $grnItem) {
            if ($grnItem->purchase_order_item_id) {
                $poItem = $po->items()->find($grnItem->purchase_order_item_id);
                if ($poItem) {
                    // Add accepted qty to PO item's received qty
                    $poItem->received_qty += $grnItem->accepted_qty;
                    $poItem->save();
                }
            }
        }

        // Check if fully or partially received
        $allReceived = true;
        $anyReceived = false;

        foreach ($po->items as $item) {
            if ($item->received_qty > 0) {
                $anyReceived = true;
            }
            if ($item->received_qty < $item->qty) {
                $allReceived = false;
            }
        }

        // Update PO status
        if ($allReceived) {
            $po->status = 'RECEIVED';
            $po->delivery_date = now();
        } elseif ($anyReceived) {
            $po->status = 'PARTIALLY_RECEIVED';
        }
        
        $po->save();
    }
}
