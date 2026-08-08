<?php

use App\Models\ProjectPayment;
use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\User;

test('project payment auto-generates UUID on creation', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling UUID Test',
        'location' => 'Jl. UUID',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'total_project_price' => 500000000,
        'created_by' => $founder->id,
    ]);

    $payment = ProjectPayment::create([
        'project_id' => $project->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 100000000,
        'payment_method' => 'Transfer Bank',
        'notes' => 'Pembayaran lahan tahap 1',
        'created_by' => $founder->id,
    ]);

    expect($payment->uuid)->not->toBeNull();
    expect(strlen($payment->uuid))->toBe(36);
});

test('project payment has correct attributes and casts', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Cast Test',
        'location' => 'Jl. Cast',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'total_project_price' => 500000000,
        'created_by' => $founder->id,
    ]);

    $payment = ProjectPayment::create([
        'project_id' => $project->id,
        'payment_date' => '2026-07-15',
        'amount_paid' => 250000000,
        'payment_method' => 'Transfer',
        'created_by' => $founder->id,
    ]);

    expect((float)$payment->amount_paid)->toBe(250000000.00);
    expect($payment->payment_date)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

test('project payment has correct relationships: project, creator', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Relasi Pay',
        'location' => 'Jl. Relasi Pay',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $payment = ProjectPayment::create([
        'project_id' => $project->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 50000000,
        'payment_method' => 'Cash',
        'created_by' => $founder->id,
    ]);

    expect($payment->project->id)->toBe($project->id);
    expect($payment->creator->id)->toBe($founder->id);
});

test('project payment has morphOne cashflow transaction relationship', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Morph Test',
        'location' => 'Jl. Morph',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $payment = ProjectPayment::create([
        'project_id' => $project->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 75000000,
        'payment_method' => 'Transfer',
        'created_by' => $founder->id,
    ]);

    $cashflow = CashflowTransaction::create([
        'project_id' => $project->id,
        'type' => 'keluar',
        'category' => 'operasional',
        'amount' => 75000000,
        'transaction_date' => now()->toDateString(),
        'description' => 'Pembayaran Lahan Proyek',
        'reference_type' => ProjectPayment::class,
        'reference_id' => $payment->id,
        'created_by' => $founder->id,
    ]);

    expect($payment->cashflowTransaction)->not->toBeNull();
    expect($payment->cashflowTransaction->id)->toBe($cashflow->id);
});

test('receipt photo url accessor returns correct url or null', function () {
    $founder = User::factory()->create(['role' => 'founder']);

    $project = Project::create([
        'name' => 'Kavling Photo Test',
        'location' => 'Jl. Photo',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'created_by' => $founder->id,
    ]);

    $paymentNoPhoto = ProjectPayment::create([
        'project_id' => $project->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 10000000,
        'payment_method' => 'Cash',
        'created_by' => $founder->id,
    ]);
    expect($paymentNoPhoto->receipt_photo_url)->toBeNull();

    $paymentWithPhoto = ProjectPayment::create([
        'project_id' => $project->id,
        'payment_date' => now()->toDateString(),
        'amount_paid' => 20000000,
        'payment_method' => 'Transfer',
        'receipt_photo_path' => 'project-payments/test-photo.jpg',
        'created_by' => $founder->id,
    ]);
    expect($paymentWithPhoto->receipt_photo_url)->toContain('project-payments/test-photo.jpg');
});
