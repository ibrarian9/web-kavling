<?php

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogger;

test('activity log model can be created with correct attributes', function () {
    $user = User::factory()->create(['role' => 'founder']);

    $log = ActivityLog::create([
        'user_id' => $user->id,
        'user_name' => $user->name,
        'user_role' => 'founder',
        'action' => 'UNIT_CREATED',
        'description' => 'Founder membuat unit baru A1',
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Mozilla/5.0 Test Agent',
    ]);

    expect($log)->not->toBeNull();
    expect($log->action)->toBe('UNIT_CREATED');
    expect($log->description)->toBe('Founder membuat unit baru A1');
    expect($log->ip_address)->toBe('127.0.0.1');
});

test('activity log has correct relationship: user', function () {
    $user = User::factory()->create(['role' => 'finance']);

    $log = ActivityLog::create([
        'user_id' => $user->id,
        'user_name' => $user->name,
        'user_role' => 'finance',
        'action' => 'PAYMENT_RECORDED',
        'description' => 'Finance mencatat pembayaran',
        'ip_address' => '192.168.1.1',
        'user_agent' => 'Chrome',
    ]);

    expect($log->user->id)->toBe($user->id);
});

test('ActivityLogger service creates log entry with correct user fields', function () {
    $user = User::factory()->create(['role' => 'founder', 'name' => 'Founder Logger Test']);

    $this->actingAs($user);

    $log = ActivityLogger::log('TEST_ACTION', 'Ini adalah deskripsi tes logger');

    expect($log)->toBeInstanceOf(ActivityLog::class);
    expect($log->action)->toBe('TEST_ACTION');
    expect($log->description)->toBe('Ini adalah deskripsi tes logger');
    expect($log->user_id)->toBe($user->id);
    expect($log->user_name)->toBe('Founder Logger Test');
    expect($log->user_role)->toBe('founder');
});

test('ActivityLogger service handles null user gracefully', function () {
    // No user authenticated
    $log = ActivityLogger::log('SYSTEM_EVENT', 'Event dari sistem tanpa user login');

    expect($log)->toBeInstanceOf(ActivityLog::class);
    expect($log->user_id)->toBeNull();
    expect($log->user_name)->toBe('Guest/System');
    expect($log->user_role)->toBe('System');
});
