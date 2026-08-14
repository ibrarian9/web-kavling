<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');
    $this->founder = User::factory()->create(['role' => 'founder', 'is_active' => true]);
});

function createTestProjectForValidation($name, $userId) {
    return Project::create([
        'name' => $name,
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 100000000,
        'status' => 'aktif',
        'created_by' => $userId,
    ]);
}

test('livewire bookings component validates receipt_photo upload real-time without throwing no property found error', function () {
    $project = createTestProjectForValidation('Proyek Test Validation', $this->founder->id);

    $file = UploadedFile::fake()->create('receipt.jpg', 500, 'image/jpeg');

    Livewire::actingAs($this->founder)
        ->test(\App\Livewire\Bookings\Index::class)
        ->set('receipt_photo', $file)
        ->assertHasNoErrors('receipt_photo');
});

test('livewire units index component validates receipt_photo upload real-time without throwing error', function () {
    $project = createTestProjectForValidation('Proyek Test Unit Validation', $this->founder->id);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'A1',
        'category' => 'kavling',
        'type' => 'kavling',
        'land_area' => 100,
        'hpp' => 100000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);

    $file = UploadedFile::fake()->create('receipt.png', 300, 'image/png');

    Livewire::actingAs($this->founder)
        ->test(\App\Livewire\Units\Index::class)
        ->set('bookingUnitId', $unit->id)
        ->set('receipt_photo', $file)
        ->assertHasNoErrors('receipt_photo');
});

test('livewire units show component validates receipt_photo upload real-time without throwing error', function () {
    $project = createTestProjectForValidation('Proyek Test Show Validation', $this->founder->id);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'B1',
        'category' => 'kavling',
        'type' => 'kavling',
        'land_area' => 100,
        'hpp' => 100000000,
        'status' => 'tersedia',
        'created_by' => $this->founder->id,
    ]);

    $file = UploadedFile::fake()->create('receipt.pdf', 200, 'application/pdf');

    Livewire::actingAs($this->founder)
        ->test(\App\Livewire\Units\Show::class, ['id' => $unit->id])
        ->set('receipt_photo', $file)
        ->assertHasNoErrors('receipt_photo');
});
