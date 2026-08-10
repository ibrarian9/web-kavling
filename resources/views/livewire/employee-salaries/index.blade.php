<div class="space-y-6">
    @include('livewire.employee-salaries.partials.header-banner')

    @include('livewire.employee-salaries.partials.kpi-summary')

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-2 flex items-center gap-2 shadow-xs">
        <button wire:click="$set('activeTab', 'salaries')" class="flex-1 py-2.5 px-4 rounded-xl text-xs font-extrabold transition flex items-center justify-center gap-2 {{ $activeTab === 'salaries' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2H7a2 2 0 01-2 2v14a2 2 0 012 2z"/></svg>
            <span>Standar Gaji & Tunjangan Karyawan ({{ $salaries->count() }})</span>
        </button>

        <button wire:click="$set('activeTab', 'payments')" class="flex-1 py-2.5 px-4 rounded-xl text-xs font-extrabold transition flex items-center justify-center gap-2 {{ $activeTab === 'payments' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-500 hover:text-slate-800' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Histori Pembayaran Gaji & Slip PDF</span>
        </button>
    </div>

    @if($activeTab === 'salaries')
        @include('livewire.employee-salaries.partials.tab-salaries-table')
    @endif

    @if($activeTab === 'payments')
        @include('livewire.employee-salaries.partials.tab-payments-table')
    @endif

    @include('livewire.employee-salaries.partials.modal-salary-setup')

    @include('livewire.employee-salaries.partials.modal-payment-process')
</div>
