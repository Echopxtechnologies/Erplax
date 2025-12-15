<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'countries';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'country_id';

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'iso2',
        'iso3',
        'short_name',
        'long_name',
        'numcode',
        'calling_code',
        'cctld',
        'un_member',
    ];

    /**
     * Scope: UN Members only
     */
    public function scopeUnMembers($query)
    {
        return $query->where('un_member', 'yes');
    }

    /**
     * Scope: Active countries (can be customized)
     */
    public function scopeActive($query)
    {
        return $query->whereNotNull('iso2');
    }

    /**
     * Get display name with code
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->short_name} ({$this->iso2})";
    }

    /**
     * Get formatted calling code
     */
    public function getFormattedCallingCodeAttribute(): string
    {
        return $this->calling_code ? "+{$this->calling_code}" : '';
    }

    /**
     * Helper: Get countries for dropdown
     */
    public static function forDropdown(): array
    {
        return static::orderBy('short_name')
            ->pluck('short_name', 'country_id')
            ->toArray();
    }

    /**
     * Helper: Find by ISO2 code
     */
    public static function findByIso2(string $iso2): ?self
    {
        return static::where('iso2', strtoupper($iso2))->first();
    }

    /**
     * Helper: Find by ISO3 code
     */
    public static function findByIso3(string $iso3): ?self
    {
        return static::where('iso3', strtoupper($iso3))->first();
    }
}