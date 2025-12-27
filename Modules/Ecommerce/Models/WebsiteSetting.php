<?php

namespace Modules\Ecommerce\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $table = 'website_settings';

    protected $fillable = [
        // General
        'site_name',
        'site_url',
        'site_prefix',
        'shop_prefix',
        'site_logo',
        'site_favicon',
        'site_mode',
        'homepage_id',
        'contact_email',
        'contact_phone',
        'meta_title',
        'meta_description',
        'is_active',
        
        // Shipping
        'shipping_fee',
        'free_shipping_min',
        'delivery_days',
        
        // COD
        'cod_enabled',
        'cod_fee',
        'cod_max_amount',
        
        // Order
        'order_prefix',
        'min_order_amount',
        'guest_checkout',
        
        // Tax
        'tax_included_in_price',
        'show_tax_breakup',
        
        // Store Info
        'store_address',
        'store_city',
        'store_state',
        'store_pincode',
        'store_gstin',
        
        // Invoice
        'invoice_prefix',
        'invoice_footer',
        
        // Email Notifications
        'order_notification_email',
        'send_customer_order_email',
        'send_admin_order_alert',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'cod_enabled' => 'boolean',
        'guest_checkout' => 'boolean',
        'tax_included_in_price' => 'boolean',
        'show_tax_breakup' => 'boolean',
        'send_customer_order_email' => 'boolean',
        'send_admin_order_alert' => 'boolean',
        'shipping_fee' => 'decimal:2',
        'free_shipping_min' => 'decimal:2',
        'cod_fee' => 'decimal:2',
        'cod_max_amount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
    ];

    /**
     * Get the settings instance (singleton pattern)
     */
    public static function instance()
    {
        return static::first() ?? static::create([
            'site_name' => config('app.name', 'My Website'),
            'site_url' => config('app.url', 'http://localhost'),
            'site_prefix' => 'site',
            'shop_prefix' => 'shop',
            'site_mode' => 'both',
            'is_active' => true,
        ]);
    }

    /**
     * Get a specific setting value
     */
    public static function getValue(string $key, $default = null)
    {
        $settings = static::instance();
        return $settings->{$key} ?? $default;
    }

    /**
     * Check if site mode is website only
     */
    public function isWebsiteOnly(): bool
    {
        return $this->site_mode === 'website_only';
    }

    /**
     * Check if site mode is ecommerce only
     */
    public function isEcommerceOnly(): bool
    {
        return $this->site_mode === 'ecommerce_only';
    }

    /**
     * Check if site mode is both
     */
    public function isBoth(): bool
    {
        return $this->site_mode === 'both';
    }

    /**
     * Check if ecommerce is enabled
     */
    public function hasEcommerce(): bool
    {
        return in_array($this->site_mode, ['ecommerce_only', 'both']);
    }

    /**
     * Check if website pages are enabled
     */
    public function hasWebsite(): bool
    {
        return in_array($this->site_mode, ['website_only', 'both']);
    }

    /**
     * Get site mode label
     */
    public function getSiteModeLabel(): string
    {
        $labels = [
            'website_only' => 'Website Only',
            'ecommerce_only' => 'Ecommerce Only',
            'both' => 'Both',
        ];

        return $labels[$this->site_mode] ?? 'Unknown';
    }

    /**
     * Get logo URL
     */
    public function getLogoUrl(): ?string
    {
        if ($this->site_logo) {
            return asset('storage/' . $this->site_logo);
        }
        return null;
    }

    /**
     * Get favicon URL
     */
    public function getFaviconUrl(): ?string
    {
        if ($this->site_favicon) {
            return asset('storage/' . $this->site_favicon);
        }
        return null;
    }

    /**
     * Get base site URL (without any prefix)
     */
    public function getBaseUrl(): string
    {
        if ($this->site_url) {
            return rtrim($this->site_url, '/');
        }
        return url('/');
    }

    /**
     * Get full public site URL (site_url + site_prefix)
     */
    public function getPublicUrl(): ?string
    {
        $url = $this->getBaseUrl();
        
        if ($this->site_prefix) {
            $prefix = trim($this->site_prefix, '/');
            $url .= '/' . $prefix;
        }

        return $url;
    }

    /**
     * Get full shop URL (site_url + shop_prefix)
     */
    public function getShopFullUrl(): string
    {
        $url = $this->getBaseUrl();
        $prefix = $this->shop_prefix ? trim($this->shop_prefix, '/') : 'shop';
        return $url . '/' . $prefix;
    }

    /**
     * Get site prefix with leading slash
     */
    public function getPrefixPath(): string
    {
        if ($this->site_prefix) {
            return '/' . trim($this->site_prefix, '/');
        }
        return '/site';
    }

    /**
     * Get shop prefix with leading slash
     */
    public function getShopPrefixPath(): string
    {
        if ($this->shop_prefix) {
            return '/' . trim($this->shop_prefix, '/');
        }
        return '/shop';
    }

    /**
     * Get home URL based on site mode
     */
    public function getHomeUrl(): string
    {
        if ($this->site_mode === 'ecommerce_only') {
            return $this->getShopPrefixPath();
        }
        return $this->getPrefixPath();
    }

    /**
     * Get shop URL
     */
    public function getShopUrl(): string
    {
        return $this->getShopPrefixPath();
    }

    /**
     * Get login URL based on site mode
     */
    public function getLoginUrl(): string
    {
        if ($this->site_mode === 'ecommerce_only') {
            return $this->getShopPrefixPath() . '/login';
        }
        return $this->getPrefixPath() . '/login';
    }

    /**
     * Get account URL based on site mode
     */
    public function getAccountUrl(): string
    {
        if ($this->site_mode === 'ecommerce_only') {
            return $this->getShopPrefixPath() . '/account';
        }
        return $this->getPrefixPath() . '/account';
    }

    // =====================
    // SHIPPING HELPERS
    // =====================

    /**
     * Calculate shipping fee based on subtotal
     */
    public function calculateShipping(float $subtotal): float
    {
        // Free shipping if subtotal meets minimum
        if ($this->free_shipping_min > 0 && $subtotal >= $this->free_shipping_min) {
            return 0;
        }
        
        return (float) ($this->shipping_fee ?? 0);
    }

    /**
     * Check if order qualifies for free shipping
     */
    public function hasFreeShipping(float $subtotal): bool
    {
        return $this->free_shipping_min > 0 && $subtotal >= $this->free_shipping_min;
    }

    /**
     * Get amount needed for free shipping
     */
    public function amountForFreeShipping(float $subtotal): float
    {
        if ($this->free_shipping_min <= 0) {
            return 0; // No free shipping threshold
        }
        
        $remaining = $this->free_shipping_min - $subtotal;
        return max(0, $remaining);
    }

    // =====================
    // COD HELPERS
    // =====================

    /**
     * Check if COD is available for given amount
     */
    public function isCodAvailable(float $total): bool
    {
        if (!$this->cod_enabled) {
            return false;
        }
        
        // Check max amount limit
        if ($this->cod_max_amount > 0 && $total > $this->cod_max_amount) {
            return false;
        }
        
        return true;
    }

    /**
     * Get COD fee
     */
    public function getCodFee(): float
    {
        return (float) ($this->cod_fee ?? 0);
    }

    // =====================
    // ORDER HELPERS
    // =====================

    /**
     * Generate next order number
     */
    public function generateOrderNumber(): string
    {
        $prefix = $this->order_prefix ?? 'ORD-';
        $lastOrder = \Modules\Ecommerce\Models\WebsiteOrder::orderBy('id', 'desc')->first();
        $nextId = $lastOrder ? ($lastOrder->id + 1) : 1;
        
        return $prefix . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Generate next invoice number
     */
    public function generateInvoiceNumber(): string
    {
        $prefix = $this->invoice_prefix ?? 'INV-';
        $year = date('Y');
        $lastOrder = \Modules\Ecommerce\Models\WebsiteOrder::whereYear('created_at', $year)
            ->whereNotNull('transaction_id')
            ->orderBy('id', 'desc')
            ->first();
        $nextId = $lastOrder ? ($lastOrder->id + 1) : 1;
        
        return $prefix . $year . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Check if order meets minimum amount
     */
    public function meetsMinimumOrder(float $subtotal): bool
    {
        if ($this->min_order_amount <= 0) {
            return true;
        }
        
        return $subtotal >= $this->min_order_amount;
    }

    /**
     * Get minimum order shortfall
     */
    public function minimumOrderShortfall(float $subtotal): float
    {
        if ($this->min_order_amount <= 0) {
            return 0;
        }
        
        return max(0, $this->min_order_amount - $subtotal);
    }

    // =====================
    // STORE INFO HELPERS
    // =====================

    /**
     * Get full store address
     */
    public function getFullStoreAddress(): string
    {
        $parts = array_filter([
            $this->store_address,
            $this->store_city,
            $this->store_state,
            $this->store_pincode,
        ]);
        
        return implode(', ', $parts);
    }

    /**
     * Check if store info is complete
     */
    public function hasCompleteStoreInfo(): bool
    {
        return !empty($this->store_address) 
            && !empty($this->store_city) 
            && !empty($this->store_state) 
            && !empty($this->store_pincode);
    }
}
