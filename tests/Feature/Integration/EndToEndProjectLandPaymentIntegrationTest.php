<?php

use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\CashflowTransaction;
use App\Models\User;
use App\Livewire\Projects\Show as ProjectShow;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'founder']);
    Role::firstOrCreate(['name' => 'finance']);
});

test('end to end project land payment workflow: create payment -> cashflow auto recorded -> edit -> delete -> cashflow synced', function () {
    // 1. Setup Users
    $founder = User::create([
        'name' => 'Founder Land E2E',
        'email' => 'founder_land_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $finance = User::create([
        'name' => 'Finance Land E2E',
        'email' => 'finance_land_' . Str::random(5) . '@test.com',
        'password' => bcrypt('password'),
        'role' => 'finance',
        'is_active' => true,
    ]);
    $finance->assignRole('finance');

    // 2. Setup Project with total_project_price
    $project = Project::create([
        'name' => 'Kavling Pembayaran Tanah E2E',
        'location' => 'Jl. Tanah Baru No. 99',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1000000,
        'base_price' => 150000000,
        'total_project_price' => 500000000,
        'created_by' => $founder->id,
    ]);

    // 3. Founder records first land payment
    Livewire::actingAs($founder)
        ->test(ProjectShow::class, ['id' => $project->id])
        ->call('openPaymentModal')
        ->set('payment_amount', 200000000)
        ->set('payment_date', now()->toDateString())
        ->set('payment_method', 'Transfer Bank')
        ->set('payment_notes', 'Pembayaran lahan tahap 1')
        ->call('submitProjectPayment')
        ->assertHasNoErrors();

    // Verify ProjectPayment was created
    $payment1 = ProjectPayment::where('project_id', $project->id)->first();
    expect($payment1)->not->toBeNull();
    expect((float)$payment1->amount_paid)->toBe(200000000.00);
    expect($payment1->uuid)->not->toBeNull();

    // Verify CashflowTransaction was auto-created (Kas Keluar)
    $cashflow1 = CashflowTransaction::where('reference_type', ProjectPayment::class)
        ->where('reference_id', $payment1->id)
        ->first();
    expect($cashflow1)->not->toBeNull();
    expect($cashflow1->type)->toBe('keluar');
    expect((float)$cashflow1->amount)->toBe(200000000.00);

    // 4. Finance records second land payment
    Livewire::actingAs($finance)
        ->test(ProjectShow::class, ['id' => $project->id])
        ->call('openPaymentModal')
        ->set('payment_amount', 150000000)
        ->set('payment_date', now()->toDateString())
        ->set('payment_method', 'Giro / Cek')
        ->set('payment_notes', 'Pembayaran lahan tahap 2')
        ->call('submitProjectPayment')
        ->assertHasNoErrors();

    $totalPayments = ProjectPayment::where('project_id', $project->id)->count();
    expect($totalPayments)->toBe(2);

    $totalPaid = (float) ProjectPayment::where('project_id', $project->id)->sum('amount_paid');
    expect($totalPaid)->toBe(350000000.00);

    // Verify total cashflow keluar for project land payments
    $totalCashflowKeluar = (float) CashflowTransaction::where('reference_type', ProjectPayment::class)
        ->where('project_id', $project->id)
        ->where('type', 'keluar')
        ->sum('amount');
    expect($totalCashflowKeluar)->toBe(350000000.00);

    // 5. Founder edits first payment amount
    Livewire::actingAs($founder)
        ->test(ProjectShow::class, ['id' => $project->id])
        ->call('editProjectPayment', $payment1->id)
        ->set('payment_amount', 250000000)
        ->set('payment_notes', 'Pembayaran lahan tahap 1 (dikoreksi)')
        ->call('submitProjectPayment')
        ->assertHasNoErrors();

    // Verify the cashflow was synced with updated amount
    $updatedCashflow = CashflowTransaction::where('reference_type', ProjectPayment::class)
        ->where('reference_id', $payment1->id)
        ->first();
    expect((float)$updatedCashflow->amount)->toBe(250000000.00);

    // 6. Founder deletes second payment
    $payment2 = ProjectPayment::where('project_id', $project->id)
        ->where('id', '!=', $payment1->id)
        ->first();

    Livewire::actingAs($founder)
        ->test(ProjectShow::class, ['id' => $project->id])
        ->call('deleteProjectPayment', $payment2->id)
        ->assertHasNoErrors();

    // Verify payment was deleted
    expect(ProjectPayment::find($payment2->id))->toBeNull();

    // Verify related cashflow was also deleted
    $deletedCashflow = CashflowTransaction::where('reference_type', ProjectPayment::class)
        ->where('reference_id', $payment2->id)
        ->first();
    expect($deletedCashflow)->toBeNull();

    // 7. Verify final state: only 1 payment remains with corrected amount
    $remainingPayments = ProjectPayment::where('project_id', $project->id)->get();
    expect($remainingPayments)->toHaveCount(1);
    expect((float)$remainingPayments->first()->amount_paid)->toBe(250000000.00);

    // 8. Test PDF receipt streaming route
    $receiptUrl = route('land-payment.receipt', ['uuid' => $payment1->fresh()->uuid]);
    $receiptResponse = $this->actingAs($founder)->get($receiptUrl);
    $receiptResponse->assertStatus(200);

    // 9. Test QR verification route
    $verifyUrl = route('verify.land-payment', ['uuid' => $payment1->fresh()->uuid]);
    $verifyResponse = $this->get($verifyUrl);
    $verifyResponse->assertStatus(200);
});
