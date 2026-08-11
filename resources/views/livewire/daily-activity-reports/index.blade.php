<div class="space-y-6">
    <!-- Header Banner & Stat Cards -->
    @include('livewire.daily-activity-reports.partials.header-banner')

    <!-- Filter & Search Bar -->
    @include('livewire.daily-activity-reports.partials.filter-bar')

    <!-- Daily Activity Table List -->
    @include('livewire.daily-activity-reports.partials.table-list')

    <!-- Modal Form (Catat / Edit Laporan) -->
    @include('livewire.daily-activity-reports.partials.modal-form')

    <!-- Modal Detail Popup -->
    @include('livewire.daily-activity-reports.partials.modal-detail')

    <!-- Modal Export PDF Popup -->
    @include('livewire.daily-activity-reports.partials.modal-export-pdf')

    <!-- PDF Viewer Modal -->
    @include('livewire.daily-activity-reports.partials.modal-viewer')
</div>
