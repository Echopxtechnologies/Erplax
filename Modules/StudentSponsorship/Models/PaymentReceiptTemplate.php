<?php

namespace Modules\StudentSponsorship\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentReceiptTemplate extends Model
{
    protected $table = 'payment_receipt_templates';

    protected $fillable = [
        'currency',
        'currency_name',
        'currency_symbol',
        'organization_name',
        'organization_address',
        'organization_phone',
        'organization_email',
        'organization_website',
        'organization_logo',
        'receipt_title',
        'header_text',
        'footer_text',
        'thank_you_message',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'bank_branch',
        'bank_swift_code',
        'primary_color',
        'secondary_color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get template by currency code
     */
    public static function getByCurrency(string $currency): ?self
    {
        return self::where('currency', strtoupper($currency))->first();
    }

    /**
     * Get or create template for currency
     */
    public static function getOrCreateByCurrency(string $currency): self
    {
        $currency = strtoupper($currency);
        
        $template = self::where('currency', $currency)->first();
        
        if (!$template) {
            $template = self::create([
                'currency' => $currency,
                'currency_name' => $currency,
                'currency_symbol' => $currency,
                'organization_name' => 'Student Sponsorship Program',
                'receipt_title' => 'Payment Receipt',
                'thank_you_message' => 'Thank you for your generous contribution.',
                'is_active' => true,
            ]);
        }
        
        return $template;
    }

    /**
     * Format amount with currency symbol
     */
    public function formatAmount($amount): string
    {
        $symbol = $this->currency_symbol ?: $this->currency;
        return $symbol . ' ' . number_format($amount, 2);
    }
}
