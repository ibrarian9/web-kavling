<div class="space-y-6">
    <!-- Header Title, Subtitle & CTA -->
    @include('livewire.proposals.partials.proposal-header')

    <!-- Summary KPI Cards (Total Proposal, Menunggu Approval & ACC) -->
    @include('livewire.proposals.partials.proposal-kpi-summary')

    <!-- Proposal Data Table List & System Centered Loading Indicator -->
    @include('livewire.proposals.partials.proposal-table-list')

    <!-- Form Modal Buat & Edit Pengajuan Harga Jual -->
    @include('livewire.proposals.partials.proposal-form-modal')

    <!-- Action Modals (Approval Sejajar, Terbitkan SPP & PDF Viewer) -->
    @include('livewire.proposals.partials.proposal-action-modals')
</div>
