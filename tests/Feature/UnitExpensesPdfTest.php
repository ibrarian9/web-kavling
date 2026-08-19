<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\Worker;
use App\Models\WeeklyMaterialPurchase;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
});

test('authenticated user can stream unit expenses summary pdf and material purchase receipt pdf', function () {
    $founder = User::create([
        'name' => 'Founder PDF Test',
        'email' => 'founder_pdf@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Proyek PDF Test',
        'location' => 'Batu, Malang',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'UNIT-PDF-01',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    $worker = Worker::create([
        'name' => 'Mandor Material',
        'phone' => '08123456780',
        'type' => 'mandor',
        'status' => 'active',
        'created_by' => $founder->id,
    ]);

    $material = WeeklyMaterialPurchase::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'pengawas_id' => $founder->id,
        'purchase_date' => now()->toDateString(),
        'item_name' => 'Semen Gresik 50 Sak',
        'quantity' => 50,
        'unit_measure' => 'sak',
        'unit_price' => 65000,
        'total_price' => 3250000,
        'notes' => 'Pembelian material pondasi',
    ]);

    // 1. Test Unit Expenses Summary PDF
    $responseExpenses = $this->actingAs($founder)->get(route('units.expenses-pdf', $unit->id));
    $responseExpenses->assertStatus(200);
    $responseExpenses->assertHeader('content-type', 'application/pdf');

    // 2. Test Material Purchase Item Receipt PDF
    $responseMaterial = $this->actingAs($founder)->get(route('material-purchases.receipt', $material->id));
    $responseMaterial->assertStatus(200);
    $responseMaterial->assertHeader('content-type', 'application/pdf');

    // 3. Test Guest Verification pages
    $responseVerifyExpenses = $this->get(route('verify.unit-expenses', $unit->id));
    $responseVerifyExpenses->assertStatus(200);
    $responseVerifyExpenses->assertSee('UNIT-PDF-01');

    $responseVerifyMaterial = $this->get(route('verify.material-purchase', $material->id));
    $responseVerifyMaterial->assertStatus(200);
    $responseVerifyMaterial->assertSee('Semen Gresik 50 Sak');
});

test('can generate unit expenses pdf even when unit has no expense records yet', function () {
    $founder = User::create([
        'name' => 'Founder Empty Test',
        'email' => 'founder_empty@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Proyek Empty Test',
        'location' => 'Batu, Malang',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    $emptyUnit = Unit::create([
        'project_id' => $project->id,
        'code' => 'UNIT-EMPTY-01',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    $response = $this->actingAs($founder)->get(route('units.expenses-pdf', $emptyUnit->id));
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});
