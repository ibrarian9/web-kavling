<div class="space-y-6">
    <!-- Header Banner & Summary KPI Cards -->
    <div class="bg-slate-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-40 -top-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800 border border-slate-700 text-purple-400 text-xs font-semibold mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Modul Keuangan Terpadu</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Hutang & Piutang Perusahaan</h1>
                <p class="text-xs text-slate-400 mt-1 max-w-xl">
                    Pusat pemantauan hutang toko material, sisa upah tukang, komisi persenan agen/marketing, serta piutang kasbon staf yang terintegrasi langsung dengan Arus Kas Global.
                </p>
            </div>

            <!-- Dual KPI Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 shrink-0">
                <!-- Card 1: Total Hutang Perusahaan -->
                <div class="bg-slate-800/90 border border-slate-700/80 p-4 rounded-2xl space-y-1 min-w-[210px]">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-rose-400 uppercase tracking-wider">Hutang Perusahaan</span>
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    </div>
                    <div class="text-lg font-black font-mono text-rose-300">
                        Rp {{ number_format($totalCompanyPayables, 0, ',', '.') }}
                    </div>
                    <p class="text-[10px] text-slate-400">Toko Material + Upah + Komisi</p>
                </div>

                <!-- Card 2: Total Piutang Perusahaan -->
                <div class="bg-slate-800/90 border border-slate-700/80 p-4 rounded-2xl space-y-1 min-w-[210px]">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider">Piutang / Kasbon Staf</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    </div>
                    <div class="text-lg font-black font-mono text-emerald-300">
                        Rp {{ number_format($totalCompanyReceivables, 0, ',', '.') }}
                    </div>
                    <p class="text-[10px] text-slate-400">Kasbon Mandor, Tukang, & Marketing</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200 space-y-5">
        
        <!-- REORDERED CONTROLS HEADER: TOP ROW (NAV TABS + DROPDOWN FILTERS) -->
        <div class="space-y-4 border-b border-slate-100 pb-5">
            
            <!-- TOP ROW: 5 TAB NAVIGATION PILLS & DROPDOWN FILTERS -->
            <div class="flex items-center justify-between flex-wrap gap-3">
                
                <!-- Left: 5 Navigation Tabs -->
                <div class="flex items-center gap-1.5 bg-slate-100 p-1.5 rounded-2xl flex-wrap">
                    <button type="button" wire:click="setTab('material_bills')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap shrink-0 {{ $activeTab === 'material_bills' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Hutang Toko Material</span>
                        @if($totalUnpaidMaterialBills > 0)
                            <span class="bg-rose-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ \App\Models\WeeklyMaterialPurchase::where('payment_status', 'belum_lunas')->count() }}</span>
                        @endif
                    </button>
                    <button type="button" wire:click="setTab('worker_payrolls')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap shrink-0 {{ $activeTab === 'worker_payrolls' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Sisa Upah Pekerja</span>
                        @if($totalUnpaidWorkerWages > 0)
                            <span class="bg-rose-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ \App\Models\WorkerUnitPayroll::whereRaw('agreed_salary > paid_amount')->count() }}</span>
                        @endif
                    </button>
                    <button type="button" wire:click="setTab('unit_commissions')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap shrink-0 {{ $activeTab === 'unit_commissions' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Komisi Penjual Unit</span>
                        @if($totalUnpaidCommissions > 0)
                            <span class="bg-rose-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ \App\Models\UnitCommission::where('status', 'belum_dibayar')->count() }}</span>
                        @endif
                    </button>
                    <button type="button" wire:click="setTab('company_receivables')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap shrink-0 {{ $activeTab === 'company_receivables' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Piutang & Kasbon Staf</span>
                        @if($totalCompanyReceivables > 0)
                            <span class="bg-emerald-600 text-white text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ \App\Models\CompanyReceivable::where('status', 'belum_lunas')->count() }}</span>
                        @endif
                    </button>

                    <!-- TAB 5: RIWAYAT LUNAS GLOBAL (BARU) -->
                    <button type="button" wire:click="setTab('settled_history')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap shrink-0 {{ $activeTab === 'settled_history' ? 'bg-emerald-600 text-white shadow-xs' : 'text-emerald-700 bg-emerald-50 hover:bg-emerald-100' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Riwayat Lunas Global</span>
                    </button>
                </div>

                <!-- Right: Dropdown Filters -->
                <div class="flex items-center gap-2">
                    @if($activeTab !== 'company_receivables' && $activeTab !== 'settled_history')
                        <select wire:model.live="filter_project_id" class="select-clean text-xs font-semibold">
                            <option value="">Semua Proyek</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    @endif

                    @if($activeTab !== 'settled_history')
                        <select wire:model.live="filter_status" class="select-clean text-xs font-semibold">
                            <option value="belum_lunas">BELUM LUNAS / TERUTANG</option>
                            <option value="lunas">LUNAS / TERBAYAR</option>
                            <option value="all">SEMUA STATUS</option>
                        </select>
                    @endif
                </div>
            </div>

            <!-- BOTTOM ROW: SEARCH BAR (LEFT) & ACTION BUTTONS (RIGHT) -->
            <div class="flex items-center justify-between flex-wrap gap-3 pt-1">
                <!-- Search Bar (Left) -->
                <x-search-input placeholder="Cari toko / barang / unit / peminjam..." />

                <!-- Action Buttons (Right) -->
                <div class="flex items-center gap-2">
                    @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                        @if($activeTab === 'material_bills' || $activeTab === 'settled_history')
                            <x-button variant="rose" icon="plus" wire:click="openCreateBillModal">Catat Tagihan Material</x-button>
                        @endif

                        @if($activeTab === 'unit_commissions' || $activeTab === 'settled_history')
                            <x-button variant="purple" icon="plus" wire:click="openCreateCommissionModal">Catat Komisi Penjual</x-button>
                        @endif

                        @if($activeTab === 'company_receivables' || $activeTab === 'settled_history')
                            <x-button variant="emerald" icon="plus" wire:click="openCreateReceivableModal">Catat Pinjaman / Kasbon</x-button>
                        @endif
                    @endif
                </div>
            </div>

        </div>

        <!-- 5 MAIN TABS PARTIALS -->
        @include('livewire.payables.partials.tab-material-bills')
        @include('livewire.payables.partials.tab-worker-payrolls')
        @include('livewire.payables.partials.tab-unit-commissions')
        @include('livewire.payables.partials.tab-company-receivables')
        @include('livewire.payables.partials.tab-settled-history')

    </div>

    <!-- 7 MODAL PARTIALS -->
    @include('livewire.payables.partials.modal-settle-material')
    @include('livewire.payables.partials.modal-settle-worker-payroll')
    @include('livewire.payables.partials.modal-create-bill')
    @include('livewire.payables.partials.modal-create-commission')
    @include('livewire.payables.partials.modal-settle-commission')
    @include('livewire.payables.partials.modal-create-receivable')
    @include('livewire.payables.partials.modal-pay-receivable')

    <!-- Media Viewer Modal (Foto Struk / Resi / PDF) -->
    <x-media-viewer-modal 
        :show="$showViewerModal ?? false" 
        :type="$viewerType ?? 'auto'" 
        :url="$viewerUrl ?? ''" 
        :title="$viewerTitle ?? 'Pratinjau Berkas & Dokumen'" 
        closeAction="closeViewerModal"
    />
</div>
