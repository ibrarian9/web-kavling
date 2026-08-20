<?php

namespace Tests\Feature;

use App\Models\CashflowTransaction;
use App\Models\ExternalProject;
use App\Models\ExternalProjectMaterial;
use App\Models\ExternalProjectWorkerWage;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ExternalProjectsModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->seed(DatabaseSeeder::class);
        Storage::fake('public');
    }

    public function test_founder_can_create_edit_and_delete_external_project(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        // 1. Create External Project
        Livewire::actingAs($founder)
            ->test(\App\Livewire\ExternalProjects\Index::class)
            ->call('openModal')
            ->set('name', 'Renovasi Ruko Sudirman')
            ->set('client_name', 'Bpk. Hendra Wijaya')
            ->set('client_phone', '081299887766')
            ->set('location', 'Jl. Jenderal Sudirman No. 45')
            ->set('contract_value', 75000000)
            ->set('status', 'aktif')
            ->set('start_date', '2026-08-01')
            ->set('notes', 'Renovasi lantai 1 & 2 ruko')
            ->call('save')
            ->assertHasNoErrors();

        $project = ExternalProject::where('name', 'Renovasi Ruko Sudirman')->first();
        $this->assertNotNull($project);
        $this->assertEquals('Bpk. Hendra Wijaya', $project->client_name);
        $this->assertEquals(75000000, (float) $project->contract_value);

        // 2. Edit External Project
        Livewire::actingAs($founder)
            ->test(\App\Livewire\ExternalProjects\Index::class)
            ->call('openModal', $project->id)
            ->set('contract_value', 80000000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals(80000000, (float) $project->fresh()->contract_value);

        // 3. Delete External Project
        Livewire::actingAs($founder)
            ->test(\App\Livewire\ExternalProjects\Index::class)
            ->call('deleteProject', $project->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('external_projects', ['id' => $project->id]);
    }

    public function test_non_founder_cannot_access_external_projects(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        }

        Livewire::actingAs($admin)
            ->test(\App\Livewire\ExternalProjects\Index::class)
            ->assertForbidden();

        $project = ExternalProject::create([
            'name' => 'Proyek Rahasia',
            'status' => 'aktif',
        ]);

        Livewire::actingAs($admin)
            ->test(\App\Livewire\ExternalProjects\Show::class, ['id' => $project->id])
            ->assertForbidden();

        $response = $this->actingAs($admin)->get(route('external-projects.report-pdf', $project->id));
        $response->assertForbidden();
    }

    public function test_can_record_bulk_multi_row_materials_without_affecting_global_cashflow(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        $project = ExternalProject::create([
            'name' => 'Pembangunan Gudang Logistik',
            'client_name' => 'PT. Logistik Maju',
            'location' => 'Kawasan Industri KM 12',
            'contract_value' => 150000000,
            'status' => 'aktif',
            'created_by' => $founder->id,
        ]);

        $initialCashflowCount = CashflowTransaction::count();

        $fakePhoto = UploadedFile::fake()->image('nota_gabungan.jpg');

        // 1. Record Bulk Multi-Row Material Purchases
        Livewire::actingAs($founder)
            ->test(\App\Livewire\ExternalProjects\Show::class, ['id' => $project->id])
            ->call('openMaterialModal')
            ->set('material_purchase_date', '2026-08-10')
            ->set('material_supplier_global', 'Toko Bangunan Berkah')
            ->set('material_receipt_photo', $fakePhoto)
            ->set('material_rows', [
                [
                    'item_name' => 'Semen Padang 50 Kg',
                    'supplier' => 'Toko Bangunan Berkah',
                    'quantity' => 100,
                    'unit' => 'sak',
                    'unit_price' => 65000,
                    'total_price' => 6500000,
                    'notes' => 'Pondasi',
                ],
                [
                    'item_name' => 'Pasir Pasang',
                    'supplier' => 'Toko Bangunan Berkah',
                    'quantity' => 2,
                    'unit' => 'truk',
                    'unit_price' => 850000,
                    'total_price' => 1700000,
                    'notes' => 'Pasir cor',
                ],
            ])
            ->call('saveMaterial')
            ->assertHasNoErrors();

        $materials = ExternalProjectMaterial::where('external_project_id', $project->id)->get();
        $this->assertCount(2, $materials);
        $this->assertEquals(8200000, (float) $materials->sum('total_price'));

        // CRITICAL: Global Cashflow must remain untouched!
        $this->assertEquals($initialCashflowCount, CashflowTransaction::count(), 'External project materials must NOT enter global cashflow.');

        // 2. Edit Material
        $mat1 = $materials->first();
        Livewire::actingAs($founder)
            ->test(\App\Livewire\ExternalProjects\Show::class, ['id' => $project->id])
            ->call('openMaterialModal', $mat1->id)
            ->set('material_rows.0.total_price', 7000000)
            ->call('saveMaterial')
            ->assertHasNoErrors();

        $this->assertEquals(7000000, (float) $mat1->fresh()->total_price);

        // 3. Delete Material
        Livewire::actingAs($founder)
            ->test(\App\Livewire\ExternalProjects\Show::class, ['id' => $project->id])
            ->call('deleteMaterial', $mat1->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('external_project_materials', ['id' => $mat1->id]);
        $this->assertEquals($initialCashflowCount, CashflowTransaction::count());
    }

    public function test_can_record_worker_wages_without_affecting_global_cashflow(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        $project = ExternalProject::create([
            'name' => 'Renovasi Kafe Santai',
            'client_name' => 'Ibu Maya',
            'contract_value' => 30000000,
            'status' => 'aktif',
            'created_by' => $founder->id,
        ]);

        $initialCashflowCount = CashflowTransaction::count();

        $fakePhoto = UploadedFile::fake()->image('kwitansi_upah.jpg');

        // 1. Record Worker Wage
        Livewire::actingAs($founder)
            ->test(\App\Livewire\ExternalProjects\Show::class, ['id' => $project->id])
            ->call('openWageModal')
            ->set('wage_worker_name', 'Pak Budi & Tim')
            ->set('wage_role_type', 'tukang')
            ->set('wage_type', 'mingguan')
            ->set('wage_amount', 3500000)
            ->set('wage_payment_date', '2026-08-15')
            ->set('wage_receipt_photo', $fakePhoto)
            ->set('wage_notes', 'Upah kerja minggu ke-1 pasang keramik')
            ->call('saveWage')
            ->assertHasNoErrors();

        $wage = ExternalProjectWorkerWage::where('external_project_id', $project->id)->first();
        $this->assertNotNull($wage);
        $this->assertEquals('Pak Budi & Tim', $wage->worker_name);
        $this->assertEquals(3500000, (float) $wage->amount);

        // CRITICAL: Global Cashflow must remain untouched!
        $this->assertEquals($initialCashflowCount, CashflowTransaction::count(), 'External project wages must NOT enter global cashflow.');

        // 2. Edit Wage
        Livewire::actingAs($founder)
            ->test(\App\Livewire\ExternalProjects\Show::class, ['id' => $project->id])
            ->call('openWageModal', $wage->id)
            ->set('wage_amount', 4000000)
            ->call('saveWage')
            ->assertHasNoErrors();

        $this->assertEquals(4000000, (float) $wage->fresh()->amount);

        // 3. Delete Wage
        Livewire::actingAs($founder)
            ->test(\App\Livewire\ExternalProjects\Show::class, ['id' => $project->id])
            ->call('deleteWage', $wage->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('external_project_worker_wages', ['id' => $wage->id]);
        $this->assertEquals($initialCashflowCount, CashflowTransaction::count());
    }

    public function test_all_index_buttons_and_modals_function_properly(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        $project = ExternalProject::create([
            'name' => 'Proyek Uji Tombol Index',
            'status' => 'aktif',
            'created_by' => $founder->id,
        ]);

        Livewire::actingAs($founder)
            ->test(\App\Livewire\ExternalProjects\Index::class)
            // Test 1: Open and close create modal
            ->call('openModal')
            ->assertSet('showModal', true)
            ->call('closeModal')
            ->assertSet('showModal', false)
            // Test 2: Open edit modal with ID
            ->call('openModal', $project->id)
            ->assertSet('showModal', true)
            ->assertSet('editingId', $project->id)
            ->call('closeModal')
            ->assertSet('showModal', false)
            // Test 3: PDF Preview Viewer Modal
            ->call('openViewerModal', 'pdf', route('external-projects.report-pdf', $project->id), 'Pratinjau Rekap PDF')
            ->assertSet('showViewerModal', true)
            ->assertSet('viewerType', 'pdf')
            ->call('closeViewerModal')
            ->assertSet('showViewerModal', false)
            // Test 4: Search and filter updates
            ->set('search', 'Uji')
            ->assertSet('search', 'Uji')
            ->set('statusFilter', 'aktif')
            ->assertSet('statusFilter', 'aktif')
            ->assertStatus(200);
    }

    public function test_all_show_page_buttons_and_modals_function_properly(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        $project = ExternalProject::create([
            'name' => 'Proyek Uji Tombol Show',
            'status' => 'aktif',
            'created_by' => $founder->id,
        ]);

        $material = ExternalProjectMaterial::create([
            'external_project_id' => $project->id,
            'item_name' => 'Batu Bata Merah',
            'supplier' => 'UD Bangun',
            'quantity' => 1000,
            'unit' => 'pcs',
            'unit_price' => 800,
            'total_price' => 800000,
            'purchase_date' => '2026-08-01',
            'created_by' => $founder->id,
        ]);

        $wage = ExternalProjectWorkerWage::create([
            'external_project_id' => $project->id,
            'worker_name' => 'Tukang Asep',
            'role_type' => 'tukang',
            'wage_type' => 'harian',
            'amount' => 150000,
            'payment_date' => '2026-08-02',
            'created_by' => $founder->id,
        ]);

        Livewire::actingAs($founder)
            ->test(\App\Livewire\ExternalProjects\Show::class, ['id' => $project->id])
            // Test 1: Tab switcher
            ->set('activeTab', 'materials')
            ->assertSet('activeTab', 'materials')
            ->set('activeTab', 'wages')
            ->assertSet('activeTab', 'wages')
            ->set('activeTab', 'materials')
            // Test 2: Material Multi-Row buttons (+ Tambah Baris, Hapus Baris, Auto Calculation)
            ->call('openMaterialModal')
            ->assertSet('showMaterialModal', true)
            ->assertCount('material_rows', 1)
            ->call('addMaterialRow')
            ->assertCount('material_rows', 2)
            // Auto Calculation on Row 0
            ->set('material_rows.0.quantity', 10)
            ->set('material_rows.0.unit_price', 50000)
            ->assertSet('material_rows.0.total_price', 500000)
            // Remove Row 1
            ->call('removeMaterialRow', 1)
            ->assertCount('material_rows', 1)
            ->set('showMaterialModal', false)
            ->assertSet('showMaterialModal', false)
            // Test 3: Edit Material Modal
            ->call('openMaterialModal', $material->id)
            ->assertSet('showMaterialModal', true)
            ->assertSet('editingMaterialId', $material->id)
            ->set('showMaterialModal', false)
            // Test 4: Wage Modal buttons
            ->call('openWageModal')
            ->assertSet('showWageModal', true)
            ->set('showWageModal', false)
            ->assertSet('showWageModal', false)
            ->call('openWageModal', $wage->id)
            ->assertSet('showWageModal', true)
            ->assertSet('editingWageId', $wage->id)
            ->set('showWageModal', false)
            // Test 5: Media Viewer Modal (PDF & Image Receipt)
            ->call('openViewerModal', 'pdf', route('external-projects.report-pdf', $project->id), 'Pratinjau PDF')
            ->assertSet('showViewerModal', true)
            ->call('closeViewerModal')
            ->assertSet('showViewerModal', false)
            ->call('openViewerModal', 'image', 'http://example.com/nota.jpg', 'Foto Nota')
            ->assertSet('showViewerModal', true)
            ->call('closeViewerModal')
            ->assertSet('showViewerModal', false)
            ->assertStatus(200);
    }

    public function test_external_project_report_pdf_export_streams_200_ok(): void
    {
        $founder = User::where('role', 'founder')->firstOrFail();

        $project = ExternalProject::create([
            'name' => 'Pembangunan Ruko 3 Lantai',
            'client_name' => 'Bpk. Ridwan',
            'contract_value' => 200000000,
            'status' => 'aktif',
            'created_by' => $founder->id,
        ]);

        ExternalProjectMaterial::create([
            'external_project_id' => $project->id,
            'item_name' => 'Besi Beton 12mm',
            'supplier' => 'Toko Besi Jaya',
            'quantity' => 50,
            'unit' => 'btg',
            'unit_price' => 110000,
            'total_price' => 5500000,
            'purchase_date' => '2026-08-10',
            'created_by' => $founder->id,
        ]);

        ExternalProjectWorkerWage::create([
            'external_project_id' => $project->id,
            'worker_name' => 'Mandor Sutrisno',
            'role_type' => 'mandor',
            'wage_type' => 'borongan',
            'amount' => 15000000,
            'payment_date' => '2026-08-12',
            'created_by' => $founder->id,
        ]);

        $response = $this->actingAs($founder)->get(route('external-projects.report-pdf', $project->id));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
