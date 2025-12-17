<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimation extends Model
{
    use HasFactory;

    protected $fillable = [
        'estimation_number',
        'subject',
        'customer_id',
        'proposal_id',
        'status',
        'assigned_to',
        'date',
        'valid_until',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'email',
        'phone',
        'currency',
        'subtotal',
        'discount_type',
        'discount_percent',
        'discount_amount',
        'tax_amount',
        'adjustment',
        'total',
        'content',
        'tags',
        'allow_comments',
        'admin_note',
        'terms_conditions',
        'validity_days',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'valid_until' => 'date',
        'subtotal' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'adjustment' => 'decimal:2',
        'total' => 'decimal:2',
        'allow_comments' => 'boolean',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    public function items()
    {
        return $this->hasMany(EstimationItem::class)->orderBy('sort_order');
    }

    public function taxes()
    {
        return $this->hasMany(EstimationTax::class);
    }

    // Statuses
    public static function getStatuses(): array
    {
        return [
            'draft'    => 'Draft',
            'sent'     => 'Sent',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'revised'  => 'Revised',
        ];
    }

    public static function getStatusColor(string $status): string
    {
        return match ($status) {
            'draft'    => 'secondary',
            'sent'     => 'info',
            'approved' => 'success',
            'rejected' => 'danger',
            'revised'  => 'warning',
            default    => 'secondary',
        };
    }

    // Generate unique estimation number
    public static function generateEstimationNumber(): string
    {
        $year = date('Y');
        $prefix = "EST-{$year}-";
        
        $lastEstimation = self::where('estimation_number', 'like', $prefix . '%')
            ->orderBy('estimation_number', 'desc')
            ->first();
        
        if ($lastEstimation) {
            $lastNumber = (int) substr($lastEstimation->estimation_number, -6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    // Calculate totals using estimation_taxes table
    public function calculateTotals(): void
    {
        // Refresh relationships to get latest data
        $this->load(['items', 'taxes']);

        // Calculate subtotal from items
        $subtotal = 0;
        foreach ($this->items->where('item_type', 'product') as $item) {
            $subtotal += floatval($item->amount);
        }

        // Calculate discount
        $discountAmount = 0;
        $discountPercent = floatval($this->discount_percent ?? 0);
        
        if ($this->discount_type === 'before_tax') {
            $discountAmount = ($subtotal * $discountPercent) / 100;
        }

        $afterDiscount = $subtotal - $discountAmount;

        // Get total tax from estimation_taxes table
        $taxAmount = 0;
        foreach ($this->taxes as $tax) {
            $taxAmount += floatval($tax->amount);
        }

        // If discount is after tax
        if ($this->discount_type === 'after_tax') {
            $discountAmount = (($subtotal + $taxAmount) * $discountPercent) / 100;
        }

        // Calculate grand total
        $total = $subtotal + $taxAmount - $discountAmount + floatval($this->adjustment ?? 0);

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total' => $total,
        ]);
    }

    // Create estimation from proposal
    public static function createFromProposal(Proposal $proposal): self
    {
        $estimation = self::create([
            'estimation_number' => self::generateEstimationNumber(),
            'subject' => $proposal->subject,
            'customer_id' => $proposal->customer_id,
            'proposal_id' => $proposal->id,
            'status' => 'draft',
            'assigned_to' => $proposal->assigned_to,
            'date' => now(),
            'valid_until' => now()->addDays(30),
            'address' => $proposal->address,
            'city' => $proposal->city,
            'state' => $proposal->state,
            'country' => $proposal->country,
            'zip_code' => $proposal->zip_code,
            'email' => $proposal->email,
            'phone' => $proposal->phone,
            'currency' => $proposal->currency,
            'discount_type' => $proposal->discount_type,
            'discount_percent' => $proposal->discount_percent,
            'content' => $proposal->content,
            'admin_note' => $proposal->admin_note,
            'created_by' => auth()->user()->name ?? null,
        ]);

        // Copy proposal items
        foreach ($proposal->items as $item) {
            EstimationItem::create([
                'estimation_id' => $estimation->id,
                'item_type' => $item->item_type,
                'product_id' => $item->product_id,
                'description' => $item->description,
                'long_description' => $item->long_description,
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'rate' => $item->rate,
                'tax_rate' => $item->tax_rate ?? 0,
                'tax_name' => $item->tax_name,
                'tax_amount' => $item->tax_amount,
                'amount' => $item->amount,
                'total' => $item->total,
                'sort_order' => $item->sort_order,
            ]);
        }

        // Copy proposal taxes if exists
        if (method_exists($proposal, 'taxes') && $proposal->taxes) {
            foreach ($proposal->taxes as $tax) {
                EstimationTax::create([
                    'estimation_id' => $estimation->id,
                    'tax_id' => $tax->tax_id,
                    'name' => $tax->name,
                    'rate' => $tax->rate,
                    'amount' => $tax->amount,
                ]);
            }
        }

        $estimation->calculateTotals();

        return $estimation;
    }
}