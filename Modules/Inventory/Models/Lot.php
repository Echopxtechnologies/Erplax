<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Lot extends Model
{
    use HasFactory;

    protected $table = 'lots';

    // ==================== STATUS CONSTANTS ====================
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_EXPIRED = 'EXPIRED';
    const STATUS_RECALLED = 'RECALLED';
    const STATUS_CONSUMED = 'CONSUMED';

    // ==================== FILLABLE ====================
    protected $fillable = [
        'product_id',
        'variation_id',
        'lot_no',
        'batch_no',
        'initial_qty',
        'purchase_price',
        'sale_price',
        'manufacturing_date',
        'expiry_date',
        'status',
        'notes',
    ];

    // ==================== CASTS ====================
    protected $casts = [
        'manufacturing_date' => 'date',
        'expiry_date' => 'date',
        'initial_qty' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    public function stockLevels()
    {
        return $this->hasMany(StockLevel::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // ==================== ACCESSORS ====================

    /**
     * Get display name (lot_no + batch_no)
     */
    public function getDisplayNameAttribute()
    {
        $name = $this->lot_no;
        if ($this->batch_no) {
            $name .= ' / ' . $this->batch_no;
        }
        return $name;
    }

    /**
     * Get full display name including variation
     */
    public function getFullDisplayNameAttribute()
    {
        $name = $this->display_name;
        if ($this->variation) {
            $name .= ' [' . ($this->variation->variation_name ?? $this->variation->sku) . ']';
        }
        return $name;
    }

    /**
     * Check if lot is expired
     */
    public function getIsExpiredAttribute()
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date->isPast();
    }

    /**
     * Get days to expiry (negative if expired)
     */
    public function getDaysToExpiryAttribute()
    {
        if (!$this->expiry_date) {
            return null;
        }
        return (int) now()->startOfDay()->diffInDays($this->expiry_date->startOfDay(), false);
    }

    /**
     * Get expiry status string
     */
    public function getExpiryStatusAttribute()
    {
        if (!$this->expiry_date) {
            return 'no_expiry';
        }

        $days = $this->days_to_expiry;

        if ($days < 0) {
            return 'expired';
        } elseif ($days <= 30) {
            return 'expiring_soon';
        } elseif ($days <= 90) {
            return 'expiring_medium';
        }
        
        return 'ok';
    }

    /**
     * Get expiry badge color
     */
    public function getExpiryBadgeColorAttribute()
    {
        return match($this->expiry_status) {
            'expired' => 'danger',
            'expiring_soon' => 'warning',
            'expiring_medium' => 'info',
            'ok' => 'success',
            default => 'secondary',
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'ACTIVE' => 'success',
            'EXPIRED' => 'danger',
            'RECALLED' => 'warning',
            'CONSUMED' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get current stock from stock_levels
     */
    public function getCurrentStockAttribute()
    {
        if ($this->relationLoaded('stockLevels')) {
            return $this->stockLevels->sum('qty');
        }
        return $this->stockLevels()->sum('qty');
    }

    /**
     * Get available stock (qty - reserved)
     */
    public function getAvailableStockAttribute()
    {
        if ($this->relationLoaded('stockLevels')) {
            return $this->stockLevels->sum(function ($level) {
                return $level->qty - ($level->reserved_qty ?? 0);
            });
        }
        return $this->stockLevels()->sum(\DB::raw('qty - COALESCE(reserved_qty, 0)'));
    }

    // ==================== METHODS ====================

    /**
     * Check if lot can be sold
     */
    public function canBeSold()
    {
        return $this->status === self::STATUS_ACTIVE 
            && !$this->is_expired 
            && $this->current_stock > 0;
    }

    /**
     * Mark lot as expired
     */
    public function markExpired()
    {
        $this->update(['status' => self::STATUS_EXPIRED]);
        return $this;
    }

    /**
     * Mark lot as recalled
     */
    public function markRecalled($reason = null)
    {
        $notes = $this->notes;
        if ($reason) {
            $notes = ($notes ? $notes . "\n" : '') . "Recalled: " . $reason . " (" . now()->format('Y-m-d H:i') . ")";
        }
        
        $this->update([
            'status' => self::STATUS_RECALLED,
            'notes' => $notes,
        ]);
        
        return $this;
    }

    /**
     * Mark lot as consumed
     */
    public function markConsumed()
    {
        $this->update(['status' => self::STATUS_CONSUMED]);
        return $this;
    }

    /**
     * Get stock at specific warehouse/rack
     */
    public function getStockAtWarehouse($warehouseId, $rackId = null)
    {
        $query = $this->stockLevels()->where('warehouse_id', $warehouseId);
        
        if ($rackId) {
            $query->where('rack_id', $rackId);
        }
        
        return $query->sum('qty');
    }

    // ==================== SCOPES ====================

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    public function scopeRecalled($query)
    {
        return $query->where('status', self::STATUS_RECALLED);
    }

    public function scopeConsumed($query)
    {
        return $query->where('status', self::STATUS_CONSUMED);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            });
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('status', self::STATUS_ACTIVE)
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now(), now()->addDays($days)]);
    }

    /**
     * FEFO ordering (First Expiry First Out)
     */
    public function scopeFefo($query)
    {
        return $query->orderByRaw('CASE WHEN expiry_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('expiry_date', 'asc');
    }

    /**
     * FIFO ordering (First In First Out)
     */
    public function scopeFifo($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    // ==================== STATIC METHODS ====================

    /**
     * Generate unique lot number
     */
    public static function generateLotNo($productId, $prefix = null)
    {
        $prefix = $prefix ?: 'LOT-' . date('Ymd') . '-';
        
        $last = self::where('lot_no', 'like', $prefix . '%')
            ->where('product_id', $productId)
            ->orderBy('id', 'desc')
            ->first();
        
        $num = 1;
        if ($last) {
            preg_match('/(\d+)$/', $last->lot_no, $matches);
            if (isset($matches[1])) {
                $num = (int) $matches[1] + 1;
            }
        }
        
        return $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get lots expiring within X days
     */
    public static function getExpiringLots($days = 30)
    {
        return self::with(['product.images', 'product.unit', 'stockLevels.warehouse'])
            ->where('status', self::STATUS_ACTIVE)
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [now(), now()->addDays($days)])
            ->whereHas('stockLevels', function ($q) {
                $q->where('qty', '>', 0);
            })
            ->orderBy('expiry_date', 'asc')
            ->get();
    }

    /**
     * Update statuses for all lots (for cron job)
     */
    public static function updateAllStatuses()
    {
        $expired = 0;
        $consumed = 0;

        // Mark expired lots
        $expiredCount = self::where('status', self::STATUS_ACTIVE)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->update(['status' => self::STATUS_EXPIRED]);
        
        $expired = $expiredCount;

        // Mark consumed lots (no stock remaining)
        $activeLots = self::where('status', self::STATUS_ACTIVE)
            ->with('stockLevels')
            ->get();
            
        foreach ($activeLots as $lot) {
            $totalStock = $lot->stockLevels->sum('qty');
            if ($totalStock <= 0) {
                $lot->update(['status' => self::STATUS_CONSUMED]);
                $consumed++;
            }
        }

        return [
            'expired' => $expired,
            'consumed' => $consumed,
            'total_updated' => $expired + $consumed,
        ];
    }
}