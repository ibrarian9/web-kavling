<?php

namespace Tests\Feature;

use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CashflowSeparationAndExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_can_filter_cashflow_by_global_project_unit_and_month(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $project = Project::firstOrFail();
        $unit = Unit::firstOrFail();

        CashflowTransaction::create([
            'project_id' => $project->id,
            'type' => 'masuk',
            'category' => 'pembayaran_cicilan_pembeli',
            'amount' => 15000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Setoran Unit ' . $unit->code,
            'created_by' => $founder->id,
        ]);

        Livewire::actingAs($founder)
            ->test(\App\Livewire\Cashflow\Index::class)
            ->set('view_mode', 'unit')
            ->set('filter_project_id', $project->id)
            ->set('filter_unit_id', $unit->id)
            ->set('filter_month', now()->format('Y-m'))
            ->assertSee('Setoran Unit ' . $unit->code);
    }

    public function test_can_export_cashflow_pdf(): void
    {
        $finance = User::where('role', 'finance')->firstOrFail();

        $response = $this->actingAs($finance)->get(route('cashflow.export-pdf', [
            'view_mode' => 'global',
            'month' => now()->format('Y-m'),
        ]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_can_export_excel_csv(): void
    {
        $finance = User::where('role', 'finance')->firstOrFail();

        $response = $this->actingAs($finance)->get(route('cashflow.export-excel', [
            'view_mode' => 'global',
            'month' => now()->format('Y-m'),
        ]));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }

    public function test_can_record_and_filter_non_project_cashflow(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        // 1. Record Non-Project / Corporate Manual Transaction
        Livewire::actingAs($founder)
            ->test(\App\Livewire\Cashflow\Index::class)
            ->set('project_id', '') // Empty string = Non-Proyek
            ->set('type', 'keluar')
            ->set('category', 'operasional')
            ->set('amount', 2500000)
            ->set('description', 'Pembayaran Listrik & Internet Kantor Pusat')
            ->set('transaction_date', now()->toDateString())
            ->call('saveTransaction')
            ->assertHasNoErrors();

        $trx = CashflowTransaction::where('description', 'Pembayaran Listrik & Internet Kantor Pusat')->firstOrFail();
        $this->assertNull($trx->project_id);
        $this->assertEquals(2500000, (float)$trx->amount);

        // 2. Filter by Non-Proyek
        Livewire::actingAs($founder)
            ->test(\App\Livewire\Cashflow\Index::class)
            ->set('filter_project_id', 'non_project')
            ->assertSee('Pembayaran Listrik & Internet Kantor Pusat')
            ->assertSee('Non-Proyek / Kantor Pusat');

        // 3. Edit Non-Project Transaction
        Livewire::actingAs($founder)
            ->test(\App\Livewire\Cashflow\Index::class)
            ->call('editTransaction', $trx->id)
            ->assertSet('edit_project_id', '')
            ->assertSet('edit_description', 'Pembayaran Listrik & Internet Kantor Pusat')
            ->set('edit_amount', 3000000)
            ->call('updateTransaction')
            ->assertHasNoErrors();

        $trx->refresh();
        $this->assertEquals(3000000, (float)$trx->amount);
    }

    public function test_can_open_and_close_modals_cleanly(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        Livewire::actingAs($founder)
            ->test(\App\Livewire\Cashflow\Index::class)
            ->call('openManualModal')
            ->assertSet('showManualModal', true)
            ->set('amount', 500000)
            ->set('description', 'Test Description')
            ->call('closeManualModal')
            ->assertSet('showManualModal', false)
            ->assertSet('amount', 0)
            ->assertSet('description', '');
    }
}
