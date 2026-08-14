# Graph Report - web-kavling  (2026-08-13)

## Corpus Check
- 383 files · ~190,683 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 1623 nodes · 2909 edges · 270 communities (181 shown, 89 thin omitted)
- Extraction: 84% EXTRACTED · 16% INFERRED · 0% AMBIGUOUS · INFERRED: 463 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `6975f8c7`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Illuminate\Database\Seeder
- OfficialDocument
- ActivityLogTest
- composer.json
- Index
- Index
- WorkerSalaryPayment
- Show
- DailyActivityReport
- scripts
- ActivityLogger
- Show
- Index
- Index
- Illuminate\Database\Eloquent\Relations\BelongsTo
- ProjectPayment
- Booking
- Index
- package.json
- Index
- units/show.blade.php
- Worker
- LegacySale
- projects/show.blade.php
- Livewire\Component
- DocumentPdfController
- installments/index.blade.php
- cashflow/index.blade.php
- 2. Activity Diagrams (Diagram Aktivitas UML)
- web.php
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
- Index
- section-payroll-borongan.blade.php
- proposal-table-list.blade.php
- legacy-sale.blade.php
- booking-table-list.blade.php
- tab-payments.blade.php
- header-actions.blade.php
- users/index.blade.php
- projects/index.blade.php
- ActivityLog
- InstallmentInvoiceController
- modal-payment.blade.php
- tab-siteplan.blade.php
- worker-table-list.blade.php
- section-workers.blade.php
- tab-cashflow.blade.php
- expense-table-list.blade.php
- booking-viewer-modal.blade.php
- proposal-action-modals.blade.php
- legacy-table-list.blade.php
- closeEditModal
- closeLegacyModal
- closeViewer
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
- modal-viewer.blade.php
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
- UnitInstallment
- modal-payment-process.blade.php
- Index
- require
- Illuminate\Database\Eloquent\Factories\HasFactory
- .exportPdf
- rules/graphify.md
- workflows/graphify.md
- Illuminate\Database\Eloquent\Relations\HasMany
- InstallmentPayment
- Project
- table-list.blade.php
- WorkerAssignment
- OrphanCashflowAuditor
- modal-direct-proposal.blade.php
- CompanyReceivable
- WorkerUnitPayroll
- require-dev
- daily-activity-reports/index.blade.php
- ReceivablePayment
- setup
- Unit
- Controller
- Illuminate\Http\Request
- daily-activity-reports/partials/header-banner.blade.php
- modal-detail.blade.php
- modal-form.blade.php
- filter-bar.blade.php
- .exportPdf
- User.php
- config
- MaterialPurchaseReceiptController
- .exportPdf
- modal-export-pdf.blade.php
- daily-activity-reports/partials/modal-viewer.blade.php
- LandPaymentReceiptController
- EnsureUserIsActive.php
- psr-4
- payables/index.blade.php
- extra
- BookingReceiptController
- PayrollReceiptController
- dev

## God Nodes (most connected - your core abstractions)
1. `Unit` - 119 edges
2. `User` - 87 edges
3. `Project` - 69 edges
4. `CashflowTransaction` - 66 edges
5. `Worker` - 54 edges
6. `Show` - 48 edges
7. `WorkerUnitPayroll` - 41 edges
8. `Index` - 31 edges
9. `Booking` - 31 edges
10. `TestCase` - 31 edges

## Surprising Connections (you probably didn't know these)
- `CashflowSyncAndDeleteTest` --references--> `Project`  [EXTRACTED]
  tests/Feature/CashflowSyncAndDeleteTest.php → app/Models/Project.php
- `AdminRoleCrudTest` --references--> `Unit`  [EXTRACTED]
  tests/Feature/AdminRoleCrudTest.php → app/Models/Unit.php
- `CashflowSyncAndDeleteTest` --references--> `Unit`  [EXTRACTED]
  tests/Feature/CashflowSyncAndDeleteTest.php → app/Models/Unit.php
- `ActivityLogsNotificationTabTest` --references--> `User`  [EXTRACTED]
  tests/Feature/ActivityLogsNotificationTabTest.php → app/Models/User.php
- `AdminRoleCrudTest` --references--> `User`  [EXTRACTED]
  tests/Feature/AdminRoleCrudTest.php → app/Models/User.php

## Import Cycles
- None detected.

## Communities (270 total, 89 thin omitted)

### Community 0 - "Illuminate\Database\Seeder"
Cohesion: 0.13
Nodes (11): BookingSeeder, DailyActivityReportSeeder, DatabaseSeeder, FinancialSeeder, OfficialDocumentSeeder, PriceProposalSeeder, ProductionSeeder, ProjectSeeder (+3 more)

### Community 3 - "composer.json"
Cohesion: 0.14
Nodes (13): autoload-dev, psr-4, description, keywords, license, minimum-stability, name, prefer-stable (+5 more)

