<?php

use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');
    Storage::fake('local');

    $this->founder = User::factory()->create([
        'role' => 'founder',
        'is_active' => true,
    ]);

    $this->project = Project::create([
        'name' => 'Project Test Cashflow',
        'location' => 'Pekanbaru',
        'standard_land_area' => 100,
        'base_price' => 50000000,
        'excess_price_per_sqm' => 500000,
        'status' => 'aktif',
        'created_by' => $this->founder->id,
    ]);
});

test('user can upload receipt photo when creating cashflow transaction and view it', function () {
    $file = UploadedFile::fake()->image('bukti_kas.jpg', 100, 100);

    $this->actingAs($this->founder);

    Livewire::test(\App\Livewire\Cashflow\Index::class)
        ->set('project_id', $this->project->id)
        ->set('type', 'masuk')
        ->set('category', 'operasional')
        ->set('amount', 2500000)
        ->set('transaction_date', now()->toDateString())
        ->set('description', 'Pemasukan Kas Tes')
        ->set('receipt_photo', $file)
        ->call('saveTransaction')
        ->assertHasNoErrors();

    $trx = CashflowTransaction::where('description', 'Pemasukan Kas Tes')->first();
    expect($trx)->not->toBeNull();
    expect($trx->receipt_photo_path)->not->toBeNull();
    expect($trx->receipt_photo_url)->not->toBeNull();
    $fullPath = storage_path('app/public/' . $trx->receipt_photo_path);
    expect(file_exists($fullPath))->toBeTrue();

    if (file_exists($fullPath)) {
        @unlink($fullPath);
    }

    // Test opening image modal
    Livewire::test(\App\Livewire\Cashflow\Index::class)
        ->call('openImageModal', $trx->receipt_photo_url, 'Foto Struk Resi Kas - Pemasukan Kas Tes')
        ->assertSet('showImageModal', true)
        ->assertSet('imageModalTitle', 'Foto Struk Resi Kas - Pemasukan Kas Tes');
});
