<div class="space-y-6">
    <!-- Header Title, CTAs & Session Success Alert -->
    @include('livewire.workers.partials.worker-header')

    <!-- Summary KPI Cards Grid (Total Pekerja, Mandor & Tukang) -->
    @include('livewire.workers.partials.worker-kpi-summary')

    <!-- Filters Toolbar, Worker Data Table List & Reusable Loading Indicator -->
    @include('livewire.workers.partials.worker-table-list')

    <!-- Modal Form Create & Edit Worker Data -->
    @include('livewire.workers.partials.worker-form-modal')

    <!-- Modal Form Penugasan Worker ke Proyek & Unit -->
    @include('livewire.workers.partials.worker-assign-modal')
</div>
