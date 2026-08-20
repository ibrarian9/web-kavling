<?php

namespace Tests\Feature;

use App\Livewire\Units\Show;
use App\Models\CashflowTransaction;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\WeeklyMaterialPurchase;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BulkMaterialPurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_can_add_multiple_material_items_in_one_submission(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $unit = Unit::firstOrFail();

        Livewire::actingAs($founder)
            ->test(Show::class, ['id' => $unit->id])
            ->call('openMaterialModal')
            ->assertSet('materialGrandTotal', 0)
            ->set('material_purchase_date', '2026-08-19')
            ->set('material_store_name', 'TB Sinar Abadi')
            ->set('material_payment_status', 'lunas')
            ->set('material_notes', 'Belanja batch material pondasi')
            ->set('materialRows', [
                [
                    'item_name' => 'Semen Gresik 50kg',
                    'quantity' => 20,
                    'unit_measure' => 'sak',
                    'unit_price' => 65000,
                ],
                [
                    'item_name' => 'Pasir Pasang',
                    'quantity' => 2,
                    'unit_measure' => 'truk',
                    'unit_price' => 750000,
                ],
                [
                    'item_name' => 'Batu Belah',
                    'quantity' => 3,
                    'unit_measure' => 'rit',
                    'unit_price' => 500000,
                ],
            ])
            ->call('addMaterialRow')
            ->assertCount('materialRows', 4)
            ->call('removeMaterialRow', 3)
            ->assertCount('materialRows', 3)
            ->call('saveMaterialPurchase')
            ->assertHasNoErrors()
            ->assertSet('showMaterialModal', false);

        // Verify 3 material records created
        $this->assertDatabaseHas('weekly_material_purchases', [
            'unit_id' => $unit->id,
            'item_name' => 'Semen Gresik 50kg',
            'quantity' => 20,
            'unit_price' => 65000,
            'total_price' => 1300000,
            'store_name' => 'TB Sinar Abadi',
            'payment_status' => 'lunas',
        ]);

        $this->assertDatabaseHas('weekly_material_purchases', [
            'unit_id' => $unit->id,
            'item_name' => 'Pasir Pasang',
            'quantity' => 2,
            'unit_price' => 750000,
            'total_price' => 1500000,
            'store_name' => 'TB Sinar Abadi',
            'payment_status' => 'lunas',
        ]);

        $this->assertDatabaseHas('weekly_material_purchases', [
            'unit_id' => $unit->id,
            'item_name' => 'Batu Belah',
            'quantity' => 3,
            'unit_price' => 500000,
            'total_price' => 1500000,
            'store_name' => 'TB Sinar Abadi',
            'payment_status' => 'lunas',
        ]);

        // Verify cashflow transactions created for lunas
        $this->assertDatabaseHas('cashflow_transactions', [
            'reference_type' => WeeklyMaterialPurchase::class,
            'type' => 'keluar',
            'amount' => 1300000,
        ]);
        $this->assertDatabaseHas('cashflow_transactions', [
            'reference_type' => WeeklyMaterialPurchase::class,
            'type' => 'keluar',
            'amount' => 1500000,
        ]);
    }

    public function test_can_edit_existing_single_material_purchase(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $unit = Unit::firstOrFail();

        $mat = WeeklyMaterialPurchase::create([
            'project_id' => $unit->project_id,
            'unit_id' => $unit->id,
            'pengawas_id' => $founder->id,
            'purchase_date' => '2026-08-19',
            'item_name' => 'Besi 10mm',
            'quantity' => 10,
            'unit_measure' => 'batang',
            'unit_price' => 80000,
            'total_price' => 800000,
            'payment_status' => 'lunas',
            'created_by' => $founder->id,
        ]);

        Livewire::actingAs($founder)
            ->test(Show::class, ['id' => $unit->id])
            ->call('editMaterialPurchase', $mat->id)
            ->assertSet('editingMaterialId', $mat->id)
            ->assertSet('materialRows.0.item_name', 'Besi 10mm')
            ->assertSet('materialRows.0.quantity', 10)
            ->assertSet('materialRows.0.unit_price', 80000)
            ->set('materialRows.0.quantity', 15)
            ->set('materialRows.0.unit_price', 85000)
            ->call('saveMaterialPurchase')
            ->assertHasNoErrors()
            ->assertSet('showMaterialModal', false);

        $mat->refresh();
        $this->assertEquals(15, $mat->quantity);
        $this->assertEquals(85000, $mat->unit_price);
        $this->assertEquals(1275000, $mat->total_price);
    }

    public function test_validates_material_rows_properly(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $unit = Unit::firstOrFail();

        Livewire::actingAs($founder)
            ->test(Show::class, ['id' => $unit->id])
            ->call('openMaterialModal')
            ->set('material_purchase_date', '2026-08-19')
            ->set('material_payment_status', 'lunas')
            ->set('materialRows', [
                [
                    'item_name' => '', // missing item name
                    'quantity' => 0,  // invalid quantity
                    'unit_measure' => '',
                    'unit_price' => -100, // invalid price
                ],
            ])
            ->call('saveMaterialPurchase')
            ->assertHasErrors([
                'materialRows.0.item_name',
                'materialRows.0.quantity',
                'materialRows.0.unit_measure',
                'materialRows.0.unit_price',
            ]);
    }
}
