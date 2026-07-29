<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\User;
use App\Models\Worker;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ContractorAndInstallmentCancellationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_can_register_and_filter_kontraktor_worker(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        Livewire::actingAs($founder)
            ->test(\App\Livewire\Workers\Index::class)
            ->set('name', 'PT Kontraktor Jaya')
            ->set('type', 'kontraktor')
            ->set('phone', '081299990000')
            ->set('specialty', 'Struktur & Pondasi')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('workers', [
            'name' => 'PT Kontraktor Jaya',
            'type' => 'kontraktor',
            'specialty' => 'Struktur & Pondasi',
        ]);
    }

    public function test_founder_and_finance_can_cancel_installment_and_convert_to_cash(): void
    {
        $finance = User::where('role', 'finance')->firstOrFail();
        $unit = Unit::firstOrFail();

        $installment = UnitInstallment::create([
            'unit_id' => $unit->id,
            'total_price' => 150000000,
            'down_payment' => 30000000,
            'installment_count' => 12,
            'installment_amount' => 10000000,
            'start_date' => now()->toDateString(),
            'status' => 'berjalan',
        ]);

        Livewire::actingAs($finance)
            ->test(\App\Livewire\Installments\Index::class)
            ->call('openConvertToCashModal', $installment->id)
            ->set('cash_payment_amount', 120000000)
            ->set('cash_payment_method', 'Transfer Bank')
            ->set('cash_notes', 'Pembatalan cicilan dan pelunasan cash oleh pembeli')
            ->call('submitConvertToCash')
            ->assertHasNoErrors();

        $installment->refresh();
        $this->assertEquals('konversi_cash', $installment->status);
        $this->assertEquals(150000000, $installment->total_paid);

        $this->assertDatabaseHas('cashflow_transactions', [
            'project_id' => $unit->project_id,
            'type' => 'masuk',
            'amount' => 120000000,
        ]);
    }
}
