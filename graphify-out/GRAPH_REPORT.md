# Graph Report - web-kavling  (2026-08-17)

## Corpus Check
- 440 files · ~198,172 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1806 nodes · 3264 edges · 331 communities (208 shown, 123 thin omitted)
- Extraction: 84% EXTRACTED · 16% INFERRED · 0% AMBIGUOUS · INFERRED: 529 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `90e664c5`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Index
- LegacySale
- composer.json
- Index
- Index
- WorkerAssignment
- Show
- DailyActivityReport
- scripts
- PriceProposal
- Show
- Index
- CashflowTransaction.php
- Illuminate\Database\Eloquent\Relations\BelongsTo
- Index
- Illuminate\Http\Request
- Index
- package.json
- Index
- units/show.blade.php
- WorkerSalaryPayment
- ProjectPayment
- projects/show.blade.php
- Livewire\Component
- CompanyReceivable
- installments/index.blade.php
- cashflow/index.blade.php
- User.php
- 2. Activity Diagrams (Diagram Aktivitas UML)
- Index
- 8. Rancangan Database (Skema Tabel Terbaru)
- AppServiceProvider
- employee-salaries/index.blade.php
- app.blade.php
- documents/index.blade.php
- section-installments.blade.php
- manual-invoices/index.blade.php
- section-expenses.blade.php
- units/index.blade.php
- bookings/index.blade.php
- field-expenses/index.blade.php
- proposals/index.blade.php
- tutorial/index.blade.php
- workers/index.blade.php
- Unit
- section-payroll-borongan.blade.php
- proposal-table-list.blade.php
- legacy-sale.blade.php
- booking-table-list.blade.php
- tab-payments.blade.php
- header-actions.blade.php
- users/index.blade.php
- projects/index.blade.php
- Illuminate\Database\Seeder
- WorkerUnitPayroll
- modal-payment.blade.php
- tab-siteplan.blade.php
- worker-table-list.blade.php
- section-workers.blade.php
- tab-cashflow.blade.php
- expense-table-list.blade.php
- proposal-action-modals.blade.php
- legacy-table-list.blade.php
- expense-edit-modal.blade.php
- closeLegacyModal
- openLegacyModal
- sidebar-desktop.blade.php
- sidebar-mobile.blade.php
- activity-logs/index.blade.php
- booking-form-modal.blade.php
- booking-header.blade.php
- modal-detail-transaction.blade.php
- modal-manual-transaction.blade.php
- expense-header.blade.php
- installments/partials/modal-convert-to-cash.blade.php
- modal-installment-detail.blade.php
- installments/partials/modal-installment-payment.blade.php
- installments/partials/modal-setup-installment.blade.php
- land-purchase-card.blade.php
- proposal-form-modal.blade.php
- proposal-header.blade.php
- tutorial-flow-map.blade.php
- tutorial-header.blade.php
- legacy-create-modal.blade.php
- legacy-header-info.blade.php
- modal-booking.blade.php
- units/partials/modal-convert-to-cash.blade.php
- modal-direct-spp.blade.php
- modal-edit-unit.blade.php
- units/partials/modal-installment-payment.blade.php
- modal-material-purchase.blade.php
- modal-payroll-payment.blade.php
- modal-payroll-setup.blade.php
- units/partials/modal-setup-installment.blade.php
- modal-worker-assignment.blade.php
- section-proposals-spp.blade.php
- worker-assign-modal.blade.php
- worker-form-modal.blade.php
- worker-header.blade.php
- 🎨 Panduan Standarisasi Tampilan Tombol (Button Design System)
- 🔍 Panduan Standarisasi Input Search, Filter Dropdown & Form Controls (Dropdown & Search Design System)
- User
- 📐 2. Standardisasi Komponen Antarmuka (UI Components Standard)
- modal-salary-setup.blade.php
- tab-salaries-table.blade.php
- CashflowTransaction
- openSalaryModal
- 🎴 Panduan Standarisasi Card Status & Badge Status (Status Card Design System)
- README.md
- OrphanCashflowAuditor.php
- modal-payment-process.blade.php
- Worker
- require
- Index
- rules/graphify.md
- workflows/graphify.md
- UnitInstallment
- Illuminate\Database\Eloquent\Relations\HasMany
- UnitCommission
- table-list.blade.php
- ActivityLog
- static
- modal-direct-proposal.blade.php
- UnitCommissionPayment
- Illuminate\Database\Eloquent\Factories\HasFactory
- require-dev
- daily-activity-reports/index.blade.php
- tab-unit-installments.blade.php
- setup
- WithDatePeriodFilter.php
- web.php
- CashflowSeparationAndExportTest
- daily-activity-reports/partials/header-banner.blade.php
- modal-detail.blade.php
- modal-form.blade.php
- resetFilters
- AdminRoleCrudTest
- tab-land-payments.blade.php
- config
- ActivityLogTest
- closeLandPaymentModal
- modal-export-pdf.blade.php
- InstallmentPayment
- Project
- psr-4
- payables/index.blade.php
- section-commissions.blade.php
- BookingRejectionAndHppVisibilityTest
- search-input.blade.php
- openSettleCommissionModal({{ $c->id }})
- openSettleModal({{ $m->id }})
- openPayReceivableModal({{ $r->id }})
- modal-commission.blade.php
- modal-commission-payment.blade.php
- openWorkerPaymentModal({{ $w->id }})
- Index
- modal-create-bill.blade.php
- modal-create-commission.blade.php
- ContractorAndInstallmentCancellationTest
- modal-create-receivable.blade.php
- modal-pay-receivable.blade.php
- modal-settle-commission.blade.php
- modal-settle-material.blade.php
- modal-settle-worker-payroll.blade.php
- AllPagesTest
- {{ $closeClick }}
- {{ $closeAction }}
- ActivityLogger
- .render
- MaterialPurchaseReceiptController
- Booking
- InactiveAccountLoginTest
- Controller
- OfficialDocument
- modal-edit-transaction.blade.php
- modal-generate.blade.php
- manual-invoices/partials/modal-form.blade.php
- modal-manage-pengawas.blade.php
- modal-project-form.blade.php
- ProjectReportController
- LandPaymentReceiptController
- modal-detail-land-payment.blade.php
- UnpaidInstallmentReportController
- EnsureUserIsActive.php
- UniqueUnitCodeTest
- UnitDetailResponsivenessAndRoleTest
- BookingReceiptController
- .exportPdf
- PayrollReceiptController
- extra
- UserSeeder.php
- ProjectSeeder
- UnitSeeder
- .execute
- dev

