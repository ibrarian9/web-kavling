<?php

use App\Models\Booking;
use App\Models\CashflowTransaction;
use App\Models\CompanyReceivable;
use App\Models\DailyActivityReport;
use App\Models\EmployeePayrollPayment;
use App\Models\EmployeeSalary;
use App\Models\InstallmentPayment;
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
use App\Models\WorkerSalaryPayment;
use App\Models\WorkerUnitPayroll;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('founder', 'web');
    Role::findOrCreate('admin', 'web');
    Role::findOrCreate('finance', 'web');
    Role::findOrCreate('marketing', 'web');
    Role::findOrCreate('supervisor', 'web');
    Role::findOrCreate('pengawas_project', 'web');
});

test('full crud and pdf export lifecycle across all 15 system export endpoints', function () {
    $founder = User::create([
        'name' => 'Founder Master',
        'email' => 'founder_master@atlantikperkasa.co.id',
        'password' => bcrypt('password'),
        'role' => 'founder',
        'is_active' => true,
    ]);

    $this->actingAs($founder);

    // 1. PROJECT CRUD
    $project = Project::create([
        'name' => 'Proyek Grand Atlantik Residence',
        'location' => 'Jl. Poros Utama No. 88',
        'standard_land_area' => 120,
        'excess_price_per_sqm' => 1500000,
        'base_price' => 200000000,
        'total_project_price' => 2000000000,
        'status' => 'aktif',
        'created_by' => $founder->id,
    ]);

    expect($project->id)->not->toBeNull();

    // 2. PROJECT PAYMENT (LAND PAYMENT) & PDF EXPORT
    $landPayment = ProjectPayment::create([
        'project_id' => $project->id,
        'amount_paid' => 500000000,
        'payment_date' => now()->toDateString(),
        'recipient_name' => 'Haji Abdullah (Pemilik Lahan)',
        'payment_stage' => 'DP Tahap 1 Pembelian Lahan',
        'payment_method' => 'transfer',
        'notes' => 'Pembayaran uang muka pembelian tanah',
        'created_by' => $founder->id,
    ]);

    // Test Export PDF 1: Resi Pembayaran Lahan
    $res1 = $this->get(route('land-payment.receipt', $landPayment->uuid));
    $res1->assertStatus(200);
    $res1->assertHeader('content-type', 'application/pdf');

    // Test Export PDF 2: Rekapitulasi Pembayaran Lahan Proyek
    $res2 = $this->get(route('projects.land-payments-pdf', $project->id));
    $res2->assertStatus(200);
    $res2->assertHeader('content-type', 'application/pdf');

    // 3. UNIT CRUD
    $unit = Unit::create([
        'project_id' => $project->id,
        'code' => 'GAR-A01',
        'category' => 'rumah',
        'type' => 'Type 45/120',
        'land_area' => 120,
        'building_area' => 45,
        'hpp' => 180000000,
        'final_selling_price' => 300000000,
        'status' => 'tersedia',
        'created_by' => $founder->id,
    ]);

    expect($unit->id)->not->toBeNull();

    // Test Export PDF 3: Rekapitulasi Penjualan & Profit Unit Proyek
    $res3 = $this->get(route('projects.sales-profit-pdf', $project->id));
    $res3->assertStatus(200);
    $res3->assertHeader('content-type', 'application/pdf');

    // 4. BOOKING CRUD & PDF EXPORT
    $booking = Booking::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'buyer_name' => 'Bambang Kusuma',
        'buyer_phone' => '081234567890',
        'booking_amount' => 5000000,
        'booking_date' => now()->toDateString(),
        'expiry_date' => now()->addDays(14)->toDateString(),
        'status' => 'active',
        'created_by' => $founder->id,
    ]);

    // Test Export PDF 4: Resi/Invoice Booking
    $res4 = $this->get(route('bookings.receipt', $booking->id));
    $res4->assertStatus(200);
    $res4->assertHeader('content-type', 'application/pdf');

    // 5. PRICE PROPOSAL CRUD
    $proposal = PriceProposal::create([
        'unit_id' => $unit->id,
        'booking_id' => $booking->id,
        'hpp_price' => 180000000,
        'proposed_price' => 290000000,
        'margin' => 110000000,
        'status' => 'disetujui',
        'notes' => 'Diskon promo peluncuran awal',
        'proposed_by' => $founder->id,
    ]);

    // 6. OFFICIAL DOCUMENT (SPP & SPJB) & PDF EXPORT
    $doc = OfficialDocument::create([
        'unit_id' => $unit->id,
        'price_proposal_id' => $proposal->id,
        'document_number' => 'SPP/GAR-A01/2026/001',
        'buyer_name' => 'Bambang Kusuma',
        'buyer_address' => 'Jl. Mawar No. 12',
        'buyer_contact' => '081234567890',
        'issued_by' => $founder->id,
    ]);

    // Test Export PDF 5: Dokumen SPP Resmi
    $res5 = $this->get(route('documents.stream', $doc->id));
    $res5->assertStatus(200);
    $res5->assertHeader('content-type', 'application/pdf');

    // Test Export PDF 6: Dokumen SPJB Perjanjian Jual Beli
    $res6 = $this->get(route('documents.spjb-pdf', $doc->id));
    $res6->assertStatus(200);
    $res6->assertHeader('content-type', 'application/pdf');

    // 7. INSTALLMENT SCHEME & PAYMENTS & PDF EXPORT
    $installment = UnitInstallment::create([
        'unit_id' => $unit->id,
        'official_document_id' => $doc->id,
        'total_price' => 290000000,
        'down_payment' => 50000000,
        'installment_count' => 12,
        'installment_amount' => 20000000,
        'start_date' => now()->toDateString(),
        'status' => 'berjalan',
    ]);

    $instPayment = InstallmentPayment::create([
        'unit_installment_id' => $installment->id,
        'amount_paid' => 20000000,
        'payment_date' => now()->toDateString(),
        'payment_method' => 'transfer',
        'notes' => 'Setoran cicilan bulan ke-1',
        'created_by' => $founder->id,
    ]);

    // Test Export PDF 7: Kuitansi Setoran Cicilan
    $res7 = $this->get(route('installment.invoice', $instPayment->uuid));
    $res7->assertStatus(200);
    $res7->assertHeader('content-type', 'application/pdf');

    // Test Export PDF 8: Rekapitulasi Riwayat Cicilan Unit
    $res8 = $this->get(route('installments.unit-statement-pdf', $installment->id));
    $res8->assertStatus(200);
    $res8->assertHeader('content-type', 'application/pdf');

    // Test Export PDF 9: Rekapitulasi Tunggakan Cicilan Global
    $res9 = $this->get(route('installments.unpaid-pdf'));
    $res9->assertStatus(200);
    $res9->assertHeader('content-type', 'application/pdf');

    // 8. WORKERS & ASSIGNMENTS CRUD
    $worker = Worker::create([
        'name' => 'Mandor Sutrisno',
        'type' => 'mandor',
        'specialty' => 'Pondasi & Dinding',
        'phone' => '082112233445',
        'address' => 'Kp. Makmur',
        'status' => 'active',
    ]);

    $payroll = WorkerUnitPayroll::create([
        'worker_id' => $worker->id,
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'agreed_salary' => 30000000,
        'paid_amount' => 10000000,
        'payment_frequency' => 'mingguan',
        'status' => 'berjalan',
        'notes' => 'Pengerjaan pondasi dan plesteran',
        'created_by' => $founder->id,
    ]);

    $payrollPayment = WorkerSalaryPayment::create([
        'worker_unit_payroll_id' => $payroll->id,
        'amount_gross' => 10000000,
        'loan_deduction' => 0,
        'amount_paid' => 10000000,
        'payment_date' => now()->toDateString(),
        'payment_method' => 'cash',
        'notes' => 'Pembayaran termin 1 mandor Sutrisno',
        'created_by' => $founder->id,
    ]);

    // Test Export PDF 10: SPK Borongan Pekerja
    $res10 = $this->get(route('units.payroll.spk-pdf', $payroll->id));
    $res10->assertStatus(200);
    $res10->assertHeader('content-type', 'application/pdf');

    // Test Export PDF 11: Resi Kuitansi Gaji / Upah Tukang
    $res11 = $this->get(route('payroll.receipt', $payrollPayment->uuid));
    $res11->assertStatus(200);
    $res11->assertHeader('content-type', 'application/pdf');

    // 9. MATERIAL PURCHASE CRUD & PDF EXPORT
    $material = WeeklyMaterialPurchase::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'pengawas_id' => $founder->id,
        'store_name' => 'TB. Sinar Abadi',
        'item_name' => 'Semen Gresik 100 Sak',
        'quantity' => 100,
        'unit_price' => 65000,
        'total_price' => 6500000,
        'purchase_date' => now()->toDateString(),
        'payment_status' => 'lunas',
        'paid_at' => now(),
        'paid_by' => $founder->id,
        'notes' => 'Semen untuk pondasi unit GAR-A01',
    ]);

    // Test Export PDF 12: Resi Pembelian Material
    $res12 = $this->get(route('material-purchases.receipt', $material->id));
    $res12->assertStatus(200);
    $res12->assertHeader('content-type', 'application/pdf');

    // Test Export PDF 13: Laporan Realisasi Biaya & HPP Unit
    $res13 = $this->get(route('units.expenses-pdf', $unit->id));
    $res13->assertStatus(200);
    $res13->assertHeader('content-type', 'application/pdf');

    // 10. EMPLOYEE SALARY CRUD & PDF EXPORT
    $empSalary = EmployeeSalary::create([
        'employee_name' => 'Dewi Safitri (Staf Finance)',
        'position' => 'Finance Staff',
        'basic_salary' => 5000000,
        'allowance' => 1000000,
        'net_salary' => 6000000,
        'created_by' => $founder->id,
    ]);

    $empPayment = EmployeePayrollPayment::create([
        'employee_salary_id' => $empSalary->id,
        'payroll_month' => (int)date('m'),
        'payroll_year' => (int)date('Y'),
        'payment_date' => now()->toDateString(),
        'basic_salary' => 5000000,
        'allowance' => 1000000,
        'net_salary' => 6000000,
        'payment_method' => 'transfer',
        'created_by' => $founder->id,
    ]);

    // Test Export PDF 14: Slip Gaji Karyawan
    $res14 = $this->get(route('employee-salary.slip-pdf', $empPayment->uuid));
    $res14->assertStatus(200);
    $res14->assertHeader('content-type', 'application/pdf');

    // 11. MANUAL INVOICE CRUD & PDF EXPORT
    $inv = ManualInvoice::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'recipient_name' => 'PT. Mitra Karya Semesta',
        'recipient_phone' => '081399887766',
        'recipient_address' => 'Kawasan Industri Cikarang',
        'type' => 'masuk',
        'category' => 'lain_lain',
        'amount' => 15000000,
        'invoice_date' => now()->toDateString(),
        'due_date' => now()->addDays(14)->toDateString(),
        'status' => 'pending',
        'payment_method' => 'Transfer Bank',
        'description' => 'Tagihan jasa konstruksi penambahan kanopi',
        'created_by' => $founder->id,
    ]);

    // Test Export PDF 15: Invoice Manual Tagihan
    $res15 = $this->get(route('manual-invoices.pdf', $inv->uuid));
    $res15->assertStatus(200);
    $res15->assertHeader('content-type', 'application/pdf');

    // 12. FIELD EXPENSES REPORT EXPORT (PDF)
    $resField = $this->get(route('field-expenses.export-pdf', ['period' => 'this_month']));
    $resField->assertStatus(200);
    $resField->assertHeader('content-type', 'application/pdf');

    // 13. CASHFLOW REPORT EXPORT (PDF)
    $resCashflow = $this->get(route('cashflow.export-pdf', ['month' => date('Y-m')]));
    $resCashflow->assertStatus(200);
    $resCashflow->assertHeader('content-type', 'application/pdf');

    // 14. DAILY ACTIVITY REPORT CRUD & PDF EXPORT
    $dailyRep = DailyActivityReport::create([
        'user_id' => $founder->id,
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'report_date' => now()->toDateString(),
        'client_name' => 'Dr. Hendra Gunawan',
        'client_phone' => '081288990011',
        'lead_source' => 'facebook_ads',
        'interaction_type' => 'survey_lokasi',
        'lead_stage' => 'hot',
        'payment_type' => 'cash_bertahap',
        'deal_amount' => 300000000,
        'notes' => 'Klien tertarik unit hook GAR-A01 dan rencana booking besok',
    ]);

    $resDaily = $this->get(route('daily-activity-reports.export-pdf', ['period' => 'this_month']));
    $resDaily->assertStatus(200);
    $resDaily->assertHeader('content-type', 'application/pdf');

    // 15. PAYABLES CRUD (RECEIVABLES & COMMISSIONS)
    $receivable = CompanyReceivable::create([
        'debtor_type' => 'worker',
        'debtor_name' => 'Sutrisno (Mandor)',
        'worker_id' => $worker->id,
        'amount' => 2000000,
        'paid_amount' => 500000,
        'loan_date' => now()->toDateString(),
        'status' => 'belum_lunas',
        'notes' => 'Kasbon kebutuhan mendesak keluarga',
        'created_by' => $founder->id,
    ]);

    $commission = UnitCommission::create([
        'project_id' => $project->id,
        'unit_id' => $unit->id,
        'seller_name' => 'Agent Properti Harapan',
        'seller_phone' => '087711223344',
        'commission_amount' => 5000000,
        'paid_amount' => 0,
        'status' => 'pending',
        'notes' => 'Komisi closing unit GAR-A01',
        'created_by' => $founder->id,
    ]);

    expect($receivable->id)->not->toBeNull();
    expect($commission->id)->not->toBeNull();

    expect($receivable->id)->not->toBeNull();
    expect($commission->id)->not->toBeNull();
});
