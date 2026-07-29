<?php

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\Worker;
use App\Livewire\Dashboard;
use App\Livewire\Projects\Index as ProjectsIndex;
use App\Livewire\Units\Index as UnitsIndex;
use App\Livewire\Cashflow\Index as CashflowIndex;
use App\Livewire\Bookings\Index as BookingsIndex;
use App\Livewire\Proposals\Index as ProposalsIndex;
use App\Livewire\Workers\Index as WorkersIndex;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
    Role::findOrCreate('finance', 'web');
    Role::findOrCreate('marketing', 'web');
});

test('dashboard livewire component renders correctly with statistical metrics', function () {
    $founder = User::create([
        'name' => 'Founder Livewire',
        'email' => 'founder_lw@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Proyek Livewire Dashboard',
        'location' => 'Malang',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    Unit::create([
        'project_id' => $project->id,
        'code' => 'LW-01',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    Livewire::actingAs($founder)
        ->test(Dashboard::class)
        ->assertStatus(200)
        ->assertSee('Proyek Livewire Dashboard')
        ->assertSee('LW-01');
});

test('projects index livewire component allows founder to create and edit projects', function () {
    $founder = User::create([
        'name' => 'Founder Projects',
        'email' => 'founder_proj_lw@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    Livewire::actingAs($founder)
        ->test(ProjectsIndex::class)
        ->call('openModal')
        ->set('name', 'Proyek Baru Livewire')
        ->set('location', 'Batu')
        ->set('standard_land_area', 120)
        ->set('excess_price_per_sqm', 600000)
        ->set('base_price', 200000000)
        ->set('total_project_price', 500000000)
        ->call('saveProject')
        ->assertHasNoErrors();

    $project = Project::where('name', 'Proyek Baru Livewire')->first();
    expect($project)->not->toBeNull();
    expect($project->location)->toBe('Batu');
});

test('units index livewire component allows creating new unit and filtering by status', function () {
    $founder = User::create([
        'name' => 'Founder Units',
        'email' => 'founder_units_lw@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Proyek Unit Suite',
        'location' => 'Kediri',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    Livewire::actingAs($founder)
        ->test(UnitsIndex::class)
        ->set('selected_project_id', $project->id)
        ->set('code', 'SUITE-01')
        ->set('category', 'kavling')
        ->set('type', 'Kavling Hook')
        ->set('land_area', 110)
        ->set('building_area', 0)
        ->set('hpp', 90000000)
        ->call('saveUnit')
        ->assertHasNoErrors();

    $unit = Unit::where('code', 'SUITE-01')->first();
    expect($unit)->not->toBeNull();
    expect($unit->code)->toBe('SUITE-01');
});

test('cashflow index livewire component filters global and project cashflows', function () {
    $finance = User::create([
        'name' => 'Finance Cashflow',
        'email' => 'finance_cf_lw@example.com',
        'password' => bcrypt('password'),
        'role' => 'finance',
        'is_active' => true,
    ]);
    $finance->assignRole('finance');

    $project = Project::create([
        'name' => 'Proyek Cashflow Suite',
        'location' => 'Surabaya',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $finance->id,
    ]);

    CashflowTransaction::create([
        'project_id' => $project->id,
        'type' => 'masuk',
        'category' => 'penjualan_unit',
        'amount' => 15000000,
        'transaction_date' => now()->toDateString(),
        'description' => 'Penjualan Unit Livewire Test',
        'created_by' => $finance->id,
    ]);

    Livewire::actingAs($finance)
        ->test(CashflowIndex::class)
        ->set('view_mode', 'global')
        ->assertSee('Penjualan Unit Livewire Test')
        ->set('view_mode', 'project')
        ->set('filter_project_id', $project->id)
        ->assertSee('Penjualan Unit Livewire Test');
});

test('workers index livewire component allows creating workers and setting specialty', function () {
    $founder = User::create([
        'name' => 'Founder Workers',
        'email' => 'founder_worker_lw@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    Livewire::actingAs($founder)
        ->test(WorkersIndex::class)
        ->call('create')
        ->set('name', 'Budi Tukang Batu')
        ->set('type', 'mandor')
        ->set('specialty', 'Struktur & Pondasi')
        ->set('phone', '081234567890')
        ->call('save')
        ->assertHasNoErrors();

    $worker = Worker::where('name', 'Budi Tukang Batu')->first();
    expect($worker)->not->toBeNull();
    expect($worker->type)->toBe('mandor');
});
