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

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Master Data Proyek & Unit
    Route::get('/projects', ProjectsIndex::class)->name('projects.index');
    Route::get('/projects/{id}', ProjectsShow::class)->name('projects.show');
    Route::get('/units', UnitsIndex::class)->name('units.index');
    Route::get('/units/{id}', UnitShow::class)->name('units.show');

    // Worker & Pengawas Lapangan
    Route::get('/workers', WorkersIndex::class)->name('workers.index');
    Route::get('/field-expenses', FieldExpensesIndex::class)->name('field-expenses.index');
    Route::get('/worker-payroll/{uuid}/receipt', [PayrollReceiptController::class, 'streamReceipt'])->name('payroll.receipt');

    // Penjualan, Booking & Approval
    Route::get('/bookings', BookingsIndex::class)->name('bookings.index');
    Route::get('/bookings/{id}/receipt', [BookingReceiptController::class, 'streamReceipt'])->name('bookings.receipt');
    Route::get('/proposals', ProposalsIndex::class)->name('proposals.index');
    Route::get('/documents', DocumentsIndex::class)->name('documents.index');
    Route::get('/documents/{id}/pdf', [DocumentPdfController::class, 'streamPdf'])->name('documents.stream');

    // Keuangan & Pembayaran
    Route::get('/installments', InstallmentsIndex::class)->name('installments.index');
    Route::get('/cashflow', CashflowIndex::class)->name('cashflow.index');

    // Manajemen User (Founder Only)
    Route::get('/users', \App\Livewire\Users\Index::class)->name('users.index');
});
