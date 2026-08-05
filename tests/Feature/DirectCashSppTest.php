<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
});

test('founder can generate SPP and SPJB PDF directly on unit detail page without booking fee', function () {
    $founder = User::create([
        'name' => 'Founder Executive',
        'email' => 'founder_exec@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Atlantik Residence',
        'location' => 'Bandung',
        'base_price' => 100000000,
        'standard_land_area' => 60,
        'standard_building_area' => 36,
        'excess_price_per_sqm' => 1500000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'ALT-A01',
        'type' => 'kavling',
        'status' => 'tersedia',
        'price' => 200000000,
        'final_selling_price' => 200000000,
        'created_by' => $founder->id,
    ]);

    Livewire::actingAs($founder)
        ->test(\App\Livewire\Units\Show::class, ['id' => $unit->id])
        ->assertSee('Pembelian Cash')
        ->call('openDirectSppModal')
        ->assertSet('showDirectSppModal', true)
        ->set('spp_buyer_name', 'Bpk. Hendra Wijaya')
        ->set('spp_buyer_contact', '081299998888')
        ->set('spp_buyer_address', 'NIK 3271000099990001, Bandung')
        ->set('spp_cash_price', 195000000)
        ->call('saveDirectSpp')
        ->assertHasNoErrors()
        ->assertSet('showDirectSppModal', false);

    $this->assertDatabaseHas('official_documents', [
        'unit_id' => $unit->id,
        'buyer_name' => 'Bpk. Hendra Wijaya',
        'buyer_contact' => '081299998888',
    ]);

    $unit->refresh();
    expect((float) $unit->final_selling_price)->toBe(195000000.0);
});