### Community 9 - "scripts"
Cohesion: 0.12
Nodes (17): scripts, octane, post-autoload-dump, post-create-project-cmd, post-update-cmd, pre-package-uninstall, test, Illuminate\\Foundation\\ComposerScripts::postAutoloadDump (+9 more)

### Community 10 - "ActivityLogger"
Cohesion: 0.14
Nodes (3): Index, ActivityLogger, ActivityLogsNotificationTabTest

### Community 12 - "Index"
Cohesion: 0.11
Nodes (3): ManualInvoiceController, Index, ManualInvoice

### Community 13 - "Index"
Cohesion: 0.10
Nodes (3): Index, Approval, PriceProposal

### Community 14 - "Illuminate\Database\Eloquent\Relations\BelongsTo"
Cohesion: 0.16
Nodes (3): UnitCommission, WeeklyMaterialPurchase, Illuminate\Database\Eloquent\Relations\BelongsTo

### Community 16 - "Booking"
Cohesion: 0.11
Nodes (4): Index, Dashboard, Booking, me_date()

### Community 18 - "package.json"
Cohesion: 0.10
Nodes (20): concurrently, laravel-vite-plugin, micromodal, dependencies, micromodal, devDependencies, concurrently, laravel-vite-plugin (+12 more)

### Community 19 - "Index"
Cohesion: 0.06
Nodes (9): EmployeeSalarySlipController, Index, EmployeePayrollPayment, EmployeeSalary, UserFactory, EmployeeSalarySeeder, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Http\Response (+1 more)

### Community 20 - "units/show.blade.php"
Cohesion: 0.10
Nodes (19): livewire.units.partials.header-actions, livewire.units.partials.modal-booking, livewire.units.partials.modal-convert-to-cash, livewire.units.partials.modal-direct-proposal, livewire.units.partials.modal-direct-spp, livewire.units.partials.modal-edit-unit, livewire.units.partials.modal-installment-payment, livewire.units.partials.modal-material-purchase (+11 more)

### Community 21 - "Worker"
Cohesion: 0.10
Nodes (3): Index, Worker, WorkerSeeder

### Community 23 - "projects/show.blade.php"
Cohesion: 0.14
Nodes (13): livewire.projects.partials.header, livewire.projects.partials.kpi-dashboard, livewire.projects.partials.land-purchase-card, livewire.projects.partials.modal-legacy-sale, livewire.projects.partials.modal-payment, livewire.projects.partials.specifications-strip, livewire.projects.partials.tab-cashflow, livewire.projects.partials.tab-payments (+5 more)

### Community 24 - "Livewire\Component"
Cohesion: 0.20
Nodes (3): Livewire\Component, Livewire\WithFileUploads, Livewire\WithPagination

### Community 26 - "installments/index.blade.php"
Cohesion: 0.18
Nodes (10): livewire.installments.partials.modal-convert-to-cash, livewire.installments.partials.modal-installment-detail, livewire.installments.partials.modal-installment-payment, livewire.installments.partials.modal-setup-installment, openDetailModal({{ $inst->id }}), openPaymentModal({{ $inst->id }}), openSetupModal, closeViewerModal (+2 more)

