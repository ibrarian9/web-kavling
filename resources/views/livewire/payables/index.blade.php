<div class="space-y-6">
    <!-- Header Banner & Summary KPI Cards -->
    <div class="bg-slate-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-60 h-60 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-40 -top-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800 border border-slate-700 text-purple-400 text-xs font-semibold mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Modul Keuangan Terpadu</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Hutang & Piutang Perusahaan</h1>
                <p class="text-xs text-slate-400 mt-1">
                    Pusat pemantauan hutang toko material, sisa upah tukang, komisi marketing, utang sub-kon & vendor luar proyek, serta piutang kasbon staf yang terintegrasi langsung dengan Arus Kas Global.
                </p>
            </div>

            <!-- Dual KPI Summary Cards: 50% width, Top & Bottom on tablet, side-by-side on wide desktop -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-3 w-full">
                <!-- Card 1: Total Hutang Perusahaan -->
                <div class="bg-slate-800/90 border border-slate-700/80 p-4 rounded-2xl space-y-1 w-full">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-rose-400 uppercase tracking-wider">Hutang Perusahaan</span>
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    </div>
                    <div class="text-lg font-black font-mono text-rose-300">
                        Rp {{ number_format($totalCompanyPayables, 0, ',', '.') }}
                    </div>
                    <p class="text-[10px] text-slate-400">Toko + Upah + Komisi + Sub-kon/Vendor</p>
                </div>

                <!-- Card 2: Total Piutang Perusahaan -->
                <div class="bg-slate-800/90 border border-slate-700/80 p-4 rounded-2xl space-y-1 w-full">
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
        
        <!-- UNIFIED MODERN CONTROLS HEADER -->
        <div class="space-y-4 border-b border-slate-100 pb-5">
            
            <!-- ROW 1: SLEEK SEGMENTED TAB NAVIGATION BAR -->
            <div class="p-1.5 bg-slate-100/90 border border-slate-200/70 rounded-2xl flex items-center gap-1 overflow-x-auto scrollbar-hide">
                <!-- Group 1: Hutang Perusahaan (4 tabs) -->
                <!-- Tab 1: Hutang Toko Material -->
                <button type="button" wire:click="setTab('material_bills')" 
                        class="px-2 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap shrink-0 {{ $activeTab === 'material_bills' ? 'bg-white text-slate-900 shadow-xs ring-1 ring-slate-200/80' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>Toko Material</span>
                    @if($totalUnpaidMaterialBills > 0)
                        <span class="text-[10px] px-1.5 py-0.5 rounded-full font-mono {{ $activeTab === 'material_bills' ? 'bg-rose-100 text-rose-700 font-extrabold' : 'bg-slate-200/80 text-slate-700' }}">{{ \App\Models\WeeklyMaterialPurchase::where('payment_status', 'belum_lunas')->count() }}</span>
                    @endif
                </button>

                <!-- Tab 2: Sisa Upah Pekerja -->
                <button type="button" wire:click="setTab('worker_payrolls')" 
                        class="px-2 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap shrink-0 {{ $activeTab === 'worker_payrolls' ? 'bg-white text-slate-900 shadow-xs ring-1 ring-slate-200/80' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Upah Pekerja</span>
                    @if($totalUnpaidWorkerWages > 0)
                        <span class="text-[10px] px-1.5 py-0.5 rounded-full font-mono {{ $activeTab === 'worker_payrolls' ? 'bg-rose-100 text-rose-700 font-extrabold' : 'bg-slate-200/80 text-slate-700' }}">{{ \App\Models\WorkerUnitPayroll::whereRaw('agreed_salary > paid_amount')->count() }}</span>
                    @endif
                </button>

                <!-- Tab 3: Komisi Penjual Unit -->
                <button type="button" wire:click="setTab('unit_commissions')" 
                        class="px-2 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap shrink-0 {{ $activeTab === 'unit_commissions' ? 'bg-white text-slate-900 shadow-xs ring-1 ring-slate-200/80' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <svg class="w-3.5 h-3.5 text-purple-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Komisi Penjual</span>
                    @if($totalUnpaidCommissions > 0)
                        <span class="text-[10px] px-1.5 py-0.5 rounded-full font-mono {{ $activeTab === 'unit_commissions' ? 'bg-rose-100 text-rose-700 font-extrabold' : 'bg-slate-200/80 text-slate-700' }}">{{ \App\Models\UnitCommission::where('status', 'belum_dibayar')->count() }}</span>
                    @endif
                </button>

                <!-- Tab 4: Utang Sub-kon & Vendor (Baru) -->
                <button type="button" wire:click="setTab('company_debts')" 
                        class="px-2 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap shrink-0 {{ $activeTab === 'company_debts' ? 'bg-white text-slate-900 shadow-xs ring-1 ring-slate-200/80' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Sub-kon & Vendor</span>
                    @if($totalUnpaidCompanyDebtsCount > 0)
                        <span class="text-[10px] px-1.5 py-0.5 rounded-full font-mono {{ $activeTab === 'company_debts' ? 'bg-rose-100 text-rose-700 font-extrabold' : 'bg-slate-200/80 text-slate-700' }}">{{ $totalUnpaidCompanyDebtsCount }}</span>
                    @endif
                </button>

                <!-- Divider between Hutang & Piutang -->
                <div class="h-4 w-px bg-slate-300/80 mx-0.5 shrink-0"></div>

                <!-- Group 2: Piutang Staf / Kasbon -->
                <!-- Tab 5: Piutang & Kasbon Staf -->
                <button type="button" wire:click="setTab('company_receivables')" 
                        class="px-2 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap shrink-0 {{ $activeTab === 'company_receivables' ? 'bg-white text-emerald-900 shadow-xs ring-1 ring-emerald-300/80' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Piutang Staf</span>
                    @if($totalCompanyReceivables > 0)
                        <span class="text-[10px] px-1.5 py-0.5 rounded-full font-mono {{ $activeTab === 'company_receivables' ? 'bg-emerald-100 text-emerald-800 font-extrabold' : 'bg-slate-200/80 text-slate-700' }}">{{ \App\Models\CompanyReceivable::where('status', 'belum_lunas')->count() }}</span>
                    @endif
                </button>

                <!-- Divider between Piutang & Riwayat -->
                <div class="h-4 w-px bg-slate-300/80 mx-0.5 shrink-0"></div>

                <!-- Group 3: Riwayat Lunas Global -->
                <!-- Tab 6: Riwayat Lunas -->
                <button type="button" wire:click="setTab('settled_history')" 
                        class="px-2 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap shrink-0 {{ $activeTab === 'settled_history' ? 'bg-white text-emerald-800 shadow-xs ring-1 ring-emerald-300/80' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50' }}">
                    <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Riwayat Lunas</span>
                </button>
            </div>

            <!-- ROW 2: UNIFIED CONTROL TOOLBAR (SEARCH + FILTERS) -->
            <div class="flex items-center gap-2.5 flex-wrap pt-0.5">
                <!-- Search Input -->
                <div class="flex-1 min-w-[200px] sm:max-w-xs md:max-w-sm">
                    <x-search-input placeholder="Cari data catatan..." />
                </div>

                <!-- Filter Periode Waktu Tanggal -->
                <x-date-period-filter periodModel="datePeriod" startModel="startDate" endModel="endDate" :periodValue="$datePeriod" />

                <!-- Filter Proyek -->
                @if($activeTab !== 'company_receivables' && $activeTab !== 'settled_history')
                    <select wire:model.live="filter_project_id" class="select-clean text-xs font-semibold py-1.5 px-2.5">
                        <option value="">Semua Proyek</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                @endif

                <!-- Filter Status -->
                @if($activeTab !== 'settled_history')
                    <select wire:model.live="filter_status" class="select-clean text-xs font-semibold py-1.5 px-2.5">
                        <option value="belum_lunas">Belum Lunas</option>
                        <option value="lunas">Sudah Lunas</option>
                        <option value="all">Semua Status</option>
                    </select>
                @endif

                <!-- Reset Filter Button -->
                @if($search || $filter_project_id || $filter_status !== 'belum_lunas' || $datePeriod !== 'all' || $startDate || $endDate)
                    <x-reset-filter-button 
                        wire:click="$set('search', ''); $set('filter_project_id', ''); $set('filter_status', 'belum_lunas'); $set('datePeriod', 'all'); $set('startDate', ''); $set('endDate', '');" 
                    />
                @endif
            </div>

        </div>

        <!-- 6 MAIN TABS PARTIALS -->
        @include('livewire.payables.partials.tab-material-bills')
        @include('livewire.payables.partials.tab-worker-payrolls')
        @include('livewire.payables.partials.tab-unit-commissions')
        @include('livewire.payables.partials.tab-company-debts')
        @include('livewire.payables.partials.tab-company-receivables')
        @include('livewire.payables.partials.tab-settled-history')

    </div>

    <!-- MODAL PARTIALS -->
    @include('livewire.payables.partials.modal-settle-material')
    @include('livewire.payables.partials.modal-settle-worker-payroll')
    @include('livewire.payables.partials.modal-create-bill')
    @include('livewire.payables.partials.modal-create-commission')
    @include('livewire.payables.partials.modal-settle-commission')
    @include('livewire.payables.partials.modal-create-debt')
    @include('livewire.payables.partials.modal-pay-debt')
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