## God Nodes (most connected - your core abstractions)
1. `Unit` - 127 edges
2. `User` - 92 edges
3. `Project` - 81 edges
4. `CashflowTransaction` - 78 edges
5. `Worker` - 59 edges
6. `Show` - 56 edges
7. `WorkerUnitPayroll` - 47 edges
8. `Index` - 40 edges
9. `Index` - 39 edges
10. `UnitInstallment` - 35 edges

## Surprising Connections (you probably didn't know these)
- `createTestProjectForValidation()` --calls--> `Project`  [INFERRED]
  tests/Feature/LivewireFileUploadValidationTest.php → app/Models/Project.php
- `AdminRoleCrudTest` --references--> `Project`  [EXTRACTED]
  tests/Feature/AdminRoleCrudTest.php → app/Models/Project.php
- `CashflowSyncAndDeleteTest` --references--> `Project`  [EXTRACTED]
  tests/Feature/CashflowSyncAndDeleteTest.php → app/Models/Project.php
- `AdminRoleCrudTest` --references--> `Unit`  [EXTRACTED]
  tests/Feature/AdminRoleCrudTest.php → app/Models/Unit.php
- `CashflowSyncAndDeleteTest` --references--> `Unit`  [EXTRACTED]
  tests/Feature/CashflowSyncAndDeleteTest.php → app/Models/Unit.php

## Import Cycles
- None detected.

## Communities (331 total, 123 thin omitted)

### Community 3 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 8 - "DailyActivityReport"
Cohesion: 0.08
Nodes (3): Index, DailyActivityReport, DailyActivityReportSeeder

### Community 9 - "scripts"
Cohesion: 0.12
Nodes (17): scripts, octane, post-autoload-dump, post-create-project-cmd, post-update-cmd, pre-package-uninstall, test, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+9 more)

### Community 10 - "PriceProposal"
Cohesion: 0.16
Nodes (3): Approval, HasOne, PriceProposal

### Community 12 - "Index"
Cohesion: 0.10
Nodes (3): ManualInvoiceController, Index, ManualInvoice

### Community 16 - "Illuminate\Http\Request"
Cohesion: 0.24
Nodes (4): AuthController, CashflowReportController, Illuminate\Http\Request, Symfony\Component\HttpFoundation\StreamedResponse

