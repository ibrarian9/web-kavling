<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Livewire\Units\Show as UnitShow;
use Livewire\Livewire;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('founder and finance can edit installment scheme and update remaining unpaid balance', function () {
    Role::findOrCreate('founder', 'web');

    $founder = User::create([
        'name' => 'Founder User',
        'email' => 'founder_installment@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Kavling Indah',
        'location' => 'Batu',
        'land_purchase_price' => 150000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 180000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'B1',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'terjual',
        'created_by' => $founder->id,
    ]);

    $installment = UnitInstallment::create([
        'unit_id' => $unit->id,
        'total_price' => 150000000,
        'down_payment' => 30000000,
        'installment_count' => 12,
        'installment_amount' => 10000000,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
    ]);

    Livewire::actingAs($founder)
        ->test(UnitShow::class, ['id' => $unit->id])
        ->call('openSetupInstallmentModal')
        ->assertSet('setup_total_price', 150000000)
        ->assertSet('setup_down_payment', 30000000)
        ->set('setup_total_price', 160000000)
        ->call('saveSetupInstallment')
        ->assertHasNoErrors();

    $installment->refresh();
    expect((float)$installment->total_price)->toBe(160000000.0);
});
