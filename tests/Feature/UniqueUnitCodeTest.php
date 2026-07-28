<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UniqueUnitCodeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_cannot_create_duplicate_unit_code_in_units_index(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $existingUnit = Unit::firstOrFail();

        Livewire::actingAs($founder)
            ->test(\App\Livewire\Units\Index::class)
            ->set('selected_project_id', $existingUnit->project_id)
            ->set('code', $existingUnit->code)
            ->set('category', 'kavling')
            ->set('land_width', 10)
            ->set('land_length', 10)
            ->set('land_area', 100)
            ->call('saveUnit')
            ->assertHasErrors(['code' => 'unique']);
    }

    public function test_cannot_create_duplicate_unit_code_in_legacy_sale(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $existingUnit = Unit::firstOrFail();

        Livewire::actingAs($founder)
            ->test(\App\Livewire\Units\LegacySale::class)
            ->set('project_id', $existingUnit->project_id)
            ->set('code', $existingUnit->code)
            ->set('category', 'kavling')
            ->set('type', 'Kavling Standar')
            ->set('land_width', 10)
            ->set('land_length', 10)
            ->set('land_area', 100)
            ->set('hpp', 100000000)
            ->set('final_selling_price', 150000000)
            ->set('buyer_name', 'Pembeli Duplikat')
            ->set('buyer_phone', '08123456789')
            ->set('sale_date', now()->toDateString())
            ->call('save')
            ->assertHasErrors(['code']);
    }

    public function test_cannot_create_duplicate_unit_code_in_project_detail(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();
        $existingUnit = Unit::firstOrFail();

        Livewire::actingAs($founder)
            ->test(\App\Livewire\Projects\Show::class, ['id' => $existingUnit->project_id])
            ->set('legacy_code', $existingUnit->code)
            ->set('legacy_category', 'kavling')
            ->set('legacy_type', 'Kavling Standar')
            ->set('legacy_land_width', 10)
            ->set('legacy_land_length', 10)
            ->set('legacy_land_area', 100)
            ->set('legacy_hpp', 100000000)
            ->set('legacy_final_selling_price', 150000000)
            ->set('legacy_buyer_name', 'Pembeli Duplikat')
            ->set('legacy_buyer_phone', '08123456789')
            ->set('legacy_sale_date', now()->toDateString())
            ->call('submitLegacySale')
            ->assertHasErrors(['legacy_code']);
    }
}
