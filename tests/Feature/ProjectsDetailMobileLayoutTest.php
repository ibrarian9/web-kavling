<?php

use App\Models\ExternalProject;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder', 'guard_name' => 'web']);
    $this->founder = User::create([
        'name' => 'Marwansyah',
        'email' => 'marwan@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $this->founder->assignRole('founder');

    $this->project = Project::create([
        'name' => 'Barokah Village Kartika Sari',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 120000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $this->unit = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'BLOK B KIOS K 1',
        'category' => 'kavling',
        'status' => 'tersedia',
        'land_width' => 8,
        'land_length' => 10,
        'land_area' => 80,
        'standard_land_area' => 80,
        'excess_land_area' => 0,
        'base_price' => 100000000,
        'excess_land_price' => 0,
        'final_selling_price' => 100000000,
        'hpp' => 60000000,
        'created_by' => $this->founder->id,
    ]);
});

test('project detail page renders 4 distinct tab backgrounds and subtitles in 2 lines', function () {
    $this->actingAs($this->founder);

    $test = Livewire::test(\App\Livewire\Projects\Show::class, ['id' => $this->project->id]);

    $html = $test->html();

    // Verify Tab titles and subtitles
    expect($html)->toContain('Penjualan Unit')
        ->toContain('Site Plan Visual')
        ->toContain('Denah Interaktif')
        ->toContain('Pembayaran Lahan')
        ->toContain('Setoran ke Pemilik')
        ->toContain('Arus Kas Proyek')
        ->toContain('Buku Kas Masuk/Keluar');

    // Verify distinct colors exist in rendered html
    expect($html)->toContain('bg-emerald-600')
        ->toContain('bg-sky-50')
        ->toContain('bg-amber-50')
        ->toContain('bg-indigo-50');
});

test('mobile unit card has 2-row layout with full-width unit code and action buttons', function () {
    $this->actingAs($this->founder);

    $test = Livewire::test(\App\Livewire\Projects\Show::class, ['id' => $this->project->id]);

    $html = $test->html();

    // Verify Unit Code is displayed with non-wrapping break-words in its own container
    expect($html)->toContain('BLOK B KIOS K 1');
    expect($html)->toContain('Pembelian Cash');
    expect($html)->toContain('Detail');
    // Verify 2-row mobile structure markers
    expect($html)->toContain('border-t border-slate-100');
});

test('all PDF buttons use uniform red rose styling', function () {
    $this->actingAs($this->founder);

    $test = Livewire::test(\App\Livewire\Projects\Show::class, ['id' => $this->project->id]);

    $html = $test->html();

    // Verify PDF button in tab-units uses rose red styling
    expect($html)->toContain('Lihat PDF Rekap');
    expect($html)->toContain('bg-rose-50 text-rose-700');
});

test('external projects index has 4-card grid on tablet and whitespace-nowrap for rupiah', function () {
    $this->actingAs($this->founder);

    ExternalProject::create([
        'name' => 'Proyek Renovasi Ruko',
        'client_name' => 'Bpk Ahmad',
        'contract_value' => 2000000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $test = Livewire::test(\App\Livewire\ExternalProjects\Index::class);
    $html = $test->html();

    expect($html)->toContain('grid-cols-2 xl:grid-cols-4')
        ->toContain('whitespace-nowrap')
        ->toContain('Proyek Renovasi Ruko');
});

test('project detail page includes top sticky loading bar, inline loading banner, and setTab method works', function () {
    $this->actingAs($this->founder);

    $test = Livewire::test(\App\Livewire\Projects\Show::class, ['id' => $this->project->id]);

    $html = $test->html();

    // Verify top loading progress bar and inline loading feedback
    expect($html)->toContain('animate-livewire-progress')
        ->toContain('Memuat data menu proyek')
        ->toContain('wire:target="setTab, activeTab');

    // Test switching tabs with setTab
    $test->call('setTab', 'siteplan')
        ->assertSet('activeTab', 'siteplan')
        ->assertSee('Site Plan Interaktif');

    $test->call('setTab', 'payments')
        ->assertSet('activeTab', 'payments')
        ->assertSee('Riwayat Pembayaran Lahan Proyek ke Penjual');

    $test->call('setTab', 'cashflow')
        ->assertSet('activeTab', 'cashflow')
        ->assertSee('Rincian Transaksi Mutasi Kas Proyek');
});

test('mobile units list renders distinct card boundaries and separate desktop table', function () {
    $this->actingAs($this->founder);

    $test = Livewire::test(\App\Livewire\Projects\Show::class, ['id' => $this->project->id]);

    $html = $test->html();

    // Verify mobile cards have clear border boundaries and spacing
    expect($html)->toContain('block md:hidden space-y-3.5')
        ->toContain('border-2 border-slate-200/90 hover:border-emerald-300 rounded-2xl p-4')
        ->toContain('hidden md:block');
});

test('siteplan visual cards have distinct colorful non-white backgrounds', function () {
    $this->actingAs($this->founder);

    $test = Livewire::test(\App\Livewire\Projects\Show::class, ['id' => $this->project->id]);
    $test->call('setTab', 'siteplan');

    $html = $test->html();

    // Verify colorful card backgrounds and badges for siteplan
    expect($html)->toContain('bg-emerald-100/80')
        ->toContain('border-2 border-emerald-300')
        ->toContain('bg-emerald-600 text-white')
        ->toContain('bg-slate-100/90 rounded-3xl');
});

