<?php

namespace Modules\StudentSponsor\Models;

use Illuminate\Database\Eloquent\Model;

class SponsorPayment extends Model
{
    protected $table = 'tblsponsor_payments';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'transaction_id',
        'sponsor_id',
        'student_id',
        'payment_date',
        'amount',
        'currency',
        'note',
        'created_at',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->created_at) {
                $model->created_at = now();
            }
        });
    }

    public function transaction()
    {
        return $this->belongsTo(SponsorTransaction::class, 'transaction_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class, 'sponsor_id');
    }
}
