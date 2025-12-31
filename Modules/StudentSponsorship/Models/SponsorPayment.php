<?php

namespace Modules\StudentSponsorship\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SponsorPayment extends Model
{
    use SoftDeletes;

    protected $table = 'sponsor_payments';

    protected $fillable = [
        'transaction_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'receipt_number',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // =========================================
    // RELATIONSHIPS
    // =========================================

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(SponsorTransaction::class, 'transaction_id');
    }

    /**
     * Get sponsor through transaction
     */
    public function getSponsorAttribute()
    {
        return $this->transaction?->sponsor;
    }

    // =========================================
    // ACCESSORS
    // =========================================

    /**
     * Get currency from transaction
     */
    public function getCurrencyAttribute(): string
    {
        return $this->transaction?->currency ?? 'LKR';
    }

    public function getFormattedAmountAttribute(): string
    {
        $currency = $this->currency;
        return $currency . ' ' . number_format($this->amount, 2);
    }

    public function getPaymentMethodDisplayAttribute(): ?string
    {
        $methods = [
            'cash' => 'Cash',
            'bank_transfer' => 'Bank Transfer',
            'cheque' => 'Cheque',
            'upi' => 'UPI',
            'online' => 'Online',
            'card' => 'Card',
        ];
        return $methods[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    // =========================================
    // BOOT
    // =========================================

    protected static function boot()
    {
        parent::boot();

        // After creating payment, update transaction amount_paid
        static::created(function ($payment) {
            $payment->updateTransactionTotals();
        });

        // After updating payment, update transaction amount_paid
        static::updated(function ($payment) {
            $payment->updateTransactionTotals();
        });

        // After deleting payment, update transaction amount_paid
        static::deleted(function ($payment) {
            $payment->updateTransactionTotals();
        });
    }

    /**
     * Update transaction totals after payment changes
     * Creates sponsorship relation on ANY payment (even partial/₹1)
     */
    public function updateTransactionTotals(): void
    {
        $transaction = $this->transaction;
        if (!$transaction) return;

        // Sum all payments for this transaction
        $totalPaid = SponsorPayment::where('transaction_id', $transaction->id)
            ->whereNull('deleted_at')
            ->sum('amount');

        $previousStatus = $transaction->status;
        
        $transaction->amount_paid = $totalPaid;
        
        // Update last payment date
        $lastPayment = SponsorPayment::where('transaction_id', $transaction->id)
            ->whereNull('deleted_at')
            ->orderBy('payment_date', 'desc')
            ->first();
        
        $transaction->last_payment_date = $lastPayment?->payment_date;

        // Update status based on payment
        // Note: amount_paid can exceed total_amount (extra contributions allowed)
        if ($totalPaid >= $transaction->total_amount) {
            $transaction->status = 'completed';
        } elseif ($totalPaid > 0) {
            $transaction->status = 'partial';
        } else {
            $transaction->status = 'pending';
        }

        $transaction->save();

        // Send email if status changed to completed
        if ($previousStatus !== 'completed' && $transaction->status === 'completed') {
            $this->sendPaymentCompletedEmail($transaction);
        }
        
        // Send partial payment confirmation email (on first payment if partial)
        if ($previousStatus === 'pending' && $transaction->status === 'partial') {
            $this->sendPartialPaymentEmail($transaction);
        }
    }

    /**
     * Send partial payment confirmation email to sponsor
     */
    protected function sendPartialPaymentEmail(SponsorTransaction $transaction): void
    {
        try {
            $sponsor = $transaction->sponsor;
            if (!$sponsor || !$sponsor->email) {
                return;
            }

            $studentName = $transaction->student_name ?? 'General Donation';
            $balance = max(0, $transaction->total_amount - $transaction->amount_paid);

            $subject = "Payment Received - Transaction #{$transaction->transaction_number}";

            $body = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
                <div style="background: linear-gradient(135deg, #3b82f6, #2563eb); padding: 30px; border-radius: 12px 12px 0 0; text-align: center;">
                    <h1 style="color: #fff; margin: 0; font-size: 24px;">💙 Payment Received</h1>
                </div>
                
                <div style="background: #fff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 12px 12px;">
                    <p style="font-size: 16px; color: #374151; margin-bottom: 20px;">Dear <strong>' . htmlspecialchars($sponsor->name) . '</strong>,</p>
                    
                    <p style="font-size: 15px; color: #4b5563; line-height: 1.6;">
                        Thank you for your payment! We have received your contribution and your sponsorship is now active.
                    </p>
                    
                    <div style="background: #f9fafb; border-radius: 8px; padding: 20px; margin: 25px 0;">
                        <h3 style="color: #111827; margin: 0 0 15px 0; font-size: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px;">Payment Details</h3>
                        
                        <table style="width: 100%; font-size: 14px; color: #4b5563;">
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Transaction #:</td>
                                <td style="padding: 8px 0; text-align: right; font-family: monospace;">' . $transaction->transaction_number . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Student:</td>
                                <td style="padding: 8px 0; text-align: right;">' . htmlspecialchars($studentName) . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Amount Paid:</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #059669;">' . $transaction->formatted_paid . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Remaining Balance:</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #d97706;">' . $transaction->currency_symbol . number_format($balance, 2) . '</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div style="background: #dbeafe; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 0 8px 8px 0;">
                        <p style="margin: 0; color: #1e40af; font-size: 14px;">
                            <strong>Your sponsorship is now active!</strong><br>
                            You can continue to make payments at your convenience.
                        </p>
                    </div>
                    
                    <p style="font-size: 14px; color: #374151; margin-top: 20px;">
                        Warm regards,<br>
                        <strong>87 Initiative</strong>
                    </p>
                </div>
            </div>';

            send_mail($sponsor->email, $subject, $body);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[SponsorPayment] Failed to send partial payment email', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send payment completed email to sponsor
     */
    protected function sendPaymentCompletedEmail(SponsorTransaction $transaction): void
    {
        try {
            $sponsor = $transaction->sponsor;
            if (!$sponsor || !$sponsor->email) {
                return;
            }

            $studentName = $transaction->student_name ?? 'General Donation';
            $studentType = $transaction->student_type ? ucfirst($transaction->student_type) . ' Student' : '';
            $companyName = '87 Initiative';

            $subject = "Payment Completed - Transaction #{$transaction->transaction_number}";

            $body = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
                <div style="background: linear-gradient(135deg, #10b981, #059669); padding: 30px; border-radius: 12px 12px 0 0; text-align: center;">
                    <h1 style="color: #fff; margin: 0; font-size: 24px;">✓ Payment Completed</h1>
                </div>
                
                <div style="background: #fff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 12px 12px;">
                    <p style="font-size: 16px; color: #374151; margin-bottom: 20px;">Dear <strong>' . htmlspecialchars($sponsor->name) . '</strong>,</p>
                    
                    <p style="font-size: 15px; color: #4b5563; line-height: 1.6;">
                        We are pleased to confirm that your payment has been completed successfully. Thank you for your generous support!
                    </p>
                    
                    <div style="background: #f9fafb; border-radius: 8px; padding: 20px; margin: 25px 0;">
                        <h3 style="color: #111827; margin: 0 0 15px 0; font-size: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px;">Transaction Details</h3>
                        
                        <table style="width: 100%; font-size: 14px; color: #4b5563;">
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Transaction #:</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 600; font-family: monospace;">' . $transaction->transaction_number . '</td>
                            </tr>
                            ' . ($studentName ? '
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Student:</td>
                                <td style="padding: 8px 0; text-align: right;">' . htmlspecialchars($studentName) . '</td>
                            </tr>' : '') . '
                            ' . ($studentType ? '
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Category:</td>
                                <td style="padding: 8px 0; text-align: right;">' . $studentType . '</td>
                            </tr>' : '') . '
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Total Amount:</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #059669;">' . $transaction->formatted_total . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Amount Paid:</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #059669;">' . $transaction->formatted_paid . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Payment Type:</td>
                                <td style="padding: 8px 0; text-align: right;">' . $transaction->payment_type_display . '</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; color: #6b7280;">Completed On:</td>
                                <td style="padding: 8px 0; text-align: right;">' . now()->format('d M Y, h:i A') . '</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div style="background: #ecfdf5; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0; border-radius: 0 8px 8px 0;">
                        <p style="margin: 0; color: #065f46; font-size: 14px;">
                            <strong>Thank you for your contribution!</strong><br>
                            Your support makes a significant difference in the lives of our students.
                        </p>
                    </div>
                    
                    <p style="font-size: 14px; color: #6b7280; margin-top: 25px;">
                        If you have any questions, please don\'t hesitate to contact us.
                    </p>
                    
                    <p style="font-size: 14px; color: #374151; margin-top: 20px;">
                        Warm regards,<br>
                        <strong>' . $companyName . '</strong>
                    </p>
                </div>
                
                <div style="text-align: center; padding: 20px; color: #9ca3af; font-size: 12px;">
                    <p style="margin: 0;">This is an automated email. Please do not reply directly.</p>
                </div>
            </div>';

            // Use the send_mail helper
            send_mail($sponsor->email, $subject, $body);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[SponsorPayment] Failed to send completion email', [
                'transaction_id' => $transaction->id,
                'sponsor_id' => $transaction->sponsor_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get created by user
     */
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Admin\Staff::class, 'created_by', 'id');
    }

    /**
     * Get created by name
     */
    public function getCreatedByNameAttribute(): ?string
    {
        if (!$this->created_by) {
            return null;
        }
        
        $staff = $this->createdBy;
        if ($staff) {
            return $staff->firstname . ' ' . $staff->lastname;
        }
        
        return 'System';
    }
}
