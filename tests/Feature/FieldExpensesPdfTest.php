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

test('authenticated user can stream field expenses summary pdf report', function () {
    $founder = User::create([
        'name' => 'Founder Field PDF Test',
        'email' => 'founder_field_pdf@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Proyek Field PDF Test',
        'location' => 'Batu, Malang',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'UNIT-FLD-01',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    $worker = Worker::create([
        'name' => 'Mandor Field',
        'phone' => '08123456789',
        'type' => 'mandor',
        'status' => 'active',
        'created_by' => $founder->id,
    ]);

    WeeklyMaterialPurchase::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'pengawas_id' => $founder->id,
        'purchase_date' => now()->toDateString(),
        'item_name' => 'Batu Kali 1 Truck',
        'quantity' => 1,
        'unit_measure' => 'truck',
        'unit_price' => 1500000,
        'total_price' => 1500000,
        'notes' => 'Pembelian batu kali',
    ]);

    $response = $this->actingAs($founder)->get(route('field-expenses.export-pdf'));
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');

    $responseVerify = $this->get(route('verify.field-expenses'));
    $responseVerify->assertStatus(200);
    $responseVerify->assertSee('Laporan Operasional Belanja');
});

test('cannot generate field expenses pdf report when no transactions match filter', function () {
    $founder = User::create([
        'name' => 'Founder Empty Field Test',
        'email' => 'founder_empty_field@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $response = $this->actingAs($founder)->get(route('field-expenses.export-pdf', ['search' => 'NONEXISTENT_ITEM_XYZ']));
    $response->assertRedirect();
    $response->assertSessionHas('error');
});

test('field expenses livewire index supports date period filter and search', function () {
    $founder = User::create([
        'name' => 'Founder Livewire Test',
        'email' => 'founder_livewire_field@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);
    $founder->assignRole('founder');

    $project = Project::create([
        'name' => 'Proyek Field Livewire Test',
        'location' => 'Batu, Malang',
        'land_purchase_price' => 100000000,
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 500000,
        'base_price' => 120000000,
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'UNIT-FLD-LW',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    $worker = Worker::create([
        'name' => 'Mandor Bambang',
        'phone' => '08123456788',
        'type' => 'mandor',
        'status' => 'active',
        'created_by' => $founder->id,
    ]);

    WeeklyMaterialPurchase::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'worker_id' => $worker->id,
        'pengawas_id' => $founder->id,
        'purchase_date' => now()->toDateString(),
        'item_name' => 'Semen Gresik 50 Sak',
        'quantity' => 50,
        'unit_measure' => 'sak',
        'unit_price' => 70000,
        'total_price' => 3500000,
        'notes' => 'Semen untuk pondasi',
    ]);

    \Livewire\Livewire::actingAs($founder)
        ->test(\App\Livewire\FieldExpenses\Index::class)
        ->assertStatus(200)
        ->assertSee('Semen Gresik 50 Sak')
        ->set('datePeriod', 'today')
        ->assertSee('Semen Gresik 50 Sak')
        ->set('search', 'Semen')
        ->assertSee('Semen Gresik 50 Sak')
        ->set('search', 'BarangYangTidakAdaXYZ')
        ->assertDontSee('Semen Gresik 50 Sak');

    // Also test PDF export with date_period param
    $responsePdf = $this->actingAs($founder)->get(route('field-expenses.export-pdf', [
        'date_period' => 'today',
        'search' => 'Semen',
    ]));
    $responsePdf->assertStatus(200);
    $responsePdf->assertHeader('content-type', 'application/pdf');
});