### Community 27 - "cashflow/index.blade.php"
Cohesion: 0.18
Nodes (10): editTransaction({{ $trx->id }}), livewire.cashflow.partials.modal-manual-transaction, openDetailModal({{ $trx->id }}), openManualModal, closeImageModal, livewire.cashflow.partials.modal-detail-transaction, openImageModal(, $set( (+2 more)

### Community 29 - "2. Activity Diagrams (Diagram Aktivitas UML)"
Cohesion: 0.08
Nodes (25): 1. 🚨 CRITICAL: Celah Keamanan pada Route Switch Role (`/switch-role/{role}`), 1. 🐛 CRITICAL: Inkonsistensi Method `isFullyApproved()` pada Model `PriceProposal`, 1. 💡 CRITICAL: Penjumlahan Nomor Surat Resmi Menggunakan `rand()`, 1. Ringkasan Sistem & Peran Pengguna (Role Architecture), 2.1. Activity Diagram 1: Autentikasi & Switch Role (Simulation System), 2.2. Activity Diagram 2: Manajemen Proyek, Unit & Kalkulasi HPP, 2.3. Activity Diagram 3: Pengajuan Harga & Approval Penawaran (< HPP), 2.4. Activity Diagram 4: Penerbitan Surat Pemesanan Properti & E-Signature PDF (+17 more)

### Community 30 - "web.php"
Cohesion: 0.17
Nodes (3): DocumentVerificationController, PayrollVerificationController, ReceiptVerificationController

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
Nodes (5): editDocument({{ $doc->id }}), openGenerateModal, closeViewerModal, openViewerModal(, $set(

### Community 36 - "section-installments.blade.php"
Cohesion: 0.33
Nodes (5): openConvertToCashModal, openInstallmentPaymentModal, openSetupInstallmentModal, editInstallmentPayment({{ $pay->id }}), openViewerModal(

### Community 37 - "manual-invoices/index.blade.php"
Cohesion: 0.33
Nodes (5): editInvoice({{ $inv->id }}), openPdfPreview(, closeModal, closeViewerModal, openCreateModal

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
Cohesion: 0.40
Nodes (4): editProposal({{ $prop->id }}), openApprovalModal({{ $prop->id }}), openDocModal({{ $prop->id }}), openViewerModal(

### Community 48 - "legacy-sale.blade.php"
Cohesion: 0.40
Nodes (4): livewire.units.partials.legacy-create-modal, livewire.units.partials.legacy-header-info, livewire.units.partials.legacy-table-list, livewire.units.partials.modal-viewer

### Community 49 - "booking-table-list.blade.php"
Cohesion: 0.50
Nodes (3): editBooking({{ $b->id }}), openImageModal(, openViewerModal(

### Community 50 - "tab-payments.blade.php"
Cohesion: 0.50
Nodes (3): editProjectPayment({{ $pay->id }}), openDetailModal({{ $pay->cashflowTransaction->id }}), openPaymentModal

### Community 51 - "header-actions.blade.php"
Cohesion: 0.50
Nodes (3): openBookingModal, openEditUnitModal, openDirectSppModal

### Community 52 - "users/index.blade.php"
Cohesion: 0.50
Nodes (3): openEditModal({{ $u->id }}), openCreateModal, $set(

### Community 53 - "projects/index.blade.php"
Cohesion: 0.50
Nodes (3): closeModal, openModal, $set(

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
Cohesion: 0.06
Nodes (9): Index, User, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, Spatie\Permission\Traits\HasRoles, BookingRejectionAndHppVisibilityTest, CashflowSeparationAndExportTest, ContractorAndInstallmentCancellationTest (+1 more)

### Community 208 - "📐 2. Standardisasi Komponen Antarmuka (UI Components Standard)"
Cohesion: 0.17
Nodes (11): 🌟 1. Prinsip Utama Desain (Core Design Principles), 📐 2. Standardisasi Komponen Antarmuka (UI Components Standard), 🗺️ 3. Rencana Eksekusi Overhaul per Modul, A. Stat Metrics Summary Card, B. Filter & Search Toolbar Baris Tunggal, C. Data Table & List View Modern, 🌿 Clean & Uncluttered Layout, D. Modal Form Interaktif & Backdrop Blur (+3 more)

### Community 211 - "CashflowTransaction"
Cohesion: 0.18
Nodes (3): CashflowTransaction, CascadeDeletionService, CashflowSyncAndDeleteTest

### Community 213 - "🎴 Panduan Standarisasi Card Status & Badge Status (Status Card Design System)"
Cohesion: 0.25
Nodes (7): 🗂️ 1. Kategorisasi Summary KPI Status Cards, 📐 2. Standard Layout & Anatomi Summary Status Card, 🏷️ 3. Standard Badge Status (Status Pills di Tabel & Modal), 🛠️ 4. Pembagian Implementasi Bertahap (Phased Implementation Plan), Contoh Blade Component Layout:, Daftar Warna & Status Standard:, 🎴 Panduan Standarisasi Card Status & Badge Status (Status Card Design System)

### Community 214 - "README.md"
Cohesion: 0.25
Nodes (7): About Laravel, Agentic Development, Code of Conduct, Contributing, Learning Laravel, License, Security Vulnerabilities

### Community 218 - "require"
Cohesion: 0.18
Nodes (11): require, barryvdh/laravel-dompdf, laravel/framework, laravel/octane, laravel/tinker, livewire/livewire, php, simplesoftwareio/simple-qrcode (+3 more)

### Community 227 - "Project"
Cohesion: 0.10
Nodes (5): ProjectReportController, GlobalSearch, Project, AdminRoleCrudTest, AllPagesTest

### Community 228 - "table-list.blade.php"
Cohesion: 0.50
Nodes (3): deleteReport({{ $rep->id }}), editReport({{ $rep->id }}), showReportDetail({{ $rep->id }})

### Community 229 - "WorkerAssignment"
Cohesion: 0.11
Nodes (3): Index, WorkerAssignment, PengawasManagementTest

### Community 230 - "OrphanCashflowAuditor"
Cohesion: 0.33
Nodes (3): AuditOrphanCashflows, OrphanCashflowAuditor, Illuminate\Console\Command

### Community 236 - "require-dev"
Cohesion: 0.22
Nodes (9): require-dev, fakerphp/faker, laravel/pail, laravel/pao, laravel/pint, mockery/mockery, nunomaduro/collision, pestphp/pest (+1 more)

### Community 237 - "daily-activity-reports/index.blade.php"
Cohesion: 0.25
Nodes (7): livewire.daily-activity-reports.partials.filter-bar, livewire.daily-activity-reports.partials.header-banner, livewire.daily-activity-reports.partials.modal-detail, livewire.daily-activity-reports.partials.modal-export-pdf, livewire.daily-activity-reports.partials.modal-form, livewire.daily-activity-reports.partials.modal-viewer, livewire.daily-activity-reports.partials.table-list

### Community 239 - "setup"
Cohesion: 0.25
Nodes (8): post-root-package-install, setup, composer install, npm install --ignore-scripts, npm run build, @php artisan key:generate, @php artisan migrate --force, @php -r \"file_exists('.env') || copy('.env.example', '.env');\

### Community 240 - "Unit"
Cohesion: 0.10
Nodes (4): Unit, Illuminate\Database\Eloquent\Relations\HasOne, UniqueUnitCodeTest, UnitDetailResponsivenessAndRoleTest

### Community 241 - "Controller"
Cohesion: 0.29
Nodes (3): CashflowVerificationController, Controller, UnpaidInstallmentReportController

### Community 242 - "Illuminate\Http\Request"
Cohesion: 0.27
Nodes (3): AuthController, DailyActivityReportPdfController, Illuminate\Http\Request

### Community 251 - "User.php"
Cohesion: 0.13
Nodes (5): Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, GlobalSearchTest, OfficialDocumentSearchFilterTest, TestCase

### Community 252 - "config"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 261 - "EnsureUserIsActive.php"
Cohesion: 0.60
Nodes (3): EnsureUserIsActive, Closure, Symfony\Component\HttpFoundation\Response

### Community 262 - "psr-4"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 263 - "payables/index.blade.php"
Cohesion: 0.20
Nodes (9): openCreateBillModal, openCreateCommissionModal, openCreateReceivableModal, openPayReceivableModal({{ $r->id }}), openSettleCommissionModal({{ $c->id }}), openSettleModal({{ $m->id }}), openWorkerPaymentModal({{ $w->id }}), $set( (+1 more)

### Community 264 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 270 - "dev"
Cohesion: 0.67
Nodes (3): dev, Composer\\Config::disableProcessTimeout, npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan octane:start --server=roadrunner --port=8001\" \"php artisan queue:listen --tries=1 --timeout=0\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=octane,queue,logs,vite --kill-others

## Knowledge Gaps
- **381 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+376 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **89 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Unit` connect `Unit` to `Index`, `Index`, `WorkerSalaryPayment`, `Show`, `DailyActivityReport`, `Show`, `Index`, `Index`, `Illuminate\Database\Eloquent\Relations\BelongsTo`, `ProjectPayment`, `Booking`, `Index`, `Worker`, `LegacySale`, `Project.php`, `Index`, `User`, `CashflowTransaction`, `UnitInstallment`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `.exportPdf`, `Illuminate\Database\Eloquent\Relations\HasMany`, `InstallmentPayment`, `Project`, `WorkerAssignment`, `CompanyReceivable`, `Controller`, `.exportPdf`, `.exportPdf`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **Why does `User` connect `User` to `OfficialDocument`, `ActivityLogTest`, `Index`, `DailyActivityReport`, `ActivityLogger`, `Show`, `Index`, `Booking`, `Index`, `Worker`, `Project.php`, `ActivityLog`, `CashflowTransaction`, `Illuminate\Database\Eloquent\Factories\HasFactory`, `InstallmentPayment`, `Project`, `WorkerAssignment`, `CompanyReceivable`, `Unit`, `Illuminate\Http\Request`, `User.php`?**
  _High betweenness centrality (0.034) - this node is a cross-community bridge._
- **Why does `Index` connect `Index` to `Livewire\Component`, `CashflowTransaction`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Are the 83 inferred relationships involving `Unit` (e.g. with `.exportExcel()` and `.exportPdf()`) actually correct?**
  _`Unit` has 83 INFERRED edges - model-reasoned connections that need verification._
- **Are the 63 inferred relationships involving `User` (e.g. with `.login()` and `.switchRole()`) actually correct?**
  _`User` has 63 INFERRED edges - model-reasoned connections that need verification._
- **Are the 47 inferred relationships involving `Project` (e.g. with `.exportPdf()` and `.verify()`) actually correct?**
  _`Project` has 47 INFERRED edges - model-reasoned connections that need verification._
- **Are the 46 inferred relationships involving `CashflowTransaction` (e.g. with `.exportExcel()` and `.exportPdf()`) actually correct?**
  _`CashflowTransaction` has 46 INFERRED edges - model-reasoned connections that need verification._