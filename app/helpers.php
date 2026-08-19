<?php

use App\Support\DateHelper;

if (!function_exists('format_id_date')) {
    /**
     * Format date into user-friendly Indonesian format (e.g. 18 Agu 2026).
     */
    function format_id_date($date, string $fallback = '-'): string
    {
        return DateHelper::format($date, $fallback);
    }
}

if (!function_exists('format_id_datetime')) {
    /**
     * Format date and time into user-friendly Indonesian format (e.g. 18 Agu 2026, 14:30 WIB).
     */
    function format_id_datetime($date, bool $withWib = true, string $fallback = '-'): string
    {
        return DateHelper::formatDateTime($date, $withWib, $fallback);
    }
}

if (!function_exists('format_id_full_date')) {
    /**
     * Format date into full formal Indonesian format (e.g. 18 Agustus 2026).
     */
    function format_id_full_date($date, string $fallback = '-'): string
    {
        return DateHelper::formatFull($date, $fallback);
    }
}

if (!function_exists('format_id_day_date')) {
    /**
     * Format date with Indonesian day name (e.g. Selasa, 18 Agustus 2026).
     */
    function format_id_day_date($date, string $fallback = '-'): string
    {
        return DateHelper::formatDayDate($date, $fallback);
    }
}

if (!function_exists('format_id_month_year')) {
    /**
     * Format month and year in Indonesian (e.g. Agustus 2026).
     */
    function format_id_month_year($date, string $fallback = '-'): string
    {
        return DateHelper::formatMonthYear($date, $fallback);
    }
}

if (!function_exists('format_id_diff')) {
    /**
     * Format relative date diff in Indonesian (e.g. 2 jam yang lalu).
     */
    function format_id_diff($date, string $fallback = '-'): string
    {
        return DateHelper::diffForHumans($date, $fallback);
    }
}
