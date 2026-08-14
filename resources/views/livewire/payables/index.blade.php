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
                    <button type="button" wire:click="setTab('material_bills')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $activeTab === 'material_bills' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        <span>Hutang Toko Material</span>
                        @if($totalUnpaidMaterialBills > 0)
                            <span class="bg-rose-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ \App\Models\WeeklyMaterialPurchase::where('payment_status', 'belum_lunas')->count() }}</span>
                        @endif
                    </button>
                    <button type="button" wire:click="setTab('worker_payrolls')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $activeTab === 'worker_payrolls' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Sisa Upah Pekerja</span>
                        @if($totalUnpaidWorkerWages > 0)
                            <span class="bg-rose-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ \App\Models\WorkerUnitPayroll::whereRaw('agreed_salary > paid_amount')->count() }}</span>
                        @endif
                    </button>
                    <button type="button" wire:click="setTab('unit_commissions')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $activeTab === 'unit_commissions' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Komisi Penjual Unit</span>
                        @if($totalUnpaidCommissions > 0)
                            <span class="bg-rose-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ \App\Models\UnitCommission::where('status', 'belum_dibayar')->count() }}</span>
                        @endif
                    </button>
                    <button type="button" wire:click="setTab('company_receivables')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $activeTab === 'company_receivables' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>Piutang & Kasbon Staf</span>
                        @if($totalCompanyReceivables > 0)
                            <span class="bg-emerald-600 text-white text-[10px] px-1.5 py-0.5 rounded-full font-mono">{{ \App\Models\CompanyReceivable::where('status', 'belum_lunas')->count() }}</span>
                        @endif
                    </button>

                    <!-- TAB 5: RIWAYAT LUNAS GLOBAL (BARU) -->
                    <button type="button" wire:click="setTab('settled_history')" class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $activeTab === 'settled_history' ? 'bg-emerald-600 text-white shadow-xs' : 'text-emerald-700 bg-emerald-50 hover:bg-emerald-100' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Riwayat Lunas Global</span>
                    </button>
                </div>

                <!-- Right: Dropdown Filters -->
                <div class="flex items-center gap-2">
                    @if($activeTab !== 'company_receivables' && $activeTab !== 'settled_history')
                        <select wire:model.live="filter_project_id" class="select-clean text-xs">
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
                <div class="relative w-full sm:w-80">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari toko / barang / unit / peminjam..." class="input-clean text-xs w-full pl-9">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Action Buttons (Right) -->
                <div class="flex items-center gap-2">
                    @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                        @if($activeTab === 'material_bills' || $activeTab === 'settled_history')
                            <button type="button" wire:click="openCreateBillModal" class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center gap-1.5 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Catat Tagihan Material</span>
                            </button>
                        @endif

                        @if($activeTab === 'unit_commissions' || $activeTab === 'settled_history')
                            <button type="button" wire:click="openCreateCommissionModal" class="px-3.5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center gap-1.5 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Catat Komisi Penjual</span>
                            </button>
                        @endif

                        @if($activeTab === 'company_receivables' || $activeTab === 'settled_history')
                            <button type="button" wire:click="openCreateReceivableModal" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center gap-1.5 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Catat Pinjaman / Kasbon</span>
                            </button>
                        @endif
                    @endif
                </div>
            </div>

        </div>

        <!-- TAB 1: TAGIHAN MATERIAL TOKO -->
        @if($activeTab === 'material_bills')
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                        <span>Daftar Tagihan Belanja Material & Operasional</span>
                        <span class="text-xs text-slate-500 font-normal">({{ $materialBills->total() }} Item)</span>
                    </h3>
                    <div class="text-xs font-mono font-bold text-rose-700 bg-rose-50 border border-rose-200 px-3 py-1 rounded-xl">
                        Subtotal Hutang Toko: Rp {{ number_format($totalUnpaidMaterialBills, 0, ',', '.') }}
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs text-slate-600 min-w-[750px]">
                        <thead class="bg-slate-50 font-bold text-slate-700 uppercase tracking-wider text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="p-3">Tanggal Beli</th>
                                <th class="p-3">Proyek & Unit</th>
                                <th class="p-3">Toko / Supplier</th>
                                <th class="p-3">Barang / Uraian</th>
                                <th class="p-3">Total Nominal</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($materialBills as $m)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-3 font-mono font-medium">{{ \Carbon\Carbon::parse($m->purchase_date)->format('d/m/Y') }}</td>
                                    <td class="p-3">
                                        <span class="font-bold text-slate-800 block">{{ $m->project->name ?? 'Operasional Umum' }}</span>
                                        <span class="text-[10px] text-slate-500 font-mono">Unit: {{ $m->unit ? $m->unit->code : '-' }}</span>
                                    </td>
                                    <td class="p-3 font-semibold text-slate-700">{{ $m->store_name ?: '-' }}</td>
                                    <td class="p-3 font-bold text-slate-900">
                                        {{ $m->item_name }}
                                        <span class="block text-[10px] font-normal text-slate-500 font-mono">{{ $m->quantity }} {{ $m->unit_measure }} @ Rp {{ number_format($m->unit_price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="p-3 font-mono font-bold text-rose-700 text-sm">Rp {{ number_format($m->total_price, 0, ',', '.') }}</td>
                                    <td class="p-3">
                                        @if($m->payment_status === 'lunas')
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px] inline-flex items-center gap-1">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                LUNAS
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-rose-100 text-rose-800 rounded-full font-bold text-[10px] inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                                                HUTANG TOKO
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if($m->payment_status !== 'lunas')
                                                @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                                    <button type="button" wire:click="openSettleModal({{ $m->id }})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition shadow-xs">
                                                        Bayar Lunas
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-[11px] text-emerald-600 font-semibold">Terbayar</span>
                                            @endif

                                            @if(auth()->user()->isFounder())
                                                <button type="button" wire:confirm="Hapus catatan tagihan material ini?" wire:click="deleteMaterialPurchase({{ $m->id }})" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Hapus Tagihan">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-400">Tidak ada data tagihan toko material.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>{{ $materialBills->links() }}</div>
            </div>
        @endif

        <!-- TAB 2: SISA UPAH PEKERJA -->
        @if($activeTab === 'worker_payrolls')
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                        <span>Daftar Sisa Upah Terutang Pekerja Lapangan</span>
                        <span class="text-xs text-slate-500 font-normal">({{ $workerPayrolls->total() }} Kontrak)</span>
                    </h3>
                    <div class="text-xs font-mono font-bold text-blue-700 bg-blue-50 border border-blue-200 px-3 py-1 rounded-xl">
                        Subtotal Sisa Upah Worker: Rp {{ number_format($totalUnpaidWorkerWages, 0, ',', '.') }}
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs text-slate-600 min-w-[750px]">
                        <thead class="bg-slate-50 font-bold text-slate-700 uppercase tracking-wider text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="p-3">Nama Pekerja / Tukang</th>
                                <th class="p-3">Proyek & Unit</th>
                                <th class="p-3">Uraian Pekerjaan</th>
                                <th class="p-3">Total Kontrak</th>
                                <th class="p-3">Sudah Dibayar</th>
                                <th class="p-3">Sisa Terutang</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($workerPayrolls as $w)
                                @php $sisaUpah = max(0, (float)$w->agreed_salary - (float)$w->paid_amount); @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-3 font-bold text-slate-900">
                                        {{ $w->worker->name ?? '-' }}
                                        <span class="block text-[10px] text-slate-500 font-normal capitalize">{{ $w->worker->type ?? 'Tukang' }}</span>
                                    </td>
                                    <td class="p-3">
                                        <span class="font-bold text-slate-800 block">{{ $w->project->name ?? '-' }}</span>
                                        <span class="text-[10px] text-slate-500 font-mono">Unit: {{ $w->unit ? $w->unit->code : '-' }}</span>
                                    </td>
                                    <td class="p-3 text-slate-700">{{ $w->notes ?: 'Pekerjaan Borongan Unit' }}</td>
                                    <td class="p-3 font-mono font-bold text-slate-800">Rp {{ number_format($w->agreed_salary, 0, ',', '.') }}</td>
                                    <td class="p-3 font-mono font-bold text-emerald-600">Rp {{ number_format($w->paid_amount, 0, ',', '.') }}</td>
                                    <td class="p-3 font-mono font-bold text-rose-700 text-sm">Rp {{ number_format($sisaUpah, 0, ',', '.') }}</td>
                                    <td class="p-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if($sisaUpah > 0)
                                                @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                                    <button type="button" wire:click="openWorkerPaymentModal({{ $w->id }})" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition shadow-xs">
                                                        Bayar Upah
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-[11px] text-emerald-600 font-semibold">Lunas</span>
                                            @endif

                                            @if(auth()->user()->isFounder())
                                                <button type="button" wire:confirm="Hapus kontrak upah worker ini?" wire:click="deleteWorkerPayroll({{ $w->id }})" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Hapus Upah">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-400">Tidak ada sisa upah terutang pekerja.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>{{ $workerPayrolls->links() }}</div>
            </div>
        @endif

        <!-- TAB 3: HUTANG KOMISI PENJUAL UNIT -->
        @if($activeTab === 'unit_commissions')
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                        <span>Daftar Hutang Komisi / Fee Penjual per Unit</span>
                        <span class="text-xs text-slate-500 font-normal">({{ $unitCommissions->total() }} Komisi)</span>
                    </h3>
                    <div class="text-xs font-mono font-bold text-purple-700 bg-purple-50 border border-purple-200 px-3 py-1 rounded-xl">
                        Subtotal Hutang Komisi: Rp {{ number_format($totalUnpaidCommissions, 0, ',', '.') }}
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs text-slate-600 min-w-[750px]">
                        <thead class="bg-slate-50 font-bold text-slate-700 uppercase tracking-wider text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="p-3">Tgl Catat</th>
                                <th class="p-3">Proyek & Unit</th>
                                <th class="p-3">Penjual / Marketing</th>
                                <th class="p-3">Persenan (%)</th>
                                <th class="p-3">Nominal Komisi</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($unitCommissions as $c)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-3 font-mono font-medium">{{ $c->created_at->format('d/m/Y') }}</td>
                                    <td class="p-3">
                                        <span class="font-bold text-slate-800 block">{{ $c->project->name ?? 'Non-Proyek' }}</span>
                                        <span class="text-[10px] text-slate-500 font-mono">Unit: {{ $c->unit ? $c->unit->code : '-' }}</span>
                                    </td>
                                    <td class="p-3 font-bold text-purple-900">
                                        {{ $c->seller_name }}
                                        @if($c->seller_phone)
                                            <span class="block text-[10px] text-slate-500 font-mono font-normal">{{ $c->seller_phone }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3 font-mono font-bold text-slate-700">{{ $c->percentage > 0 ? $c->percentage . '%' : '-' }}</td>
                                    <td class="p-3 font-mono font-bold text-purple-700 text-sm">Rp {{ number_format($c->commission_amount, 0, ',', '.') }}</td>
                                    <td class="p-3">
                                        @if($c->status === 'lunas')
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px] inline-flex items-center gap-1">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                LUNAS
                                            </span>
                                        @elseif($c->status === 'berjalan')
                                            <span class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full font-bold text-[10px] inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span>
                                                DICICIL
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-purple-100 text-purple-800 rounded-full font-bold text-[10px] inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-purple-600 animate-pulse"></span>
                                                BELUM DIBAYAR
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if($c->status !== 'lunas')
                                                @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                                    <button type="button" wire:click="openSettleCommissionModal({{ $c->id }})" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold text-xs transition shadow-xs">
                                                        Bayar Komisi
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-[11px] text-emerald-600 font-semibold">Lunas</span>
                                            @endif

                                            @if(auth()->user()->isFounder())
                                                <button type="button" wire:confirm="Hapus catatan komisi ini?" wire:click="deleteCommission({{ $c->id }})" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Hapus Komisi">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-400">Tidak ada catatan hutang komisi penjual.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>{{ $unitCommissions->links() }}</div>
            </div>
        @endif

        <!-- TAB 4: PIUTANG & KASBON STAF / WORKERS -->
        @if($activeTab === 'company_receivables')
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                        <span>Daftar Piutang / Uang Dipinjam Staf & Workers (Kasbon)</span>
                        <span class="text-xs text-slate-500 font-normal">({{ $companyReceivables->total() }} Peminjam)</span>
                    </h3>
                    <div class="text-xs font-mono font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-xl">
                        Subtotal Total Piutang: Rp {{ number_format($totalCompanyReceivables, 0, ',', '.') }}
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs text-slate-600 min-w-[750px]">
                        <thead class="bg-slate-50 font-bold text-slate-700 uppercase tracking-wider text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="p-3">Tanggal Pinjam</th>
                                <th class="p-3">Peminjam (Mandor/Tukang/Marketing)</th>
                                <th class="p-3">Total Pinjaman</th>
                                <th class="p-3">Sudah Dikembalikan</th>
                                <th class="p-3">Sisa Piutang</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($companyReceivables as $r)
                                @php $sisaRec = max(0, (float)$r->amount - (float)$r->paid_amount); @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-3 font-mono font-medium">{{ \Carbon\Carbon::parse($r->loan_date)->format('d/m/Y') }}</td>
                                    <td class="p-3 font-bold text-slate-900">
                                        {{ $r->debtor_name }}
                                        @if($r->notes)
                                            <span class="block text-[10px] text-slate-500 font-normal">{{ $r->notes }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3 font-mono font-bold text-slate-800">Rp {{ number_format($r->amount, 0, ',', '.') }}</td>
                                    <td class="p-3 font-mono font-bold text-emerald-600">Rp {{ number_format($r->paid_amount, 0, ',', '.') }}</td>
                                    <td class="p-3 font-mono font-bold text-amber-700 text-sm">Rp {{ number_format($sisaRec, 0, ',', '.') }}</td>
                                    <td class="p-3">
                                        @if($r->status === 'lunas')
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px] inline-flex items-center gap-1">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                LUNAS / LUNAS DIPULANGKAN
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full font-bold text-[10px] inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                                BELUM LUNAS
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if($sisaRec > 0)
                                                @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                                    <button type="button" wire:click="openPayReceivableModal({{ $r->id }})" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition shadow-xs">
                                                        Terima Pengembalian
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-[11px] text-emerald-600 font-semibold">Lunas</span>
                                            @endif

                                            @if(auth()->user()->isFounder())
                                                <button type="button" wire:confirm="Hapus catatan piutang ini?" wire:click="deleteReceivable({{ $r->id }})" class="p-1.5 text-slate-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Hapus Piutang">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-slate-400">Tidak ada catatan piutang / kasbon staf.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div>{{ $companyReceivables->links() }}</div>
            </div>
        @endif

        <!-- TAB 5: RIWAYAT LUNAS GLOBAL (BARU) -->
        @if($activeTab === 'settled_history')
            @php $historyList = $settledHistory ?? collect(); @endphp
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                        <span>Riwayat Lunas Hutang & Piutang Perusahaan (Global)</span>
                        <span class="text-xs text-slate-500 font-normal">({{ $historyList->count() }} Transaksi Terbayar)</span>
                    </h3>
                    <div class="text-xs font-mono font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-xl">
                        Total Riwayat Terbayar: {{ $historyList->count() }} Transaksi
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-200">
                    <table class="w-full text-left text-xs text-slate-600 min-w-[800px]">
                        <thead class="bg-slate-50 font-bold text-slate-700 uppercase tracking-wider text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="p-3">Tgl Lunas / Setoran</th>
                                <th class="p-3">Kategori Navigasi</th>
                                <th class="p-3">Rincian / Penjual / Supplier / Pekerja</th>
                                <th class="p-3">Proyek & Unit / Metode</th>
                                <th class="p-3">Nominal Terbayar</th>
                                <th class="p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($historyList as $sh)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-3 font-mono font-medium">
                                        {{ $sh->date ? (is_string($sh->date) ? \Carbon\Carbon::parse($sh->date)->format('d/m/Y') : $sh->date->format('d/m/Y')) : '-' }}
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2.5 py-1 rounded-full font-bold text-[10px] border {{ $sh->badge_class }}">
                                            {{ $sh->category_name }}
                                        </span>
                                    </td>
                                    <td class="p-3 font-bold text-slate-900">
                                        {{ $sh->title }}
                                        @if($sh->notes)
                                            <span class="block text-[10px] text-slate-500 font-normal italic">{{ $sh->notes }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-slate-700 font-medium">{{ $sh->sub_info }}</td>
                                    <td class="p-3 font-mono font-bold text-emerald-700 text-sm">Rp {{ number_format($sh->amount, 0, ',', '.') }}</td>
                                    <td class="p-3">
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-bold text-[10px] inline-flex items-center gap-1">
                                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            {{ $sh->status_label }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-400">Belum ada riwayat lunas hutang & piutang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>

    <!-- MODAL 1: SETTLEMENT MATERIAL BILL -->
    @if($showSettleModal)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base">Pelunasan Tagihan Material Toko</h3>
                    <button wire:click="$set('showSettleModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <form wire:submit.prevent="processMaterialSettlement" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tanggal Bayar Lunas <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="settle_payment_date" required class="input-clean w-full">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Metode Pembayaran <span class="text-rose-500">*</span></label>
                        <select wire:model="settle_payment_method" class="select-clean w-full font-semibold">
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Cash / Tunai">Cash / Tunai</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Bukti Transfer / Resi Nota</label>
                        <input type="file" wire:model="settle_receipt_photo" accept="image/*,.pdf" class="input-clean w-full">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Catatan</label>
                        <input type="text" wire:model="settle_notes" class="input-clean w-full">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showSettleModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary bg-emerald-600 hover:bg-emerald-700">Pelunasan Lunas & Catat Kas Keluar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 2: WORKER SALARY PAYMENT -->
    @if($showWorkerPaymentModal)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base">Pembayaran Upah Pekerja Lapangan</h3>
                    <button wire:click="$set('showWorkerPaymentModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <form wire:submit.prevent="processWorkerPayment" class="space-y-4 text-xs">
                    <x-currency-input
                        label="Nominal Pembayaran Upah (Rp)"
                        model="worker_payment_amount"
                        :value="$worker_payment_amount"
                        placeholder="1.000.000"
                        badgeColor="blue"
                        required
                    />
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tanggal Pembayaran <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="worker_payment_date" required class="input-clean w-full">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Metode Pembayaran <span class="text-rose-500">*</span></label>
                        <select wire:model="worker_payment_method" class="select-clean w-full font-semibold">
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Cash / Tunai">Cash / Tunai</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Catatan / Termin</label>
                        <input type="text" wire:model="worker_payment_notes" class="input-clean w-full">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showWorkerPaymentModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary bg-blue-600 hover:bg-blue-700">Konfirmasi Pembayaran & Catat Kas Keluar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 3: CREATE NEW MATERIAL / OPERATIONAL BILL -->
    @if($showCreateBillModal)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="p-2 rounded-xl bg-rose-50 text-rose-700 border border-rose-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base">Catat Tagihan Belanja Material / Vendor</h3>
                            <p class="text-[11px] text-slate-500">Mencatat hutang ke toko material atau pengeluaran operasional proyek</p>
                        </div>
                    </div>
                    <button wire:click="$set('showCreateBillModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <form wire:submit.prevent="saveNewBill" class="space-y-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Pilih Proyek (Opsional)</label>
                            <select wire:model.live="new_project_id" class="select-clean w-full">
                                <option value="">Non-Proyek / Operasional Umum</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Pilih Unit (Opsional)</label>
                            <select wire:model="new_unit_id" class="select-clean w-full">
                                <option value="">Semua Unit / Umum</option>
                                @foreach($availableUnits as $u)
                                    <option value="{{ $u->id }}">Unit {{ $u->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nama Toko / Supplier <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="new_store_name" placeholder="Contoh: TB Subur Jaya" required class="input-clean w-full">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nama Barang / Tagihan <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="new_item_name" placeholder="Contoh: Semen Gresik 50 Sak" required class="input-clean w-full">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Jumlah (Qty)</label>
                            <input type="number" step="0.01" wire:model.live="new_quantity" required class="input-clean w-full">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Satuan</label>
                            <input type="text" wire:model="new_unit_measure" placeholder="sak / m3 / ret" required class="input-clean w-full">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Harga Satuan (Rp)</label>
                            <input type="number" wire:model.live="new_unit_price" required class="input-clean w-full">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 p-3 rounded-2xl border border-slate-200">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Total Nominal Tagihan (Rp)</label>
                            <input type="number" wire:model="new_total_price" readonly class="input-clean w-full font-bold text-rose-700 text-sm bg-white">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Status Pembayaran Awal</label>
                            <select wire:model="new_payment_status" class="select-clean w-full font-bold">
                                <option value="belum_lunas">HUTANG TOKO / BELUM LUNAS</option>
                                <option value="lunas">LUNAS (LANGSUNG KAS KELUAR)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tanggal Belanja / Tagihan</label>
                        <input type="date" wire:model="new_purchase_date" required class="input-clean w-full">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Upload Bukti Nota / Foto (Opsional)</label>
                        <input type="file" wire:model="new_receipt_photo" accept="image/*,.pdf" class="input-clean w-full">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Catatan / Keterangan</label>
                        <input type="text" wire:model="new_notes" placeholder="Catatan syarat tempo..." class="input-clean w-full">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showCreateBillModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary bg-rose-600 hover:bg-rose-700">Simpan Tagihan Material</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 4: CREATE NEW UNIT COMMISSION -->
    @if($showCreateCommissionModal)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="p-2 rounded-xl bg-purple-50 text-purple-700 border border-purple-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base">Catat Hutang Komisi Penjual Unit</h3>
                            <p class="text-[11px] text-slate-500">Mencatat persenan / fee komisi untuk agen, marketing internal, atau broker</p>
                        </div>
                    </div>
                    <button wire:click="$set('showCreateCommissionModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <form wire:submit.prevent="saveCommission" class="space-y-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Pilih Proyek (Opsional)</label>
                            <select wire:model.live="comm_project_id" class="select-clean w-full">
                                <option value="">Non-Proyek / Umum</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Pilih Unit (Opsional)</label>
                            <select wire:model="comm_unit_id" class="select-clean w-full">
                                <option value="">Semua Unit / Umum</option>
                                @foreach($commAvailableUnits as $u)
                                    <option value="{{ $u->id }}">Unit {{ $u->code }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Penjual / Agent / Marketing <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="comm_seller_name" placeholder="Contoh: Pak Agus Broker Eksternal" required class="input-clean w-full">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">No. HP / Kontak Penjual</label>
                            <input type="text" wire:model="comm_seller_phone" placeholder="08123456789" class="input-clean w-full">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Persentase Komisi (%)</label>
                            <input type="number" step="0.1" wire:model="comm_percentage" placeholder="2.5" class="input-clean w-full font-bold">
                        </div>
                    </div>

                    <x-currency-input
                        label="Total Nominal Komisi (Rp)"
                        model="comm_amount"
                        :value="$comm_amount"
                        placeholder="5.000.000"
                        badgeColor="purple"
                        required
                    />

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Catatan / Keterangan</label>
                        <input type="text" wire:model="comm_notes" placeholder="Catatan unit closing..." class="input-clean w-full">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showCreateCommissionModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary bg-purple-600 hover:bg-purple-700">Simpan Hutang Komisi</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 5: SETTLE UNIT COMMISSION -->
    @if($showSettleCommissionModal)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base">Bayar Cicilan Komisi Penjual Unit</h3>
                    <button wire:click="$set('showSettleCommissionModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <form wire:submit.prevent="processCommissionSettlement" class="space-y-4 text-xs">
                    <x-currency-input
                        label="Nominal Pembayaran Cicilan (Rp)"
                        model="settle_comm_amount"
                        :value="$settle_comm_amount"
                        placeholder="2.500.000"
                        badgeColor="purple"
                        required
                    />

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tanggal Bayar Komisi <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="settle_comm_date" required class="input-clean w-full">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Metode Pembayaran <span class="text-rose-500">*</span></label>
                        <select wire:model="settle_comm_method" class="select-clean w-full font-semibold">
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Cash / Tunai">Cash / Tunai</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Bukti Transfer / Kuitansi</label>
                        <input type="file" wire:model="settle_comm_photo" accept="image/*,.pdf" class="input-clean w-full">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Catatan</label>
                        <input type="text" wire:model="settle_comm_notes" class="input-clean w-full">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showSettleCommissionModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary bg-purple-600 hover:bg-purple-700">Konfirmasi Cicilan & Catat Kas Keluar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 6: CREATE COMPANY RECEIVABLE / KASBON -->
    @if($showCreateReceivableModal)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="p-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base">Catat Piutang Perusahaan / Kasbon Staf & Worker</h3>
                            <p class="text-[11px] text-slate-500">Mencatat uang perusahaan yang dipinjam oleh Mandor, Tukang, atau Marketing</p>
                        </div>
                    </div>
                    <button wire:click="$set('showCreateReceivableModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <form wire:submit.prevent="saveReceivable" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Pilih Kategori Peminjam <span class="text-rose-500">*</span></label>
                        <select wire:model.live="rec_debtor_type" class="select-clean w-full font-semibold">
                            <option value="worker">Pekerja Lapangan / Mandor / Tukang</option>
                            <option value="user">Staf Internal / Marketing / User SIM</option>
                            <option value="other">Peminjam Lainnya / Pihak Luar</option>
                        </select>
                    </div>

                    @if($rec_debtor_type === 'worker')
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Pilih Mandor / Tukang <span class="text-rose-500">*</span></label>
                            <select wire:model="rec_worker_id" class="select-clean w-full font-semibold">
                                <option value="">-- Pilih Pekerja --</option>
                                @foreach($allWorkers as $wk)
                                    <option value="{{ $wk->id }}">{{ $wk->name }} ({{ ucfirst($wk->type) }})</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif($rec_debtor_type === 'user')
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Pilih Staf / User SIM <span class="text-rose-500">*</span></label>
                            <select wire:model="rec_user_id" class="select-clean w-full font-semibold">
                                <option value="">-- Pilih User Staf --</option>
                                @foreach($allUsers as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ ucfirst($u->role) }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Peminjam / Kasbon <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="rec_debtor_name" placeholder="Contoh: Mandor Slamet (Uang Muka Servis Motor)" required class="input-clean w-full">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <x-currency-input
                            label="Nominal Pinjaman / Kasbon (Rp)"
                            model="rec_amount"
                            :value="$rec_amount"
                            placeholder="1.000.000"
                            badgeColor="emerald"
                            required
                        />

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Tanggal Pinjam <span class="text-rose-500">*</span></label>
                            <input type="date" wire:model="rec_loan_date" required class="input-clean w-full">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Catatan / Perjanjian Pengembalian</label>
                        <input type="text" wire:model="rec_notes" placeholder="Catatan skema potong gaji / tanggal janji bayar..." class="input-clean w-full">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showCreateReceivableModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary bg-emerald-600 hover:bg-emerald-700">Simpan Piutang Kasbon</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 7: PAY RECEIVABLE / TERIMA PENGEMBALIAN KASBON -->
    @if($showPayReceivableModal)
        <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base">Terima Pengembalian Piutang / Kasbon</h3>
                    <button wire:click="$set('showPayReceivableModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <form wire:submit.prevent="processReceivablePayment" class="space-y-4 text-xs">
                    <x-currency-input
                        label="Nominal Setoran Pengembalian (Rp)"
                        model="pay_rec_amount"
                        :value="$pay_rec_amount"
                        placeholder="1.000.000"
                        badgeColor="emerald"
                        helpText="*Nominal ini akan dicatat otomatis sebagai KAS MASUK GLOBAL di Arus Kas Keuangan."
                        required
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Tanggal Terima Setoran <span class="text-rose-500">*</span></label>
                            <input type="date" wire:model="pay_rec_date" required class="input-clean w-full">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Metode Setoran <span class="text-rose-500">*</span></label>
                            <select wire:model="pay_rec_method" class="select-clean w-full font-semibold">
                                <option value="Cash / Tunai">Cash / Tunai</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Upload Bukti Transfer / Resi Setoran (Opsional)</label>
                        <input type="file" wire:model="pay_rec_photo" accept="image/*,.pdf" class="input-clean w-full">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Catatan Setoran</label>
                        <input type="text" wire:model="pay_rec_notes" placeholder="Setoran tunai via kasir kantor..." class="input-clean w-full">
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showPayReceivableModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary bg-emerald-600 hover:bg-emerald-700">Konfirmasi Setoran & Catat Kas Masuk</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
