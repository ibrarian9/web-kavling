<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->founder = User::factory()->create([
        'role' => 'founder',
        'is_active' => true,
    ]);

    $this->project = Project::create([
        'name' => 'Perumahan Siteplan Indah',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 75000000,
        'excess_price_per_sqm' => 500000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $this->unitReady = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'SITE-A01',
        'type' => 'kavling',
        'category' => 'kavling',
        'land_width' => 10,
        'land_length' => 10,
        'land_area' => 100,
        'hpp' => 75000000,
        'final_selling_price' => 85000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);

    $this->unitSold = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'SITE-A02',
        'type' => 'kavling',
        'category' => 'kavling',
        'land_width' => 10,
        'land_length' => 10,
        'land_area' => 100,
        'hpp' => 75000000,
        'final_selling_price' => 85000000,
        'status' => 'terjual',
        'created_by' => $this->founder->id,
    ]);
});

test('can load siteplan tab and select unit in projects detail page', function () {
    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Projects\Show::class, ['id' => $this->project->id])
        ->set('activeTab', 'siteplan')
        ->assertSee('SITE-A01')
        ->assertSee('SITE-A02')
        ->call('openSiteplanUnitModal', $this->unitReady->id)
        ->assertSet('showSiteplanModal', true)
        ->assertSet('selectedSiteplanUnit.code', 'SITE-A01')
        ->call('closeSiteplanUnitModal')
        ->assertSet('showSiteplanModal', false);
});

test('can switch to siteplan view mode in units index page', function () {
    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Units\Index::class)
        ->assertSet('viewMode', 'table')
        ->set('viewMode', 'siteplan')
        ->assertSet('viewMode', 'siteplan')
        ->assertSee('SITE-A01');
});
