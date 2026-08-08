<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\PriceProposal;
use App\Models\OfficialDocument;
use App\Livewire\Proposals\Index as ProposalIndex;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder']);
    Role::firstOrCreate(['name' => 'supervisor']);
    Role::firstOrCreate(['name' => 'marketing']);
});

test('end to end price proposal workflow: marketing submits proposal -> founder approves -> selling price updates -> official document generated', function () {
    // 1. Setup Users
    $founder = User::create([
        'name' => 'Founder Proposal E2E',
        'email' => 'founder_prop_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $supervisor = User::create([
        'name' => 'Supervisor Proposal E2E',
        'email' => 'supervisor_prop_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'supervisor',
        'is_active' => true,
    ]);
    $supervisor->assignRole('supervisor');

    $marketing = User::create([
        'name' => 'Marketing Proposal E2E',
        'email' => 'marketing_prop_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);
    $marketing->assignRole('marketing');

    // 2. Create Project & Unit (HPP 100M, Default Price 150M)
    $project = Project::create([
        'name' => 'Kavling Proposal Residence',
        'location' => 'Jl. Usulan No. 9',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'PROP-01',
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 0,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    // 3. Marketing Submits Special Price Proposal (Discounted to 140,000,000)
    Livewire::actingAs($marketing)
        ->test(ProposalIndex::class)
        ->call('openCreateModal')
        ->set('unit_id', $unit->id)
        ->set('proposed_price', 140000000)
        ->set('discount_reason', 'Diskon promo akhir tahun')
        ->set('proposal_notes', 'Disetujui nego Marketing')
        ->call('submitProposal')
        ->assertHasNoErrors();

    $proposal = PriceProposal::where('unit_id', $unit->id)->first();
    expect($proposal)->not->toBeNull();
    expect($proposal->status)->toBe('menunggu');
    expect((float)$proposal->proposed_price)->toBe(140000000.0);
    expect($unit->fresh()->status)->toBe('menunggu_persetujuan');

    // 4. Supervisor & Founder Approve Proposal
    Livewire::actingAs($supervisor)
        ->test(ProposalIndex::class)
        ->call('openApprovalModal', $proposal->id)
        ->set('approval_decision', 'disetujui')
        ->set('approval_notes', 'Disetujui oleh Supervisor')
        ->call('submitApproval')
        ->assertHasNoErrors();

    Livewire::actingAs($founder)
        ->test(ProposalIndex::class)
        ->call('openApprovalModal', $proposal->id)
        ->set('approval_decision', 'disetujui')
        ->set('approval_notes', 'Disetujui oleh Founder')
        ->call('submitApproval')
        ->assertHasNoErrors();

    expect($proposal->fresh()->status)->toBe('disetujui');
    expect((float)$unit->fresh()->final_selling_price)->toBe(140000000.0);
    expect($unit->fresh()->status)->toBe('disetujui');

    // Official Document auto-generated upon Founder approval
    $document = OfficialDocument::where('price_proposal_id', $proposal->id)->first();
    expect($document)->not->toBeNull();

    // 5. Marketing Issues SPP for Buyer
    Livewire::actingAs($marketing)
        ->test(ProposalIndex::class)
        ->call('openDocModal', $proposal->id)
        ->set('buyer_name', 'Hendra Gunawan')
        ->set('buyer_contact', '081234433221')
        ->set('buyer_address', 'Jl. Sudirman Jakarta')
        ->call('issueDocument')
        ->assertHasNoErrors();

    expect($unit->fresh()->status)->toBe('terjual');
});
