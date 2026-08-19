<?php

use App\Livewire\ActivityLogs\Index as ActivityLogsIndex;
use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Livewire;

beforeEach(function () {
    $this->founder = User::factory()->create(['role' => 'founder']);
});

test('activity logs filters by date period correctly', function () {
    $this->actingAs($this->founder);

    $logToday = ActivityLog::create([
        'user_id' => $this->founder->id,
        'user_name' => $this->founder->name,
        'user_role' => 'founder',
        'action' => 'PROPOSAL_APPROVED',
        'description' => 'Aktivitas Hari Ini Log Test',
        'ip_address' => '127.0.0.1',
    ]);
    $logToday->created_at = Carbon::today();
    $logToday->saveQuietly();

    $logPast = ActivityLog::create([
        'user_id' => $this->founder->id,
        'user_name' => $this->founder->name,
        'user_role' => 'founder',
        'action' => 'PROPOSAL_REJECTED',
        'description' => 'Aktivitas Masa Lalu Log Test',
        'ip_address' => '127.0.0.1',
    ]);
    $logPast->created_at = Carbon::now()->subMonths(2);
    $logPast->saveQuietly();

    Livewire::test(ActivityLogsIndex::class)
        ->assertSee('Aktivitas Hari Ini Log Test')
        ->assertSee('Aktivitas Masa Lalu Log Test')
        ->set('datePeriod', 'today')
        ->assertSee('Aktivitas Hari Ini Log Test')
        ->assertDontSee('Aktivitas Masa Lalu Log Test')
        ->set('datePeriod', 'all')
        ->assertSee('Aktivitas Masa Lalu Log Test');
});
