<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Proposal extends Model
{
   // use HasFactory, SoftDeletes;
   use HasFactory;

    protected $fillable = [
        'proposal_number',
        'subject',
        'customer_id',
        'status',
        'assigned_to',
        'date',
        'open_till',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'email',
        'phone',
        'currency',
        'discount_type',
        'discount_percent',
        'discount_amount',
        'subtotal',
        'tax_amount',
        'total_tax',
        'total',
        'adjustment',
        'content',
        'tags',
        'allow_comments',
        'admin_note',
        'sent_at',
        'accepted_at',
        'declined_at',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'open_till' => 'date',
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
        'allow_comments' => 'boolean',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_tax' => 'decimal:2',
        'total' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'adjustment' => 'decimal:2',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_OPEN = 'open';
    const STATUS_REVISED = 'revised';
    const STATUS_DECLINED = 'declined';
    const STATUS_ACCEPTED = 'accepted';

    public function getAssignedUserAttribute()
    {
        return null;
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SENT => 'Sent',
            self::STATUS_OPEN => 'Open',
            self::STATUS_REVISED => 'Revised',
            self::STATUS_DECLINED => 'Declined',
            self::STATUS_ACCEPTED => 'Accepted',
        ];
    }

    public static function getStatusColor(string $status): string
    {
        return match($status) {
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_SENT => 'info',
            self::STATUS_OPEN => 'primary',
            self::STATUS_REVISED => 'warning',
            self::STATUS_DECLINED => 'danger',
            self::STATUS_ACCEPTED => 'success',
            default => 'secondary',
        };
    }

    public static function generateProposalNumber(): string
    {
        $prefix = 'PRO-';
        $year = date('Y');
        $lastProposal = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastProposal) {
            $lastNumber = (int) substr($lastProposal->proposal_number, -6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $year . '-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(ProposalItem::class)->orderBy('sort_order');
    }

    public function taxes()
    {
        return $this->hasMany(ProposalTax::class);
    }

    public function productItems()
    {
        return $this->hasMany(ProposalItem::class)->where('item_type', 'product')->orderBy('sort_order');
    }

    public function sectionItems()
    {
        return $this->hasMany(ProposalItem::class)->where('item_type', 'section')->orderBy('sort_order');
    }

    public function noteItems()
    {
        return $this->hasMany(ProposalItem::class)->where('item_type', 'note')->orderBy('sort_order');
    }

    // Calculate totals
    public function calculateTotals(): void
    {
        // Refresh relationships
        $this->load(['items', 'taxes']);

        $subtotal = 0;

        foreach ($this->items->where('item_type', 'product') as $item) {
            $subtotal += floatval($item->amount);
        }

        // Get tax from proposal_taxes table
        $taxAmount = 0;
        foreach ($this->taxes as $tax) {
            $taxAmount += floatval($tax->amount);
        }

        // Apply discount
        $discountPercent = floatval($this->discount_percent ?? 0);
        $discountAmount = 0;
        
        if ($this->discount_type === 'before_tax') {
            $discountAmount = ($subtotal * $discountPercent) / 100;
        } elseif ($this->discount_type === 'after_tax') {
            $discountAmount = (($subtotal + $taxAmount) * $discountPercent) / 100;
        }

        $total = $subtotal + $taxAmount - $discountAmount + floatval($this->adjustment ?? 0);

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_tax' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total' => $total,
        ]);
    }
}