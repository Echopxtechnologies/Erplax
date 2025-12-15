<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'label',
        'offset',
        'offset_minutes',
        'country_code',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'offset_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Scope for active timezones
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by offset
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('offset_minutes')->orderBy('name');
    }

    /**
     * Get dropdown list
     */
    public static function dropdown(): array
    {
        return static::active()
            ->ordered()
            ->pluck('label', 'name')
            ->toArray();
    }

    /**
     * Get dropdown list with ID
     */
    public static function dropdownById(): array
    {
        return static::active()
            ->ordered()
            ->pluck('label', 'id')
            ->toArray();
    }

    /**
     * Find by timezone name
     */
    public static function findByName(string $name): ?self
    {
        return static::where('name', $name)->first();
    }

    /**
     * Get timezones by country
     */
    public static function getByCountry(string $countryCode): \Illuminate\Database\Eloquent\Collection
    {
        return static::active()
            ->where('country_code', strtoupper($countryCode))
            ->ordered()
            ->get();
    }

    /**
     * Get current time in this timezone
     */
    public function getCurrentTime(): \Carbon\Carbon
    {
        return now()->timezone($this->name);
    }

    /**
     * Convert time to this timezone
     */
    public function convertTime(\Carbon\Carbon $time): \Carbon\Carbon
    {
        return $time->copy()->timezone($this->name);
    }
}