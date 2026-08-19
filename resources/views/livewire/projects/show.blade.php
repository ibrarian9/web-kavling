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

    <!-- Navigation Tabs for Integrated Project Management -->
    <div class="bg-slate-100/90 p-1.5 sm:p-2 rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-2xs">
        <div class="grid grid-cols-2 {{ (!auth()->user()->isPengawasProject() && !auth()->user()->isMarketing()) ? 'lg:grid-cols-4' : 'lg:grid-cols-2' }} gap-1.5 sm:gap-2">
            <!-- Tab 1: Penjualan Unit -->
            <button type="button" wire:click="$set('activeTab', 'units')" 
                    class="group relative text-left p-3 sm:p-3.5 rounded-xl sm:rounded-2xl transition-all duration-200 flex items-center justify-between gap-2 sm:gap-3 {{ $activeTab === 'units' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/80 ring-1 ring-emerald-500/20' : 'bg-transparent text-slate-600 hover:text-slate-900 hover:bg-white/60 border border-transparent' }}">
                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $activeTab === 'units' ? 'bg-emerald-500 text-white shadow-xs' : 'bg-slate-200/70 text-slate-600 group-hover:bg-slate-300/70 group-hover:text-slate-800' }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-xs sm:text-sm font-black tracking-tight truncate {{ $activeTab === 'units' ? 'text-slate-900' : 'text-slate-700' }}">Penjualan Unit</span>
                        <span class="block text-[10px] sm:text-[11px] font-medium text-slate-400 truncate hidden sm:block">Status & Rekap Unit</span>
                    </div>
                </div>
                <span class="shrink-0 font-mono text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-full {{ $activeTab === 'units' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-200/80 text-slate-700' }}">
                    {{ count($unitsList) }}
                </span>
            </button>

            <!-- Tab 2: Site Plan Visual -->
            <button type="button" wire:click="$set('activeTab', 'siteplan')" 
                    class="group relative text-left p-3 sm:p-3.5 rounded-xl sm:rounded-2xl transition-all duration-200 flex items-center justify-between gap-2 sm:gap-3 {{ $activeTab === 'siteplan' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/80 ring-1 ring-teal-500/20' : 'bg-transparent text-slate-600 hover:text-slate-900 hover:bg-white/60 border border-transparent' }}">
                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $activeTab === 'siteplan' ? 'bg-teal-600 text-white shadow-xs' : 'bg-slate-200/70 text-slate-600 group-hover:bg-slate-300/70 group-hover:text-slate-800' }}">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-xs sm:text-sm font-black tracking-tight truncate {{ $activeTab === 'siteplan' ? 'text-slate-900' : 'text-slate-700' }}">Site Plan Visual</span>
                        <span class="block text-[10px] sm:text-[11px] font-medium text-slate-400 truncate hidden sm:block">Denah Kavling Interaktif</span>
                    </div>
                </div>
                <span class="shrink-0 font-mono text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-full {{ $activeTab === 'siteplan' ? 'bg-teal-100 text-teal-800 border border-teal-200' : 'bg-slate-200/80 text-slate-700' }}">
                    Peta
                </span>
            </button>

            @if(!auth()->user()->isPengawasProject() && !auth()->user()->isMarketing())
                <!-- Tab 3: Pembayaran Lahan -->
                <button type="button" wire:click="$set('activeTab', 'payments')" 
                        class="group relative text-left p-3 sm:p-3.5 rounded-xl sm:rounded-2xl transition-all duration-200 flex items-center justify-between gap-2 sm:gap-3 {{ $activeTab === 'payments' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/80 ring-1 ring-amber-500/20' : 'bg-transparent text-slate-600 hover:text-slate-900 hover:bg-white/60 border border-transparent' }}">
                    <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $activeTab === 'payments' ? 'bg-amber-600 text-white shadow-xs' : 'bg-slate-200/70 text-slate-600 group-hover:bg-slate-300/70 group-hover:text-slate-800' }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-xs sm:text-sm font-black tracking-tight truncate {{ $activeTab === 'payments' ? 'text-slate-900' : 'text-slate-700' }}">Pembayaran Lahan</span>
                            <span class="block text-[10px] sm:text-[11px] font-medium text-slate-400 truncate hidden sm:block">Setoran ke Pemilik Tanah</span>
                        </div>
                    </div>
                    <span class="shrink-0 font-mono text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-full {{ $activeTab === 'payments' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-slate-200/80 text-slate-700' }}">
                        {{ count($projectPaymentsList) }}
                    </span>
                </button>

                <!-- Tab 4: Arus Kas Proyek -->
                <button type="button" wire:click="$set('activeTab', 'cashflow')" 
                        class="group relative text-left p-3 sm:p-3.5 rounded-xl sm:rounded-2xl transition-all duration-200 flex items-center justify-between gap-2 sm:gap-3 {{ $activeTab === 'cashflow' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/80 ring-1 ring-indigo-500/20' : 'bg-transparent text-slate-600 hover:text-slate-900 hover:bg-white/60 border border-transparent' }}">
                    <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $activeTab === 'cashflow' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-200/70 text-slate-600 group-hover:bg-slate-300/70 group-hover:text-slate-800' }}">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <span class="block text-xs sm:text-sm font-black tracking-tight truncate {{ $activeTab === 'cashflow' ? 'text-slate-900' : 'text-slate-700' }}">Arus Kas Proyek</span>
                            <span class="block text-[10px] sm:text-[11px] font-medium text-slate-400 truncate hidden sm:block">Buku Kas Masuk & Keluar</span>
                        </div>
                    </div>
                    <span class="shrink-0 font-mono text-[10px] sm:text-xs font-bold px-2 py-0.5 rounded-full {{ $activeTab === 'cashflow' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : 'bg-slate-200/80 text-slate-700' }}">
                        {{ count($cashflowTransactions) }}
                    </span>
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
