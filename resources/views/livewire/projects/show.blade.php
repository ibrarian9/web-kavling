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

    <!-- Top Sticky Progress Bar on Screen Edge -->
    <div wire:loading.delay.shortest wire:target="setTab, activeTab, unitSearch, statusFilter, typeFilter, page, deleteUnit, openPaymentModal" class="fixed top-0 left-0 right-0 z-[9999] h-1.5 bg-slate-900/10 backdrop-blur-xs">
        <div class="h-full bg-gradient-to-r from-emerald-500 via-teal-400 to-indigo-500 w-full animate-livewire-progress shadow-sm shadow-emerald-500/50"></div>
    </div>

    <!-- Visual Loading Feedback Bar when switching tabs or updating filters -->
    <div wire:loading.flex wire:target="setTab, activeTab, unitSearch, statusFilter, typeFilter" class="w-full items-center justify-between gap-3 p-3 bg-emerald-50/90 border border-emerald-200/90 rounded-2xl text-emerald-900 text-xs font-bold shadow-xs animate-pulse">
        <div class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-emerald-600 animate-spin shrink-0" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Memuat data menu proyek... Mohon tunggu sebentar</span>
        </div>
        <span class="text-[10px] font-mono uppercase bg-emerald-200/80 text-emerald-900 px-2 py-0.5 rounded-full font-extrabold shrink-0">Memuat...</span>
    </div>

    <!-- Navigation Tabs for Integrated Project Management (Card Background Sama dengan Card KPI di Atasnya) -->
    <div class="grid grid-cols-2 {{ (auth()->user()->isFounder() || auth()->user()->isFinance()) ? 'md:grid-cols-4' : 'md:grid-cols-2' }} gap-3 sm:gap-4">
        <!-- Tab 1: Penjualan Unit -->
        <button type="button" wire:click="setTab('units')" 
                class="group relative text-left p-3.5 sm:p-4 rounded-2xl transition-all duration-200 flex flex-col justify-between gap-2.5 {{ $activeTab === 'units' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md ring-2 ring-emerald-500/25' : 'bg-emerald-50/60 hover:bg-emerald-100/70 border border-emerald-200/80 text-emerald-950 shadow-2xs' }}">
            <div class="flex items-center justify-between w-full gap-2">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $activeTab === 'units' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-700' }}">
                    <svg wire:loading.remove wire:target="setTab('units')" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <svg wire:loading wire:target="setTab('units')" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
                <span class="shrink-0 font-mono text-[11px] sm:text-xs font-bold px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full border {{ $activeTab === 'units' ? 'bg-white/20 text-white border-white/30' : 'bg-emerald-100/90 text-emerald-800 border-emerald-200' }}">
                    {{ count($unitsList) }}
                </span>
            </div>
            <div class="min-w-0 w-full">
                <span class="block text-xs sm:text-sm font-extrabold tracking-tight leading-snug {{ $activeTab === 'units' ? 'text-white' : 'text-emerald-950' }}">
                    Penjualan Unit
                </span>
                <span class="block text-[10px] sm:text-[11px] font-medium leading-tight mt-0.5 {{ $activeTab === 'units' ? 'text-emerald-100' : 'text-emerald-700' }}">
                    Status & Rekap Unit
                </span>
            </div>
        </button>

        <!-- Tab 2: Site Plan Visual -->
        <button type="button" wire:click="setTab('siteplan')" 
                class="group relative text-left p-3.5 sm:p-4 rounded-2xl transition-all duration-200 flex flex-col justify-between gap-2.5 {{ $activeTab === 'siteplan' ? 'bg-sky-600 text-white border-sky-600 shadow-md ring-2 ring-sky-500/25' : 'bg-sky-50/60 hover:bg-sky-100/70 border border-sky-200/80 text-sky-950 shadow-2xs' }}">
            <div class="flex items-center justify-between w-full gap-2">
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $activeTab === 'siteplan' ? 'bg-white/20 text-white' : 'bg-sky-100 text-sky-700' }}">
                    <svg wire:loading.remove wire:target="setTab('siteplan')" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    <svg wire:loading wire:target="setTab('siteplan')" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
                <span class="shrink-0 font-mono text-[11px] sm:text-xs font-bold px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full border {{ $activeTab === 'siteplan' ? 'bg-white/20 text-white border-white/30' : 'bg-sky-100/90 text-sky-800 border-sky-200' }}">
                    Peta
                </span>
            </div>
            <div class="min-w-0 w-full">
                <span class="block text-xs sm:text-sm font-extrabold tracking-tight leading-snug {{ $activeTab === 'siteplan' ? 'text-white' : 'text-sky-950' }}">
                    Site Plan Visual
                </span>
                <span class="block text-[10px] sm:text-[11px] font-medium leading-tight mt-0.5 {{ $activeTab === 'siteplan' ? 'text-sky-100' : 'text-sky-700' }}">
                    Denah Interaktif
                </span>
            </div>
        </button>

        @if(auth()->user()->isFounder() || auth()->user()->isFinance())
            <!-- Tab 3: Pembayaran Lahan -->
            <button type="button" wire:click="setTab('payments')" 
                    class="group relative text-left p-3.5 sm:p-4 rounded-2xl transition-all duration-200 flex flex-col justify-between gap-2.5 {{ $activeTab === 'payments' ? 'bg-amber-600 text-white border-amber-600 shadow-md ring-2 ring-amber-500/25' : 'bg-amber-50/60 hover:bg-amber-100/70 border border-amber-200/80 text-amber-950 shadow-2xs' }}">
                <div class="flex items-center justify-between w-full gap-2">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $activeTab === 'payments' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-700' }}">
                        <svg wire:loading.remove wire:target="setTab('payments')" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <svg wire:loading wire:target="setTab('payments')" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                    <span class="shrink-0 font-mono text-[11px] sm:text-xs font-bold px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full border {{ $activeTab === 'payments' ? 'bg-white/20 text-white border-white/30' : 'bg-amber-100/90 text-amber-800 border-amber-200' }}">
                        {{ count($projectPaymentsList) }}
                    </span>
                </div>
                <div class="min-w-0 w-full">
                    <span class="block text-xs sm:text-sm font-extrabold tracking-tight leading-snug {{ $activeTab === 'payments' ? 'text-white' : 'text-amber-950' }}">
                        Pembayaran Lahan
                    </span>
                    <span class="block text-[10px] sm:text-[11px] font-medium leading-tight mt-0.5 {{ $activeTab === 'payments' ? 'text-amber-100' : 'text-amber-700' }}">
                        Setoran ke Pemilik
                    </span>
                </div>
            </button>

            <!-- Tab 4: Arus Kas Proyek -->
            <button type="button" wire:click="setTab('cashflow')" 
                    class="group relative text-left p-3.5 sm:p-4 rounded-2xl transition-all duration-200 flex flex-col justify-between gap-2.5 {{ $activeTab === 'cashflow' ? 'bg-indigo-600 text-white border-indigo-600 shadow-md ring-2 ring-indigo-500/25' : 'bg-indigo-50/60 hover:bg-indigo-100/70 border border-indigo-200/80 text-indigo-950 shadow-2xs' }}">
                <div class="flex items-center justify-between w-full gap-2">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors {{ $activeTab === 'cashflow' ? 'bg-white/20 text-white' : 'bg-indigo-100 text-indigo-700' }}">
                        <svg wire:loading.remove wire:target="setTab('cashflow')" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <svg wire:loading wire:target="setTab('cashflow')" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                    <span class="shrink-0 font-mono text-[11px] sm:text-xs font-bold px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full border {{ $activeTab === 'cashflow' ? 'bg-white/20 text-white border-white/30' : 'bg-indigo-100/90 text-indigo-800 border-indigo-200' }}">
                        {{ count($cashflowTransactions) }}
                    </span>
                </div>
                <div class="min-w-0 w-full">
                    <span class="block text-xs sm:text-sm font-extrabold tracking-tight leading-snug {{ $activeTab === 'cashflow' ? 'text-white' : 'text-indigo-950' }}">
                        Arus Kas Proyek
                    </span>
                    <span class="block text-[10px] sm:text-[11px] font-medium leading-tight mt-0.5 {{ $activeTab === 'cashflow' ? 'text-indigo-100' : 'text-indigo-700' }}">
                        Buku Kas Masuk/Keluar
                    </span>
                </div>
            </button>
        @endif
    </div>

    <!-- Active Tab Content Area with Smooth Loading State -->
    <div class="relative min-h-[350px]" wire:loading.class="opacity-40 pointer-events-none transition-opacity duration-200" wire:target="setTab, activeTab, unitSearch, statusFilter, typeFilter">
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
    </div>

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
