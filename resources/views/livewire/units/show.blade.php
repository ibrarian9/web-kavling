<div class="space-y-6">

    <!-- Top Navigation, Header & Metric Cards -->
    @include('livewire.units.partials.header-actions')

    <!-- Main Grid Structure: Left Column (Physical & Workers) & Right Column (Sales, Installment, Expenses) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Specs & Physical Details & Worker Management -->
        <div class="space-y-6 lg:col-span-1">
            <!-- Physical Specifications Card -->
            @include('livewire.units.partials.section-specifications')

            <!-- Assigned Workers (Mandor & Tukang) Card -->
            @include('livewire.units.partials.section-workers')

            <!-- Gaji Borongan Worker Unit Card -->
            @include('livewire.units.partials.section-payroll-borongan')
        </div>

        <!-- Right Column: Proposals, SPP, Financials & Costs -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Proposal & Official Document (SPP) Status Card -->
            @include('livewire.units.partials.section-proposals-spp')

            <!-- Installment & Buyer Payments Card -->
            @include('livewire.units.partials.section-installments')

            <!-- Unit Expenses & Material Purchases Combined Table Card -->
            @include('livewire.units.partials.section-expenses')
        </div>
    </div>

    <!-- Include Floating Modals -->
    @include('livewire.units.partials.modal-worker-assignment')
    @include('livewire.units.partials.modal-booking')
    @include('livewire.units.partials.modal-payroll-setup')
    @include('livewire.units.partials.modal-payroll-payment')
    @include('livewire.units.partials.modal-material-purchase')
    @include('livewire.units.partials.modal-installment-payment')
    @include('livewire.units.partials.modal-setup-installment')
    @include('livewire.units.partials.modal-viewer')
    @include('livewire.units.partials.modal-convert-to-cash')
    @include('livewire.units.partials.modal-edit-unit')
    @include('livewire.units.partials.modal-direct-spp')
    @include('livewire.units.partials.modal-direct-proposal')
</div>
