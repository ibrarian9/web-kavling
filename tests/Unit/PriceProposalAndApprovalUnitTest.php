<?php

use App\Models\PriceProposal;
use App\Models\Approval;
use App\Models\Unit;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('price proposal model can be created with correct attributes and casts', function () {
    $user = User::factory()->create(['role' => 'marketing']);
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Proposal Test',
        'location' => 'Jl. Proposal',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'PROP-A1',
        'category' => 'kavling',
        'land_length' => 10,
        'land_width' => 10,
        'land_area' => 100,
        'building_area' => 0,
        'hpp' => 120000000,
        'final_selling_price' => 180000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    $proposal = PriceProposal::create([
        'unit_id' => $unit->id,
        'hpp_price' => 120000000,
        'proposed_price' => 160000000,
        'margin' => 40000000,
        'is_below_hpp' => false,
        'proposed_by' => $user->id,
        'status' => 'menunggu',
    ]);

    expect($proposal)->not->toBeNull();
    expect((float)$proposal->hpp_price)->toBe(120000000.00);
    expect((float)$proposal->proposed_price)->toBe(160000000.00);
    expect((float)$proposal->margin)->toBe(40000000.00);
    expect($proposal->is_below_hpp)->toBeFalse();
    expect($proposal->status)->toBe('menunggu');
});

test('price proposal has correct relationships: unit, proposer, approvals', function () {
    $marketing = User::factory()->create(['role' => 'marketing']);
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Relasi Test',
        'location' => 'Jl. Relasi',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'REL-B1',
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

    $proposal = PriceProposal::create([
        'unit_id' => $unit->id,
        'hpp_price' => 100000000,
        'proposed_price' => 140000000,
        'margin' => 40000000,
        'is_below_hpp' => false,
        'proposed_by' => $marketing->id,
        'status' => 'menunggu',
    ]);

    $approval = Approval::create([
        'price_proposal_id' => $proposal->id,
        'approver_id' => $founder->id,
        'approver_role' => 'founder',
        'decision' => 'disetujui',
        'notes' => 'Harga OK',
        'decided_at' => now(),
    ]);

    expect($proposal->unit->id)->toBe($unit->id);
    expect($proposal->proposer->id)->toBe($marketing->id);
    expect($proposal->approvals)->toHaveCount(1);
    expect($approval->proposal->id)->toBe($proposal->id);
    expect($approval->approver->id)->toBe($founder->id);
    expect($approval->decided_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('isFullyApproved returns true only when both founder AND supervisor approve', function () {
    $marketing = User::factory()->create(['role' => 'marketing']);
    $founder = User::factory()->create(['role' => 'founder']);
    $supervisor = User::factory()->create(['role' => 'supervisor']);

    $project = Project::create([
        'name' => 'Kavling Approval Test',
        'location' => 'Jl. Approval',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'APR-C1',
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

    $proposal = PriceProposal::create([
        'unit_id' => $unit->id,
        'hpp_price' => 100000000,
        'proposed_price' => 130000000,
        'margin' => 30000000,
        'is_below_hpp' => false,
        'proposed_by' => $marketing->id,
        'status' => 'menunggu',
    ]);

    // No approvals yet
    expect($proposal->isFullyApproved())->toBeFalse();

    // Only founder approves
    Approval::create([
        'price_proposal_id' => $proposal->id,
        'approver_id' => $founder->id,
        'approver_role' => 'founder',
        'decision' => 'disetujui',
        'decided_at' => now(),
    ]);
    expect($proposal->isFullyApproved())->toBeFalse();

    // Supervisor also approves
    Approval::create([
        'price_proposal_id' => $proposal->id,
        'approver_id' => $supervisor->id,
        'approver_role' => 'supervisor',
        'decision' => 'disetujui',
        'decided_at' => now(),
    ]);
    expect($proposal->isFullyApproved())->toBeTrue();
});

test('isRejected returns true when any approver rejects', function () {
    $marketing = User::factory()->create(['role' => 'marketing']);
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Reject Test',
        'location' => 'Jl. Reject',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'REJ-D1',
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

    $proposal = PriceProposal::create([
        'unit_id' => $unit->id,
        'hpp_price' => 100000000,
        'proposed_price' => 90000000,
        'margin' => -10000000,
        'is_below_hpp' => true,
        'discount_reason' => 'Harga di bawah HPP untuk pembeli khusus',
        'proposed_by' => $marketing->id,
        'status' => 'menunggu',
    ]);

    expect($proposal->isRejected())->toBeFalse();

    Approval::create([
        'price_proposal_id' => $proposal->id,
        'approver_id' => $founder->id,
        'approver_role' => 'founder',
        'decision' => 'ditolak',
        'notes' => 'Terlalu rendah',
        'decided_at' => now(),
    ]);

    expect($proposal->isRejected())->toBeTrue();
});
