<?php

use App\Models\Booking;
use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->founder = User::factory()->create([
        'role' => 'founder',
        'is_active' => true,
    ]);

    $this->project = Project::create([
        'name' => 'Proyek Rollback Test',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $this->unit = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'RLB-01',
        'type' => 'kavling',
        'category' => 'kavling',
        'land_width' => 10,
        'land_length' => 10,
        'land_area' => 100,
        'hpp' => 50000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);
});

test('deleting spp document on booked unit restores unit status to booked', function () {
    $booking = Booking::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'buyer_name' => 'Budi Pembeli Booked',
        'buyer_phone' => '081234567890',
        'booking_type' => 'unit',
        'booking_amount' => 5000000,
        'booking_date' => now()->toDateString(),
        'status' => 'active',
        'created_by' => $this->founder->id,
    ]);

    $this->unit->update(['status' => 'booked']);

    $proposal = PriceProposal::create([
        'unit_id' => $this->unit->id,
        'hpp_price' => 50000000,
        'proposed_price' => 75000000,
        'margin' => 25000000,
        'is_below_hpp' => false,
        'proposed_by' => $this->founder->id,
        'status' => 'disetujui',
    ]);

    $doc = OfficialDocument::create([
        'unit_id' => $this->unit->id,
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/RLB/2026/001',
        'buyer_name' => 'Budi Pembeli Booked',
        'buyer_contact' => '081234567890',
        'issued_by' => $this->founder->id,
        'issued_at' => now(),
    ]);

    $this->unit->update(['status' => 'terjual']);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Documents\Index::class)
        ->call('deleteDocument', $doc->id)
        ->assertHasNoErrors();

    expect(OfficialDocument::find($doc->id))->toBeNull();
    expect($this->unit->fresh()->status)->toBe('booked');
});

test('deleting spp document on approved proposal unit restores unit status to disetujui', function () {
    $proposal = PriceProposal::create([
        'unit_id' => $this->unit->id,
        'hpp_price' => 50000000,
        'proposed_price' => 70000000,
        'margin' => 20000000,
        'is_below_hpp' => false,
        'proposed_by' => $this->founder->id,
        'status' => 'disetujui',
    ]);

    $this->unit->update(['status' => 'disetujui']);

    $doc = OfficialDocument::create([
        'unit_id' => $this->unit->id,
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/RLB/2026/002',
        'buyer_name' => 'Siti Pembeli ACC',
        'buyer_contact' => '081987654321',
        'issued_by' => $this->founder->id,
        'issued_at' => now(),
    ]);

    $this->unit->update(['status' => 'terjual']);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Documents\Index::class)
        ->call('deleteDocument', $doc->id)
        ->assertHasNoErrors();

    expect(OfficialDocument::find($doc->id))->toBeNull();
    expect($this->unit->fresh()->status)->toBe('disetujui');
});

test('deleting spp document on direct available unit restores unit status to tersedia', function () {
    $proposal = PriceProposal::create([
        'unit_id' => $this->unit->id,
        'hpp_price' => 50000000,
        'proposed_price' => 65000000,
        'margin' => 15000000,
        'is_below_hpp' => false,
        'proposed_by' => $this->founder->id,
        'status' => 'menunggu',
    ]);

    $doc = OfficialDocument::create([
        'unit_id' => $this->unit->id,
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/RLB/2026/003',
        'buyer_name' => 'Hendra Direct',
        'buyer_contact' => '081333444555',
        'issued_by' => $this->founder->id,
        'issued_at' => now(),
    ]);

    $this->unit->update(['status' => 'terjual']);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Documents\Index::class)
        ->call('deleteDocument', $doc->id)
        ->assertHasNoErrors();

    expect(OfficialDocument::find($doc->id))->toBeNull();
    expect($this->unit->fresh()->status)->toBe('tersedia');
});

test('founder alone can approve proposal and auto generate spp document on unit detail', function () {
    $proposal = PriceProposal::create([
        'unit_id' => $this->unit->id,
        'hpp_price' => 50000000,
        'proposed_price' => 80000000,
        'margin' => 30000000,
        'is_below_hpp' => false,
        'proposed_by' => $this->founder->id,
        'status' => 'menunggu',
    ]);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
        ->call('approveProposal', $proposal->id, 'disetujui')
        ->assertHasNoErrors();

    expect($proposal->fresh()->status)->toBe('disetujui');
    expect($this->unit->fresh()->status)->toBe('disetujui');
    expect(OfficialDocument::where('price_proposal_id', $proposal->id)->exists())->toBeTrue();
});

test('openDirectSppModal pre-fills cash price from installment total price if available', function () {
    $installment = UnitInstallment::create([
        'unit_id' => $this->unit->id,
        'total_price' => 95000000,
        'down_payment' => 20000000,
        'installment_count' => 12,
        'installment_amount' => 6250000,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
    ]);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
        ->call('openDirectSppModal')
        ->assertSet('spp_cash_price', 95000000);
});

test('deleting spp document when installment scheme is running reverts status to disetujui', function () {
    $proposal = PriceProposal::create([
        'unit_id' => $this->unit->id,
        'hpp_price' => 50000000,
        'proposed_price' => 95000000,
        'margin' => 45000000,
        'is_below_hpp' => false,
        'proposed_by' => $this->founder->id,
        'status' => 'disetujui',
    ]);

    $installment = UnitInstallment::create([
        'unit_id' => $this->unit->id,
        'total_price' => 95000000,
        'down_payment' => 20000000,
        'installment_count' => 12,
        'installment_amount' => 6250000,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
    ]);

    $doc = OfficialDocument::create([
        'unit_id' => $this->unit->id,
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/RLB/2026/004',
        'buyer_name' => 'Bambang Installment',
        'buyer_contact' => '081222333444',
        'issued_by' => $this->founder->id,
        'issued_at' => now(),
    ]);

    $this->unit->update(['status' => 'terjual']);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Documents\Index::class)
        ->call('deleteDocument', $doc->id)
        ->assertHasNoErrors();

    expect(OfficialDocument::find($doc->id))->toBeNull();
    expect($this->unit->fresh()->status)->toBe('disetujui');
});

test('rejection of proposal on unit detail sets status to ditolak', function () {
    $proposal = PriceProposal::create([
        'unit_id' => $this->unit->id,
        'hpp_price' => 50000000,
        'proposed_price' => 40000000,
        'margin' => -10000000,
        'is_below_hpp' => true,
        'proposed_by' => $this->founder->id,
        'status' => 'menunggu',
    ]);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
        ->call('approveProposal', $proposal->id, 'ditolak')
        ->assertHasNoErrors();

    expect($proposal->fresh()->status)->toBe('ditolak');
    expect($this->unit->fresh()->status)->toBe('ditolak');
});
