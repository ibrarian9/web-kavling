<?php

use App\Models\PriceProposal;
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
        'name' => 'Project Proposal Test',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $this->unit = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'PROP-01',
        'type' => 'kavling',
        'category' => 'kavling',
        'land_width' => 10,
        'land_length' => 10,
        'land_area' => 100,
        'hpp' => 50000000,
        'status' => 'menunggu_persetujuan',
        'created_by' => $this->founder->id,
    ]);
});

test('founder can edit proposal data', function () {
    $proposal = PriceProposal::create([
        'unit_id' => $this->unit->id,
        'hpp_price' => 50000000,
        'proposed_price' => 55000000,
        'margin' => 5000000,
        'is_below_hpp' => false,
        'proposed_by' => $this->founder->id,
        'status' => 'menunggu',
        'notes' => 'Catatan awal',
    ]);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Proposals\Index::class)
        ->call('editProposal', $proposal->id)
        ->assertSet('editingProposalId', $proposal->id)
        ->assertSet('proposed_price', 55000000.0)
        ->set('proposed_price', 60000000)
        ->call('submitProposal')
        ->assertHasNoErrors();

    $proposal->refresh();
    expect((float) $proposal->proposed_price)->toBe(60000000.0);
    expect((float) $proposal->margin)->toBe(10000000.0);
});

test('founder can delete proposal data and revert unit status', function () {
    $proposal = PriceProposal::create([
        'unit_id' => $this->unit->id,
        'hpp_price' => 50000000,
        'proposed_price' => 55000000,
        'margin' => 5000000,
        'is_below_hpp' => false,
        'proposed_by' => $this->founder->id,
        'status' => 'menunggu',
        'notes' => 'Akan dihapus',
    ]);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Proposals\Index::class)
        ->call('deleteProposal', $proposal->id)
        ->assertHasNoErrors();

    expect(PriceProposal::find($proposal->id))->toBeNull();
    expect($this->unit->fresh()->status)->toBe('tersedia');
});
