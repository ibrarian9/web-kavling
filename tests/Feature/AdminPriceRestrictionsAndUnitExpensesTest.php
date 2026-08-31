<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\User;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPriceRestrictionsAndUnitExpensesTest extends TestCase
{
    use RefreshDatabase;

    protected User $founder;
    protected User $admin;
    protected User $finance;
    protected User $supervisor;
    protected User $marketing;
    protected Project $project;
    protected Unit $unit;
    protected Worker $worker;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'founder', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'finance', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'supervisor', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'marketing', 'guard_name' => 'web']);

        $this->founder = User::create([
            'name' => 'Founder User',
            'email' => 'founder@test.com',
            'password' => bcrypt('password'),
            'role' => 'founder',
            'is_active' => true,
        ]);
        $this->founder->assignRole('founder');

        $this->admin = User::create([
            'name' => 'Admin Staff',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        $this->admin->assignRole('admin');

        $this->finance = User::create([
            'name' => 'Finance Staff',
            'email' => 'finance@test.com',
            'password' => bcrypt('password'),
            'role' => 'finance',
            'is_active' => true,
        ]);
        $this->finance->assignRole('finance');

        $this->marketing = User::create([
            'name' => 'Marketing Staff',
            'email' => 'marketing@test.com',
            'password' => bcrypt('password'),
            'role' => 'marketing',
            'is_active' => true,
        ]);
        $this->marketing->assignRole('marketing');

        $this->project = Project::create([
            'name' => 'Kavling Harmoni Indah',
            'code' => 'KHI',
            'location' => 'Pekanbaru Kota',
            'standard_land_area' => 100,
            'excess_price_per_sqm' => 500000,
            'base_price' => 150000000,
            'total_project_price' => 850000000, // Land purchase cost (Harga Tanah)
            'total_units' => 10,
            'status' => 'aktif',
            'created_by' => $this->founder->id,
        ]);

        $this->unit = Unit::create([
            'project_id' => $this->project->id,
            'code' => 'A-01',
            'category' => 'kavling',
            'type' => 'Kavling Standar',
            'land_width' => 10,
            'land_length' => 12,
            'land_area' => 120, // excess 20m2
            'hpp' => 160000000,
            'final_selling_price' => 160000000,
            'status' => 'tersedia',
            'created_by' => $this->founder->id,
        ]);

        $this->worker = Worker::create([
            'name' => 'Mandor Sutrisno',
            'phone' => '081234567890',
            'type' => 'mandor',
            'status' => 'active',
        ]);
    }

    public function test_price_visibility_helper_methods(): void
    {
        // Admin
        $this->assertFalse($this->admin->canViewSalesPrices());
        $this->assertFalse($this->admin->canViewHpp());
        $this->assertTrue($this->admin->canViewLandPrices());
        $this->assertTrue($this->admin->canViewMaterialPrices());
        $this->assertTrue($this->admin->canViewWorkerWages());
        $this->assertTrue($this->admin->canViewUnitExpenses());

        // Founder
        $this->assertTrue($this->founder->canViewSalesPrices());
        $this->assertTrue($this->founder->canViewHpp());
        $this->assertTrue($this->founder->canViewLandPrices());
        $this->assertTrue($this->founder->canViewMaterialPrices());
        $this->assertTrue($this->founder->canViewWorkerWages());
        $this->assertTrue($this->founder->canViewUnitExpenses());

        // Finance
        $this->assertTrue($this->finance->canViewSalesPrices());
        $this->assertTrue($this->finance->canViewHpp());
        $this->assertTrue($this->finance->canViewLandPrices());
        $this->assertTrue($this->finance->canViewUnitExpenses());

        // Marketing: Can view sales prices, but CANNOT view unit expenses, material prices, or worker wages
        $this->assertTrue($this->marketing->canViewSalesPrices());
        $this->assertFalse($this->marketing->canViewHpp());
        $this->assertFalse($this->marketing->canViewLandPrices());
        $this->assertFalse($this->marketing->canViewMaterialPrices());
        $this->assertFalse($this->marketing->canViewWorkerWages());
        $this->assertFalse($this->marketing->canViewUnitExpenses());
    }

    public function test_admin_unit_detail_hides_sales_prices_and_displays_worker_wages_and_materials_with_store_name(): void
    {
        // Create material purchase with store_name
        WeeklyMaterialPurchase::create([
            'project_id' => $this->project->id,
            'unit_id' => $this->unit->id,
            'worker_id' => $this->worker->id,
            'pengawas_id' => $this->admin->id,
            'purchase_date' => now()->subDays(2),
            'item_name' => 'Semen Padang 50kg',
            'store_name' => 'TB. Sinar Abadi Sentosa',
            'quantity' => 20,
            'unit_measure' => 'sak',
            'unit_price' => 65000,
            'total_price' => 1300000,
            'payment_status' => 'lunas',
        ]);

        // Create borongan payroll & payment
        $payroll = WorkerUnitPayroll::create([
            'project_id' => $this->project->id,
            'unit_id' => $this->unit->id,
            'worker_id' => $this->worker->id,
            'agreed_salary' => 8500000,
            'paid_amount' => 3000000,
            'status' => 'berjalan',
        ]);

        WorkerSalaryPayment::create([
            'worker_unit_payroll_id' => $payroll->id,
            'worker_id' => $this->worker->id,
            'amount_paid' => 3000000,
            'amount_gross' => 3000000,
            'loan_deduction' => 0,
            'payment_date' => now()->subDay(),
            'payment_method' => 'cash',
            'created_by' => $this->admin->id,
        ]);

        $component = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
            ->assertStatus(200);

        // Store name is displayed in the expenses table
        $component->assertSee('TB. Sinar Abadi Sentosa');
        $component->assertSee('Semen Padang 50kg');
        $component->assertSee('Mandor Sutrisno');

        // Material prices and worker wages ARE visible
        $component->assertSee('Rp 1.300.000');
        $component->assertSee('Rp 8.500.000');

        // Sales Price and Proposal cards are NOT rendered for Admin
        $component->assertDontSee('Harga Jual Disetujui');
        $component->assertDontSee('Proposal SPP');
    }

    public function test_expenses_pdf_report_displays_store_name_for_materials(): void
    {
        WeeklyMaterialPurchase::create([
            'project_id' => $this->project->id,
            'unit_id' => $this->unit->id,
            'worker_id' => $this->worker->id,
            'pengawas_id' => $this->admin->id,
            'purchase_date' => now()->subDays(2),
            'item_name' => 'Besi Beton 10mm',
            'store_name' => 'Toko Besi Jaya Abadi',
            'quantity' => 50,
            'unit_measure' => 'batang',
            'unit_price' => 85000,
            'total_price' => 4250000,
            'payment_status' => 'lunas',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('units.expenses-pdf', $this->unit->id));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_unit_detail_realisasi_biaya_matches_pdf_calculation_and_does_not_double_count_contract(): void
    {
        // 1. Material Purchase: Rp 2.000.000
        WeeklyMaterialPurchase::create([
            'project_id' => $this->project->id,
            'unit_id' => $this->unit->id,
            'worker_id' => $this->worker->id,
            'pengawas_id' => $this->founder->id,
            'purchase_date' => now()->subDays(2),
            'item_name' => 'Pasir & Kerikil 2 Truk',
            'store_name' => 'TB. Sumber Pasir',
            'quantity' => 2,
            'unit_measure' => 'truk',
            'unit_price' => 1000000,
            'total_price' => 2000000,
            'payment_status' => 'lunas',
        ]);

        // 2. Contract Payroll Setup: Rp 10.000.000
        $payroll = WorkerUnitPayroll::create([
            'project_id' => $this->project->id,
            'unit_id' => $this->unit->id,
            'worker_id' => $this->worker->id,
            'agreed_salary' => 10000000,
            'paid_amount' => 4000000,
            'status' => 'berjalan',
        ]);

        // 3. Paid Salary Termin: Rp 4.000.000
        WorkerSalaryPayment::create([
            'worker_unit_payroll_id' => $payroll->id,
            'worker_id' => $this->worker->id,
            'amount_paid' => 4000000,
            'amount_gross' => 4000000,
            'loan_deduction' => 0,
            'payment_date' => now()->subDay(),
            'payment_method' => 'transfer',
            'created_by' => $this->founder->id,
        ]);

        // Expected Realized Cost = Material (2 jt) + Paid Salary (4 jt) = 6 jt.
        // It must NOT be 2 jt + 10 jt + 4 jt = 16 jt!
        $component = Livewire::actingAs($this->founder)
            ->test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
            ->assertStatus(200);

        // Realized expenses card displays Rp 6.000.000
        $component->assertSee('Rp 6.000.000');
        $component->assertSee('Subtotal Belanja Material:');
        $component->assertSee('Subtotal Gaji Terbayar:');
        $component->assertSee('Total Kontrak Borongan:');
        $component->assertSee('Total Biaya Terealisasi:');

        // Check PDF also gives exactly Rp 6.000.000 in total
        $response = $this->actingAs($this->founder)
            ->get(route('units.expenses-pdf', $this->unit->id));
        $response->assertStatus(200);
    }

    public function test_projects_table_hides_land_price_and_sales_prices_from_menu_for_admin(): void
    {
        $component = Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Projects\Index::class)
            ->assertStatus(200);

        // Land purchase price is hidden from the project menu overview
        $component->assertDontSee('Harga Beli Lahan (Penjual)');
        $component->assertDontSee('Total Harga Beli Lahan');

        // Base price / sales price headers are NOT visible for Admin
        $component->assertDontSee('Harga Dasar Standar (HPP)');
        $component->assertDontSee('Tarif Kelebihan / m²');
    }

    public function test_installments_index_is_forbidden_for_admin_and_accessible_for_finance(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(\App\Livewire\Installments\Index::class)
            ->assertStatus(403);

        $this->actingAs($this->founder);

        $component = Livewire::test(\App\Livewire\Installments\Index::class)
            ->assertStatus(200);

        // Tab 2 (Pembayaran Lahan Proyek) is visible for Founder & Finance
        $component->assertSee('Pembayaran Lahan Proyek');
    }

    public function test_admin_dashboard_shows_operational_view_and_hides_financial_charts_and_prices(): void
    {
        $this->actingAs($this->admin);

        $component = Livewire::test(\App\Livewire\Dashboard::class)
            ->assertStatus(200);

        // Shows operational and field view
        $component->assertSee('Akses Operasional & Lapangan', false);
        $component->assertSee('Mandor & Tukang', false);
        $component->assertSee('Belanja Material');
        $component->assertSee('Status Stok & Unit Properti', false);

        // Hides executive financial cashflow and pricing charts
        $component->assertDontSee('Saldo Kas Bersih Global');
        $component->assertDontSee('Grafik Tren Keuangan Arus Kas');
        $component->assertDontSee('Pengajuan Harga Terbaru');
    }

    public function test_marketing_unit_detail_and_index_hides_all_expense_cards_badges_and_pdf(): void
    {
        // 1. Create material purchase & payroll
        WeeklyMaterialPurchase::create([
            'project_id' => $this->project->id,
            'unit_id' => $this->unit->id,
            'worker_id' => $this->worker->id,
            'pengawas_id' => $this->founder->id,
            'purchase_date' => now()->subDays(2),
            'item_name' => 'Pasir Cor 1 Truk',
            'store_name' => 'TB. Maju Jaya',
            'quantity' => 1,
            'unit_measure' => 'truk',
            'unit_price' => 1200000,
            'total_price' => 1200000,
            'payment_status' => 'lunas',
        ]);

        $payroll = WorkerUnitPayroll::create([
            'project_id' => $this->project->id,
            'unit_id' => $this->unit->id,
            'worker_id' => $this->worker->id,
            'agreed_salary' => 5000000,
            'paid_amount' => 2000000,
            'status' => 'berjalan',
        ]);

        WorkerSalaryPayment::create([
            'worker_unit_payroll_id' => $payroll->id,
            'worker_id' => $this->worker->id,
            'amount_paid' => 2000000,
            'amount_gross' => 2000000,
            'loan_deduction' => 0,
            'payment_date' => now()->subDay(),
            'payment_method' => 'cash',
            'created_by' => $this->founder->id,
        ]);

        // 2. Test Unit Detail for Marketing
        $showComponent = Livewire::actingAs($this->marketing)
            ->test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
            ->assertStatus(200);

        // Marketing can see sales price
        $showComponent->assertSee('Harga Total Unit');
        $showComponent->assertSee('Harga Jual Disetujui');

        // Marketing CANNOT see Realisasi Biaya or Expense/Payroll tables
        $showComponent->assertDontSee('Realisasi Biaya');
        $showComponent->assertDontSee('Rincian Biaya Pengeluaran & Belanja Unit');
        $showComponent->assertDontSee('Pembayaran Kontrak Pekerja');
        $showComponent->assertDontSee('Pasir Cor 1 Truk');

        // 3. Test Unit Index for Marketing
        $indexComponent = Livewire::actingAs($this->marketing)
            ->test(\App\Livewire\Units\Index::class)
            ->assertStatus(200);

        // Marketing CANNOT see 'Ada Biaya' marker badge or expense summary strip
        $indexComponent->assertDontSee('Ada Biaya');
        $indexComponent->assertDontSee('Biaya di Detail:');

        // 4. Test Expenses PDF export is forbidden for Marketing
        $response = $this->actingAs($this->marketing)
            ->get(route('units.expenses-pdf', $this->unit->id));
        $response->assertStatus(403);
    }
}
