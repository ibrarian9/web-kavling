<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingReceiptController;
use App\Http\Controllers\DocumentPdfController;
use App\Http\Controllers\DocumentVerificationController;
use App\Http\Controllers\ReceiptVerificationController;
use App\Livewire\Cashflow\Index as CashflowIndex;
use App\Livewire\Dashboard;
use App\Livewire\Documents\Index as DocumentsIndex;
use App\Livewire\Installments\Index as InstallmentsIndex;
use App\Livewire\Projects\Index as ProjectsIndex;
use App\Livewire\Projects\Show as ProjectsShow;
use App\Livewire\Proposals\Index as ProposalsIndex;
use App\Livewire\Units\Index as UnitsIndex;
use App\Livewire\Units\Show as UnitShow;
use App\Livewire\Bookings\Index as BookingsIndex;
use App\Http\Controllers\LandPaymentReceiptController;
use App\Http\Controllers\PayrollReceiptController;
use App\Http\Controllers\PayrollVerificationController;
use App\Livewire\FieldExpenses\Index as FieldExpensesIndex;
use App\Livewire\Workers\Index as WorkersIndex;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/switch-role/{role}', [AuthController::class, 'switchRole'])->name('switch-role');

// Redirect root to dashboard or login
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Public Guest Verification Routes (Scan QR Code without login)
Route::get('/verify-receipt/{id}', [ReceiptVerificationController::class, 'verify'])->name('verify.receipt');
Route::get('/verify-document/{id}', [DocumentVerificationController::class, 'verify'])->name('verify.document');
Route::get('/verify-payroll/{uuid}', [PayrollVerificationController::class, 'verify'])->name('verify.payroll');
Route::get('/verify-land-payment/{uuid}', [LandPaymentReceiptController::class, 'verify'])->name('verify.land-payment');
Route::get('/verify-cashflow', [\App\Http\Controllers\CashflowVerificationController::class, 'verify'])->name('verify.cashflow');
Route::get('/verify-installment/{uuid}', [\App\Http\Controllers\InstallmentInvoiceController::class, 'verify'])->name('verify.installment');
Route::get('/verify-manual-invoice/{uuid}', [\App\Http\Controllers\ManualInvoiceController::class, 'verify'])->name('verify.manual-invoice');
Route::get('/verify-material-purchase/{id}', [\App\Http\Controllers\MaterialPurchaseReceiptController::class, 'verify'])->name('verify.material-purchase');
Route::get('/verify-unit-expenses/{id}', [\App\Http\Controllers\UnitExpensesReportController::class, 'verify'])->name('verify.unit-expenses');
Route::get('/verify-field-expenses', [\App\Http\Controllers\FieldExpensesReportController::class, 'verify'])->name('verify.field-expenses');
Route::get('/verify-project-land-payments/{id}', [\App\Http\Controllers\ProjectReportController::class, 'verifyLandPayments'])->name('verify.project-land-payments');
Route::get('/verify-project-sales-profit/{id}', [\App\Http\Controllers\ProjectReportController::class, 'verifySalesProfit'])->name('verify.project-sales-profit');
Route::get('/verify-spjb/{id}', [DocumentPdfController::class, 'verifySpjb'])->name('verify.spjb');
Route::get('/verify-worker-spk/{id}', [\App\Http\Controllers\WorkerSpkController::class, 'verify'])->name('verify.worker-spk');

