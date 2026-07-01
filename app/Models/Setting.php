<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    public const PAYPAL_ENABLED_KEY = 'paypal_enabled';
    public const CONSULTATION_WEEKLY_HOURS_KEY = 'consultation_weekly_hours';

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

    public static function isPaypalEnabled(): bool
    {
        return self::getBoolValue(self::PAYPAL_ENABLED_KEY, true);
    }

    public static function defaultConsultationWeeklyHours(): array
    {
        return [
            'monday' => ['start' => '09:00', 'end' => '17:00'],
            'tuesday' => ['start' => '09:00', 'end' => '17:00'],
            'wednesday' => ['start' => '09:00', 'end' => '17:00'],
            'thursday' => ['start' => '09:00', 'end' => '17:00'],
            'friday' => ['start' => '09:00', 'end' => '17:00'],
            'saturday' => ['start' => '09:00', 'end' => '12:00'],
            'sunday' => ['start' => null, 'end' => null],
        ];
    }

    public static function getConsultationWeeklyHours(): array
    {
        $storedValue = self::getValue(self::CONSULTATION_WEEKLY_HOURS_KEY);
        $decodedValue = is_string($storedValue) ? json_decode($storedValue, true) : null;

        if (!is_array($decodedValue)) {
            return self::defaultConsultationWeeklyHours();
        }

        return self::normalizeConsultationWeeklyHours($decodedValue);
    }

    public static function normalizeConsultationWeeklyHours(array $weeklyHours): array
    {
        $normalized = self::defaultConsultationWeeklyHours();

        foreach ($normalized as $day => $defaultSchedule) {
            $daySchedule = $weeklyHours[$day] ?? [];
            $start = self::normalizeConsultationTime($daySchedule['start'] ?? null);
            $end = self::normalizeConsultationTime($daySchedule['end'] ?? null);

            $normalized[$day] = [
                'start' => $start,
                'end' => $end,
            ];
        }

        return $normalized;
    }

    protected static function normalizeConsultationTime($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return preg_match('/^(?:[01]\d|2[0-3]):(?:00|30)$/', $value) ? $value : null;
    }
}