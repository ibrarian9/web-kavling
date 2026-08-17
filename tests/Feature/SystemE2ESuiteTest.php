<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\CompanyReceivable;
use App\Models\DailyActivityReport;
use App\Models\ManualInvoice;
use App\Models\OfficialDocument;
use App\Models\PriceProposal;
use App\Models\Project;
use App\Models\ProjectPayment;
use App\Models\Unit;
use App\Models\UnitCommission;
use App\Models\UnitInstallment;
use App\Models\User;
use App\Models\WeeklyMaterialPurchase;
use App\Models\Worker;
use App\Models\WorkerAssignment;
use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SystemE2ESuiteTest extends TestCase
{
    use RefreshDatabase;

    protected User $founder;
    protected User $admin;
    protected User $finance;
    protected User $supervisor;
    protected User $marketing;
    protected User $pengawas;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->founder = User::where('role', 'founder')->firstOrFail();
        $this->admin = User::where('role', 'admin')->firstOrFail();
        $this->finance = User::where('role', 'finance')->firstOrFail();
        $this->supervisor = User::where('role', 'supervisor')->firstOrFail();
        $this->marketing = User::where('role', 'marketing')->firstOrFail();
        $this->pengawas = User::where('role', 'pengawas_project')->firstOrFail();
    }

    /**
     * 1. PROJECT & LAND ACQUISITION WORKFLOW
     */
    public function test_project_crud_and_land_payment_calculations(): void
    {
        $this->actingAs($this->founder);

        // A. Create new Project
        Livewire::test(\App\Livewire\Projects\Index::class)
            ->set('name', 'Grand Atlantik Indah')
            ->set('location', 'Jl. Garuda Sakti Km 3, Pekanbaru')
            ->set('standard_land_area', 120.00)
            ->set('excess_price_per_sqm', 1200000.00)
            ->set('base_price', 180000000.00)
            ->set('total_project_price', 500000000.00)
            ->call('saveProject')
            ->assertHasNoErrors();

        $project = Project::where('name', 'Grand Atlantik Indah')->firstOrFail();
        $this->assertEquals(500000000.00, (float)$project->total_project_price);
        $this->assertEquals(0, (float)$project->total_paid);
        $this->assertEquals(500000000.00, (float)$project->remaining_balance);

        // B. Edit Project parameters via Modal
        Livewire::test(\App\Livewire\Projects\Index::class)
            ->call('editProject', $project->id)
            ->assertSet('name', 'Grand Atlantik Indah')
            ->assertSet('location', 'Jl. Garuda Sakti Km 3, Pekanbaru')
            ->set('total_project_price', 600000000.00)
            ->call('saveProject')
            ->assertHasNoErrors();

        $project->refresh();
        $this->assertEquals(600000000.00, (float)$project->total_project_price);

        // C. Record Land Payment to Landowner
        $payment = ProjectPayment::create([
            'project_id' => $project->id,
            'amount_paid' => 200000000.00,
            'payment_date' => now()->toDateString(),
            'payment_method' => 'Transfer Bank BCA',
            'notes' => 'Pembayaran Termin 1 Akuisisi Lahan',
            'created_by' => $this->founder->id,
        ]);

        CashflowTransaction::create([
            'project_id' => $project->id,
            'type' => 'keluar',
            'category' => 'pembelian_lahan',
            'amount' => 200000000.00,
            'transaction_date' => now()->toDateString(),
            'description' => 'Pembayaran Termin 1 Lahan Proyek ' . $project->name,
            'reference_type' => ProjectPayment::class,
            'reference_id' => $payment->id,
            'created_by' => $this->founder->id,
        ]);

        $project->refresh();
        $this->assertEquals(200000000.00, (float)$project->total_paid);
        $this->assertEquals(400000000.00, (float)$project->remaining_balance);
        $this->assertEquals(33.3, (float)$project->payment_progress_percentage);
    }

    /**
     * 2. UNIT HPP RECALCULATION & PROPOSAL APPROVAL WORKFLOW
     */
    public function test_unit_creation_and_proposal_approval_flow(): void
    {
        $project = Project::firstOrFail();

        // A. Unit with Excess Land Area
        $unit = Unit::create([
            'project_id' => $project->id,
            'code' => 'Z-99',
            'category' => 'kavling',
            'type' => 'Standar Plus',
            'land_width' => 10,
            'land_length' => 15,
            'land_area' => 150, // 150 - standard 100 = 50 m2 excess
            'status' => 'tersedia',
            'created_by' => $this->founder->id,
        ]);

        $unit->recalculateLandAndHpp();
        $unit->save();

        $expectedExcessCost = 50 * (float)$project->excess_price_per_sqm;
        $expectedHpp = (float)$project->base_price + $expectedExcessCost;

        $this->assertEquals(50, (float)$unit->excess_land_area);
        $this->assertEquals($expectedExcessCost, (float)$unit->excess_cost);
        $this->assertEquals($expectedHpp, (float)$unit->hpp);

        // B. Marketing Submits Proposal
        $this->actingAs($this->marketing);
        Livewire::test(\App\Livewire\Proposals\Index::class)
            ->set('unit_id', $unit->id)
            ->set('proposed_price', $expectedHpp + 25000000)
            ->set('proposal_notes', 'Pengajuan penawaran harga konsumen Bapak Joko')
            ->call('submitProposal')
            ->assertHasNoErrors();

        $proposal = PriceProposal::where('unit_id', $unit->id)->latest('id')->firstOrFail();
        $this->assertEquals('menunggu', $proposal->status);

        // C. Founder Approves Proposal
        $this->actingAs($this->founder);
        Livewire::test(\App\Livewire\Proposals\Index::class)
            ->set('selectedProposalId', $proposal->id)
            ->set('approval_decision', 'disetujui')
            ->set('approval_notes', 'Approved by Founder executive')
            ->call('submitApproval')
            ->assertHasNoErrors();

        $proposal->refresh();
        $this->assertEquals('disetujui', $proposal->status);
    }

    /**
     * 3. BOOKING FEE, CONVERSION & OFFICIAL DOCUMENT (SPP) GENERATION
     */
    public function test_booking_to_spp_issuance_flow(): void
    {
        $project = Project::firstOrFail();
        $unit = Unit::where('status', 'tersedia')->firstOrFail();

        $this->actingAs($this->marketing);

        // A. Record Booking Fee
        Livewire::test(\App\Livewire\Bookings\Index::class)
            ->set('project_id', $project->id)
            ->set('unit_id', $unit->id)
            ->set('buyer_name', 'Haji Sulaiman')
            ->set('buyer_phone', '081299887766')
            ->set('booking_type', 'unit')
            ->set('booking_amount', 5000000.00)
            ->set('booking_date', now()->toDateString())
            ->call('save')
            ->assertHasNoErrors();

        $booking = Booking::where('buyer_name', 'Haji Sulaiman')->firstOrFail();
        $this->assertEquals('active', $booking->status);
        $unit->refresh();
        $this->assertEquals('booked', $unit->status);

        // B. Approve DP in System
        $this->actingAs($this->finance);
        Livewire::test(\App\Livewire\Bookings\Index::class)
            ->call('approveDp', $booking->id);

        $booking->refresh();
        $this->assertEquals('converted', $booking->status);

        // C. Issue Official Document (SPP PDF)
        $this->actingAs($this->founder);
        Livewire::test(\App\Livewire\Documents\Index::class)
            ->set('selected_unit_id', $unit->id)
            ->set('buyer_name', 'Haji Sulaiman')
            ->set('buyer_nik', '1471012345678901')
            ->set('buyer_contact', '081299887766')
            ->set('buyer_address', 'Jl. Sudirman No. 100, Pekanbaru')
            ->call('generateDocument')
            ->assertHasNoErrors();

        $doc = OfficialDocument::where('unit_id', $unit->id)->firstOrFail();
        $this->assertNotEmpty($doc->document_number);
        $unit->refresh();
        $this->assertEquals('terjual', $unit->status);
    }

    /**
     * 4. UNIT INSTALLMENT (CICILAN PEMBELI) & CASHFLOW SYNC
     */
    public function test_installment_setup_and_payment_flow(): void
    {
        $project = Project::firstOrFail();
        $unit = Unit::create([
            'project_id' => $project->id,
            'code' => 'INST-01',
            'category' => 'kavling',
            'type' => 'Standar',
            'land_width' => 10,
            'land_length' => 10,
            'land_area' => 100,
            'status' => 'disetujui',
            'created_by' => $this->founder->id,
        ]);

        $this->actingAs($this->finance);

        // A. Setup Installment Scheme
        Livewire::test(\App\Livewire\Installments\Index::class)
            ->set('unit_id', $unit->id)
            ->set('total_price', 120000000.00)
            ->set('down_payment', 20000000.00)
            ->set('installment_count', 10)
            ->set('installment_amount', 10000000.00)
            ->set('start_date', now()->toDateString())
            ->call('saveSetup')
            ->assertHasNoErrors();

        $installment = UnitInstallment::where('unit_id', $unit->id)->firstOrFail();
        $this->assertEquals('berjalan', $installment->status);
        $this->assertEquals(20000000.00, (float)$installment->total_paid);
        $this->assertEquals(100000000.00, (float)$installment->remaining_balance);

        // B. Submit Monthly Payment 1
        Livewire::test(\App\Livewire\Installments\Index::class)
            ->call('openPaymentModal', $installment->id)
            ->set('payment_amount', 10000000.00)
            ->set('payment_date', now()->toDateString())
            ->set('payment_method', 'Transfer Bank')
            ->set('payment_notes', 'Setoran Cicilan Bulan ke-1')
            ->call('submitPayment')
            ->assertHasNoErrors();

        $installment->refresh();
        $this->assertEquals(30000000.00, (float)$installment->total_paid);
        $this->assertEquals(90000000.00, (float)$installment->remaining_balance);

        // Verify Cashflow Transaction Recorded
        $cashflow = CashflowTransaction::where('category', 'pembayaran_cicilan_pembeli')
            ->where('project_id', $project->id)
            ->latest('id')
            ->firstOrFail();
        $this->assertEquals(10000000.00, (float)$cashflow->amount);
        $this->assertEquals('masuk', $cashflow->type);
    }

    /**
     * 5. WORKER ASSIGNMENT, BORONGAN PAYROLL & FIELD EXPENSES
     */
    public function test_worker_and_field_expense_workflow(): void
    {
        $this->actingAs($this->founder);
        $project = Project::firstOrFail();
        $unit = Unit::firstOrFail();

        // A. Create Worker
        $worker = Worker::create([
            'name' => 'Pak Slamet Mandor',
            'phone' => '085211223344',
            'type' => 'mandor',
            'specialty' => 'Pembersihan Lahan & Cut-Fill',
            'status' => 'active',
        ]);

        // B. Assign Worker to Unit
        WorkerAssignment::create([
            'worker_id' => $worker->id,
            'project_id' => $project->id,
            'unit_id' => $unit->id,
            'assigned_role' => 'Mandor Pembersihan Lahan',
            'start_date' => now()->toDateString(),
            'status' => 'active',
        ]);

        // C. Setup Borongan Contract Payroll
        $payroll = WorkerUnitPayroll::create([
            'worker_id' => $worker->id,
            'project_id' => $project->id,
            'unit_id' => $unit->id,
            'agreed_salary' => 15000000.00,
            'paid_amount' => 0,
            'payment_frequency' => 'termin',
            'status' => 'berjalan',
            'created_by' => $this->founder->id,
        ]);

        // D. Pay Wage Termin
        $salaryPayment = WorkerSalaryPayment::create([
            'worker_unit_payroll_id' => $payroll->id,
            'payment_date' => now()->toDateString(),
            'amount_gross' => 5000000.00,
            'amount_paid' => 5000000.00,
            'payment_method' => 'cash',
            'notes' => 'Termin 1 Pembersihan Lahan',
            'created_by' => $this->founder->id,
        ]);

        $payroll->update(['paid_amount' => 5000000.00]);

        // E. Record Material Purchase
        WeeklyMaterialPurchase::create([
            'project_id' => $project->id,
            'unit_id' => $unit->id,
            'worker_id' => $worker->id,
            'pengawas_id' => $this->pengawas->id,
            'purchase_date' => now()->toDateString(),
            'item_name' => 'Semen Padang 50 Zak',
            'store_name' => 'Toko Bangunan Berkah Jaya',
            'quantity' => 50,
            'unit_measure' => 'sak',
            'unit_price' => 70000.00,
            'total_price' => 3500000.00,
            'payment_status' => 'lunas',
            'paid_at' => now()->toDateString(),
            'paid_by' => $this->finance->id,
        ]);

        // F. Assert Field Expenses View Renders Cleanly
        $this->actingAs($this->finance);
        Livewire::test(\App\Livewire\FieldExpenses\Index::class)
            ->assertSee('Pak Slamet Mandor')
            ->assertSee('Semen Padang 50 Zak');
    }

    /**
     * 6. MANUAL INVOICE & PUBLIC VERIFICATION
     */
    public function test_manual_invoice_and_public_verification_flow(): void
    {
        $this->actingAs($this->finance);
        $project = Project::firstOrFail();

        // A. Create Manual Invoice
        Livewire::test(\App\Livewire\ManualInvoices\Index::class)
            ->set('project_id', $project->id)
            ->set('recipient_name', 'Ibu Ratna Dewi')
            ->set('recipient_phone', '081399881122')
            ->set('type', 'masuk')
            ->set('category', 'pemasukan_lain')
            ->set('amount', 7500000.00)
            ->set('invoice_date', now()->toDateString())
            ->set('status', 'lunas')
            ->set('record_cashflow', true)
            ->set('description', 'Jasa Pengurusan Administrasi Surat Tanah')
            ->call('saveInvoice')
            ->assertHasNoErrors();

        $invoice = ManualInvoice::where('recipient_name', 'Ibu Ratna Dewi')->firstOrFail();
        $this->assertEquals(7500000.00, (float)$invoice->amount);
        $this->assertEquals('lunas', $invoice->status);

        // B. Public QR Verification Route Access
        $response = $this->get(route('verify.manual-invoice', $invoice->uuid));
        $response->assertStatus(200);
        $response->assertSee('Ibu Ratna Dewi');
    }

    /**
     * 7. DAILY ACTIVITY REPORT (MARKETING CRM)
     */
    public function test_daily_activity_report_crm_flow(): void
    {
        $this->actingAs($this->marketing);
        $project = Project::firstOrFail();

        Livewire::test(\App\Livewire\DailyActivityReports\Index::class)
            ->set('project_id', $project->id)
            ->set('client_name', 'Bapak Hendra Gunawan')
            ->set('client_phone', '081234567890')
            ->set('report_date', now()->toDateString())
            ->set('lead_source', 'facebook_ads')
            ->set('interaction_type', 'site_visit')
            ->set('lead_stage', 'hot_deal')
            ->set('payment_type', 'cicilan_bertahap')
            ->set('deal_amount', 150000000.00)
            ->set('notes', 'Konsumen sangat berminat dengan kavling sudut.')
            ->call('saveReport')
            ->assertHasNoErrors();

        $report = DailyActivityReport::where('client_name', 'Bapak Hendra Gunawan')->firstOrFail();
        $this->assertEquals('hot_deal', $report->lead_stage);
        $this->assertEquals(150000000.00, (float)$report->deal_amount);
    }

    /**
     * 8. ROLE-BASED ACCESS CONTROL & PERMISSIONS
     */
    public function test_rbac_security_boundaries(): void
    {
        // Marketing cannot access executive activity logs or delete projects
        $this->actingAs($this->marketing);
        $response = $this->get('/activity-logs');
        $response->assertStatus(403);

        $project = Project::firstOrFail();
        Livewire::test(\App\Livewire\Projects\Index::class)
            ->call('deleteProject', $project->id);

        $this->assertDatabaseHas('projects', ['id' => $project->id]);

        // Founder can access activity logs
        $this->actingAs($this->founder);
        $response = $this->get('/activity-logs');
        $response->assertStatus(200);
    }
}
