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

    // Test opening image viewer modal in component
    Livewire::test(\App\Livewire\Bookings\Index::class)
        ->call('openImageModal', asset('storage/' . $booking->receipt_photo_path), 'Foto Struk Resi Booking - Bpk. Ahmad Santoso')
        ->assertSet('showImageModal', true)
        ->assertSet('imageModalTitle', 'Foto Struk Resi Booking - Bpk. Ahmad Santoso');
});
