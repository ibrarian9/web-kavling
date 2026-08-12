<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\InstallmentPayment;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\Unit;
use App\Models\UnitInstallment;
use App\Models\User;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use App\Services\CascadeDeletionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CashflowSyncAndDeleteTest extends TestCase
{
    use RefreshDatabase;

    protected User $founder;
    protected Project $project;
    protected Unit $unit;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'founder', 'guard_name' => 'web']);

        $this->founder = User::create([
            'name' => 'Founder Executive',
            'email' => 'founder_sync@test.com',
            'password' => bcrypt('password'),
            'role' => 'founder',
            'is_active' => true,
        ]);
        $this->founder->assignRole('founder');

        $this->project = Project::create([
            'name' => 'Proyek Cluster E-7',
            'location' => 'Pekanbaru Suburb',
            'standard_land_area' => 100,
            'excess_price_per_sqm' => 1000000,
            'base_price' => 150000000,
            'total_project_price' => 400000000,
            'status' => 'aktif',
            'created_by' => $this->founder->id,
        ]);

        $this->unit = Unit::create([
            'project_id' => $this->project->id,
            'code' => 'E-7',
            'category' => 'kavling',
            'type' => 'kavling',
            'land_width' => 10,
            'land_length' => 10,
            'land_area' => 100,
            'hpp' => 150000000,
            'status' => 'booked',
            'created_by' => $this->founder->id,
        ]);
    }

    public function test_deleting_unit_e7_cascades_and_removes_booking_fee_from_global_cashflow(): void
    {
        $this->actingAs($this->founder);

        // 1. Create Booking & Cashflow Transaction
        $booking = Booking::create([
            'project_id' => $this->project->id,
            'unit_id' => $this->unit->id,
            'buyer_name' => 'Pembeli Unit E7',
            'buyer_phone' => '08123456789',
            'booking_amount' => 5000000,
            'booking_date' => now()->toDateString(),
            'status' => 'active',
            'created_by' => $this->founder->id,
        ]);

        $cashflowBooking = CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'masuk',
            'category' => 'booking_fee',
            'amount' => 5000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Booking Fee Unit E-7',
            'reference_type' => Booking::class,
            'reference_id' => $booking->id,
            'created_by' => $this->founder->id,
        ]);

        // 2. Create Installment & Installment Payment
        $installment = UnitInstallment::create([
            'unit_id' => $this->unit->id,
            'total_price' => 150000000,
            'down_payment' => 30000000,
            'installment_count' => 12,
            'installment_amount' => 10000000,
            'start_date' => now()->toDateString(),
            'status' => 'berjalan',
        ]);

        $payment = InstallmentPayment::create([
            'unit_installment_id' => $installment->id,
            'payment_date' => now()->toDateString(),
            'amount_paid' => 10000000,
            'payment_method' => 'Transfer Bank',
            'notes' => 'Setoran ke-1 Unit E-7',
            'created_by' => $this->founder->id,
        ]);

        $cashflowInstallment = CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'masuk',
            'category' => 'pembayaran_cicilan_pembeli',
            'amount' => 10000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Setoran Cicilan Unit E-7',
            'reference_type' => InstallmentPayment::class,
            'reference_id' => $payment->id,
            'created_by' => $this->founder->id,
        ]);

        // Verify initial state
        $this->assertEquals(2, CashflowTransaction::count());
        $this->assertEquals(1, Booking::count());
        $this->assertEquals(1, InstallmentPayment::count());

        // 3. Delete Unit E-7 via Livewire Show component
        Livewire::test(\App\Livewire\Units\Show::class, ['id' => $this->unit->id])
            ->call('deleteUnit')
            ->assertHasNoErrors();

        // 4. Assert Unit E-7, Booking, Installment & Cashflow Transactions are 100% Wiped!
        $this->assertNull(Unit::find($this->unit->id));
        $this->assertEquals(0, Booking::count());
        $this->assertEquals(0, UnitInstallment::count());
        $this->assertEquals(0, InstallmentPayment::count());
        $this->assertEquals(0, CashflowTransaction::count());
    }

    public function test_deleting_unit_cascades_upah_and_material_purchases_from_global_cashflow(): void
    {
        $this->actingAs($this->founder);

        $worker = Worker::create([
            'name' => 'Tukang Joni',
            'type' => 'tukang',
            'status' => 'active',
        ]);

        // 1. Create Worker Unit Payroll & Salary Payment
        $payroll = WorkerUnitPayroll::create([
            'project_id' => $this->project->id,
            'worker_id' => $worker->id,
            'unit_id' => $this->unit->id,
            'work_description' => 'Pekerjaan Dinding E-7',
            'agreed_salary' => 10000000,
            'status' => 'berjalan',
        ]);

        $salaryPayment = WorkerSalaryPayment::create([
            'worker_unit_payroll_id' => $payroll->id,
            'amount_paid' => 2000000,
            'payment_date' => now()->toDateString(),
            'notes' => 'Panjar Dinding E-7',
            'created_by' => $this->founder->id,
        ]);

        $cashflowUpah = CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'keluar',
            'category' => 'upah_tukang',
            'amount' => 2000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Pembayaran Upah E-7',
            'reference_type' => WorkerSalaryPayment::class,
            'reference_id' => $salaryPayment->id,
            'created_by' => $this->founder->id,
        ]);

        // 2. Create Weekly Material Purchase
        $material = WeeklyMaterialPurchase::create([
            'project_id' => $this->project->id,
            'unit_id' => $this->unit->id,
            'worker_id' => $worker->id,
            'pengawas_id' => $this->founder->id,
            'item_name' => 'Semen 50 sak',
            'quantity' => 50,
            'unit_type' => 'sak',
            'unit_price' => 60000,
            'total_price' => 3000000,
            'purchase_date' => now()->toDateString(),
            'created_by' => $this->founder->id,
        ]);

        $cashflowMaterial = CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'keluar',
            'category' => 'material',
            'amount' => 3000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Pembelian Semen E-7',
            'reference_type' => WeeklyMaterialPurchase::class,
            'reference_id' => $material->id,
            'created_by' => $this->founder->id,
        ]);

        $this->assertEquals(2, CashflowTransaction::count());

        // 3. Delete Unit E-7 via CascadeDeletionService
        CascadeDeletionService::deleteUnit($this->unit);

        // 4. Assert Upah, Material, and Cashflow Transactions are 100% Wiped!
        $this->assertEquals(0, WorkerUnitPayroll::count());
        $this->assertEquals(0, WorkerSalaryPayment::count());
        $this->assertEquals(0, WeeklyMaterialPurchase::count());
        $this->assertEquals(0, CashflowTransaction::count());
    }

    public function test_deleting_project_cascades_all_units_and_project_land_payments_from_global_cashflow(): void
    {
        $this->actingAs($this->founder);

        // 1. Create Project Payment (Land Payment)
        $projectPayment = ProjectPayment::create([
            'project_id' => $this->project->id,
            'payment_date' => now()->toDateString(),
            'amount_paid' => 50000000,
            'payment_method' => 'Transfer Bank',
            'notes' => 'Pembayaran DP Lahan Proyek',
            'created_by' => $this->founder->id,
        ]);

        $cashflowLand = CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'keluar',
            'category' => 'pembelian_lahan',
            'amount' => 50000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Pembayaran Lahan Proyek',
            'reference_type' => ProjectPayment::class,
            'reference_id' => $projectPayment->id,
            'created_by' => $this->founder->id,
        ]);

        // 2. Create Unit Booking for Project
        $booking = Booking::create([
            'project_id' => $this->project->id,
            'unit_id' => $this->unit->id,
            'buyer_name' => 'Pembeli Proyek',
            'buyer_phone' => '081299998888',
            'booking_amount' => 10000000,
            'booking_date' => now()->toDateString(),
            'status' => 'active',
            'created_by' => $this->founder->id,
        ]);

        $cashflowBooking = CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'masuk',
            'category' => 'booking_fee',
            'amount' => 10000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Booking Fee Proyek',
            'reference_type' => Booking::class,
            'reference_id' => $booking->id,
            'created_by' => $this->founder->id,
        ]);

        $this->assertEquals(2, CashflowTransaction::count());
        $this->assertEquals(1, ProjectPayment::count());

        // 3. Delete Project via Projects/Index component
        Livewire::test(\App\Livewire\Projects\Index::class)
            ->call('deleteProject', $this->project->id)
            ->assertHasNoErrors();

        // 4. Assert Project, Units, Project Payments, & Cashflows are 100% Wiped!
        $this->assertNull(Project::find($this->project->id));
        $this->assertEquals(0, Unit::count());
        $this->assertEquals(0, ProjectPayment::count());
        $this->assertEquals(0, CashflowTransaction::count());
    }

    public function test_deleting_unit_cascades_spp_official_documents_proposals_and_approvals(): void
    {
        $this->actingAs($this->founder);

        // 1. Create Price Proposal & Approval
        $proposal = \App\Models\PriceProposal::create([
            'unit_id' => $this->unit->id,
            'buyer_name' => 'Calon Pembeli SPP',
            'hpp_price' => 150000000,
            'proposed_price' => 160000000,
            'margin' => 10000000,
            'payment_scheme' => 'cicilan',
            'status' => 'disetujui',
            'proposed_by' => $this->founder->id,
        ]);

        $approval = \App\Models\Approval::create([
            'price_proposal_id' => $proposal->id,
            'approver_id' => $this->founder->id,
            'status' => 'disetujui',
            'approved_at' => now(),
        ]);

        // 2. Create Official Document (Surat SPP / SPJB)
        $officialDoc = \App\Models\OfficialDocument::create([
            'unit_id' => $this->unit->id,
            'price_proposal_id' => $proposal->id,
            'document_number' => 'SPP/2026/08/001',
            'buyer_name' => 'Calon Pembeli SPP',
            'buyer_nik' => '1471012304850009',
            'buyer_contact' => '08123456789',
            'buyer_address' => 'Pekanbaru Center',
            'issued_by' => $this->founder->id,
            'issued_at' => now(),
        ]);

        $this->assertEquals(1, \App\Models\PriceProposal::count());
        $this->assertEquals(1, \App\Models\Approval::count());
        $this->assertEquals(1, \App\Models\OfficialDocument::count());

        // 3. Delete Unit E-7 via CascadeDeletionService
        CascadeDeletionService::deleteUnit($this->unit);

        // 4. Assert Unit, Proposals, Approvals, & Official Document (Surat SPP) are 100% Wiped!
        $this->assertNull(Unit::find($this->unit->id));
        $this->assertEquals(0, \App\Models\PriceProposal::count());
        $this->assertEquals(0, \App\Models\Approval::count());
        $this->assertEquals(0, \App\Models\OfficialDocument::count());
    }

    public function test_orphan_cashflow_auditor_detects_and_purges_orphan_records(): void
    {
        // Create an intentional orphan transaction (pointing to non-existent Booking ID 99999)
        $orphanTx = CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'masuk',
            'category' => 'booking_fee',
            'amount' => 5000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Orphan Transaction Test',
            'reference_type' => Booking::class,
            'reference_id' => 99999,
            'created_by' => $this->founder->id,
        ]);

        // Audit check
        $orphans = \App\Services\OrphanCashflowAuditor::audit();
        $this->assertCount(1, $orphans);
        $this->assertEquals($orphanTx->id, $orphans[0]['id']);

        // Purge check via Artisan Command
        $this->artisan('cashflow:audit-orphans', ['--fix' => true])
            ->expectsConfirmation('Apakah Anda yakin ingin menghapus seluruh orphan record di atas dari Arus Kas Global?', 'yes')
            ->assertExitCode(0);

        // Verify orphan record is gone
        $this->assertCount(0, \App\Services\OrphanCashflowAuditor::audit());
        $this->assertNull(CashflowTransaction::find($orphanTx->id));
    }

    public function test_fatal_scenario_extreme_multi_entity_interconnected_unit_deletion(): void
    {
        $this->actingAs($this->founder);

        // 1. Create Unit X-99
        $unitX = Unit::create([
            'project_id' => $this->project->id,
            'code' => 'X-99',
            'category' => 'kavling',
            'type' => 'kavling',
            'land_width' => 10,
            'land_length' => 10,
            'land_area' => 100,
            'hpp' => 200000000,
            'status' => 'booked',
            'created_by' => $this->founder->id,
        ]);

        // 2. Create Multiple Bookings
        $b1 = Booking::create([
            'project_id' => $this->project->id,
            'unit_id' => $unitX->id,
            'buyer_name' => 'Pembeli Lama (Cancelled)',
            'buyer_phone' => '0811111111',
            'booking_amount' => 5000000,
            'booking_date' => now()->subMonth()->toDateString(),
            'status' => 'cancelled',
            'created_by' => $this->founder->id,
        ]);

        $b2 = Booking::create([
            'project_id' => $this->project->id,
            'unit_id' => $unitX->id,
            'buyer_name' => 'Pembeli Baru (Active)',
            'buyer_phone' => '0822222222',
            'booking_amount' => 5000000,
            'booking_date' => now()->toDateString(),
            'status' => 'active',
            'created_by' => $this->founder->id,
        ]);

        CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'masuk',
            'category' => 'booking_fee',
            'amount' => 5000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Booking Fee X-99',
            'reference_type' => Booking::class,
            'reference_id' => $b2->id,
            'created_by' => $this->founder->id,
        ]);

        // 3. Create UnitInstallment & 2 InstallmentPayments + Cashflows
        $inst = UnitInstallment::create([
            'unit_id' => $unitX->id,
            'total_price' => 250000000,
            'down_payment' => 50000000,
            'installment_count' => 10,
            'installment_amount' => 20000000,
            'start_date' => now()->toDateString(),
            'status' => 'berjalan',
        ]);

        $pay1 = InstallmentPayment::create([
            'unit_installment_id' => $inst->id,
            'payment_date' => now()->subDays(10)->toDateString(),
            'amount_paid' => 20000000,
            'payment_method' => 'Transfer Bank',
            'created_by' => $this->founder->id,
        ]);

        $pay2 = InstallmentPayment::create([
            'unit_installment_id' => $inst->id,
            'payment_date' => now()->toDateString(),
            'amount_paid' => 20000000,
            'payment_method' => 'Transfer Bank',
            'created_by' => $this->founder->id,
        ]);

        CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'masuk',
            'category' => 'pembayaran_cicilan_pembeli',
            'amount' => 20000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Setoran 1 X-99',
            'reference_type' => InstallmentPayment::class,
            'reference_id' => $pay1->id,
            'created_by' => $this->founder->id,
        ]);

        CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'masuk',
            'category' => 'pembayaran_cicilan_pembeli',
            'amount' => 20000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Setoran 2 X-99',
            'reference_type' => InstallmentPayment::class,
            'reference_id' => $pay2->id,
            'created_by' => $this->founder->id,
        ]);

        // 4. Create Worker Payroll & Salary Payment + Upah Cashflow
        $worker = Worker::create(['name' => 'Tukang X', 'type' => 'tukang', 'status' => 'active']);
        $payroll = WorkerUnitPayroll::create([
            'project_id' => $this->project->id,
            'worker_id' => $worker->id,
            'unit_id' => $unitX->id,
            'work_description' => 'Pondasi X-99',
            'agreed_salary' => 15000000,
            'status' => 'berjalan',
        ]);

        $salPay = WorkerSalaryPayment::create([
            'worker_unit_payroll_id' => $payroll->id,
            'amount_paid' => 5000000,
            'payment_date' => now()->toDateString(),
            'created_by' => $this->founder->id,
        ]);

        CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'keluar',
            'category' => 'upah_tukang',
            'amount' => 5000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Upah Pondasi X-99',
            'reference_type' => WorkerSalaryPayment::class,
            'reference_id' => $salPay->id,
            'created_by' => $this->founder->id,
        ]);

        // 5. Create Weekly Material Purchase + Material Cashflow
        $mat = WeeklyMaterialPurchase::create([
            'project_id' => $this->project->id,
            'unit_id' => $unitX->id,
            'worker_id' => $worker->id,
            'pengawas_id' => $this->founder->id,
            'item_name' => 'Besi 10mm',
            'quantity' => 100,
            'unit_type' => 'batang',
            'unit_price' => 80000,
            'total_price' => 8000000,
            'purchase_date' => now()->toDateString(),
            'created_by' => $this->founder->id,
        ]);

        CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'keluar',
            'category' => 'material',
            'amount' => 8000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Besi X-99',
            'reference_type' => WeeklyMaterialPurchase::class,
            'reference_id' => $mat->id,
            'created_by' => $this->founder->id,
        ]);

        // 6. Create Price Proposal + Approval + Official Document (Surat SPP)
        $prop = \App\Models\PriceProposal::create([
            'unit_id' => $unitX->id,
            'buyer_name' => 'Pembeli X-99',
            'hpp_price' => 200000000,
            'proposed_price' => 250000000,
            'margin' => 50000000,
            'payment_scheme' => 'cicilan',
            'status' => 'disetujui',
            'proposed_by' => $this->founder->id,
        ]);

        \App\Models\Approval::create([
            'price_proposal_id' => $prop->id,
            'approver_id' => $this->founder->id,
            'status' => 'disetujui',
            'approved_at' => now(),
        ]);

        \App\Models\OfficialDocument::create([
            'unit_id' => $unitX->id,
            'price_proposal_id' => $prop->id,
            'document_number' => 'SPP/X99/2026',
            'buyer_name' => 'Pembeli X-99',
            'buyer_contact' => '0812345678',
            'issued_by' => $this->founder->id,
            'issued_at' => now(),
        ]);

        // 7. Create Manual Invoice linked to Unit X-99
        $invoice = \App\Models\ManualInvoice::create([
            'project_id' => $this->project->id,
            'unit_id' => $unitX->id,
            'invoice_number' => 'INV-X99-001',
            'recipient_name' => 'Pembeli X-99',
            'amount' => 250000000,
            'invoice_date' => now()->toDateString(),
            'status' => 'lunas',
            'created_by' => $this->founder->id,
        ]);

        // 8. Create Direct Unit Cashflow (Legacy Sale)
        CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'masuk',
            'category' => 'penjualan_unit',
            'amount' => 250000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Penjualan Langsung X-99',
            'reference_type' => Unit::class,
            'reference_id' => $unitX->id,
            'created_by' => $this->founder->id,
        ]);

        // Verify pre-deletion count
        $this->assertEquals(6, CashflowTransaction::where('project_id', $this->project->id)->count());

        // EXECUTE FATAL DELETION OF UNIT X-99
        CascadeDeletionService::deleteUnit($unitX);

        // FATAL ASSERTIONS: 100% CLEAN WIPE, 0 ORPHANS, MANUAL INVOICE UNLINKED TO NULL
        $this->assertNull(Unit::find($unitX->id));
        $this->assertEquals(0, Booking::where('unit_id', $unitX->id)->count());
        $this->assertEquals(0, UnitInstallment::where('unit_id', $unitX->id)->count());
        $this->assertEquals(0, InstallmentPayment::whereIn('id', [$pay1->id, $pay2->id])->count());
        $this->assertEquals(0, WorkerUnitPayroll::where('unit_id', $unitX->id)->count());
        $this->assertEquals(0, WorkerSalaryPayment::where('id', $salPay->id)->count());
        $this->assertEquals(0, WeeklyMaterialPurchase::where('unit_id', $unitX->id)->count());
        $this->assertEquals(0, \App\Models\PriceProposal::where('unit_id', $unitX->id)->count());
        $this->assertEquals(0, \App\Models\OfficialDocument::where('unit_id', $unitX->id)->count());
        $this->assertEquals(0, CashflowTransaction::where('project_id', $this->project->id)->count());
        $this->assertNull(\App\Models\ManualInvoice::find($invoice->id)->unit_id);
        $this->assertCount(0, \App\Services\OrphanCashflowAuditor::audit());
    }

    public function test_fatal_scenario_corrupted_polymorphic_class_reference_audit_and_purge(): void
    {
        // 1. Create corrupted class reference orphan (valid project_id, but invalid reference_type)
        CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'masuk',
            'category' => 'pemasukan_lain',
            'amount' => 123456,
            'transaction_date' => now()->toDateString(),
            'description' => 'Corrupted Class Reference',
            'reference_type' => 'App\\Models\\NonExistentLegacyClass',
            'reference_id' => 88888,
            'created_by' => $this->founder->id,
        ]);

        // 2. Create orphaned reference_id pointing to non-existent model ID
        CashflowTransaction::create([
            'project_id' => $this->project->id,
            'type' => 'keluar',
            'category' => 'pengeluaran_lain',
            'amount' => 654321,
            'transaction_date' => now()->toDateString(),
            'description' => 'Deleted Booking Reference',
            'reference_type' => Booking::class,
            'reference_id' => 777777,
            'created_by' => $this->founder->id,
        ]);

        // Run auditor
        $orphans = \App\Services\OrphanCashflowAuditor::audit();
        $this->assertCount(2, $orphans);

        // Purge orphans
        $purgedCount = \App\Services\OrphanCashflowAuditor::purge();
        $this->assertEquals(2, $purgedCount);
        $this->assertCount(0, \App\Services\OrphanCashflowAuditor::audit());
    }

    public function test_fatal_scenario_massive_project_wipe_with_manual_unreferenced_cashflows(): void
    {
        $this->actingAs($this->founder);

        // 1. Create Project Z
        $projZ = Project::create([
            'name' => 'Proyek Fatal Z',
            'location' => 'Pekanbaru East',
            'standard_land_area' => 100,
            'excess_price_per_sqm' => 1000000,
            'base_price' => 100000000,
            'total_project_price' => 300000000,
            'status' => 'aktif',
            'created_by' => $this->founder->id,
        ]);

        // 2. Create 3 Units under Project Z
        $u1 = Unit::create(['project_id' => $projZ->id, 'code' => 'Z-01', 'category' => 'kavling', 'type' => 'kavling', 'land_width' => 10, 'land_length' => 10, 'land_area' => 100, 'hpp' => 100000000, 'status' => 'available', 'created_by' => $this->founder->id]);
        $u2 = Unit::create(['project_id' => $projZ->id, 'code' => 'Z-02', 'category' => 'kavling', 'type' => 'kavling', 'land_width' => 10, 'land_length' => 10, 'land_area' => 100, 'hpp' => 100000000, 'status' => 'available', 'created_by' => $this->founder->id]);
        $u3 = Unit::create(['project_id' => $projZ->id, 'code' => 'Z-03', 'category' => 'kavling', 'type' => 'kavling', 'land_width' => 10, 'land_length' => 10, 'land_area' => 100, 'hpp' => 100000000, 'status' => 'available', 'created_by' => $this->founder->id]);

        // 3. Add manual standalone project cashflow (reference_type = null)
        CashflowTransaction::create([
            'project_id' => $projZ->id,
            'type' => 'keluar',
            'category' => 'operasional_lapangan',
            'amount' => 15000000,
            'transaction_date' => now()->toDateString(),
            'description' => 'Biaya Pembersihan Lahan Proyek Z (Manual Direct)',
            'created_by' => $this->founder->id,
        ]);

        $this->assertEquals(1, CashflowTransaction::where('project_id', $projZ->id)->count());

        // EXECUTE FATAL DELETION OF PROJECT Z
        CascadeDeletionService::deleteProject($projZ);

        // FATAL ASSERTIONS: PROJECT Z, ALL UNITS, AND MANUAL UNREFERENCED CASHFLOWS ARE 100% WIPED
        $this->assertNull(Project::find($projZ->id));
        $this->assertEquals(0, Unit::where('project_id', $projZ->id)->count());
        $this->assertEquals(0, CashflowTransaction::where('project_id', $projZ->id)->count());
        $this->assertCount(0, \App\Services\OrphanCashflowAuditor::audit());
    }
}
