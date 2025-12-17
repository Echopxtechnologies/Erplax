<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalTax extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'proposal_taxes';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'proposal_id',
        'tax_id',
        'name',
        'rate',
        'amount',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'tax_id' => 'integer',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the proposal that owns the tax.
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Get the tax definition.
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
}