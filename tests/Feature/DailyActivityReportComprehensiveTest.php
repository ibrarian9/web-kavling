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
        'name' => 'Founder Utama',
        'role' => 'founder',
        'is_active' => true,
    ]);

    $this->marketing1 = User::factory()->create([
        'name' => 'Sales Budi',
        'role' => 'marketing',
        'is_active' => true,
    ]);

    $this->marketing2 = User::factory()->create([
        'name' => 'Sales Ani',
        'role' => 'marketing',
        'is_active' => true,
    ]);

    $this->project = Project::create([
        'name' => 'Proyek Grand Kavling',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 100000000,
        'excess_price_per_sqm' => 500000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $this->unit = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'A-01',
        'type' => 'kavling',
        'category' => 'kavling',
        'land_width' => 10,
        'land_length' => 10,
        'land_area' => 100,
        'hpp' => 60000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);
});

/* ========================================================================= */
/* 1. BASIC SCENARIOS (Skenario Normal / Standar)                            */
/* ========================================================================= */

test('basic: marketing can view daily activity reports index and stats cards', function () {
    DailyActivityReport::create([
        'user_id' => $this->marketing1->id,
        'project_id' => $this->project->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Bpk. Klien Pertama',
        'client_phone' => '081234567890',
        'lead_source' => 'facebook_ads',
        'interaction_type' => 'chat_wa',
        'lead_stage' => 'hot_deal',
        'payment_type' => 'dp_booking',
        'deal_amount' => 15000000,
    ]);

    $this->actingAs($this->marketing1);

    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->assertSee('Daily Activity Report')
        ->assertSee('Bpk. Klien Pertama')
        ->assertSee('15.000.000');
});

test('basic: marketing can create a new daily activity report', function () {
    $this->actingAs($this->marketing1);

    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('openCreateModal')
        ->set('client_name', 'Ibu Siska Permata')
        ->set('client_phone', '081987654321')
        ->set('report_date', now()->toDateString())
        ->set('lead_source', 'instagram')
        ->set('interaction_type', 'survey_lokasi')
        ->set('lead_stage', 'warm')
        ->set('payment_type', 'tanpa_dp')
        ->set('deal_amount', 0)
        ->set('notes', 'Sudah survey lokasi bersama keluarga.')
        ->call('saveReport')
        ->assertHasNoErrors();

    expect(DailyActivityReport::where('client_name', 'Ibu Siska Permata')->exists())->toBeTrue();
});

test('basic: user can open detail modal and view full prospect information', function () {
    $report = DailyActivityReport::create([
        'user_id' => $this->marketing1->id,
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Bpk. Detail Test',
        'client_phone' => '081211112222',
        'lead_source' => 'tiktok',
        'interaction_type', 'presentasi',
        'lead_stage' => 'booking',
        'payment_type' => 'dp_booking',
        'deal_amount' => 10000000,
        'notes' => 'Catatan lengkap prospek detail.',
    ]);

    $this->actingAs($this->marketing1);

    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('showReportDetail', $report->id)
        ->assertSet('showDetailModal', true)
        ->assertSee('Detail Aktivitas Prospek Harian')
        ->assertSee('Bpk. Detail Test')
        ->assertSee('Catatan lengkap prospek detail.');
});

test('basic: reset filters button clears search and dropdown filters', function () {
    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->set('search', 'Klien Khusus')
        ->set('filter_lead_stage', 'hot_deal')
        ->set('filter_lead_source', 'tiktok')
        ->call('resetFilters')
        ->assertSet('search', '')
        ->assertSet('filter_lead_stage', '')
        ->assertSet('filter_lead_source', '');
});

/* ========================================================================= */
/* 2. WORST-CASE & EDGE-CASE SCENARIOS (Skenario Terburuk & Batas)           */
/* ========================================================================= */

test('worst-case: submitting empty form triggers validation errors', function () {
    $this->actingAs($this->marketing1);

    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('openCreateModal')
        ->set('client_name', '')
        ->set('client_phone', '')
        ->set('report_date', '')
        ->call('saveReport')
        ->assertHasErrors(['client_name', 'client_phone', 'report_date']);
});

test('worst-case: negative deal amount is rejected by validation', function () {
    $this->actingAs($this->marketing1);

    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('openCreateModal')
        ->set('client_name', 'Test Negative Amount')
        ->set('client_phone', '081234567890')
        ->set('report_date', now()->toDateString())
        ->set('deal_amount', -500000)
        ->call('saveReport')
        ->assertHasErrors(['deal_amount']);
});