// Authenticated Routes
Route::middleware(['auth', \App\Http\Middleware\EnsureUserIsActive::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/profile', \App\Livewire\Profile\Index::class)->name('profile.index');
    Route::get('/tutorial', \App\Livewire\Tutorial\Index::class)->name('tutorial.index');

    // Master Data Proyek & Unit
    Route::get('/projects', ProjectsIndex::class)->name('projects.index');
    Route::get('/projects/{id}', ProjectsShow::class)->name('projects.show');
    Route::get('/projects/{id}/land-payments-pdf', [\App\Http\Controllers\ProjectReportController::class, 'exportLandPaymentsPdf'])->name('projects.land-payments-pdf');
    Route::get('/projects/{id}/sales-profit-pdf', [\App\Http\Controllers\ProjectReportController::class, 'exportSalesProfitPdf'])->name('projects.sales-profit-pdf');
    Route::get('/land-payment/{uuid}/receipt', [LandPaymentReceiptController::class, 'streamReceipt'])->name('land-payment.receipt');
    Route::get('/units', UnitsIndex::class)->name('units.index');
    Route::get('/units/legacy-sale', \App\Livewire\Units\LegacySale::class)->name('units.legacy-sale');
    Route::get('/units/{id}', UnitShow::class)->name('units.show');
    Route::get('/units/{id}/expenses-pdf', [\App\Http\Controllers\UnitExpensesReportController::class, 'exportPdf'])->name('units.expenses-pdf');
    Route::get('/units/payroll/{id}/spk-pdf', [\App\Http\Controllers\WorkerSpkController::class, 'streamSpk'])->name('units.payroll.spk-pdf');
    Route::get('/installment-invoice/{uuid}', [\App\Http\Controllers\InstallmentInvoiceController::class, 'streamInvoice'])->name('installment.invoice');
    Route::get('/material-purchases/{id}/receipt', [\App\Http\Controllers\MaterialPurchaseReceiptController::class, 'streamReceipt'])->name('material-purchases.receipt');

    // Worker & Pengawas Lapangan
    Route::get('/workers', WorkersIndex::class)->name('workers.index');
    Route::get('/field-expenses', FieldExpensesIndex::class)->name('field-expenses.index');
    Route::get('/field-expenses/export-pdf', [\App\Http\Controllers\FieldExpensesReportController::class, 'exportPdf'])->name('field-expenses.export-pdf');
    Route::get('/worker-payroll/{uuid}/receipt', [PayrollReceiptController::class, 'streamReceipt'])->name('payroll.receipt');

    // Penjualan, Booking & Approval
    Route::get('/daily-activity-reports', \App\Livewire\DailyActivityReports\Index::class)->name('daily-activity-reports.index');
    Route::get('/daily-activity-reports/export-pdf', [\App\Http\Controllers\DailyActivityReportPdfController::class, 'exportPdf'])->name('daily-activity-reports.export-pdf');
    Route::get('/bookings', BookingsIndex::class)->name('bookings.index');
    Route::get('/bookings/{id}/receipt', [BookingReceiptController::class, 'streamReceipt'])->name('bookings.receipt');
    Route::get('/proposals', ProposalsIndex::class)->name('proposals.index');
    Route::get('/documents', DocumentsIndex::class)->name('documents.index');
    Route::get('/documents/{id}/pdf', [DocumentPdfController::class, 'streamPdf'])->name('documents.stream');
    Route::get('/documents/{id}/spjb-pdf', [DocumentPdfController::class, 'streamSpjbPdf'])->name('documents.spjb-pdf');

    // Keuangan & Pembayaran
    Route::get('/installments', InstallmentsIndex::class)->name('installments.index');
    Route::get('/installments/unpaid-pdf', [\App\Http\Controllers\UnpaidInstallmentReportController::class, 'exportPdf'])->name('installments.unpaid-pdf');
    Route::get('/verify-unpaid-installments', [\App\Http\Controllers\UnpaidInstallmentReportController::class, 'verify'])->name('verify.unpaid-installments');
    Route::get('/cashflow', CashflowIndex::class)->name('cashflow.index');
    Route::get('/cashflow/export-pdf', [\App\Http\Controllers\CashflowReportController::class, 'exportPdf'])->name('cashflow.export-pdf');
    Route::get('/cashflow/export-excel', [\App\Http\Controllers\CashflowReportController::class, 'exportExcel'])->name('cashflow.export-excel');
    Route::get('/manual-invoices', \App\Livewire\ManualInvoices\Index::class)->name('manual-invoices.index');
    Route::get('/manual-invoices/{uuid}/pdf', [\App\Http\Controllers\ManualInvoiceController::class, 'streamPdf'])->name('manual-invoices.pdf');

    // Penggajian Karyawan (Founder Only)
    Route::get('/employee-salaries', \App\Livewire\EmployeeSalaries\Index::class)->name('employee-salaries.index');
    Route::get('/employee-salary/{uuid}/slip-pdf', [\App\Http\Controllers\EmployeeSalarySlipController::class, 'streamPdf'])->name('employee-salary.slip-pdf');

    // Manajemen User & System Log (Founder Only)
    Route::get('/users', \App\Livewire\Users\Index::class)->name('users.index');
    Route::get('/activity-logs', \App\Livewire\ActivityLogs\Index::class)->name('activity-logs.index');
});
