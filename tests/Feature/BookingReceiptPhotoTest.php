<?php

use App\Models\Booking;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');

    $this->founder = User::factory()->create([
        'role' => 'founder',
        'is_active' => true,
    ]);

    $this->project = Project::create([
        'name' => 'Project Test Booking',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);

    $this->unit = Unit::create([
        'project_id' => $this->project->id,
        'code' => 'BK-01',
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

test('user can upload receipt photo when creating booking and view photo modal', function () {
    $file = UploadedFile::fake()->image('bukti_transfer_dp.jpg');

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Bookings\Index::class)
        ->set('project_id', $this->project->id)
        ->set('unit_id', $this->unit->id)
        ->set('buyer_name', 'Bpk. Ahmad Santoso')
        ->set('buyer_phone', '08123456789')
        ->set('booking_amount', 5000000)
        ->set('booking_date', now()->toDateString())
        ->set('receipt_photo', $file)
        ->call('save')
        ->assertHasNoErrors();

    $booking = Booking::where('buyer_name', 'Bpk. Ahmad Santoso')->first();
    expect($booking)->not->toBeNull();
    expect($booking->receipt_photo_path)->not->toBeNull();

    Storage::disk('public')->assertExists($booking->receipt_photo_path);

    // Test opening image modal
    Livewire::test(\App\Livewire\Bookings\Index::class)
        ->call('openImageModal', asset('storage/' . $booking->receipt_photo_path), 'Foto Resi Bukti Transfer / DP')
        ->assertSet('showImageModal', true)
        ->assertSet('imageModalTitle', 'Foto Resi Bukti Transfer / DP');
});

test('founder can edit booking data and sync cashflow', function () {
    $booking = Booking::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'buyer_name' => 'Bpk. Bambang',
        'buyer_phone' => '0811111111',
        'booking_type' => 'unit',
        'booking_amount' => 3000000,
        'dp_amount' => 0,
        'booking_date' => now()->toDateString(),
        'status' => 'active',
        'created_by' => $this->founder->id,
    ]);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Bookings\Index::class)
        ->call('editBooking', $booking->id)
        ->assertSet('editingBookingId', $booking->id)
        ->assertSet('buyer_name', 'Bpk. Bambang')
        ->set('buyer_name', 'Bpk. Bambang Edit')
        ->set('booking_amount', 7000000)
        ->call('save')
        ->assertHasNoErrors();

    $booking->refresh();
    expect($booking->buyer_name)->toBe('Bpk. Bambang Edit');
    expect((float) $booking->booking_amount)->toBe(7000000.0);
});

test('founder can delete booking data and revert unit status', function () {
    $this->unit->update(['status' => 'booked']);

    $booking = Booking::create([
        'project_id' => $this->project->id,
        'unit_id' => $this->unit->id,
        'buyer_name' => 'Bpk. Candra',
        'buyer_phone' => '0822222222',
        'booking_type' => 'unit',
        'booking_amount' => 5000000,
        'dp_amount' => 0,
        'booking_date' => now()->toDateString(),
        'status' => 'active',
        'created_by' => $this->founder->id,
    ]);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Bookings\Index::class)
        ->call('deleteBooking', $booking->id)
        ->assertHasNoErrors();

    expect(Booking::find($booking->id))->toBeNull();
    expect($this->unit->fresh()->status)->toBe('tersedia');
});
