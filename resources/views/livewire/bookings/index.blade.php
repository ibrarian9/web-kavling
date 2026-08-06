<div class="space-y-6">
    <!-- Header Title, Subtitle, CTA & Session Alerts -->
    @include('livewire.bookings.partials.booking-header')

    <!-- Summary KPI Metric Cards (Total Booking & DP Aktif) -->
    @include('livewire.bookings.partials.booking-kpi-summary')

    <!-- Filters Toolbar, Bookings Table List & Reusable Loading Indicator -->
    @include('livewire.bookings.partials.booking-table-list')

    <!-- Modal Form Catat & Edit Booking / DP -->
    @include('livewire.bookings.partials.booking-form-modal')

    <!-- Viewer Modal Invoice PDF & Foto Struk Resi -->
    @include('livewire.bookings.partials.booking-viewer-modal')
</div>
