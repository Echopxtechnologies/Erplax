<?php

namespace Modules\Purchase\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue(string $key, $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function getAll(): array
    {
        return self::pluck('value', 'key')->toArray();
    }
}
