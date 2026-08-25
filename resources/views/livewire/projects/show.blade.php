<div class="space-y-6">

    <!-- Top Navigation & Header -->
    @include('livewire.projects.partials.header')

    <!-- Project Specifications & Workers Strip -->
    @include('livewire.projects.partials.specifications-strip')

    <!-- Skema Pembayaran Lahan Proyek (ke Penjual Tanah) - Founder & Finance Only -->
    @if(auth()->user()->isFounder() || auth()->user()->isFinance())
        @include('livewire.projects.partials.land-purchase-card')
    @endif

    <!-- Unit Status & Financial KPI Dashboard Cards -->
    @include('livewire.projects.partials.kpi-dashboard')

    <!-- Navigation Tabs for Integrated Project Management (Card Background Sama dengan Card KPI di Atasnya) -->
    <div class="grid grid-cols-2 {{ (auth()->user()->isFounder() || auth()->user()->isFinance()) ? 'xl:grid-cols-4' : 'xl:grid-cols-2' }} gap-3 sm:gap-4">
        <!-- Tab 1: Penjualan Unit -->
        <button type="button" wire:click="$set('activeTab', 'units')" 
                class="group relative text-left p-4 sm:p-5 rounded-2xl transition-all duration-200 flex items-center justify-between gap-3 bg-white border {{ $activeTab === 'units' ? 'border-emerald-500 ring-2 ring-emerald-500/20 shadow-md' : 'border-slate-200/80 shadow-2xs hover:border-slate-300 hover:shadow-xs' }}">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $activeTab === 'units' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-600 border border-emerald-100 group-hover:bg-emerald-100' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
                <div class="min-w-0">
                    <span class="block text-xs sm:text-sm font-black tracking-tight whitespace-normal {{ $activeTab === 'units' ? 'text-emerald-700' : 'text-slate-800 group-hover:text-slate-900' }}">Penjualan Unit</span>
                    <span class="block text-[10px] sm:text-[11px] font-medium text-slate-400 truncate mt-0.5">Status & Rekap Unit</span>
                </div>
            </div>
            <span class="shrink-0 font-mono text-xs font-bold px-2.5 py-1 rounded-full {{ $activeTab === 'units' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200/80' }}">
                {{ count($unitsList) }}
            </span>
        </button>

        <!-- Tab 2: Site Plan Visual -->
        <button type="button" wire:click="$set('activeTab', 'siteplan')" 
                class="group relative text-left p-4 sm:p-5 rounded-2xl transition-all duration-200 flex items-center justify-between gap-3 bg-white border {{ $activeTab === 'siteplan' ? 'border-teal-500 ring-2 ring-teal-500/20 shadow-md' : 'border-slate-200/80 shadow-2xs hover:border-slate-300 hover:shadow-xs' }}">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $activeTab === 'siteplan' ? 'bg-teal-600 text-white shadow-xs' : 'bg-teal-50 text-teal-600 border border-teal-100 group-hover:bg-teal-100' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                </div>
                <div class="min-w-0">
                    <span class="block text-xs sm:text-sm font-black tracking-tight whitespace-normal {{ $activeTab === 'siteplan' ? 'text-teal-700' : 'text-slate-800 group-hover:text-slate-900' }}">Site Plan Visual</span>
                    <span class="block text-[10px] sm:text-[11px] font-medium text-slate-400 truncate mt-0.5">Denah Interaktif</span>
                </div>
            </div>
            <span class="shrink-0 font-mono text-xs font-bold px-2.5 py-1 rounded-full {{ $activeTab === 'siteplan' ? 'bg-teal-100 text-teal-800 border border-teal-200' : 'bg-slate-100 text-slate-600 border border-slate-200/80' }}">
                Peta
            </span>
        </button>

        @if(auth()->user()->isFounder() || auth()->user()->isFinance())
            <!-- Tab 3: Pembayaran Lahan -->
            <button type="button" wire:click="$set('activeTab', 'payments')" 
                    class="group relative text-left p-4 sm:p-5 rounded-2xl transition-all duration-200 flex items-center justify-between gap-3 bg-white border {{ $activeTab === 'payments' ? 'border-amber-500 ring-2 ring-amber-500/20 shadow-md' : 'border-slate-200/80 shadow-2xs hover:border-slate-300 hover:shadow-xs' }}">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $activeTab === 'payments' ? 'bg-amber-600 text-white shadow-xs' : 'bg-amber-50 text-amber-600 border border-amber-100 group-hover:bg-amber-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-xs sm:text-sm font-black tracking-tight whitespace-normal {{ $activeTab === 'payments' ? 'text-amber-700' : 'text-slate-800 group-hover:text-slate-900' }}">Pembayaran Lahan</span>
                        <span class="block text-[10px] sm:text-[11px] font-medium text-slate-400 truncate mt-0.5">Setoran ke Pemilik</span>
                    </div>
                </div>
                <span class="shrink-0 font-mono text-xs font-bold px-2.5 py-1 rounded-full {{ $activeTab === 'payments' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-600 border border-slate-200/80' }}">
                    {{ count($projectPaymentsList) }}
                </span>
            </button>

            <!-- Tab 4: Arus Kas Proyek -->
            <button type="button" wire:click="$set('activeTab', 'cashflow')" 
                    class="group relative text-left p-4 sm:p-5 rounded-2xl transition-all duration-200 flex items-center justify-between gap-3 bg-white border {{ $activeTab === 'cashflow' ? 'border-indigo-500 ring-2 ring-indigo-500/20 shadow-md' : 'border-slate-200/80 shadow-2xs hover:border-slate-300 hover:shadow-xs' }}">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $activeTab === 'cashflow' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-indigo-50 text-indigo-600 border border-indigo-100 group-hover:bg-indigo-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <span class="block text-xs sm:text-sm font-black tracking-tight whitespace-normal {{ $activeTab === 'cashflow' ? 'text-indigo-700' : 'text-slate-800 group-hover:text-slate-900' }}">Arus Kas Proyek</span>
                        <span class="block text-[10px] sm:text-[11px] font-medium text-slate-400 truncate mt-0.5">Buku Kas Masuk/Keluar</span>
                    </div>
                </div>
                <span class="shrink-0 font-mono text-xs font-bold px-2.5 py-1 rounded-full {{ $activeTab === 'cashflow' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : 'bg-slate-100 text-slate-600 border border-slate-200/80' }}">
                    {{ count($cashflowTransactions) }}
                </span>
            </button>
        @endif
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
