<div class="space-y-6">

    <!-- Top Navigation & Header -->
    @include('livewire.projects.partials.header')

    <!-- Project Specifications & Workers Strip -->
    @include('livewire.projects.partials.specifications-strip')

    <!-- Skema Pembayaran Lahan Proyek (ke Penjual Tanah) Card Header Summary -->
    <!-- Skema Pembayaran Lahan Proyek (ke Penjual Tanah) Card Header Summary -->
    @if(!auth()->user()->isPengawasProject() && !auth()->user()->isMarketing())
        @include('livewire.projects.partials.land-purchase-card')
    @endif

    <!-- Unit Status & Financial KPI Dashboard Cards -->
    @include('livewire.projects.partials.kpi-dashboard')

    <!-- Navigation Tabs for Integrated View -->
    <div class="border-b border-slate-200 overflow-x-auto scrollbar-hide -mx-1 px-1">
        <div class="flex items-center gap-2 sm:gap-6 text-sm font-bold min-w-max">
            <button wire:click="$set('activeTab', 'units')" class="pb-3 border-b-2 transition flex items-center gap-1.5 sm:gap-2 whitespace-nowrap {{ $activeTab === 'units' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                <span>Penjualan Unit</span>
                <span class="bg-slate-100 text-slate-700 text-xs px-2 py-0.5 rounded-full font-mono">{{ count($unitsList) }}</span>
            </button>

            <button wire:click="$set('activeTab', 'siteplan')" class="pb-3 border-b-2 transition flex items-center gap-1.5 sm:gap-2 whitespace-nowrap {{ $activeTab === 'siteplan' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                <span>Site Plan Visual</span>
                <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-0.5 rounded-full font-mono">{{ count($unitsList) }}</span>
            </button>

            @if(!auth()->user()->isPengawasProject() && !auth()->user()->isMarketing())
                <button wire:click="$set('activeTab', 'payments')" class="pb-3 border-b-2 transition flex items-center gap-1.5 sm:gap-2 whitespace-nowrap {{ $activeTab === 'payments' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Pembayaran Lahan</span>
                    <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded-full font-mono">{{ count($projectPaymentsList) }}</span>
                </button>

                <button wire:click="$set('activeTab', 'cashflow')" class="pb-3 border-b-2 transition flex items-center gap-1.5 sm:gap-2 whitespace-nowrap {{ $activeTab === 'cashflow' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Arus Kas</span>
                    <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-0.5 rounded-full font-mono">{{ count($cashflowTransactions) }}</span>
                </button>
            @endif
        </div>
    </div>

    <!-- TAB 1: Penjualan & Profit Per Unit -->
    @if($activeTab === 'units')
        @include('livewire.projects.partials.tab-units')
    @endif

    <!-- TAB Site Plan Visual -->
    @if($activeTab === 'siteplan')
        @include('livewire.projects.partials.tab-siteplan')
    @endif

    <!-- TAB 2: Skema & Pembayaran Lahan Proyek -->
    @if($activeTab === 'payments' && !auth()->user()->isPengawasProject() && !auth()->user()->isMarketing())
        @include('livewire.projects.partials.tab-payments')
    @endif

    <!-- TAB 3: Laporan Arus Kas Proyek -->
    @if($activeTab === 'cashflow' && !auth()->user()->isPengawasProject() && !auth()->user()->isMarketing())
        @include('livewire.projects.partials.tab-cashflow')
    @endif

    <!-- MODAL CATAT PEMBAYARAN LAHAN KE PENJUAL -->
    @include('livewire.projects.partials.modal-payment')

    <!-- Modal Form Input Penjualan Lalu -->
    @include('livewire.projects.partials.modal-legacy-sale')

    <!-- Modal Detail Alur Keuangan & Audit Trail -->
    @include('livewire.cashflow.partials.modal-detail-transaction')

    <!-- Media Viewer Modal (Foto Struk / Resi / PDF / QR) -->
    <x-media-viewer-modal 
        :show="$showViewerModal ?? false" 
        :type="$viewerType ?? 'auto'" 
        :url="$viewerUrl ?? ''" 
        :title="$viewerTitle ?? 'Pratinjau Berkas & Dokumen'" 
        closeAction="closeViewerModal"
    />
</div>
