<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\PriceProposal;
use App\Models\OfficialDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
    Role::findOrCreate('marketing', 'web');
    Role::findOrCreate('finance', 'web');
});

test('founder can use price per m2 mode in direct SPP calculation', function () {
    $founder = User::create([
        'name' => 'Founder Chief',
        'email' => 'founder_chief@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Grand Atlantik',
        'location' => 'Pekanbaru',
        'base_price' => 100000000,
        'standard_land_area' => 100,
        'standard_building_area' => 0,
        'excess_price_per_sqm' => 1500000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'GA-01',
        'type' => 'kavling',
        'status' => 'tersedia',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'price' => 150000000,
        'final_selling_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    // Test price per m2 mode
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Units\Show::class, ['id' => $unit->id])
        ->call('openDirectSppModal')
        ->assertSet('showDirectSppModal', true)
        ->set('spp_buyer_name', 'Bpk. Budi Santoso')
        ->set('spp_buyer_nik', '1471012345678901')
        ->set('spp_buyer_contact', '081234567890')
        ->set('spp_buyer_address', 'Jl. Sudirman No. 10, Pekanbaru')
        ->set('spp_price_mode', 'per_sqm')
        ->set('spp_price_per_sqm', 1600000)
        ->assertSet('spp_cash_price', 160000000) // 100 m2 * 1.600.000
        ->call('saveDirectSpp')
        ->assertHasNoErrors()
        ->assertSet('showDirectSppModal', false);

    $unit->refresh();
    expect((float)$unit->final_selling_price)->toBe(160000000.0);
    expect($unit->status)->toBe('terjual');

    $doc = OfficialDocument::where('unit_id', $unit->id)->first();
    expect($doc)->not->toBeNull();
    expect($doc->buyer_name)->toBe('Bpk. Budi Santoso');
    expect($doc->buyer_nik)->toBe('1471012345678901');

    // Test edit SPP
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Units\Show::class, ['id' => $unit->id])
        ->call('editDirectSpp', $doc->id)
        ->assertSet('showDirectSppModal', true)
        ->assertSet('editingSppId', $doc->id)
        ->set('spp_cash_price', 165000000)
        ->call('saveDirectSpp')
        ->assertHasNoErrors();

    $doc->refresh();
    $unit->refresh();
    expect((float)$unit->final_selling_price)->toBe(165000000.0);
});

test('marketing can propose price using price per m2 mode and founder can approve or edit', function () {
    $founder = User::create([
        'name' => 'Founder Leader',
        'email' => 'founder_lead@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $marketing = User::create([
        'name' => 'Marketing Agent',
        'email' => 'marketing_agent@example.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);
    $marketing->assignRole('marketing');

    $project = Project::create([
        'name' => 'Citra Atlantik',
        'location' => 'Pekanbaru',
        'base_price' => 100000000,
        'standard_land_area' => 120,
        'standard_building_area' => 0,
        'excess_price_per_sqm' => 1500000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'CA-05',
        'type' => 'kavling',
        'status' => 'tersedia',
        'land_length' => 12,
        'land_width' => 10,
        'land_area' => 120,
        'price' => 180000000,
        'hpp' => 120000000,
        'final_selling_price' => 180000000,
        'created_by' => $founder->id,
    ]);

    // Marketing opens modal and uses per_sqm mode
    Livewire::actingAs($marketing)
        ->test(\App\Livewire\Units\Show::class, ['id' => $unit->id])
        ->call('openDirectProposalModal')
        ->assertSet('showDirectProposalModal', true)
        ->set('prop_price_mode', 'per_sqm')
        ->set('prop_price_per_sqm', 1500000)
        ->assertSet('prop_proposed_price', 180000000) // 120 m2 * 1.500.000
        ->set('prop_notes', 'Nego harga per meter sesuai kesepakatan buyer')
        ->call('saveDirectProposal')
        ->assertHasNoErrors()
        ->assertSet('showDirectProposalModal', false);

    $proposal = PriceProposal::where('unit_id', $unit->id)->first();
    expect($proposal)->not->toBeNull();
    expect((float)$proposal->proposed_price)->toBe(180000000.0);
    expect($proposal->status)->toBe('menunggu');

    // Founder can edit or approve proposal
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Units\Show::class, ['id' => $unit->id])
        ->call('editDirectProposal', $proposal->id)
        ->assertSet('showDirectProposalModal', true)
        ->assertSet('editingProposalId', $proposal->id)
        ->set('prop_proposed_price', 185000000)
        ->call('saveDirectProposal')
        ->assertHasNoErrors();

    $proposal->refresh();
    expect((float)$proposal->proposed_price)->toBe(185000000.0);

    // Founder ACCs the proposal
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Units\Show::class, ['id' => $unit->id])
        ->call('approveProposal', $proposal->id, 'disetujui')
        ->assertHasNoErrors();

    $proposal->refresh();
    $unit->refresh();
    expect($proposal->status)->toBe('disetujui');
    expect($unit->status)->toBe('disetujui');
    expect((float)$unit->final_selling_price)->toBe(185000000.0);
});

test('founder can edit unit dimensions and customize excess land cost', function () {
    $founder = User::create([
        'name' => 'Founder Boss',
        'email' => 'founder_boss@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Bukit Atlantik',
        'location' => 'Pekanbaru',
        'base_price' => 100000000,
        'standard_land_area' => 100,
        'standard_building_area' => 0,
        'excess_price_per_sqm' => 1500000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'BA-10',
        'type' => 'kavling',
        'status' => 'tersedia',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'excess_land_area' => 0,
        'excess_cost' => 0,
        'price' => 150000000,
        'final_selling_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    // Founder edits land dimensions to 10 x 12 = 120 m2 (excess 20 m2)
    // and customizes excess price per m2 to 1.200.000 -> excess cost 24.000.000
    Livewire::actingAs($founder)
        ->test(\App\Livewire\Units\Show::class, ['id' => $unit->id])
        ->call('openEditUnitModal')
        ->assertSet('showEditUnitModal', true)
        ->set('edit_land_length', 12)
        ->set('edit_land_width', 10)
        ->assertSet('edit_land_area', 120.0)
        ->assertSet('edit_excess_land_area', 20.0)
        ->set('edit_excess_price_per_sqm', 1200000)
        ->assertSet('edit_excess_cost', 24000000.0)
        ->call('saveEditUnit')
        ->assertHasNoErrors()
        ->assertSet('showEditUnitModal', false);

    $unit->refresh();
    expect((float)$unit->land_area)->toBe(120.0);
    expect((float)$unit->excess_land_area)->toBe(20.0);
    expect((float)$unit->excess_cost)->toBe(24000000.0);
});
