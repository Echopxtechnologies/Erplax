<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'symbol_native',
        'decimal_digits',
        'decimal_separator',
        'thousand_separator',
        'symbol_position',
        'space_between',
        'exchange_rate',
        'is_default',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'decimal_digits' => 'integer',
        'space_between' => 'boolean',
        'exchange_rate' => 'decimal:6',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the default currency
     */
    public static function getDefault(): ?self
    {
        return static::where('is_default', true)->first();
    }

    /**
     * Set as default currency
     */
    public function setAsDefault(): bool
    {
        // Remove default from all others
        static::where('is_default', true)->update(['is_default' => false]);
        
        // Set this as default
        return $this->update(['is_default' => true]);
    }

    /**
     * Scope for active currencies
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get dropdown list
     */
    public static function dropdown(): array
    {
        return static::active()
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => "{$item->name} ({$item->code}) - {$item->symbol}"];
            })
            ->toArray();
    }

    /**
     * Format amount with this currency
     */
    public function format(float $amount): string
    {
        $formatted = number_format(
            $amount,
            $this->decimal_digits,
            $this->decimal_separator,
            $this->thousand_separator
        );

        $space = $this->space_between ? ' ' : '';

        if ($this->symbol_position === 'before') {
            return $this->symbol . $space . $formatted;
        }

        return $formatted . $space . $this->symbol;
    }

    /**
     * Convert amount to this currency from base
     */
    public function convertFrom(float $amount, Currency $fromCurrency): float
    {
        if ($fromCurrency->id === $this->id) {
            return $amount;
        }

        // Convert to base currency first, then to target
        $baseAmount = $amount / $fromCurrency->exchange_rate;
        return $baseAmount * $this->exchange_rate;
    }
}