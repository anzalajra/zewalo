<?php

namespace App\Services;

use App\Models\Setting;
use Carbon\Carbon;

class RentalValidationService
{
    public static function getSchedule(): array
    {
        return json_decode(Setting::get('operational_schedule'), true) ?? [];
    }

    public static function getHolidays(): array
    {
        return json_decode(Setting::get('holidays'), true) ?? [];
    }

    /**
     * Validate that a Carbon datetime is within operational hours for its day.
     * Returns an error message string, or null if valid.
     */
    public static function validateOperationalDateTime(Carbon $dateTime, array $schedule): ?string
    {
        if (empty($schedule)) {
            return null;
        }

        $dayKey = (string) $dateTime->dayOfWeek; // 0=Sunday … 6=Saturday
        $day    = $schedule[$dayKey] ?? null;

        if (! $day || ! ($day['enabled'] ?? false)) {
            return 'Tanggal tidak termasuk hari operasional.';
        }

        if ($day['is_24h'] ?? false) {
            return null;
        }

        $time  = $dateTime->format('H:i');
        $open  = $day['open']  ?? '00:00';
        $close = $day['close'] ?? '23:59';

        if ($time < $open || $time > $close) {
            return "Jam operasional hari tersebut adalah {$open} – {$close}.";
        }

        return null;
    }

    /**
     * Check whether a date falls within any configured holiday range.
     */
    public static function isHoliday(Carbon $date, array $holidays): bool
    {
        $dateStr = $date->format('Y-m-d');

        foreach ($holidays as $holiday) {
            $start = $holiday['start_date'] ?? null;
            $end   = $holiday['end_date']   ?? null;

            if ($start && $end && $dateStr >= $start && $dateStr <= $end) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate both start and end datetimes against operational schedule and holidays.
     * Returns an array keyed by field name; empty array means no errors.
     */
    public static function validateRentalPeriod(Carbon $startDate, Carbon $endDate): array
    {
        $errors   = [];
        $schedule = self::getSchedule();
        $holidays = self::getHolidays();

        // --- start_date ---
        if (self::isHoliday($startDate, $holidays)) {
            $errors['start_date'] = 'Tanggal pengambilan termasuk hari libur operasional.';
        } elseif ($msg = self::validateOperationalDateTime($startDate, $schedule)) {
            $errors['start_date'] = $msg;
        }

        // --- end_date ---
        if (self::isHoliday($endDate, $holidays)) {
            $errors['end_date'] = 'Tanggal pengembalian termasuk hari libur operasional.';
        } elseif ($msg = self::validateOperationalDateTime($endDate, $schedule)) {
            $errors['end_date'] = $msg;
        }

        return $errors;
    }
}
