<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    // Helper method untuk get setting value
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    // Helper method untuk set setting value
    public static function setValue($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    // Helper method untuk get boolean value
    public static function getBoolValue($key, $default = false)
    {
        $value = self::getValue($key, $default);
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    // Helper method untuk get numeric value
    public static function getNumericValue($key, $default = 0)
    {
        $value = self::getValue($key, $default);
        return is_numeric($value) ? (float)$value : $default;
    }
}