### Community 18 - "package.json"
Cohesion: 0.10
Nodes (20): concurrently, laravel-vite-plugin, micromodal, dependencies, micromodal, devDependencies, concurrently, laravel-vite-plugin (+12 more)

### Community 19 - "Index"
Cohesion: 0.08
Nodes (6): EmployeeSalarySlipController, Index, EmployeePayrollPayment, EmployeeSalary, EmployeeSalarySeeder, Illuminate\Http\Response

### Community 20 - "units/show.blade.php"
Cohesion: 0.09
Nodes (22): livewire.units.partials.header-actions, livewire.units.partials.modal-booking, livewire.units.partials.modal-commission, livewire.units.partials.modal-commission-payment, livewire.units.partials.modal-convert-to-cash, livewire.units.partials.modal-direct-proposal, livewire.units.partials.modal-direct-spp, livewire.units.partials.modal-edit-unit (+14 more)

### Community 22 - "ProjectPayment"
Cohesion: 0.14
Nodes (3): DeleteLandPaymentAction, RecordLandPaymentAction, ProjectPayment

### Community 23 - "projects/show.blade.php"
Cohesion: 0.15
Nodes (12): livewire.projects.partials.header, livewire.projects.partials.kpi-dashboard, livewire.projects.partials.land-purchase-card, livewire.projects.partials.modal-legacy-sale, livewire.projects.partials.modal-payment, livewire.projects.partials.specifications-strip, livewire.projects.partials.tab-cashflow, livewire.projects.partials.tab-payments (+4 more)

### Community 24 - "Livewire\Component"
Cohesion: 0.18
Nodes (3): Livewire\Component, Livewire\WithFileUploads, Livewire\WithPagination

### Community 25 - "CompanyReceivable"
Cohesion: 0.15
Nodes (3): CompanyReceivable, ReceivablePayment, PayablesAndReceivablesSeeder

### Community 26 - "installments/index.blade.php"
Cohesion: 0.15
Nodes (12): livewire.installments.partials.modal-convert-to-cash, livewire.installments.partials.modal-detail-land-payment, livewire.installments.partials.modal-installment-detail, livewire.installments.partials.modal-installment-payment, livewire.installments.partials.modal-land-payment, livewire.installments.partials.modal-setup-installment, livewire.installments.partials.tab-land-payments, livewire.installments.partials.tab-unit-installments (+4 more)

