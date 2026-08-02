<?php

use Carbon\Carbon;

test('application uses Indonesian timezone Asia/Jakarta', function () {
    expect(config('app.timezone'))->toBe('Asia/Jakarta');
    expect(date_default_timezone_get())->toBe('Asia/Jakarta');
    expect(now()->getTimezone()->getName())->toBe('Asia/Jakarta');
});

test('carbon formats dates in Indonesian locale', function () {
    expect(config('app.locale'))->toBe('id');
    $date = Carbon::parse('2026-08-02 14:30:00', 'Asia/Jakarta');
    expect($date->locale('id')->isoFormat('dddd, D MMMM YYYY'))->toContain('Minggu');
});
