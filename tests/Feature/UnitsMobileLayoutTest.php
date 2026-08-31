<?php

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
        'name' => 'Kavling Harmoni',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 120000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $this->unit1 = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'A-01',
        'status' => 'tersedia',
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'hpp' => 150000000,
        'created_by' => $this->founder->id,
    ]);

    $this->unit2 = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'B-02',
        'status' => 'booked',
        'category' => 'kavling',
        'land_length' => 12,
        'land_width' => 10,
        'land_area' => 120,
        'hpp' => 180000000,
        'created_by' => $this->founder->id,
    ]);
});

test('units index renders responsive header, view switcher, and clean filter grid', function () {
    $this->actingAs($this->founder);

    $test = Livewire::test(\App\Livewire\Units\Index::class);
    $html = $test->html();

    // Verify view switcher and mobile responsive classes
    expect($html)->toContain('Data Unit Kavling, Rumah & Infrastruktur')
        ->toContain('Site Plan Visual')
        ->toContain('grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5')
        ->toContain('Semua Status Unit')
        ->toContain('Semua Kategori')
        ->toContain('Semua Proyek Properti');

    // Test filter active triggers reset button at the bottom of filters
    $test->set('status_filter', 'tersedia');
    $htmlWithFilter = $test->html();
    expect($htmlWithFilter)->toContain('Filter aktif diterapkan')
        ->toContain('Reset Filter');
});

test('units index KPI cards show clear labels and bold counts without awkward truncation', function () {
    $this->actingAs($this->founder);

    $test = Livewire::test(\App\Livewire\Units\Index::class);
    $html = $test->html();

    // Verify 4 KPI cards
    expect($html)->toContain('Total Stok Unit')
        ->toContain('Unit Tersedia')
        ->toContain('Booked & Pending')
        ->toContain('Unit Terjual / ACC')
        ->toContain('grid grid-cols-2 md:grid-cols-4 gap-2.5 sm:gap-4');
});

test('units index renders styled unit cards with distinct borders', function () {
    $this->actingAs($this->founder);

    $test = Livewire::test(\App\Livewire\Units\Index::class);
    $html = $test->html();

    // Verify unit cards styling
    expect($html)->toContain('border-2 border-slate-200/90 hover:border-emerald-300 rounded-3xl')
        ->toContain('A-01')
        ->toContain('B-02');
});

test('units index includes top sticky loading progress bar, loading feedback banner, and setViewMode method works', function () {
    $this->actingAs($this->founder);

    $test = Livewire::test(\App\Livewire\Units\Index::class);
    $html = $test->html();

    // Verify top loading progress bar and inline loading feedback
    expect($html)->toContain('animate-livewire-progress')
        ->toContain('Memuat tampilan data unit')
        ->toContain('wire:target="setViewMode');

    // Test switching view mode with setViewMode
    $test->call('setViewMode', 'siteplan')
        ->assertSet('viewMode', 'siteplan');

    $test->call('setViewMode', 'table')
        ->assertSet('viewMode', 'table');
});

test('units index displays Ada Biaya marker and expense summary when unit has recorded expenses', function () {
    $this->actingAs($this->founder);

    $worker = \App\Models\Worker::create([
        'name' => 'Tukang Batu',
        'phone' => '08123456789',
        'type' => 'tukang',
        'status' => 'active',
        'created_by' => $this->founder->id,
    ]);

    \App\Models\WeeklyMaterialPurchase::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit1->id,
        'worker_id' => $worker->id,
        'pengawas_id' => $this->founder->id,
        'purchase_date' => now()->toDateString(),
        'item_name' => 'Batu Bata 1000 pcs',
        'quantity' => 1000,
        'unit_measure' => 'pcs',
        'unit_price' => 1500,
        'total_price' => 1500000,
        'payment_status' => 'lunas',
    ]);

    $test = Livewire::test(\App\Livewire\Units\Index::class);
    $html = $test->html();

    expect($html)->toContain('Ada Biaya')
        ->toContain('Biaya di Detail:')
        ->toContain('Rp 1.500.000');
});
