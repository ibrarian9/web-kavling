<?php

namespace App\Enums;

enum UnitStatus: string
{
    case TERSEDIA = 'tersedia';
    case BOOKED = 'booked';
    case DISETUJUI = 'disetujui';
    case TERJUAL = 'terjual';
    case CONVERTED = 'converted';
    case MENUNGGU_PERSETUJUAN = 'menunggu_persetujuan';

    /**
     * Determine if a status represents a sold / booked / non-available unit.
     */
    public static function isSold(?string $status): bool
    {
        if (!$status) return false;
        return in_array($status, [
            self::BOOKED->value,
            self::DISETUJUI->value,
            self::TERJUAL->value,
            self::CONVERTED->value,
            'booking',
            'dibooking',
        ], true);
    }

    /**
     * Determine if a status represents an available unit.
     */
    public static function isAvailable(?string $status): bool
    {
        return $status === self::TERSEDIA->value;
    }

    /**
     * Determine if a status represents a booked unit.
     */
    public static function isBooked(?string $status): bool
    {
        if (!$status) return false;
        return in_array($status, [
            self::BOOKED->value,
            self::MENUNGGU_PERSETUJUAN->value,
            'booking',
            'dibooking',
        ], true);
    }

    /**
     * Get human readable label for status.
     */
    public static function label(?string $status): string
    {
        return match ($status) {
            self::TERSEDIA->value => 'Tersedia',
            self::BOOKED->value, 'booking', 'dibooking' => 'Booked',
            self::MENUNGGU_PERSETUJUAN->value => 'Menunggu Persetujuan',
            self::DISETUJUI->value => 'Disetujui',
            self::TERJUAL->value => 'Terjual',
            self::CONVERTED->value => 'Konversi Cash',
            default => ucfirst($status ?? 'Unknown'),
        };
    }

    /**
     * Get Tailwind CSS badge class for status.
     */
    public static function badgeClass(?string $status): string
    {
        return match ($status) {
            self::TERSEDIA->value => 'status-tersedia',
            self::BOOKED->value, 'booking', 'dibooking' => 'status-booked',
            self::MENUNGGU_PERSETUJUAN->value => 'status-menunggu',
            self::DISETUJUI->value => 'status-disetujui',
            self::TERJUAL->value => 'status-terjual',
            self::CONVERTED->value => 'status-converted',
            default => 'status-default',
        };
    }
}
