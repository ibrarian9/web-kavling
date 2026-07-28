<div class="space-y-6">

    <!-- Top Navigation & Header -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('projects.index') }}" class="hover:text-emerald-600 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Daftar Proyek</span>
                </a>
                <span>/</span>
                <span class="text-slate-600 font-semibold">Detail & Dashboard Proyek</span>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $project->name }}</h1>
            <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                {{ $project->location }}
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('units.index', ['project_id' => $project->id]) }}" class="btn-secondary text-xs flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                <span>Kelola Stok Unit</span>
            </a>

            @if(auth()->user()->isFounder())
                <button wire:click="openLegacyModal" class="px-3 py-2 rounded-xl bg-purple-900/90 hover:bg-purple-800 text-purple-200 border border-purple-700/60 text-xs font-semibold flex items-center gap-1.5 transition shadow-sm">
                    <svg class="w-4 h-4 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>+ Input Penjualan Lalu</span>
                </button>
            @endif

            @if(!auth()->user()->isPengawasProject())
                <a href="{{ route('cashflow.index') }}" class="btn-primary text-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Arus Kas Global</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Project Specifications & Workers Strip -->
    <div class="card-clean p-4 bg-slate-900 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 text-xs">
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Harga Beli Lahan</span>
                <span class="font-mono font-bold text-sm text-purple-400">
                    @if($project->total_project_price > 0)
                        Rp {{ number_format($project->total_project_price, 0, ',', '.') }}
                    @else
                        <span class="text-slate-400 font-normal italic">-</span>
                    @endif
                </span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Luas Standar</span>
                <span class="font-mono font-bold text-sm text-emerald-400">{{ number_format($project->standard_land_area, 0, ',', '.') }} m²</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Harga Dasar Standar</span>
                <span class="font-mono font-bold text-sm text-emerald-400">Rp {{ number_format($project->base_price, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Kelebihan Tanah / m²</span>
                <span class="font-mono font-bold text-sm text-emerald-400">Rp {{ number_format($project->excess_price_per_sqm, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Total Unit Kavling</span>
                <span class="font-mono font-bold text-sm text-emerald-400">{{ $totalUnits }} Unit</span>
            </div>
        </div>

        <div class="text-xs border-t md:border-t-0 md:border-l border-slate-800 pt-2 md:pt-0 md:pl-4">
            <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-1">Mandor / Pekerja Bertugas</span>
            @php
                $projActiveAssignments = $project->assignments->where('status', 'active');
                $firstProjWorker = $projActiveAssignments->first();
                $projWorkerCount = $projActiveAssignments->count();
            @endphp
            <div class="flex items-center gap-1">
                @if($firstProjWorker && $firstProjWorker->worker)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-[10px] font-bold bg-emerald-500 text-white border border-emerald-500/30 max-w-[200px] truncate" title="{{ $firstProjWorker->worker->name }} ({{ ucfirst($firstProjWorker->worker->type) }})">
                        <span class="truncate">{{ $firstProjWorker->worker->name }} ({{ ucfirst($firstProjWorker->worker->type) }})</span>
                        @if($projWorkerCount > 1)
                            <span class="font-bold shrink-0 text-emerald-200">...</span>
                        @endif
                    </span>
                @else
                    <span class="text-slate-500 text-[11px] italic">Belum ada pekerja ditugaskan</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Skema Pembayaran Lahan Proyek (ke Penjual Tanah) Card Header Summary -->
    @if(!auth()->user()->isPengawasProject())
        <div class="card-clean p-5 bg-gradient-to-r from-purple-900 via-slate-900 to-indigo-900 text-white shadow-md">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-purple-500/20 text-purple-300 font-mono text-[10px] uppercase font-bold border border-purple-400/30">Skema Beli Lahan dari Penjual</span>
                        @if($project->total_project_price > 0 && $project->remaining_balance <= 0)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/30 text-emerald-300 border border-emerald-400/40">LAHAN LUNAS</span>
                        @endif
                    </div>
                    <h3 class="text-lg font-extrabold text-white tracking-tight">Pembelian & Pelunasan Lahan Proyek ke Penjual Tanah</h3>
                    <p class="text-xs text-purple-200/80">Pencatatan termin & riwayat pembayaran tanah/lahan proyek {{ $project->name }} ke Penjual Lahan</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 font-mono text-xs">
                    <div class="bg-white/10 backdrop-blur-sm p-3 rounded-xl border border-white/10">
                        <span class="text-purple-300 block text-[10px] uppercase font-bold tracking-wider">Harga Beli Lahan</span>
                        <span class="font-bold text-base text-white">
                            @if($project->total_project_price > 0)
                                Rp {{ number_format($project->total_project_price, 0, ',', '.') }}
                            @else
                                <span class="text-slate-400 font-normal italic">Belum diset</span>
                            @endif
                        </span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm p-3 rounded-xl border border-white/10">
                        <span class="text-emerald-300 block text-[10px] uppercase font-bold tracking-wider">Sudah Dibayar ke Penjual</span>
                        <span class="font-bold text-base text-emerald-400">Rp {{ number_format($project->total_paid, 0, ',', '.') }}</span>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm p-3 rounded-xl border border-white/10">
                        <span class="text-amber-300 block text-[10px] uppercase font-bold tracking-wider">Sisa Hutang / Tagihan Lahan</span>
                        <span class="font-bold text-base text-amber-300">Rp {{ number_format($project->remaining_balance, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if(auth()->user()->isFounder() || auth()->user()->isFinance() || auth()->user()->isSupervisor())
                    <div class="shrink-0">
                        <button wire:click="openPaymentModal" class="w-full sm:w-auto bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs px-4 py-3 rounded-xl shadow-lg transition inline-flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Catat Bayar Lahan ke Penjual</span>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Progress Bar -->
            @if($project->total_project_price > 0)
                <div class="mt-4 pt-3 border-t border-purple-800/60">
                    <div class="flex items-center justify-between text-[11px] font-bold text-purple-200 mb-1">
                        <span>Progress Pelunasan Lahan ke Penjual: {{ number_format($project->payment_progress_percentage, 1) }}%</span>
                        <span>{{ number_format($project->total_paid, 0, ',', '.') }} / {{ number_format($project->total_project_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full h-2.5 bg-slate-800 rounded-full overflow-hidden border border-purple-700/50">
                        <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500" style="width: {{ $project->payment_progress_percentage }}%"></div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Unit Status & Financial KPI Dashboard Cards (Hidden from Pengawas Project) -->
    @if(!auth()->user()->isPengawasProject())
        <div class="space-y-4">
            <!-- Unit Status Summary Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <!-- Unit Terjual -->
                <div class="card-clean p-4 bg-emerald-50/70 border border-emerald-200/80 space-y-1">
                    <div class="flex items-center justify-between text-emerald-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider">Unit Terjual / Deal</span>
                        <span class="p-1.5 rounded-lg bg-emerald-200/60 text-emerald-800 font-extrabold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                    </div>
                    <p class="text-2xl font-extrabold font-mono text-emerald-900">{{ $soldUnits }} <span class="text-xs font-normal font-sans text-emerald-700">Unit</span></p>
                    <p class="text-[10px] text-emerald-700 font-medium">Disetujui / Booked / Terjual</p>
                </div>

                <!-- Unit Belum Terjual / Tersedia -->
                <div class="card-clean p-4 bg-sky-50/70 border border-sky-200/80 space-y-1">
                    <div class="flex items-center justify-between text-sky-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider">Unit Belum Terjual</span>
                        <span class="p-1.5 rounded-lg bg-sky-200/60 text-sky-800 font-extrabold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </span>
                    </div>
                    <p class="text-2xl font-extrabold font-mono text-sky-900">{{ $availableUnits }} <span class="text-xs font-normal font-sans text-sky-700">Unit</span></p>
                    <p class="text-[10px] text-sky-700 font-medium">Masih Tersedia Ditawarkan</p>
                </div>

                <!-- Unit Lunas -->
                <div class="card-clean p-4 bg-indigo-50/70 border border-indigo-200/80 space-y-1">
                    <div class="flex items-center justify-between text-indigo-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider">Unit Sudah Lunas</span>
                        <span class="p-1.5 rounded-lg bg-indigo-200/60 text-indigo-800 font-extrabold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </span>
                    </div>
                    <p class="text-2xl font-extrabold font-mono text-indigo-900">{{ $fullyPaidUnitsCount ?? 0 }} <span class="text-xs font-normal font-sans text-indigo-700">Unit</span></p>
                    <p class="text-[10px] text-indigo-700 font-medium">Pembayaran Selesai 100%</p>
                </div>

                <!-- Unit Cicilan / Belum Lunas -->
                <div class="card-clean p-4 bg-amber-50/70 border border-amber-200/80 space-y-1">
                    <div class="flex items-center justify-between text-amber-800">
                        <span class="text-[10px] font-bold uppercase tracking-wider">Unit Masih Cicilan</span>
                        <span class="p-1.5 rounded-lg bg-amber-200/60 text-amber-800 font-extrabold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                    </div>
                    <p class="text-2xl font-extrabold font-mono text-amber-900">{{ $installmentUnitsCount ?? 0 }} <span class="text-xs font-normal font-sans text-amber-700">Unit</span></p>
                    <p class="text-[10px] text-amber-700 font-medium">Dalam Masa Cicilan / Belum Lunas</p>
                </div>
            </div>

            <!-- Financial KPI Dashboard Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Total Nilai Deal Penjualan -->
                <div class="card-clean p-5 relative overflow-hidden bg-emerald-950/10 border-emerald-200/80">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-800">Total Nilai Deal Proyek</span>
                        <div class="p-2.5 rounded-xl bg-emerald-500/20 text-emerald-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">Rp {{ number_format($totalSalesRevenue, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-emerald-700 mt-1 font-medium">{{ $soldUnits }} Unit Deal / Terjual / Booked</p>
                </div>

                <!-- Total Terbayar Masuk -->
                <div class="card-clean p-5 relative overflow-hidden bg-sky-950/10 border-sky-200/80">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-sky-800">Total Kas Masuk Terbayar</span>
                        <div class="p-2.5 rounded-xl bg-sky-500/20 text-sky-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-sky-700 font-mono mt-2">Rp {{ number_format($totalPaidRevenue, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-sky-700 mt-1 font-medium">Setoran Booking, DP, & Cicilan Masuk</p>
                </div>

                <!-- Sisa Piutang Penjualan -->
                <div class="card-clean p-5 relative overflow-hidden bg-amber-950/10 border-amber-200/80">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-amber-800">Sisa Tagihan / Piutang</span>
                        <div class="p-2.5 rounded-xl bg-amber-500/20 text-amber-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold text-amber-700 font-mono mt-2">Rp {{ number_format($totalOutstandingReceivable, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-amber-700 mt-1 font-medium">Piutang Pembeli Belum Lunas</p>
                </div>

                <!-- Total Biaya & Profit -->
                <div class="card-clean p-5 relative overflow-hidden bg-purple-950/10 border-purple-200/80">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-purple-800">Profit Proyek Bersih</span>
                        <div class="p-2.5 rounded-xl bg-purple-500/20 text-purple-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                    </div>
                    <p class="text-2xl font-extrabold font-mono mt-2 {{ $totalProjectProfit >= 0 ? 'text-purple-700' : 'text-rose-700' }}">
                        Rp {{ number_format($totalProjectProfit, 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] text-purple-700 mt-1 font-medium">Pengeluaran: Rp {{ number_format($totalProjectExpenses, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Navigation Tabs for Integrated View -->
    <div class="border-b border-slate-200 flex items-center gap-6 text-sm font-bold">
        <button wire:click="$set('activeTab', 'units')" class="pb-3 border-b-2 transition flex items-center gap-2 {{ $activeTab === 'units' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
            <span>{{ auth()->user()->isPengawasProject() ? 'Daftar Unit Kavling & Pekerja' : 'Penjualan & Profit Per Unit' }}</span>
            <span class="bg-slate-100 text-slate-700 text-xs px-2 py-0.5 rounded-full font-mono">{{ count($unitsList) }}</span>
        </button>

        @if(!auth()->user()->isPengawasProject())
            <button wire:click="$set('activeTab', 'payments')" class="pb-3 border-b-2 transition flex items-center gap-2 {{ $activeTab === 'payments' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Skema & Pembayaran Lahan Proyek</span>
                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded-full font-mono">{{ count($projectPaymentsList) }}</span>
            </button>

            <button wire:click="$set('activeTab', 'cashflow')" class="pb-3 border-b-2 transition flex items-center gap-2 {{ $activeTab === 'cashflow' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Laporan Arus Kas Proyek (Inflow & Outflow)</span>
                <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-0.5 rounded-full font-mono">{{ count($cashflowTransactions) }}</span>
            </button>
        @endif
    </div>

    <!-- TAB 1: Penjualan & Profit Per Unit -->
    @if($activeTab === 'units')
        <div class="space-y-4">
            <div class="card-clean p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <h3 class="font-bold text-slate-900 text-sm whitespace-nowrap">{{ auth()->user()->isPengawasProject() ? 'Daftar Unit Kavling Proyek' : 'Dashboard Penjualan & Profit Per Unit' }}</h3>
                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">
                        {{ count($unitsList) }} Unit
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-56">
                        <input type="text" wire:model.live.debounce.300ms="unitSearch" placeholder="Cari kode unit..." class="input-clean w-full text-xs pl-8 pr-3 py-1.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500">
                        <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <div class="w-full sm:w-40">
                        <select wire:model.live="statusFilter" class="input-clean w-full text-xs">
                            <option value="">Semua Status Unit</option>
                            <option value="tersedia">Tersedia</option>
                            <option value="menunggu_persetujuan">Menunggu Approval</option>
                            <option value="disetujui">Disetujui / Terjual</option>
                            <option value="booked">Booked</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-36">
                        <select wire:model.live="typeFilter" class="input-clean w-full text-xs">
                            <option value="">Semua Tipe</option>
                            <option value="kavling">Kavling Tanah</option>
                            <option value="rumah">Rumah Bangunan</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table: Unit Sales, Costs, and Profit Performance -->
            <div class="card-clean overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-100/90 text-slate-700 uppercase text-[10px] font-bold tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-3 py-3.5">Kode Unit & Status</th>
                                <th class="px-3 py-3.5">Kategori & Luas Tanah</th>
                                @if(!auth()->user()->isPengawasProject())
                                    <th class="px-3 py-3.5">Nama Pembeli</th>
                                    <th class="px-3 py-3.5 text-right">Harga Deal (Rp)</th>
                                    <th class="px-3 py-3.5 text-right">Sudah Dibayar (Rp)</th>
                                    <th class="px-3 py-3.5 text-right">Sisa Tagihan (Rp)</th>
                                    @if(auth()->user()->canViewHpp())
                                        <th class="px-3 py-3.5 text-right">HPP & Biaya (Rp)</th>
                                    @endif
                                    <th class="px-3 py-3.5 text-right">Profit / Margin (Rp)</th>
                                @else
                                    <th class="px-3 py-3.5">Mandor / Worker Bertugas</th>
                                @endif
                                <th class="px-3 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($unitsList as $u)
                                @php 
                                    $perf = $unitPerformances[$u->id] ?? [
                                        'selling_price' => 0,
                                        'paid_amount' => 0,
                                        'remaining_amount' => 0,
                                        'hpp' => (float)$u->hpp,
                                        'unit_costs' => 0,
                                        'profit' => 0,
                                        'buyer_name' => '-',
                                        'is_sold' => false,
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-3 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <span class="font-extrabold text-slate-900 text-sm font-mono text-emerald-700">{{ $u->code }}</span>
                                            @if ($u->status === 'tersedia')
                                                <span class="status-tersedia">Tersedia</span>
                                            @elseif ($u->status === 'disetujui' || $u->status === 'converted' || $u->status === 'terjual')
                                                <span class="status-disetujui">Terjual</span>
                                            @elseif ($u->status === 'booked')
                                                <span class="status-booked">Booked</span>
                                            @else
                                                <span class="status-menunggu">{{ ucfirst($u->status) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-3.5">
                                        <span class="font-semibold text-slate-800 capitalize">{{ $u->category ?? $u->type }}</span>
                                        <span class="text-[11px] text-slate-500 font-mono block">{{ number_format($u->land_area, 0, ',', '.') }} m²</span>
                                    </td>

                                    @if(!auth()->user()->isPengawasProject())
                                        <td class="px-3 py-3.5 font-bold text-slate-800">
                                            {{ $perf['buyer_name'] }}
                                        </td>
                                        <td class="px-3 py-3.5 text-right font-mono font-extrabold text-emerald-700">
                                            @if($perf['selling_price'] > 0)
                                                Rp {{ number_format($perf['selling_price'], 0, ',', '.') }}
                                            @else
                                                <span class="text-slate-400 font-normal italic">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3.5 text-right font-mono font-extrabold text-sky-700">
                                            @if($perf['paid_amount'] > 0)
                                                Rp {{ number_format($perf['paid_amount'], 0, ',', '.') }}
                                            @else
                                                <span class="text-slate-400 font-normal italic">Rp 0</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3.5 text-right font-mono font-extrabold text-amber-700">
                                            @if($perf['is_sold'] && $perf['remaining_amount'] > 0)
                                                Rp {{ number_format($perf['remaining_amount'], 0, ',', '.') }}
                                            @elseif($perf['is_sold'] && $perf['remaining_amount'] == 0)
                                                <span class="text-emerald-600 font-bold">LUNAS</span>
                                            @else
                                                <span class="text-slate-400 font-normal italic">-</span>
                                            @endif
                                        </td>
                                        @if(auth()->user()->canViewHpp())
                                            <td class="px-3 py-3.5 text-right font-mono text-slate-600">
                                                <div>HPP: Rp {{ number_format($perf['hpp'], 0, ',', '.') }}</div>
                                                @if($perf['unit_costs'] > 0)
                                                    <div class="text-[10px] text-rose-600 font-semibold">+ Biaya: Rp {{ number_format($perf['unit_costs'], 0, ',', '.') }}</div>
                                                @endif
                                            </td>
                                        @endif
                                        <td class="px-3 py-3.5 text-right font-mono font-extrabold">
                                            @if($perf['is_sold'])
                                                <span class="{{ $perf['profit'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                    {{ $perf['profit'] >= 0 ? '+' : '' }} Rp {{ number_format($perf['profit'], 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-slate-400 font-normal italic">Belum Terjual</span>
                                            @endif
                                        </td>
                                    @else
                                        <td class="px-3 py-3.5">
                                            @php
                                                $uAssignment = $u->assignments->where('status', 'active')->first();
                                            @endphp
                                            @if($uAssignment && $uAssignment->worker)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-blue-50 text-blue-800 border border-blue-200 text-xs font-semibold">
                                                    {{ $uAssignment->worker->name }} ({{ ucfirst($uAssignment->worker->type) }})
                                                </span>
                                            @else
                                                <span class="text-slate-400 text-xs italic">Penugasan mengikuti proyek</span>
                                            @endif
                                        </td>
                                    @endif

                                    <td class="px-3 py-3.5 text-center whitespace-nowrap">
                                        <a href="{{ route('units.show', $u->id) }}" class="btn-primary text-[11px] px-2.5 py-1">
                                            Detail Unit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                                        <p class="font-semibold text-slate-600">Tidak ada unit kavling ditemukan</p>
                                        <p class="text-xs text-slate-400 mt-1">Coba ganti filter status atau tambahkan unit baru untuk proyek ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 3: Skema & Riwayat Pembayaran Lahan Proyek (Hidden from Pengawas Project) -->
    @if($activeTab === 'payments' && !auth()->user()->isPengawasProject())
        <div class="space-y-4">
            <div class="card-clean p-4 flex flex-col sm:flex-row items-center justify-between gap-3 bg-purple-950/5 border-purple-200/80">
                <div>
                    <h3 class="font-bold text-purple-950 text-sm">Riwayat Pembayaran Lahan Proyek ke Penjual</h3>
                    <p class="text-xs text-purple-700">Daftar setoran termin / pembayaran yang telah diserahkan ke pemilik/penjual tanah proyek {{ $project->name }}</p>
                </div>
                @if(auth()->user()->isFounder() || auth()->user()->isFinance() || auth()->user()->isSupervisor())
                    <button wire:click="openPaymentModal" class="btn-primary text-xs px-4 py-2 flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Catat Bayar Lahan ke Penjual</span>
                    </button>
                @endif
            </div>

            <!-- Table of Project Payments -->
            <div class="card-clean overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-purple-50 text-purple-900 uppercase text-[10px] font-bold tracking-wider border-b border-purple-100">
                            <tr>
                                <th class="px-4 py-3.5">#</th>
                                <th class="px-4 py-3.5">Tanggal Pembayaran</th>
                                <th class="px-4 py-3.5">Metode Pembayaran</th>
                                <th class="px-4 py-3.5">Bukti Resi Transfer</th>
                                <th class="px-4 py-3.5">Kuitansi PDF & QR</th>
                                <th class="px-4 py-3.5">Catatan / Keterangan</th>
                                <th class="px-4 py-3.5">Dicatat Oleh</th>
                                <th class="px-4 py-3.5 text-right">Jumlah Dibayar (Rp)</th>
                                @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                    <th class="px-4 py-3.5 text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($projectPaymentsList as $index => $pay)
                                <tr class="hover:bg-slate-50/80">
                                    <td class="px-4 py-3.5 font-mono text-slate-500 font-semibold">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3.5 font-mono font-bold text-slate-800">
                                        {{ $pay->payment_date ? $pay->payment_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 font-semibold text-slate-700">
                                        <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-[10px]">
                                            {{ $pay->payment_method }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if($pay->receipt_photo_url)
                                            <a href="{{ $pay->receipt_photo_url }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-purple-700 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 px-2 py-1 rounded-lg border border-purple-200 transition">
                                                <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <span>Foto Resi</span>
                                            </a>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if($pay->uuid)
                                            <div class="flex items-center gap-1.5">
                                                <a href="{{ route('land-payment.receipt', $pay->uuid) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 px-2 py-1 rounded-lg border border-emerald-200 transition shadow-xs">
                                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    <span>Kuitansi PDF</span>
                                                </a>

                                                <a href="{{ route('verify.land-payment', $pay->uuid) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] font-bold text-purple-700 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 px-2 py-1 rounded-lg border border-purple-200 transition shadow-xs" title="Verifikasi QR Keabsahan">
                                                    <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                                    <span>Scan QR</span>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-600 max-w-xs truncate">
                                        {{ $pay->notes ?: '-' }}
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-600 font-medium">
                                        {{ $pay->creator->name ?? 'System' }}
                                    </td>
                                    <td class="px-4 py-3.5 text-right font-mono font-extrabold text-rose-700 text-sm">
                                        - Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}
                                    </td>
                                    @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                        <td class="px-4 py-3.5 text-center">
                                            <button onclick="confirm('Hapus pencatatan pembayaran lahan ini?') || event.stopImmediatePropagation()" wire:click="deleteProjectPayment({{ $pay->id }})" class="p-1 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded transition" title="Hapus Pembayaran">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <p class="font-semibold text-slate-600">Belum ada riwayat pembayaran lahan proyek ke penjual</p>
                                        <p class="text-xs text-slate-400 mt-1">Klik tombol "+ Catat Bayar Lahan ke Penjual" untuk memasukkan setoran pelunasan tanah.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- TAB 2: Laporan Arus Kas Proyek (Inflow & Outflow) -->
    @if($activeTab === 'cashflow')
        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="card-clean p-4 bg-emerald-50 border border-emerald-200/80 space-y-1">
                    <span class="text-slate-500 font-semibold uppercase tracking-wider text-[10px]">Total Kas Masuk Proyek</span>
                    <p class="text-xl font-mono font-extrabold text-emerald-700">Rp {{ number_format($cashflowMasuk, 0, ',', '.') }}</p>
                    <span class="text-[10px] text-emerald-600 block">DP, Booking Fee, & Setoran Cicilan</span>
                </div>

                <div class="card-clean p-4 bg-rose-50 border border-rose-200/80 space-y-1">
                    <span class="text-slate-500 font-semibold uppercase tracking-wider text-[10px]">Total Kas Keluar Proyek</span>
                    <p class="text-xl font-mono font-extrabold text-rose-700">Rp {{ number_format($cashflowKeluar, 0, ',', '.') }}</p>
                    <span class="text-[10px] text-rose-600 block">Upah Tukang, Material, Pembelian Lahan, & Operasional</span>
                </div>

                <div class="card-clean p-4 bg-blue-50 border border-blue-200/80 space-y-1">
                    <span class="text-slate-500 font-semibold uppercase tracking-wider text-[10px]">Saldo Bersih Arus Kas</span>
                    <p class="text-xl font-mono font-extrabold {{ $cashflowNet >= 0 ? 'text-blue-700' : 'text-rose-700' }}">
                        Rp {{ number_format($cashflowNet, 0, ',', '.') }}
                    </p>
                    <span class="text-[10px] text-blue-600 block">Selisih Mutasi Kas Masuk & Keluar</span>
                </div>
            </div>

            <!-- Transaction Logs Table -->
            <div class="card-clean overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-slate-900 text-sm">Rincian Transaksi Mutasi Kas Proyek</h3>
                    <span class="text-xs font-mono font-semibold text-slate-500">{{ count($cashflowTransactions) }} Transaksi Recorded</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100/90 text-slate-700 uppercase text-[10px] font-bold tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Kategori Mutasi</th>
                                <th class="px-4 py-3">Deskripsi / Keterangan Transaksi</th>
                                <th class="px-4 py-3 text-center">Jenis Mutasi</th>
                                <th class="px-4 py-3 text-right">Nominal (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($cashflowTransactions as $tx)
                                <tr class="hover:bg-slate-50/80">
                                    <td class="px-4 py-3 font-mono text-slate-600 whitespace-nowrap">{{ $tx->transaction_date ? $tx->transaction_date->format('d/m/Y') : '-' }}</td>
                                    <td class="px-4 py-3 font-semibold text-slate-800 capitalize whitespace-nowrap">{{ str_replace('_', ' ', $tx->category) }}</td>
                                    <td class="px-4 py-3 text-slate-800">{{ $tx->description }}</td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        @if($tx->type === 'masuk')
                                            <span class="px-2.5 py-0.5 text-[10px] font-bold bg-emerald-100 text-emerald-800 rounded-full border border-emerald-200">MASUK</span>
                                        @else
                                            <span class="px-2.5 py-0.5 text-[10px] font-bold bg-rose-100 text-rose-800 rounded-full border border-rose-200">KELUAR</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right font-mono font-extrabold whitespace-nowrap {{ $tx->type === 'masuk' ? 'text-emerald-700' : 'text-rose-700' }}">
                                        {{ $tx->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        <p class="font-semibold text-slate-600">Belum Ada Mutasi Kas Terdeteksi</p>
                                        <p class="text-xs text-slate-400 mt-1">Transaksi kas masuk dari booking & kas keluar biaya akan tampil di sini secara otomatis.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL CATAT PEMBAYARAN LAHAN KE PENJUAL -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white border border-slate-200 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-base">Catat Pembayaran Lahan ke Penjual</h3>
                            <p class="text-[11px] text-slate-500 font-medium">Proyek: {{ $project->name }}</p>
                        </div>
                    </div>
                    <button wire:click="closePaymentModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="submitProjectPayment" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Jumlah Pembayaran Lahan (Rp)</label>
                        <x-currency-input model="payment_amount" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-900 font-bold font-mono text-sm focus:ring-2 focus:ring-emerald-500 outline-none" />
                        @error('payment_amount') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Tanggal Pembayaran</label>
                        <input type="date" wire:model="payment_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-medium focus:ring-2 focus:ring-emerald-500 outline-none">
                        @error('payment_date') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Metode Pembayaran</label>
                        <select wire:model="payment_method" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-medium focus:ring-2 focus:ring-emerald-500 outline-none">
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Tunai / Cash">Tunai / Cash</option>
                            <option value="Cek / Giro">Cek / Giro</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Foto Resi / Bukti Transfer (Opsional)</label>
                        <input type="file" wire:model="payment_receipt_photo" accept="image/*" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                        @error('payment_receipt_photo') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                        @if ($payment_receipt_photo)
                            <div class="mt-2.5 p-2 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                                <div class="flex items-center justify-between text-[11px] font-semibold text-slate-700">
                                    <span class="flex items-center gap-1 text-emerald-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Pratinjau Resi ({{ $payment_receipt_photo->getClientOriginalName() }}):</span>
                                    </span>
                                    <button type="button" wire:click="$set('payment_receipt_photo', null)" class="text-rose-500 hover:text-rose-700 text-[10px] underline font-bold">Hapus Foto</button>
                                </div>
                                <div class="relative max-h-44 overflow-hidden rounded-lg border border-slate-200 bg-slate-900 flex items-center justify-center p-1">
                                    <img src="{{ $payment_receipt_photo->temporaryUrl() }}" alt="Preview Resi" class="max-h-40 w-full object-contain rounded-md">
                                </div>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Catatan / Keterangan</label>
                        <textarea wire:model="payment_notes" rows="2" placeholder="Pembayaran termin 1 lahan ke Pak Pemilik Tanah..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 focus:ring-2 focus:ring-emerald-500 outline-none"></textarea>
                    </div>
                    
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="closePaymentModal" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-500 shadow-md transition">Simpan & Catat Kas Keluar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Form Input Penjualan Lalu (Historis Terjual & Lunas 100% - Responsif Mobile & Desktop) -->
    @if($showLegacyModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
            <div class="bg-white border border-slate-200 rounded-2xl sm:rounded-3xl max-w-xl md:max-w-2xl w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200">HISTORIS LUNAS</span>
                            <h3 class="font-extrabold text-slate-900 text-base sm:text-lg tracking-tight">Input Penjualan Lalu</h3>
                        </div>
                        <p class="text-slate-500 text-xs mt-0.5">Proyek: <strong class="text-slate-800">{{ $project->name }}</strong></p>
                    </div>
                    <button wire:click="closeLegacyModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
                </div>

                <form wire:submit.prevent="submitLegacySale" class="space-y-4 text-xs flex-1 overflow-y-auto pr-1">
                    <!-- Section 1: Spesifikasi Unit -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-3">
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>1. Spesifikasi & Identitas Unit</span>
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Kode Unit <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="legacy_code" placeholder="Misal: A-01" class="input-clean w-full font-bold uppercase">
                                @error('legacy_code') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Kategori Unit</label>
                                <select wire:model.live="legacy_category" class="input-clean w-full font-semibold">
                                    <option value="kavling">Kavling Tanah</option>
                                    <option value="rumah">Kavling + Rumah</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Tipe Unit</label>
                                <input type="text" wire:model="legacy_type" placeholder="Kavling Standar / Rumah Tipe 36" class="input-clean w-full">
                            </div>

                            @if($legacy_category === 'rumah')
                                <div>
                                    <label class="block font-semibold text-slate-700 mb-1">Luas Bangunan (m²)</label>
                                    <input type="number" step="0.1" wire:model="legacy_building_area" placeholder="Misal: 36" class="input-clean w-full font-mono">
                                </div>
                            @endif
                        </div>

                        <!-- Dimensi Lahan -->
                        <div class="grid grid-cols-3 gap-2 pt-1">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1 text-[10px]">Lebar (m)</label>
                                <input type="number" step="0.1" wire:model.live="legacy_land_width" class="input-clean w-full font-mono text-center">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1 text-[10px]">Panjang (m)</label>
                                <input type="number" step="0.1" wire:model.live="legacy_land_length" class="input-clean w-full font-mono text-center">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1 text-[10px]">Luas Tanah</label>
                                <div class="input-clean w-full font-mono font-bold text-center bg-slate-100 text-slate-800 py-2">
                                    {{ $legacy_land_area }} m²
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Nilai Finansial -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-3">
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>2. Nilai Finansial Penjualan</span>
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Harga Pokok (HPP Unit) <span class="text-rose-500">*</span></label>
                                <x-currency-input model="legacy_hpp" class="input-clean w-full font-mono font-bold text-slate-800" />
                                @error('legacy_hpp') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Harga Jual Deal (Lunas) <span class="text-rose-500">*</span></label>
                                <x-currency-input model="legacy_final_selling_price" class="input-clean w-full font-mono font-bold text-emerald-700" />
                                @error('legacy_final_selling_price') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Data Pembeli & Transaksi -->
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-3">
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>3. Data Pembeli & Pembayaran</span>
                        </h4>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Nama Pembeli <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="legacy_buyer_name" placeholder="Misal: Bapak H. Ahmad" class="input-clean w-full font-bold">
                                @error('legacy_buyer_name') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">No. Kontak / WhatsApp <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="legacy_buyer_phone" placeholder="08123456789" class="input-clean w-full font-mono">
                                @error('legacy_buyer_phone') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Tgl Transaksi Masa Lalu <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model="legacy_sale_date" class="input-clean w-full font-mono">
                                @error('legacy_sale_date') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Metode Pembayaran Historis</label>
                                <select wire:model="legacy_payment_method" class="input-clean w-full font-semibold">
                                    <option value="Tunai / Cash Lunas">Tunai / Cash Lunas</option>
                                    <option value="Transfer Bank Lunas">Transfer Bank Lunas</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Alamat Pembeli</label>
                            <input type="text" wire:model="legacy_buyer_address" placeholder="Alamat domisili pembeli..." class="input-clean w-full">
                        </div>

                        <div class="pt-1">
                            <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-emerald-500 transition">
                                <input type="checkbox" wire:model="legacy_record_cashflow" class="rounded text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                                <div>
                                    <span class="font-bold text-slate-800 text-xs block">Catat Penerimaan ke Arus Kas Proyek?</span>
                                    <span class="text-[10px] text-slate-500 block">Centang jika dana penjualan ini ingin dicatat sebagai Arus Kas Masuk pada tanggal transaksi di atas.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea wire:model="legacy_notes" rows="2" placeholder="Catatan transaksi penjualan masa lalu..." class="input-clean w-full"></textarea>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="closeLegacyModal" class="btn-secondary py-2.5 px-4 rounded-xl text-xs">Batal</button>
                        <button type="submit" class="btn-primary bg-purple-700 hover:bg-purple-800 py-2.5 px-5 rounded-xl shadow-md text-xs text-center">Simpan Penjualan Masa Lalu</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
