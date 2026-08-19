<?php

use App\Livewire\Proposals\Index as ProposalsIndex;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Livewire;

beforeEach(function () {
    $this->founder = User::factory()->create(['role' => 'founder']);
});

test('proposals module filters by date period correctly', function () {
    $this->actingAs($this->founder);

    $project = Project::create([
        'name' => 'Kavling Harmoni Proposal',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'total_project_price' => 500000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $unitA = Unit::create([
        'project_id' => $project->id,
        'code' => 'PROP-TODAY-01',
        'land_area' => 100,
        'building_area' => 0,
        'category' => 'standar',
        'base_price' => 50000000,
        'final_selling_price' => 55000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);

    $unitB = Unit::create([
        'project_id' => $project->id,
        'code' => 'PROP-PAST-02',
        'land_area' => 100,
        'building_area' => 0,
        'category' => 'standar',
        'base_price' => 50000000,
        'final_selling_price' => 55000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);

    $propToday = PriceProposal::create([
        'unit_id' => $unitA->id,
        'hpp_price' => 45000000,
        'proposed_price' => 55000000,
        'margin' => 10000000,
        'proposed_by' => $this->founder->id,
        'status' => 'menunggu',
    ]);
    $propToday->created_at = Carbon::today();
    $propToday->saveQuietly();

    $propPast = PriceProposal::create([
        'unit_id' => $unitB->id,
        'hpp_price' => 45000000,
        'proposed_price' => 55000000,
        'margin' => 10000000,
        'proposed_by' => $this->founder->id,
        'status' => 'menunggu',
    ]);
    $propPast->created_at = Carbon::now()->subMonths(2);
    $propPast->saveQuietly();

    Livewire::test(ProposalsIndex::class)
        ->assertSee('PROP-TODAY-01')
        ->assertSee('PROP-PAST-02')
        ->set('datePeriod', 'today')
        ->assertSee('PROP-TODAY-01')
        ->assertDontSee('PROP-PAST-02')
        ->set('datePeriod', 'all')
        ->assertSee('PROP-PAST-02');
});
