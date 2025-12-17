<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class Option extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'label',
        'description',
        'is_public',
        'autoload',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'autoload' => 'boolean',
    ];

    /**
     * Keys that should be encrypted
     */
    protected static array $encryptedKeys = [
        'mail_password',
        'smtp_password',
        'api_secret',
        'secret_key',
    ];

    /**
     * Cache duration in seconds (1 hour)
     */
    protected static int $cacheTtl = 3600;

    /*
    |--------------------------------------------------------------------------
    | Boot Method - Backup Cache Clearing
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        // Backup cache clearing (in case of direct Eloquent updates)
        static::saved(function (Option $option) {
            Cache::forget("option.{$option->key}");
            Cache::forget('options.autoload');
        });

        static::deleted(function (Option $option) {
            Cache::forget("option.{$option->key}");
            Cache::forget('options.autoload');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Core Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Get option value by key
     * @param bool $fresh - Skip cache and get directly from DB
     */
    public static function get(string $key, $default = null, bool $fresh = false)
    {
        try {
            $cacheKey = "option.{$key}";
            
            // Skip cache if fresh is requested
            if ($fresh) {
                Cache::forget($cacheKey);
            }
            
            // Cache raw data instead of model object
            $cached = Cache::remember($cacheKey, static::$cacheTtl, function () use ($key) {
                $option = static::where('key', $key)->first();
                if (!$option) {
                    return null;
                }
                return [
                    'value' => $option->value,
                    'type' => $option->type,
                ];
            });

            if (!$cached) {
                return $default;
            }

            $value = static::castValue($cached['value'], $cached['type']);

            // Decrypt if it's an encrypted key
            if (in_array($key, static::$encryptedKeys) && $value) {
                try {
                    return Crypt::decryptString($value);
                } catch (\Exception $e) {
                    return $value;
                }
            }

            return $value;
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Set option value
     */
public static function set(string $key, $value, array $attributes = []): self
{
    Cache::forget("option.{$key}");
    Cache::forget('options.autoload');
    
    if (in_array($key, static::$encryptedKeys) && $value) {
        $value = Crypt::encryptString($value);
    } elseif (is_array($value) || is_object($value)) {
        $value = json_encode($value);
    } elseif (is_bool($value)) {
        $value = $value ? '1' : '0';
    }

    $option = static::updateOrCreate(
        ['key' => $key],
        array_merge(['value' => $value], $attributes)
    );
    
    Cache::forget("option.{$key}");
    Cache::forget('options.autoload');
    
    return $option;
}

    /**
     * Set multiple options at once
     */
    public static function setMany(array $options, array $attributes = []): void
    {
        foreach ($options as $key => $value) {
            static::set($key, $value, $attributes);
        }
        
        // Clear autoload cache once at the end
        Cache::forget('options.autoload');
    }

    /**
     * Save file as base64
     */
    public static function setFile(string $key, $uploadedFile, array $attributes = []): self
    {
        $mimeType = $uploadedFile->getMimeType();
        $content = file_get_contents($uploadedFile->getRealPath());
        $base64 = 'data:' . $mimeType . ';base64,' . base64_encode($content);

        return static::set($key, $base64, array_merge(['type' => 'file'], $attributes));
    }

    /**
     * Delete an option by key
     */
    public static function remove(string $key): bool
    {
        $option = static::where('key', $key)->first();
        return $option ? $option->delete() : false;
    }

    /**
     * Check if option exists
     */
    public static function has(string $key): bool
    {
        return static::get($key) !== null;
    }

    /**
     * Get all options by group
     */
    public static function getGroup(string $group): array
    {
        $options = static::where('group', $group)->get();
        
        $result = [];
        foreach ($options as $option) {
            $value = static::castValue($option->value, $option->type);
            
            if (in_array($option->key, static::$encryptedKeys) && $value) {
                try {
                    $value = Crypt::decryptString($value);
                } catch (\Exception $e) {
                    // Keep original
                }
            }
            
            $result[$option->key] = $value;
        }
        
        return $result;
    }

    /**
     * Get all autoload options (cached)
     */
    public static function getAutoload(): array
    {
        return Cache::remember('options.autoload', static::$cacheTtl, function () {
            $options = static::where('autoload', true)->get();
            
            $result = [];
            foreach ($options as $option) {
                $result[$option->key] = static::castValue($option->value, $option->type);
            }
            
            return $result;
        });
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, ?string $type)
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'number', 'integer' => (int) $value,
            'float', 'decimal' => (float) $value,
            'json', 'array' => is_array($value) ? $value : (json_decode($value, true) ?? []),
            default => $value,
        };
    }

    /**
     * Clear all options cache (use only when updating DB directly)
     */
    public static function clearCache(): void
    {
        try {
            $keys = static::pluck('key')->toArray();
            foreach ($keys as $key) {
                Cache::forget("option.{$key}");
            }
            Cache::forget('options.autoload');
        } catch (\Exception $e) {
            // Table might not exist
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Company Settings Helpers
    |--------------------------------------------------------------------------
    */

    public static function companyName(): string
    {
        return static::get('company_name', config('app.name', 'Laravel'));
    }

    public static function companyEmail(): ?string
    {
        return static::get('company_email');
    }

    public static function companyPhone(): ?string
    {
        return static::get('company_phone');
    }

    public static function companyAddress(): ?string
    {
        return static::get('company_address');
    }

    public static function companyLogo(): ?string
    {
        return static::get('company_logo');
    }

    public static function companyFavicon(): ?string
    {
        return static::get('company_favicon');
    }

    public static function companyWebsite(): ?string
    {
        return static::get('company_website');
    }

    public static function companyCity(): ?string
    {
        return static::get('company_city');
    }

    public static function companyState(): ?string
    {
        return static::get('company_state');
    }

    public static function companyCountryCode(): ?string
    {
        return static::get('company_country_code');
    }

    public static function companyZip(): ?string
    {
        return static::get('company_zip');
    }

    public static function companyGst(): ?string
    {
        return static::get('company_gst');
    }

    public static function companyPan(): ?string
    {
        return static::get('company_pan');
    }

    public static function companyCin(): ?string
    {
        return static::get('company_cin');
    }

    /**
     * Get full company address formatted
     */
    public static function companyFullAddress(): string
    {
        return implode(', ', array_filter([
            static::companyAddress(),
            static::companyCity(),
            static::companyState(),
            static::companyZip(),
        ]));
    }

    /**
     * Get all company information as array
     */
    public static function companyInfo(): array
    {
        return [
            'name' => static::companyName(),
            'email' => static::companyEmail(),
            'phone' => static::companyPhone(),
            'address' => static::companyAddress(),
            'city' => static::companyCity(),
            'state' => static::companyState(),
            'country_code' => static::companyCountryCode(),
            'zip' => static::companyZip(),
            'website' => static::companyWebsite(),
            'logo' => static::companyLogo(),
            'gst' => static::companyGst(),
            'pan' => static::companyPan(),
            'cin' => static::companyCin(),
            'full_address' => static::companyFullAddress(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Mail Settings Helpers
    |--------------------------------------------------------------------------
    */

    public static function mailConfig(): array
    {
        return [
            'mailer' => static::get('mail_mailer', 'smtp'),
            'host' => static::get('mail_host', ''),
            'port' => (int) static::get('mail_port', 587),
            'username' => static::get('mail_username', ''),
            'password' => static::get('mail_password', ''),
            'encryption' => static::get('mail_encryption', 'tls'),
            'from_address' => static::get('mail_from_address', ''),
            'from_name' => static::get('mail_from_name', static::companyName()),
        ];
    }

    public static function mailDriver(): string
    {
        return static::get('mail_mailer', 'smtp');
    }

    public static function mailHost(): ?string
    {
        return static::get('mail_host');
    }

    public static function mailPort(): int
    {
        return (int) static::get('mail_port', 587);
    }

    public static function mailUsername(): ?string
    {
        return static::get('mail_username');
    }

    public static function mailPassword(): ?string
    {
        return static::get('mail_password');
    }

    public static function mailEncryption(): ?string
    {
        return static::get('mail_encryption', 'tls');
    }

    public static function mailFromAddress(): ?string
    {
        return static::get('mail_from_address');
    }

    public static function mailFromName(): ?string
    {
        return static::get('mail_from_name');
    }
}