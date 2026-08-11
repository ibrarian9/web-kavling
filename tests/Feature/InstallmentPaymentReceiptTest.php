<?php

use App\Models\User;
use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\InstallmentPayment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('admin can upload receipt photo on installment payment creation and update', function () {
    Storage::fake('public');

    \Spatie\Permission\Models\Role::findOrCreate('admin', 'web');
    $admin = User::factory()->create(['role' => 'admin']);
    $admin->assignRole('admin');

    $project = Project::create([
        'name' => 'Grand Kavling Test',
        'code' => 'GKT',
        'location' => 'Pekanbaru',
        'standard_land_area' => 120,
        'excess_price_per_sqm' => 500000,
        'base_price' => 150000000,
        'total_units' => 10,
        'status' => 'aktif',
        'created_by' => $admin->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'A-01',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_width' => 10,
        'land_length' => 15,
        'land_area' => 150,
        'base_price' => 150000000,
        'excess_land_area' => 30,
        'excess_land_price' => 15000000,
        'final_selling_price' => 165000000,
        'status' => 'terjual',
        'created_by' => $admin->id,
    ]);

    $installment = UnitInstallment::create([
        'unit_id' => $unit->id,
        'total_price' => 100000000,
        'down_payment' => 10000000,
        'installment_count' => 10,
        'installment_amount' => 9000000,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
    ]);

    $file = UploadedFile::fake()->image('receipt.jpg');

    Livewire::actingAs($admin)
        ->test(\App\Livewire\Units\Show::class, ['id' => $unit->id])
        ->call('openInstallmentPaymentModal')
        ->set('installment_payment_amount', 9000000)
        ->set('installment_payment_date', now()->toDateString())
        ->set('installment_payment_method', 'Transfer Bank')
        ->set('installment_payment_notes', 'Setoran Bulan 1')
        ->set('installment_payment_receipt_photo', $file)
        ->call('saveInstallmentPayment')
        ->assertHasNoErrors();

    $payment = InstallmentPayment::where('unit_installment_id', $installment->id)->first();
    expect($payment)->not->toBeNull();
    expect($payment->receipt_photo_path)->not->toBeNull();
    
    $fullPath = storage_path('app/public/' . $payment->receipt_photo_path);
    expect(file_exists($fullPath))->toBeTrue();

    // Clean up created file
    if (file_exists($fullPath)) {
        @unlink($fullPath);
    }
});
