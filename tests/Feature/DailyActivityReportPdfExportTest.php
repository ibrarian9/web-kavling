<?php

use App\Models\DailyActivityReport;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
    Role::findOrCreate('admin', 'web');
    Role::findOrCreate('marketing', 'web');

    $this->founder = User::create([
        'name' => 'Founder User',
        'email' => 'founder@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'phone_number' => '081234567890',
        'is_active' => true,
    ]);
    $this->founder->assignRole('founder');

    $this->marketing = User::create([
        'name' => 'Marketing Sales',
        'email' => 'marketing@test.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'phone_number' => '081987654321',
        'is_active' => true,
    ]);
    $this->marketing->assignRole('marketing');

    $this->project = Project::create([
        'name' => 'Kavling Harmoni Test',
        'code' => 'KHT',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 100000000,
        'total_units' => 10,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    DailyActivityReport::create([
        'user_id' => $this->marketing->id,
        'project_id' => $this->project->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Bpk. Ahmad Fauzi',
        'client_phone' => '081299998888',
        'lead_source' => 'facebook_ads',
        'interaction_type' => 'chat_wa',
        'lead_stage' => 'hot_deal',
        'payment_type' => 'cash_bertahap',
        'deal_amount' => 150000000,
        'notes' => 'Minat unit kavling sudut',
        'follow_up_date' => now()->addDays(2)->toDateString(),
    ]);
});

test('authenticated user can stream daily activity report PDF by period day', function () {
    $response = $this->actingAs($this->founder)
        ->get(route('daily-activity-reports.export-pdf', [
            'period' => 'day',
            'date' => now()->toDateString(),
        ]));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('authenticated user can stream daily activity report PDF by period month', function () {
    $response = $this->actingAs($this->founder)
        ->get(route('daily-activity-reports.export-pdf', [
            'period' => 'month',
            'month' => now()->format('Y-m'),
        ]));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('marketing user can stream their own daily activity report PDF', function () {
    $response = $this->actingAs($this->marketing)
        ->get(route('daily-activity-reports.export-pdf', [
            'period' => 'all',
        ]));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});
