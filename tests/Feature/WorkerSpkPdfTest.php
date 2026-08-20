<?php

use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use App\Models\Worker;
use App\Models\WorkerUnitPayroll;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
});

test('authenticated user can stream Surat Perintah Kerja (SPK) PDF for worker unit payroll', function () {
    $founder = User::create([
        'name' => 'Founder User',
        'email' => 'founder_spk@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);

    $project = Project::create([
        'name' => 'Proyek Perumahan SPK',
        'location' => 'Jl. Proyek No. 1',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1500000,
        'base_price' => 150000000,
        'total_project_price' => 1000000000,
        'status' => 'aktif',
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'SPK-A01',
        'category' => 'rumah',
        'type' => 'Type 45',
        'land_area' => 120,
        'building_area' => 45,
        'hpp' => 150000000,
        'final_selling_price' => 250000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    $worker = Worker::create([
        'name' => 'Mandor Budi Santoso',
        'type' => 'mandor',
        'specialty' => 'Struktur & Bangunan Utama',
        'phone' => '08123456789',
        'address' => 'Jl. Merdeka No. 45',
        'status' => 'active',
    ]);

    $payroll = WorkerUnitPayroll::create([
        'worker_id' => $worker->id,
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'agreed_salary' => 25000000,
        'paid_amount' => 5000000,
        'payment_frequency' => 'mingguan',
        'status' => 'berjalan',
        'notes' => 'Lingkup borongan struktur utama dan pasang dinding unit SPK-A01',
        'created_by' => $founder->id,
    ]);

    $response = $this->actingAs($founder)->get(route('units.payroll.spk-pdf', $payroll->id));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});

test('public guest can verify Surat Perintah Kerja (SPK) via public QR code verification page', function () {
    $founder = User::create([
        'name' => 'Founder QR',
        'email' => 'founder_qr_spk@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);

    $project = Project::create([
        'name' => 'Proyek QR SPK',
        'location' => 'Jl. Proyek No. 2',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1500000,
        'base_price' => 150000000,
        'total_project_price' => 1000000000,
        'status' => 'aktif',
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'QR-01',
        'category' => 'kavling',
        'type' => 'Kavling Standar',
        'land_area' => 100,
        'hpp' => 100000000,
        'final_selling_price' => 150000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    $worker = Worker::create([
        'name' => 'Tukang Slamet',
        'type' => 'tukang',
        'specialty' => 'Keramik',
        'status' => 'active',
    ]);

    $payroll = WorkerUnitPayroll::create([
        'worker_id' => $worker->id,
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'agreed_salary' => 10000000,
        'paid_amount' => 0,
        'payment_frequency' => 'fleksibel',
        'status' => 'berjalan',
        'created_by' => $founder->id,
    ]);

    $response = $this->get(route('verify.worker-spk', $payroll->id));

    $response->assertSee('SURAT PERINTAH KERJA (SPK)');
    $response->assertSee('Tukang Slamet');
    $response->assertSee('UNIT QR-01');
});

test('spk pdf streams successfully even when unit code or worker name contains slashes', function () {
    $founder = User::create([
        'name' => 'Founder Slash Test',
        'email' => 'founder_slash@example.com',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);

    $project = Project::create([
        'name' => 'Proyek Cluster/Grand',
        'location' => 'Jl. Slash No. 9',
        'standard_land_area' => 100,
        'excess_price_per_sqm' => 1500000,
        'base_price' => 150000000,
        'total_project_price' => 1000000000,
        'status' => 'aktif',
        'created_by' => $founder->id,
    ]);

    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'KAV/16',
        'category' => 'rumah',
        'type' => 'Type 36/60',
        'land_area' => 60,
        'building_area' => 36,
        'hpp' => 120000000,
        'final_selling_price' => 200000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    $worker = Worker::create([
        'name' => 'Mandor/Tukang Asep\Budi',
        'type' => 'mandor',
        'specialty' => 'Struktur',
        'status' => 'active',
    ]);

    $payroll = WorkerUnitPayroll::create([
        'worker_id' => $worker->id,
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'agreed_salary' => 15000000,
        'paid_amount' => 0,
        'payment_frequency' => 'mingguan',
        'status' => 'berjalan',
        'created_by' => $founder->id,
    ]);

    $response = $this->actingAs($founder)->get(route('units.payroll.spk-pdf', $payroll->id));

    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/pdf');
});
