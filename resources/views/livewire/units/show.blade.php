<div class="space-y-6">

    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 font-bold text-xs rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-600 font-bold">✕</button>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold text-xs rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 font-bold">✕</button>
        </div>
    @endif

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
</div>
