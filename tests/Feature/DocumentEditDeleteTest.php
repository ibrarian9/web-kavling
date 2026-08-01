<?php

use App\Models\OfficialDocument;
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
        'name' => 'Project Document Test',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $this->unit = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'DOC-01',
        'type' => 'kavling',
        'category' => 'kavling',
        'land_width' => 10,
        'land_length' => 10,
        'land_area' => 100,
        'hpp' => 50000000,
        'status' => 'terjual',
        'created_by' => $this->founder->id,
    ]);

    $this->proposal = PriceProposal::create([
        'unit_id' => $this->unit->id,
        'hpp_price' => 50000000,
        'proposed_price' => 55000000,
        'margin' => 5000000,
        'is_below_hpp' => false,
        'proposed_by' => $this->founder->id,
        'status' => 'disetujui',
    ]);
});

test('founder can edit official document data', function () {
    $doc = OfficialDocument::create([
        'unit_id' => $this->unit->id,
        'price_proposal_id' => $this->proposal->id,
        'document_number' => 'SPP/TEST/2026/001',
        'buyer_name' => 'Ahmad Subagyo',
        'buyer_contact' => '081234567890',
        'buyer_address' => 'Pekanbaru',
        'issued_by' => $this->founder->id,
        'issued_at' => now(),
    ]);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Documents\Index::class)
        ->call('editDocument', $doc->id)
        ->assertSet('editingDocumentId', $doc->id)
        ->assertSet('buyer_name', 'Ahmad Subagyo')
        ->set('buyer_name', 'Ahmad Subagyo S.H.')
        ->call('generateDocument')
        ->assertHasNoErrors();

    $doc->refresh();
    expect($doc->buyer_name)->toBe('Ahmad Subagyo S.H.');
});

test('founder can delete official document', function () {
    $doc = OfficialDocument::create([
        'unit_id' => $this->unit->id,
        'price_proposal_id' => $this->proposal->id,
        'document_number' => 'SPP/TEST/2026/002',
        'buyer_name' => 'Budi Santoso',
        'buyer_contact' => '081987654321',
        'buyer_address' => 'Pekanbaru',
        'issued_by' => $this->founder->id,
        'issued_at' => now(),
    ]);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Documents\Index::class)
        ->call('deleteDocument', $doc->id)
        ->assertHasNoErrors();

    expect(OfficialDocument::find($doc->id))->toBeNull();
});
