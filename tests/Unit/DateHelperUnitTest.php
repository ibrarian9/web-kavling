<?php

use App\Support\DateHelper;
use Carbon\Carbon;

test('DateHelper format formats dates correctly in Indonesian', function () {
    expect(DateHelper::format('2026-08-18'))->toBe('18 Agt 2026');
    expect(DateHelper::format('2026-05-01'))->toBe('1 Mei 2026');
    expect(DateHelper::format(null))->toBe('-');
    expect(DateHelper::format(''))->toBe('-');
    expect(DateHelper::format(null, 'N/A'))->toBe('N/A');
    expect(format_id_date('2026-08-18'))->toBe('18 Agt 2026');
    expect(format_id_date(Carbon::parse('2026-12-25')))->toBe('25 Des 2026');
});

test('DateHelper formatDateTime formats timestamps with and without WIB', function () {
    $date = '2026-08-18 14:30:00';
    expect(DateHelper::formatDateTime($date))->toBe('18 Agt 2026, 14:30 WIB');
    expect(DateHelper::formatDateTime($date, false))->toBe('18 Agt 2026, 14:30');
    expect(DateHelper::formatDateTime(null))->toBe('-');
    expect(format_id_datetime($date))->toBe('18 Agt 2026, 14:30 WIB');
    expect(format_id_datetime($date, false))->toBe('18 Agt 2026, 14:30');
});

test('DateHelper formatFull formats full formal date in Indonesian', function () {
    expect(DateHelper::formatFull('2026-08-18'))->toBe('18 Agustus 2026');
    expect(DateHelper::formatFull('2026-01-05'))->toBe('5 Januari 2026');
    expect(DateHelper::formatFull(null))->toBe('-');
    expect(format_id_full_date('2026-08-18'))->toBe('18 Agustus 2026');
});

test('DateHelper formatDayDate formats date with full Indonesian day name', function () {
    // 2026-08-18 is Tuesday (Selasa)
    expect(DateHelper::formatDayDate('2026-08-18'))->toBe('Selasa, 18 Agustus 2026');
    expect(DateHelper::formatDayDate(null))->toBe('-');
    expect(format_id_day_date('2026-08-18'))->toBe('Selasa, 18 Agustus 2026');
});

test('DateHelper formatMonthYear formats month and year in Indonesian', function () {
    expect(DateHelper::formatMonthYear('2026-08-01'))->toBe('Agustus 2026');
    expect(DateHelper::formatMonthYear('2026-08'))->toBe('Agustus 2026');
    expect(DateHelper::formatMonthYear(null))->toBe('-');
    expect(format_id_month_year('2026-08-01'))->toBe('Agustus 2026');
});

test('DateHelper diffForHumans produces human readable Indonesian relative time', function () {
    $past = Carbon::now()->subHours(2);
    expect(DateHelper::diffForHumans($past))->toContain('yang lalu');
    expect(format_id_diff($past))->toContain('yang lalu');
    expect(DateHelper::diffForHumans(null))->toBe('-');
});