test('worst-case: marketing cannot edit another marketing user report', function () {
    $reportMarketing2 = DailyActivityReport::create([
        'user_id' => $this->marketing2->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Klien kepunyaan Ani',
        'client_phone' => '082222222222',
        'lead_source' => 'whatsapp',
        'interaction_type' => 'chat_wa',
        'lead_stage' => 'cold',
    ]);

    // Marketing 1 tries to edit Marketing 2's report
    $this->actingAs($this->marketing1);

    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('editReport', $reportMarketing2->id)
        ->assertDispatched('notify');

    // Report should remain unchanged
    expect($reportMarketing2->fresh()->client_name)->toBe('Klien kepunyaan Ani');
});

test('worst-case: marketing cannot delete another marketing user report', function () {
    $reportMarketing2 = DailyActivityReport::create([
        'user_id' => $this->marketing2->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Klien Rahasia Ani',
        'client_phone' => '083333333333',
        'lead_source' => 'whatsapp',
        'interaction_type' => 'chat_wa',
        'lead_stage' => 'warm',
    ]);

    // Marketing 1 tries to delete Marketing 2's report
    $this->actingAs($this->marketing1);

    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('deleteReport', $reportMarketing2->id)
        ->assertDispatched('notify');

    // Report should still exist in database
    expect(DailyActivityReport::find($reportMarketing2->id))->not->toBeNull();
});

test('worst-case: deleting associated project or unit sets project_id and unit_id to null', function () {
    $report = DailyActivityReport::create([
        'user_id' => $this->marketing1->id,
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Klien Proyek Dihapus',
        'client_phone' => '084444444444',
        'lead_source' => 'canvassing',
        'interaction_type' => 'survey_lokasi',
        'lead_stage' => 'hot_deal',
    ]);

    // Delete unit and project
    $this->unit->delete();
    $this->project->delete();

    // Report should survive with null foreign keys
    $freshReport = $report->fresh();
    expect($freshReport)->not->toBeNull();
    expect($freshReport->project_id)->toBeNull();
    expect($freshReport->unit_id)->toBeNull();
});

test('worst-case: XSS and script injection in client name or notes is escaped safely', function () {
    $xssString = "<script>alert('HACKED')</script> & ' OR '1'='1";

    $this->actingAs($this->marketing1);

    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('openCreateModal')
        ->set('client_name', $xssString)
        ->set('client_phone', '085555555555')
        ->set('report_date', now()->toDateString())
        ->set('notes', $xssString)
        ->call('saveReport')
        ->assertHasNoErrors();

    $report = DailyActivityReport::where('client_phone', '085555555555')->first();
    expect($report->client_name)->toBe($xssString);

    // View component should render without breaking or executing scripts
    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->assertSee('alert(&#039;HACKED&#039;)', false);
});

test('worst-case: guest user is denied access to daily activity reports', function () {
    $this->get('/daily-activity-reports')
        ->assertRedirect('/login');
});

test('audit log: all create edit and delete actions are recorded in activity_logs table', function () {
    $this->actingAs($this->marketing1);

    // 1. Create Action
    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('openCreateModal')
        ->set('client_name', 'Klien Audit Test')
        ->set('client_phone', '089999888777')
        ->set('report_date', now()->toDateString())
        ->set('lead_source', 'whatsapp')
        ->set('interaction_type', 'chat_wa')
        ->set('lead_stage', 'warm')
        ->call('saveReport')
        ->assertHasNoErrors();

    expect(\App\Models\ActivityLog::where('action', 'DAILY_REPORT_CREATED')->exists())->toBeTrue();

    $report = DailyActivityReport::where('client_name', 'Klien Audit Test')->first();

    // 2. Edit Action
    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('editReport', $report->id)
        ->set('client_name', 'Klien Audit Test Updated')
        ->call('saveReport')
        ->assertHasNoErrors();

    expect(\App\Models\ActivityLog::where('action', 'DAILY_REPORT_UPDATED')->exists())->toBeTrue();

    // 3. Delete Action
    Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
        ->call('deleteReport', $report->id)
        ->assertHasNoErrors();

    expect(\App\Models\ActivityLog::where('action', 'DAILY_REPORT_DELETED')->exists())->toBeTrue();
});
