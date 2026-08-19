<?php

namespace App\Support;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format a date into user-friendly Indonesian format (e.g. 18 Agu 2026).
     */
    public static function format($date, string $fallback = '-'): string
    {
        if (empty($date)) {
            return $fallback;
        }

        try {
            if (!$date instanceof Carbon) {
                $date = Carbon::parse($date);
            }
            return $date->locale('id')->isoFormat('D MMM YYYY');
        } catch (\Throwable $e) {
            return (string) $date;
        }
    }

    /**
     * Format a date & time into user-friendly Indonesian format (e.g. 18 Agu 2026, 14:30 WIB).
     */
    public static function formatDateTime($date, bool $withWib = true, string $fallback = '-'): string
    {
        if (empty($date)) {
            return $fallback;
        }

        try {
            if (!$date instanceof Carbon) {
                $date = Carbon::parse($date);
            }
            $formatted = $date->locale('id')->isoFormat('D MMM YYYY, HH:mm');
            return $withWib ? $formatted . ' WIB' : $formatted;
        } catch (\Throwable $e) {
            return (string) $date;
        }
    }

    /**
     * Format a date into full formal Indonesian format (e.g. 18 Agustus 2026).
     */
    public static function formatFull($date, string $fallback = '-'): string
    {
        if (empty($date)) {
            return $fallback;
        }

        try {
            if (!$date instanceof Carbon) {
                $date = Carbon::parse($date);
            }
            return $date->locale('id')->isoFormat('D MMMM YYYY');
        } catch (\Throwable $e) {
            return (string) $date;
        }
    }

    /**
     * Format a date with Indonesian day name (e.g. Selasa, 18 Agustus 2026).
     */
    public static function formatDayDate($date, string $fallback = '-'): string
    {
        if (empty($date)) {
            return $fallback;
        }

        try {
            if (!$date instanceof Carbon) {
                $date = Carbon::parse($date);
            }
            return $date->locale('id')->isoFormat('dddd, D MMMM YYYY');
        } catch (\Throwable $e) {
            return (string) $date;
        }
    }

    /**
     * Format month and year in Indonesian (e.g. Agustus 2026).
     */
    public static function formatMonthYear($date, string $fallback = '-'): string
    {
        if (empty($date)) {
            return $fallback;
        }

        try {
            if (!$date instanceof Carbon) {
                $date = Carbon::parse($date);
            }
            return $date->locale('id')->isoFormat('MMMM YYYY');
        } catch (\Throwable $e) {
            return (string) $date;
        }
    }

    /**
     * Format relative human-readable diff in Indonesian (e.g. 2 jam yang lalu).
     */
    public static function diffForHumans($date, string $fallback = '-'): string
    {
        if (empty($date)) {
            return $fallback;
        }

        try {
            if (!$date instanceof Carbon) {
                $date = Carbon::parse($date);
            }
            return $date->locale('id')->diffForHumans();
        } catch (\Throwable $e) {
            return $fallback;
        }
    }
}
