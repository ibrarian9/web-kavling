<div class="space-y-6">
    <!-- Header Title, Subtitle, PDF Rekap CTA & Session Alerts -->
    @include('livewire.field-expenses.partials.expense-header')

    <!-- Summary KPI Cards (Total Pengeluaran, Gaji Worker, Belanja Material) -->
    @include('livewire.field-expenses.partials.expense-kpi-summary')

    <!-- Filters Toolbar, Table List of Expenses & System Loading Component -->
    @include('livewire.field-expenses.partials.expense-table-list')

    <!-- Modal Edit Transaksi Operasional -->
    @include('livewire.field-expenses.partials.expense-edit-modal')

    <!-- Viewer Modal Foto Struk Nota & PDF Resi -->
    @include('livewire.field-expenses.partials.expense-viewer-modal')
</div>
