<?php

use App\Models\DailyActivityReport;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->founder = User::factory()->create([
        'role' => 'founder',
        'is_active' => true,
    ]);

    $this->marketing = User::factory()->create([
        'role' => 'marketing',
        'is_active' => true,
    ]);

    $this->project = Project::create([
        'name' => 'Proyek Daily Activity Test',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $this->unit = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'DAR-01',
        'type' => 'kavling',
        'category' => 'kavling',
        'land_width' => 10,
        'land_length' => 10,
        'land_area' => 100,
        'hpp' => 50000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);
});

test('marketing can record daily activity report', function () {
    $this->actingAs($this->marketing);

    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('openCreateModal')
        ->set('client_name', 'Bpk. Budi Pratama')
        ->set('client_phone', '081234567890')
        ->set('lead_source', 'facebook_ads')
        ->set('interaction_type', 'survey_lokasi')
        ->set('lead_stage', 'hot_deal')
        ->set('payment_type', 'dp_booking')
        ->set('deal_amount', 10000000)
        ->set('project_id', $this->project->id)
        ->set('unit_id', $this->unit->id)
        ->set('notes', 'Janji minggu depan transfer DP')
        ->call('saveReport')
        ->assertHasNoErrors();

    expect(DailyActivityReport::where('client_name', 'Bpk. Budi Pratama')->exists())->toBeTrue();
});

test('founder can record edit and delete daily activity report for any marketing user', function () {
    $this->actingAs($this->founder);

    // 1. Founder records report for marketing staff
    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('openCreateModal')
        ->set('user_id', $this->marketing->id)
        ->set('client_name', 'Ibu Ratna Dewi')
        ->set('client_phone', '081987654321')
        ->set('lead_source', 'tiktok')
        ->set('interaction_type', 'presentasi')
        ->set('lead_stage', 'warm')
        ->call('saveReport')
        ->assertHasNoErrors();

    $report = DailyActivityReport::where('client_name', 'Ibu Ratna Dewi')->first();
    expect($report)->not->toBeNull();
    expect($report->user_id)->toBe($this->marketing->id);

    // 2. Founder edits report
    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('editReport', $report->id)
        ->set('client_name', 'Ibu Ratna Dewi S.E.')
        ->set('lead_stage', 'hot_deal')
        ->call('saveReport')
        ->assertHasNoErrors();

    expect($report->fresh()->client_name)->toBe('Ibu Ratna Dewi S.E.');
    expect($report->fresh()->lead_stage)->toBe('hot_deal');

    // 3. Founder deletes report
    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('deleteReport', $report->id)
        ->assertHasNoErrors();

    expect(DailyActivityReport::find($report->id))->toBeNull();
});

test('filtering daily activity reports by lead stage and project', function () {
    DailyActivityReport::create([
        'user_id' => $this->marketing->id,
        'project_id' => $this->project->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Klien Facebook',
        'client_phone' => '081111111111',
        'lead_source' => 'facebook_ads',
        'interaction_type' => 'chat_wa',
        'lead_stage' => 'hot_deal',
    ]);

    DailyActivityReport::create([
        'user_id' => $this->marketing->id,
        'project_id' => null,
        'report_date' => now()->toDateString(),
        'client_name' => 'Klien TikTok',
        'client_phone' => '082222222222',
        'lead_source' => 'tiktok',
        'interaction_type' => 'chat_wa',
        'lead_stage' => 'cold',
    ]);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->set('filter_lead_stage', 'hot_deal')
        ->assertSee('Klien Facebook')
        ->assertDontSee('Klien TikTok');
});
