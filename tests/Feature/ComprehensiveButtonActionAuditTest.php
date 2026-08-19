<?php

use App\Livewire\Bookings\Index as BookingsIndex;
use App\Livewire\FieldExpenses\Index as FieldExpensesIndex;
use App\Livewire\Workers\Index as WorkersIndex;
use App\Models\Booking;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use Livewire\Livewire;

beforeEach(function () {
    $this->founder = User::factory()->create(['role' => 'founder']);
});

test('field expenses editExpense and openEditModal buttons work without error', function () {
    $this->actingAs($this->founder);

    $project = Project::create([
        'name' => 'Kavling Harmoni 2',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'total_project_price' => 500000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'KAVL-BTN-01',
        'land_area' => 100,
        'building_area' => 0,
        'category' => 'standar',
        'base_price' => 50000000,
        'final_selling_price' => 50000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);

    $mat = WeeklyMaterialPurchase::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'store_name' => 'Toko Bangunan Sejahtera',
        'item_name' => 'Semen Padang 100 Sak',
        'quantity' => 100,
        'unit_measure' => 'sak',
        'unit_price' => 65000,
        'total_price' => 6500000,
        'purchase_date' => now()->toDateString(),
        'payment_status' => 'lunas',
        'pengawas_id' => $this->founder->id,
        'created_by' => $this->founder->id,
    ]);

    // Test calling editExpense and openEditModal
    Livewire::test(FieldExpensesIndex::class)
        ->call('editExpense', 'material', $mat->id)
        ->assertSet('showEditModal', true)
        ->assertSet('editingType', 'material')
        ->assertSet('editingId', $mat->id)
        ->assertSet('edit_item_name', 'Semen Padang 100 Sak')
        ->call('closeEditModal')
        ->assertSet('showEditModal', false)
        ->call('openEditModal', 'material', $mat->id)
        ->assertSet('showEditModal', true);
});

test('bookings edit and cancel action buttons work without error', function () {
    $this->actingAs($this->founder);

    $project = Project::create([
        'name' => 'Kavling Harmoni 3',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'total_project_price' => 500000000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $booking = Booking::create([
        'project_id' => $project->id,
        'buyer_name' => 'Calon Pembeli Button Test',
        'buyer_phone' => '081234567890',
        'booking_type' => 'project',
        'booking_amount' => 5000000,
        'booking_date' => now()->toDateString(),
        'status' => 'active',
        'created_by' => $this->founder->id,
    ]);

    Livewire::test(BookingsIndex::class)
        ->call('openEditModal', $booking->id)
        ->assertSet('showModal', true)
        ->assertSet('editingBookingId', $booking->id)
        ->assertSet('buyer_name', 'Calon Pembeli Button Test')
        ->call('editBooking', $booking->id)
        ->assertSet('showModal', true)
        ->call('cancelBooking', $booking->id);

    expect(Booking::find($booking->id))->toBeNull();
});

test('worker delete action button works without error', function () {
    $this->actingAs($this->founder);

    $worker = Worker::create([
        'name' => 'Pak Joko Mandor Button Test',
        'phone' => '081299998888',
        'address' => 'Jl. Garuda No. 10',
        'type' => 'mandor',
        'specialty' => 'Mandor Struktur',
        'status' => 'active',
    ]);

    Livewire::test(WorkersIndex::class)
        ->assertSee('Pak Joko Mandor Button Test')
        ->call('delete', $worker->id);

    expect(Worker::find($worker->id))->toBeNull();
});
