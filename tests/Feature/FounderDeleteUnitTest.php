<?php

use App\Models\Booking;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Livewire\Units\Index as UnitsIndex;
use App\Livewire\Units\Show as UnitShow;
use App\Livewire\Projects\Show as ProjectsShow;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
    Role::findOrCreate('finance', 'web');
    Role::findOrCreate('marketing', 'web');
});

test('only founder user can delete unit from units index and unit detail pages', function () {
    $founder = User::create([
        'name' => 'Founder Del Unit',
        'email' => 'founder_delunit@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $finance = User::create([
        'name' => 'Finance Del Unit',
        'email' => 'finance_delunit@example.com',
        'password' => bcrypt('password'),
        'role' => 'finance',
        'is_active' => true,
    ]);
    $finance->assignRole('finance');

    $project = Project::create([
        'name' => 'Proyek Hapus Unit',
        'location' => 'Malang',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    $unitToDelete = Unit::create([
        'project_id' => $project->id,
        'code' => 'DEL-01',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    Booking::create([
        'project_id' => $project->id,
        'unit_id' => $unitToDelete->id,
        'buyer_name' => 'Calon Pembeli',
        'buyer_phone' => '08123456789',
        'booking_type' => 'unit',
        'booking_amount' => 5000000,
        'dp_amount' => 0,
        'booking_date' => now()->toDateString(),
        'expiry_date' => now()->addDays(14)->toDateString(),
        'status' => 'active',
        'created_by' => $founder->id,
    ]);

    // 1. Finance attempts to delete unit -> Should fail
    Livewire::actingAs($finance)
        ->test(UnitsIndex::class)
        ->call('deleteUnit', $unitToDelete->id);

    expect(Unit::find($unitToDelete->id))->not->toBeNull();

    // 2. Founder deletes unit from UnitsIndex -> Should succeed
    Livewire::actingAs($founder)
        ->test(UnitsIndex::class)
        ->call('deleteUnit', $unitToDelete->id)
        ->assertHasNoErrors();

    expect(Unit::find($unitToDelete->id))->toBeNull();
    expect(Booking::where('unit_id', $unitToDelete->id)->exists())->toBeFalse();

    // 3. Create another unit and delete from UnitShow page
    $unitShowTest = Unit::create([
        'project_id' => $project->id,
        'code' => 'DEL-02',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    Livewire::actingAs($founder)
        ->test(UnitShow::class, ['id' => $unitShowTest->id])
        ->call('deleteUnit')
        ->assertRedirect(route('units.index'));

    expect(Unit::find($unitShowTest->id))->toBeNull();

    // 4. Create third unit and delete from ProjectsShow page
    $unitProjectTest = Unit::create([
        'project_id' => $project->id,
        'code' => 'DEL-03',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    Livewire::actingAs($founder)
        ->test(ProjectsShow::class, ['id' => $project->id])
        ->call('deleteUnit', $unitProjectTest->id)
        ->assertHasNoErrors();

    expect(Unit::find($unitProjectTest->id))->toBeNull();
});