### Community 27 - "cashflow/index.blade.php"
Cohesion: 0.17
Nodes (11): editTransaction({{ $trx->id }}), livewire.cashflow.partials.modal-edit-transaction, livewire.cashflow.partials.modal-image-viewer, livewire.cashflow.partials.modal-manual-transaction, openDetailModal({{ $trx->id }}), openManualModal, livewire.cashflow.partials.modal-detail-transaction, openImageModal( (+3 more)

### Community 28 - "User.php"
Cohesion: 0.12
Nodes (6): Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, GlobalSearchTest, OfficialDocumentSearchFilterTest, PengawasManagementTest, TestCase

### Community 29 - "2. Activity Diagrams (Diagram Aktivitas UML)"
Cohesion: 0.08
Nodes (25): 1. 🚨 CRITICAL: Celah Keamanan pada Route Switch Role (`/switch-role/{role}`), 1. 🐛 CRITICAL: Inkonsistensi Method `isFullyApproved()` pada Model `PriceProposal`, 1. 💡 CRITICAL: Penjumlahan Nomor Surat Resmi Menggunakan `rand()`, 1. Ringkasan Sistem & Peran Pengguna (Role Architecture), 2.1. Activity Diagram 1: Autentikasi & Switch Role (Simulation System), 2.2. Activity Diagram 2: Manajemen Proyek, Unit & Kalkulasi HPP, 2.3. Activity Diagram 3: Pengajuan Harga & Approval Penawaran (< HPP), 2.4. Activity Diagram 4: Penerbitan Surat Pemesanan Properti & E-Signature PDF (+17 more)

### Community 31 - "8. Rancangan Database (Skema Tabel Terbaru)"
Cohesion: 0.08
Nodes (25): 10. Status Konfirmasi Bisnis Terbaru, 1. Ringkasan Sistem, 2. Tech Stack, 3.1. Skema Warna Utama (Palette & Theme), 3.2. Warna Identitas Aktor / Role (Role Color Coding), 3.3. Standardisasi Badge Status (Status Indicators), 3. Panduan Desain & Sistem Warna (Design System & Branding), 4. Aktor & Peran Pengguna (5 Role) (+17 more)

### Community 33 - "employee-salaries/index.blade.php"
Cohesion: 0.25
Nodes (7): livewire.employee-salaries.partials.header-banner, livewire.employee-salaries.partials.kpi-summary, livewire.employee-salaries.partials.modal-payment-process, livewire.employee-salaries.partials.modal-salary-setup, livewire.employee-salaries.partials.tab-payments-table, livewire.employee-salaries.partials.tab-salaries-table, $set(

### Community 34 - "app.blade.php"
Cohesion: 0.33
Nodes (5): components.layouts.partials.confirm-modal, components.layouts.partials.header, components.layouts.partials.sidebar-desktop, components.layouts.partials.sidebar-mobile, components.layouts.partials.toast-notifications

### Community 35 - "documents/index.blade.php"
Cohesion: 0.33
Nodes (5): editDocument({{ $doc->id }}), livewire.documents.partials.modal-generate, livewire.documents.partials.modal-viewer, openGenerateModal, openViewerModal(

### Community 36 - "section-installments.blade.php"
Cohesion: 0.33
Nodes (5): openConvertToCashModal, openInstallmentPaymentModal, openSetupInstallmentModal, editInstallmentPayment({{ $pay->id }}), openViewerModal(

### Community 37 - "manual-invoices/index.blade.php"
Cohesion: 0.33
Nodes (5): editInvoice({{ $inv->id }}), livewire.manual-invoices.partials.modal-form, livewire.manual-invoices.partials.modal-pdf-viewer, openPdfPreview(, openCreateModal

### Community 38 - "section-expenses.blade.php"
Cohesion: 0.33
Nodes (5): editMaterialPurchase({{ $exp->id }}), editPayrollPayment({{ $exp->id }}), editPayrollSetup({{ $exp->id }}), openMaterialModal, openViewerModal(

### Community 39 - "units/index.blade.php"
Cohesion: 0.33
Nodes (5): editUnit({{ $unit->id }}), openBookingModal({{ $unit->id }}), closeModal, openModal, $set(

### Community 40 - "bookings/index.blade.php"
Cohesion: 0.33
Nodes (5): livewire.bookings.partials.booking-form-modal, livewire.bookings.partials.booking-header, livewire.bookings.partials.booking-kpi-summary, livewire.bookings.partials.booking-table-list, livewire.bookings.partials.booking-viewer-modal

### Community 41 - "field-expenses/index.blade.php"
Cohesion: 0.33
Nodes (5): livewire.field-expenses.partials.expense-edit-modal, livewire.field-expenses.partials.expense-header, livewire.field-expenses.partials.expense-kpi-summary, livewire.field-expenses.partials.expense-table-list, livewire.field-expenses.partials.expense-viewer-modal

### Community 42 - "proposals/index.blade.php"
Cohesion: 0.33
Nodes (5): livewire.proposals.partials.proposal-action-modals, livewire.proposals.partials.proposal-form-modal, livewire.proposals.partials.proposal-header, livewire.proposals.partials.proposal-kpi-summary, livewire.proposals.partials.proposal-table-list

### Community 43 - "tutorial/index.blade.php"
Cohesion: 0.33
Nodes (5): livewire.tutorial.partials.tutorial-faq-accordion, livewire.tutorial.partials.tutorial-flow-booking-cash, livewire.tutorial.partials.tutorial-flow-cicilan-dokumen, livewire.tutorial.partials.tutorial-flow-map, livewire.tutorial.partials.tutorial-header

### Community 44 - "workers/index.blade.php"
Cohesion: 0.33
Nodes (5): livewire.workers.partials.worker-assign-modal, livewire.workers.partials.worker-form-modal, livewire.workers.partials.worker-header, livewire.workers.partials.worker-kpi-summary, livewire.workers.partials.worker-table-list

### Community 46 - "section-payroll-borongan.blade.php"
Cohesion: 0.40
Nodes (4): editPayrollSetup({{ $up->id }}), openPayrollPaymentModal({{ $up->id }}), openPayrollSetupModal, openViewerModal(

### Community 47 - "proposal-table-list.blade.php"
Cohesion: 0.50
Nodes (3): openApprovalModal({{ $prop->id }}), openDocModal({{ $prop->id }}), openViewerModal(

### Community 48 - "legacy-sale.blade.php"
Cohesion: 0.40
Nodes (4): livewire.units.partials.legacy-create-modal, livewire.units.partials.legacy-header-info, livewire.units.partials.legacy-table-list, livewire.units.partials.modal-viewer

### Community 50 - "tab-payments.blade.php"
Cohesion: 0.40
Nodes (4): editProjectPayment({{ $pay->id }}), openDetailModal({{ $pay->cashflowTransaction->id }}), openPaymentModal, openViewerModal(

### Community 51 - "header-actions.blade.php"
Cohesion: 0.50
Nodes (3): openBookingModal, openEditUnitModal, openDirectSppModal

### Community 52 - "users/index.blade.php"
Cohesion: 0.50
Nodes (3): openEditModal({{ $u->id }}), openCreateModal, $set(

### Community 53 - "projects/index.blade.php"
Cohesion: 0.33
Nodes (5): editProject({{ $p->id }}), livewire.projects.partials.modal-manage-pengawas, livewire.projects.partials.modal-project-form, openWorkerModal({{ $p->id }}), openModal

### Community 54 - "Illuminate\Database\Seeder"
Cohesion: 0.16
Nodes (5): DatabaseSeeder, PriceProposalSeeder, ProductionSeeder, WorkerSeeder, Illuminate\Database\Seeder

### Community 55 - "WorkerUnitPayroll"
Cohesion: 0.13
Nodes (3): UnitExpensesReportController, WorkerSpkController, WorkerUnitPayroll

### Community 109 - "modal-installment-detail.blade.php"
Cohesion: 0.40
Nodes (4): openSetupModal({{ $selectedDetailInstallment->id }}), closeDetailModal, editInstallmentPayment({{ $pay->id }}), openViewerModal(

### Community 130 - "section-proposals-spp.blade.php"
Cohesion: 0.40
Nodes (4): approveProposal({{ $prop->id }}, , openDirectProposalModal, openDirectSppModal, openViewerModal(

### Community 205 - "🎨 Panduan Standarisasi Tampilan Tombol (Button Design System)"
Cohesion: 0.14
Nodes (13): 🗂️ 1. Hirarki & Kategorisasi Tombol, 📐 2. Standard Ukuran & Radius Tombol (Button Sizing Standard), 🏷️ 3. Pedoman Penamaan Label Tombol (Naming Conventions), 🛠️ 4. Kelas CSS Rekomendasi di `resources/css/app.css`, 🎨 5. Standarisasi Tombol Aksi Berdasarkan Tema Kartu Section (Section Header Cards), 📋 6. Ringkasan Implementasi, 🔹 A. Ukuran Baris Tabel (Table Action Buttons - Compact), 🔹 B. Ukuran Header Section / Toolbar Card (Medium Action) (+5 more)

### Community 206 - "🔍 Panduan Standarisasi Input Search, Filter Dropdown & Form Controls (Dropdown & Search Design System)"
Cohesion: 0.15
Nodes (12): 🗂️ 1. Categorization & Specifications Summary, 📐 2. Standard Layout & Anatomi Input Search (Pencarian Teks), 🔽 3. Standard Select Filter Dropdown (Pilihan Filter Table & Toolbar), 🪟 4. Standard Floating Popover Dropdown Menu (Menu Aksi / Opsi Melayang), 📝 5. Standard Form Input Controls (Form Modal & Entry Data), 🛠️ 6. Kelas CSS Terpusat di `resources/css/app.css`, 📋 7. Ringkasan Prinsip Implementasi, 💡 Contoh Kode Blade Component: (+4 more)

### Community 207 - "User"
Cohesion: 0.18
Nodes (4): User, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, Spatie\Permission\Traits\HasRoles

### Community 208 - "📐 2. Standardisasi Komponen Antarmuka (UI Components Standard)"
Cohesion: 0.17
Nodes (11): 🌟 1. Prinsip Utama Desain (Core Design Principles), 📐 2. Standardisasi Komponen Antarmuka (UI Components Standard), 🗺️ 3. Rencana Eksekusi Overhaul per Modul, A. Stat Metrics Summary Card, B. Filter & Search Toolbar Baris Tunggal, C. Data Table & List View Modern, 🌿 Clean & Uncluttered Layout, D. Modal Form Interaktif & Backdrop Blur (+3 more)

### Community 211 - "CashflowTransaction"
Cohesion: 0.15
Nodes (3): CashflowTransaction, CascadeDeletionService, CashflowSyncAndDeleteTest

### Community 213 - "🎴 Panduan Standarisasi Card Status & Badge Status (Status Card Design System)"
Cohesion: 0.25
Nodes (7): 🗂️ 1. Kategorisasi Summary KPI Status Cards, 📐 2. Standard Layout & Anatomi Summary Status Card, 🏷️ 3. Standard Badge Status (Status Pills di Tabel & Modal), 🛠️ 4. Pembagian Implementasi Bertahap (Phased Implementation Plan), Contoh Blade Component Layout:, Daftar Warna & Status Standard:, 🎴 Panduan Standarisasi Card Status & Badge Status (Status Card Design System)

### Community 214 - "README.md"
Cohesion: 0.25
Nodes (7): About Laravel, Agentic Development, Code of Conduct, Contributing, Learning Laravel, License, Security Vulnerabilities

### Community 215 - "OrphanCashflowAuditor.php"
Cohesion: 0.32
Nodes (3): AuditOrphanCashflows, OrphanCashflowAuditor, Illuminate\Console\Command

### Community 217 - "Worker"
Cohesion: 0.12
Nodes (3): Dashboard, Index, Worker

### Community 218 - "require"
Cohesion: 0.18
Nodes (11): require, barryvdh/laravel-dompdf, laravel/framework, laravel/octane, laravel/tinker, livewire/livewire, php, simplesoftwareio/simple-qrcode (+3 more)

### Community 224 - "UnitInstallment"
Cohesion: 0.12
Nodes (4): DeleteInstallmentPaymentAction, SetupInstallmentSchemeAction, UnitInstallment, FinancialSeeder

### Community 230 - "static"
Cohesion: 0.16
Nodes (3): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, static

### Community 236 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 237 - "daily-activity-reports/index.blade.php"
Cohesion: 0.25
Nodes (7): livewire.daily-activity-reports.partials.filter-bar, livewire.daily-activity-reports.partials.header-banner, livewire.daily-activity-reports.partials.modal-detail, livewire.daily-activity-reports.partials.modal-export-pdf, livewire.daily-activity-reports.partials.modal-form, livewire.daily-activity-reports.partials.modal-viewer, livewire.daily-activity-reports.partials.table-list

### Community 238 - "tab-unit-installments.blade.php"
Cohesion: 0.40
Nodes (4): openDetailModal({{ $inst->id }}), openPaymentModal({{ $inst->id }}), openSetupModal({{ $inst->id }}), $set(

### Community 239 - "setup"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install --ignore-scripts, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 241 - "web.php"
Cohesion: 0.15
Nodes (3): DocumentVerificationController, PayrollVerificationController, ReceiptVerificationController

### Community 251 - "tab-land-payments.blade.php"
Cohesion: 0.33
Nodes (5): openLandPaymentModal({{ $pay->id }}), openLandPaymentModal, openViewerModal(, $set(, showLandPaymentDetail({{ $pay->id }})

### Community 252 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 258 - "InstallmentPayment"
Cohesion: 0.11
Nodes (3): InstallmentInvoiceController, InstallmentPayment, ImageCompressor

### Community 261 - "Project"
Cohesion: 0.20
Nodes (3): Project, createTestProjectForValidation(), SystemE2ESuiteTest

### Community 262 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 263 - "payables/index.blade.php"
Cohesion: 0.12
Nodes (16): livewire.payables.partials.modal-create-bill, livewire.payables.partials.modal-create-commission, livewire.payables.partials.modal-create-receivable, livewire.payables.partials.modal-pay-receivable, livewire.payables.partials.modal-settle-commission, livewire.payables.partials.modal-settle-material, livewire.payables.partials.modal-settle-worker-payroll, livewire.payables.partials.tab-company-receivables (+8 more)

### Community 264 - "section-commissions.blade.php"
Cohesion: 0.50
Nodes (3): openCommissionModal, openCommissionPaymentModal({{ $comm->id }}), openViewerModal(

### Community 297 - "ActivityLogger"
Cohesion: 0.18
Nodes (3): Index, ActivityLogger, ActivityLogsNotificationTabTest

### Community 301 - "Booking"
Cohesion: 0.11
Nodes (4): Index, Booking, BookingSeeder, me_date()

### Community 303 - "Controller"
Cohesion: 0.33
Nodes (3): CashflowVerificationController, Controller, DailyActivityReportPdfController

### Community 304 - "OfficialDocument"
Cohesion: 0.18
Nodes (3): DocumentPdfController, OfficialDocument, OfficialDocumentSeeder

### Community 318 - "EnsureUserIsActive.php"
Cohesion: 0.60
Nodes (3): EnsureUserIsActive, Closure, Symfony\Component\HttpFoundation\Response

### Community 325 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 330 - "dev"
Cohesion: 0.67
Nodes (3): dev, Composer\\Config::disableProcessTimeout, npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan octane:start --server=roadrunner --port=8001\" \"php artisan queue:listen --tries=1 --timeout=0\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=octane,queue,logs,vite --kill-others

## Knowledge Gaps
- **422 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+417 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **123 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Unit` connect `Unit` to `LegacySale`, `InstallmentPayment`, `Index`, `Project`, `WorkerAssignment`, `Show`, `DailyActivityReport`, `BookingRejectionAndHppVisibilityTest`, `PriceProposal`, `Show`, `Index`, `CashflowTransaction.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `Index`, `Illuminate\Http\Request`, `Index`, `WorkerSalaryPayment`, `ProjectPayment`, `ContractorAndInstallmentCancellationTest`, `CompanyReceivable`, `Index`, `AllPagesTest`, `.openSetupModal`, `.render`, `Booking`, `Controller`, `OfficialDocument`, `WorkerUnitPayroll`, `ProjectReportController`, `UniqueUnitCodeTest`, `UnitDetailResponsivenessAndRoleTest`, `.exportPdf`, `UnitSeeder`, `User`, `CashflowTransaction`, `Unit.php`, `UnitInstallment`, `Illuminate\Database\Eloquent\Relations\HasMany`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `CashflowSeparationAndExportTest`, `AdminRoleCrudTest`?**
  _High betweenness centrality (0.050) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `Index`, `Project.php`, `Index`, `Project`, `WorkerAssignment`, `DailyActivityReport`, `BookingRejectionAndHppVisibilityTest`, `PriceProposal`, `Show`, `CashflowTransaction.php`, `Illuminate\Http\Request`, `Index`, `ContractorAndInstallmentCancellationTest`, `CompanyReceivable`, `User.php`, `AllPagesTest`, `ActivityLogger`, `Booking`, `InactiveAccountLoginTest`, `Controller`, `OfficialDocument`, `UniqueUnitCodeTest`, `UnitDetailResponsivenessAndRoleTest`, `UserSeeder.php`, `ProjectSeeder`, `UnitSeeder`, `CashflowTransaction`, `Index`, `Unit.php`, `UnitInstallment`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `CashflowSeparationAndExportTest`, `AdminRoleCrudTest`, `ActivityLogTest`?**
  _High betweenness centrality (0.031) - this node is a cross-community bridge._
- **Why does `CashflowTransaction` connect `CashflowTransaction` to `LegacySale`, `InstallmentPayment`, `Project`, `WorkerAssignment`, `Show`, `PriceProposal`, `Show`, `Index`, `CashflowTransaction.php`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `Illuminate\Http\Request`, `Index`, `Index`, `WorkerSalaryPayment`, `ProjectPayment`, `Livewire\Component`, `CompanyReceivable`, `Booking`, `Controller`, `WorkerUnitPayroll`, `.execute`, `OrphanCashflowAuditor.php`, `UnitInstallment`, `UnitCommission`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `CashflowSeparationAndExportTest`?**
  _High betweenness centrality (0.022) - this node is a cross-community bridge._
- **Are the 91 inferred relationships involving `Unit` (e.g. with `.execute()` and `.exportExcel()`) actually correct?**
  _`Unit` has 91 INFERRED edges - model-reasoned connections that need verification._
- **Are the 62 inferred relationships involving `User` (e.g. with `.login()` and `.switchRole()`) actually correct?**
  _`User` has 62 INFERRED edges - model-reasoned connections that need verification._
- **Are the 59 inferred relationships involving `Project` (e.g. with `.execute()` and `.exportPdf()`) actually correct?**
  _`Project` has 59 INFERRED edges - model-reasoned connections that need verification._
- **Are the 58 inferred relationships involving `CashflowTransaction` (e.g. with `.execute()` and `.execute()`) actually correct?**
  _`CashflowTransaction` has 58 INFERRED edges - model-reasoned connections that need verification._