<?php

use App\Models\DailyActivityReport;
use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles
    Role::firstOrCreate(['name' => 'founder', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'marketing', 'guard_name' => 'web']);

    // Create Founder
    $this->founder = User::create([
        'name' => 'Founder User',
        'email' => 'founder@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $this->founder->assignRole('founder');

    // Create Admin
    $this->admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'is_active' => true,
    ]);
    $this->admin->assignRole('admin');

    // Create Marketing
    $this->marketing = User::create([
        'name' => 'Marketing User',
        'email' => 'marketing@test.com',
        'password' => bcrypt('password'),
        'role' => 'marketing',
        'is_active' => true,
    ]);
    $this->marketing->assignRole('marketing');

    // Seed sample Project & Unit
    $this->project = Project::create([
        'name' => 'Grand Kavling Test',
        'code' => 'GKT',
        'location' => 'Pekanbaru',
        'standard_land_area' => 120,
        'excess_price_per_sqm' => 500000,
        'base_price' => 150000000,
        'total_units' => 10,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $this->unit = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'A-01',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_width' => 10,
        'land_length' => 15,
        'land_area' => 150,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);
});

test('admin can view HPP and execute operational tasks', function () {
    expect($this->admin->isAdmin())->toBeTrue();
    expect($this->admin->isAdminOrFounder())->toBeTrue();
    expect($this->admin->canViewHpp())->toBeTrue();
});

test('admin is forbidden 403 from accessing activity logs, user management, and employee salaries', function () {
    $this->actingAs($this->admin);

    Livewire::test(\App\Livewire\ActivityLogs\Index::class)
        ->assertStatus(403);

    Livewire::test(\App\Livewire\Users\Index::class)
        ->assertStatus(403);

    Livewire::test(\App\Livewire\EmployeeSalaries\Index::class)
        ->assertStatus(403);
});

test('founder can access activity logs, user management, and employee salaries', function () {
    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\ActivityLogs\Index::class)
        ->assertStatus(200);

    Livewire::test(\App\Livewire\Users\Index::class)
        ->assertStatus(200);

    Livewire::test(\App\Livewire\EmployeeSalaries\Index::class)
        ->assertStatus(200);
});

test('admin can submit and approve proposals but cannot delete proposals', function () {
    $this->actingAs($this->admin);

    // Admin creates proposal
    Livewire::test(\App\Livewire\Proposals\Index::class)
        ->set('unit_id', $this->unit->id)
        ->set('proposed_price', 140000000)
        ->call('submitProposal')
        ->assertHasNoErrors();

    $proposal = PriceProposal::where('unit_id', $this->unit->id)->first();
    expect($proposal)->not->toBeNull();

    // Admin approves proposal
    Livewire::test(\App\Livewire\Proposals\Index::class)
        ->set('selectedProposalId', $proposal->id)
        ->set('approval_decision', 'disetujui')
        ->call('submitApproval')
        ->assertHasNoErrors();

    expect($proposal->fresh()->status)->toBe('disetujui');

    // Admin attempts to delete proposal -> should fail
    Livewire::test(\App\Livewire\Proposals\Index::class)
        ->call('deleteProposal', $proposal->id);

    expect(PriceProposal::find($proposal->id))->not->toBeNull();

    // Founder deletes proposal
    $this->actingAs($this->founder);
    Livewire::test(\App\Livewire\Proposals\Index::class)
        ->call('deleteProposal', $proposal->id);

    expect(PriceProposal::find($proposal->id))->toBeNull();
});

test('admin cannot delete official documents', function () {
    $proposal = PriceProposal::create([
        'unit_id' => $this->unit->id,
        'hpp_price' => 100000000,
        'proposed_price' => 140000000,
        'margin' => 40000000,
        'is_below_hpp' => false,
        'proposed_by' => $this->admin->id,
        'status' => 'disetujui',
    ]);

    $doc = OfficialDocument::create([
        'unit_id' => $this->unit->id,
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/TEST/2026/001',
        'buyer_name' => 'Budi Santoso',
        'buyer_contact' => '08123456789',
        'issued_by' => $this->admin->id,
        'issued_at' => now(),
    ]);

    $this->actingAs($this->admin);

    // Admin attempts delete -> should fail
    Livewire::test(\App\Livewire\Documents\Index::class)
        ->call('deleteDocument', $doc->id);

    expect(OfficialDocument::find($doc->id))->not->toBeNull();

    // Founder deletes
    $this->actingAs($this->founder);
    Livewire::test(\App\Livewire\Documents\Index::class)
        ->call('deleteDocument', $doc->id);

    expect(OfficialDocument::find($doc->id))->toBeNull();
});

test('admin can create and edit projects and edit unit specifications', function () {
    $this->actingAs($this->admin);

    // Admin creates new project
    Livewire::test(\App\Livewire\Projects\Index::class)
        ->set('name', 'Proyek Admin New')
        ->set('location', 'Pekanbaru Admin')
        ->set('standard_land_area', 100)
        ->set('excess_price_per_sqm', 1000000)
        ->set('base_price', 120000000)
        ->call('saveProject')
        ->assertHasNoErrors();

    $newProject = Project::where('name', 'Proyek Admin New')->first();
    expect($newProject)->not->toBeNull();

    // Admin edits unit specifications
    Livewire::test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
        ->set('edit_unit_code', 'A-01-UPD')
        ->set('edit_unit_category', 'kavling')
        ->set('edit_unit_status', 'tersedia')
        ->set('edit_land_area', 160)
        ->call('saveEditUnit')
        ->assertHasNoErrors();

    expect($this->unit->fresh()->code)->toBe('A-01-UPD');
});

test('marketing user is restricted from viewing HPP, cashflow, and worker management', function () {
    expect($this->marketing->isMarketing())->toBeTrue();
    expect($this->marketing->canViewHpp())->toBeFalse();

    $this->actingAs($this->marketing);

    // Dashboard renders safely without errors for Marketing
    Livewire::test(\App\Livewire\Dashboard::class)
        ->assertStatus(200)
        ->assertViewHas('user');

    // Projects Show renders safely without errors for Marketing
    Livewire::test(\App\Livewire\Projects\Show::class, ['id' => $this->project->id])
        ->assertStatus(200);

    // Workers page (Mandor & Tukang) returns 403 Forbidden for Marketing
    Livewire::test(\App\Livewire\Workers\Index::class)
        ->assertStatus(403);
});

