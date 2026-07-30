<div class="space-y-6">

    <!-- Header Section & Toolbar -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Arus Kas Per-Proyek, Per-Unit & Konsolidasi Global</h2>
            <p class="text-slate-500 text-xs mt-0.5">Rekapitulasi kas masuk & keluar per unit & lokasi perumahan serta rincian konsolidasi global</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Mode Switcher -->
            <div class="bg-slate-100 p-1 rounded-xl border border-slate-200/80 flex text-xs">
                <button wire:click="$set('view_mode', 'global')" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $view_mode === 'global' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Kas Global
                </button>
                <button wire:click="$set('view_mode', 'project')" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $view_mode === 'project' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Per Proyek
                </button>
                <button wire:click="$set('view_mode', 'unit')" class="px-3 py-1.5 rounded-lg font-semibold transition {{ $view_mode === 'unit' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Per Unit
                </button>
            </div>

            @if ($view_mode === 'project' || $view_mode === 'unit')
                <!-- Filter Proyek -->
                <select wire:model.live="filter_project_id" class="input-clean font-semibold text-xs py-2">
                    <option value="">-- Semua Proyek --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            @endif

            @if ($view_mode === 'unit' || ($view_mode === 'project' && $filter_project_id))
                <!-- Filter Unit -->
                <select wire:model.live="filter_unit_id" class="input-clean font-semibold text-xs py-2">
                    <option value="">-- Pilih Unit --</option>
                    @foreach($availableUnits as $u)
                        <option value="{{ $u->id }}">Unit {{ $u->code }} ({{ $u->project->name }})</option>
                    @endforeach
                </select>
            @endif

            <!-- Filter Bulan / Periode -->
            <div class="flex items-center gap-1 bg-slate-50 border border-slate-200/80 p-1 rounded-xl">
                <input type="month" wire:model.live="filter_month" class="input-clean font-semibold text-xs py-1 px-2 border-0 bg-transparent" title="Pilih Bulan & Tahun">
                @if(!empty($filter_month))
                    <button type="button" wire:click="$set('filter_month', '')" class="text-[10px] font-bold text-slate-500 hover:text-slate-800 px-1.5 py-0.5 rounded bg-slate-200/60" title="Tampilkan Semua Periode">
                        Semua
                    </button>
                @else
                    <button type="button" wire:click="$set('filter_month', '{{ date('Y-m') }}')" class="text-[10px] font-bold text-emerald-700 hover:text-emerald-900 px-1.5 py-0.5 rounded bg-emerald-100/70" title="Filter Bulan Ini">
                        Bulan Ini
                    </button>
                @endif
            </div>

            <!-- Export Buttons -->
            <div class="flex items-center gap-1.5">
                <a href="{{ url('/cashflow/export-pdf?' . http_build_query(['view_mode' => $view_mode ?? 'global', 'project_id' => $filter_project_id ?? '', 'unit_id' => $filter_unit_id ?? '', 'month' => $filter_month ?? ''])) }}" target="_blank" class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 transition shadow-2xs" title="Cetak / Download PDF Laporan Arus Kas">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Export PDF</span>
                </a>

                <a href="{{ url('/cashflow/export-excel?' . http_build_query(['view_mode' => $view_mode ?? 'global', 'project_id' => $filter_project_id ?? '', 'unit_id' => $filter_unit_id ?? '', 'month' => $filter_month ?? ''])) }}" class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 transition shadow-2xs" title="Download Excel CSV Laporan Arus Kas">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Export Excel</span>
                </a>
            </div>

            @if(auth()->user()->isFounder())
                <button wire:click="openManualModal" class="btn-primary whitespace-nowrap text-xs px-3.5 py-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Catat Mutasi Kas</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Summary KPI Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Total Masuk -->
        <div class="card-clean p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    {{ $view_mode === 'global' ? 'Kas Masuk Global' : 'Kas Masuk (Tersaring)' }}
                </span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600 font-mono mt-2">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Penjualan unit, DP, booking & cicilan pembeli</p>
        </div>

        <!-- Total Keluar -->
        <div class="card-clean p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    {{ $view_mode === 'global' ? 'Kas Keluar Global' : 'Kas Keluar (Tersaring)' }}
                </span>
                <div class="p-2.5 rounded-xl bg-rose-50 text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-rose-600 font-mono mt-2">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Upah tukang, material mingguan & operasional</p>
        </div>

        <!-- Net Cashflow -->
        <div class="bg-slate-900 text-white rounded-2xl p-5 shadow-xl space-y-1 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                    Saldo Kas {{ $view_mode === 'global' ? 'Konsolidasi Global' : 'Bersih' }}
                </span>
                <div class="p-2 rounded-xl bg-emerald-500/20 text-emerald-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-white mt-2">Rp {{ number_format($netCashflow, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Total akumulasi saldo kas bersih</p>
        </div>
    </div>

    <!-- Visual Charts: Tren Arus Kas, Breakdown Penjualan & Biaya-Biaya -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Chart 1: Tren Perbandingan Arus Kas (ApexCharts Area/Line) -->
        <div class="lg:col-span-2 card-clean p-5 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18"/></svg>
                        <span>Grafik Tren Arus Kas (Pemasukan vs Pengeluaran)</span>
                    </h3>
                    <p class="text-[11px] text-slate-500">Perbandingan pemasukan & pengeluaran keuangan dalam 6 bulan terakhir</p>
                </div>
            </div>

            <div id="cashflowTrendChart" class="w-full min-h-[280px]"></div>
        </div>

        <!-- Chart 2: Breakdown Penjualan & Biaya-Biaya (Category Breakdown) -->
        <div class="card-clean p-5 space-y-4 flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg>
                    <span>Analisis Biaya & Penjualan</span>
                </h3>

                <!-- Tab Switcher for Category Breakdown -->
                <div x-data="{ chartTab: 'masuk' }" class="mt-3 space-y-3">
                    <div class="flex bg-slate-100 p-1 rounded-xl text-xs font-semibold">
                        <button @click="chartTab = 'masuk'" :class="chartTab === 'masuk' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="flex-1 py-1.5 rounded-lg text-center transition">
                            Penjualan & Pemasukan
                        </button>
                        <button @click="chartTab = 'keluar'" :class="chartTab === 'keluar' ? 'bg-white text-rose-700 shadow-xs' : 'text-slate-500 hover:text-slate-800'" class="flex-1 py-1.5 rounded-lg text-center transition">
                            Biaya & Pengeluaran
                        </button>
                    </div>

                    <!-- Breakdown Kas Masuk -->
                    <div x-show="chartTab === 'masuk'" class="space-y-2.5 pt-1">
                        @forelse($masukCategories as $cat)
                            @php
                                $pct = $totalMasuk > 0 ? min(100, round(($cat->total_amount / $totalMasuk) * 100, 1)) : 0;
                            @endphp
                            <div class="space-y-1 text-xs">
                                <div class="flex justify-between font-semibold">
                                    <span class="text-slate-700 capitalize">{{ str_replace('_', ' ', $cat->category) }}</span>
                                    <span class="font-mono text-emerald-700">Rp {{ number_format($cat->total_amount, 0, ',', '.') }} ({{ $pct }}%)</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-400 text-xs italic text-center py-4">Belum ada data kas masuk.</p>
                        @endforelse
                    </div>

                    <!-- Breakdown Kas Keluar -->
                    <div x-show="chartTab === 'keluar'" class="space-y-2.5 pt-1" style="display: none;">
                        @forelse($keluarCategories as $cat)
                            @php
                                $pct = $totalKeluar > 0 ? min(100, round(($cat->total_amount / $totalKeluar) * 100, 1)) : 0;
                            @endphp
                            <div class="space-y-1 text-xs">
                                <div class="flex justify-between font-semibold">
                                    <span class="text-slate-700 capitalize">{{ str_replace('_', ' ', $cat->category) }}</span>
                                    <span class="font-mono text-rose-700">Rp {{ number_format($cat->total_amount, 0, ',', '.') }} ({{ $pct }}%)</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-rose-500 h-2 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-400 text-xs italic text-center py-4">Belum ada data kas keluar.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($view_mode === 'global')
        <!-- Breakdown per perumahan -->
        <div class="card-clean p-5 space-y-4">
            <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Ringkasan Kas Konsolidasi per Perumahan / Proyek</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($projectBreakdown as $pb)
                    <div class="p-4 bg-slate-50/80 border border-slate-200/80 rounded-xl space-y-2">
                        <div class="font-bold text-slate-900 text-xs flex justify-between items-center">
                            <span>{{ $pb['name'] }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">ID: #{{ $pb['id'] }}</span>
                        </div>
                        <div class="text-xs space-y-1.5 font-mono pt-1">
                            <div class="flex justify-between text-emerald-600 font-medium">
                                <span class="text-slate-500 font-sans">Kas Masuk:</span>
                                <span>Rp {{ number_format($pb['masuk'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-rose-600 font-medium">
                                <span class="text-slate-500 font-sans">Kas Keluar:</span>
                                <span>Rp {{ number_format($pb['keluar'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-t border-slate-200/80 pt-1.5 font-extrabold text-slate-900">
                                <span class="font-sans text-slate-700">Saldo Bersih:</span>
                                <span>Rp {{ number_format($pb['net'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Mutasi Arus Kas Table Card -->
    <div class="card-clean overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Jurnal Mutasi Transaksi Kas</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Proyek</th>
                        <th class="px-5 py-3.5">Tipe</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Keterangan Transaksi</th>
                        <th class="px-5 py-3.5">Pencatat</th>
                        <th class="px-5 py-3.5 text-right">Nominal (Rp)</th>
                        <th class="px-5 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50/60 transition duration-150">
                            <td class="px-5 py-4 text-slate-600 font-mono font-medium">
                                {{ $trx->transaction_date->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-800">
                                {{ $trx->project->name }}
                            </td>
                            <td class="px-5 py-4">
                                @if($trx->type === 'masuk')
                                    <span class="status-disetujui">Kas Masuk</span>
                                @else
                                    <span class="status-ditolak">Kas Keluar</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="capitalize font-semibold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded-md text-[10px] border border-slate-200/60">
                                    {{ str_replace('_', ' ', $trx->category) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 font-medium text-slate-800">
                                {{ $trx->description }}
                            </td>
                            <td class="px-5 py-4 text-slate-500">
                                {{ $trx->creator->name ?? 'System' }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-extrabold text-sm {{ $trx->type === 'masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $trx->type === 'masuk' ? '+' : '-' }}Rp {{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button wire:click="openDetailModal({{ $trx->id }})" class="btn-action-detail">
                                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Detail</span>
                                    </button>
                                    @if(auth()->user()->isFounder())
                                        <button wire:click="deleteTransaction({{ $trx->id }})" wire:confirm="Yakin ingin menghapus mutasi transaksi kas #TRX-{{ $trx->id }} ini?" class="btn-action-delete" title="Hapus Transaksi Kas">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="font-semibold text-slate-600">Belum Ada Mutasi Transaksi Kas Tercatat</p>
                                <p class="text-xs text-slate-400 mt-1">Catat transaksi kas masuk atau keluar baru menggunakan tombol di atas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Modal Catat Kas Manual -->
    @if($showManualModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">Catat Mutasi Kas Manual</h3>
                    <button wire:click="$set('showManualModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveTransaction" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Pilih Proyek</label>
                        <select wire:model="project_id" class="input-clean w-full font-semibold">
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Tipe Arus Kas</label>
                            <select wire:model="type" class="input-clean w-full font-bold">
                                <option value="masuk">Pemasukan (Masuk)</option>
                                <option value="keluar">Pengeluaran (Keluar)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Tanggal Mutasi</label>
                            <input type="date" wire:model="transaction_date" class="input-clean w-full font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nominal (Rp)</label>
                        <x-currency-input model="amount" class="input-clean w-full font-bold text-sm font-mono" placeholder="Rp 0" />
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Keterangan Transaksi</label>
                        <input type="text" wire:model="description" required placeholder="Pendapatan lain / Konsumsi tukang..." class="input-clean w-full">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showManualModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Mutasi</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Detail Alur Keuangan & Audit Trail -->
    @if(!empty($showDetailModal) && !empty($selectedTransaction))
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
            <div class="bg-white border border-slate-200 rounded-2xl sm:rounded-3xl max-w-lg sm:max-w-xl w-full p-5 sm:p-6 shadow-2xl space-y-4 my-auto">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 rounded-xl bg-teal-50 text-teal-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-base">Detail Alur Keuangan & Audit Trail</h3>
                            <p class="text-[11px] text-slate-500">Nomor Mutasi: <strong class="font-mono text-slate-800">#TRX-{{ $selectedTransaction->id }}</strong></p>
                        </div>
                    </div>
                    <button wire:click="closeDetailModal" class="text-slate-400 hover:text-slate-600 text-sm font-bold">✕</button>
                </div>

                <!-- Financial Highlight Box -->
                <div class="p-4 rounded-xl {{ $selectedTransaction->type === 'masuk' ? 'bg-emerald-50 border border-emerald-200/80 text-emerald-900' : 'bg-rose-50 border border-rose-200/80 text-rose-900' }} flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider block opacity-75">Nominal Mutasi Kas</span>
                        <strong class="text-xl font-mono font-extrabold">
                            {{ $selectedTransaction->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($selectedTransaction->amount, 0, ',', '.') }}
                        </strong>
                    </div>
                    <div class="text-right">
                        <span class="px-2.5 py-1 rounded-md text-xs font-extrabold uppercase {{ $selectedTransaction->type === 'masuk' ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                            {{ $selectedTransaction->type === 'masuk' ? 'Kas Masuk' : 'Kas Keluar' }}
                        </span>
                    </div>
                </div>

                <!-- Audit Timeline Steps -->
                <div class="space-y-4 pt-1 text-xs">
                    <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[11px] border-b border-slate-100 pb-1.5">Alur Pencatatan & Otorisasi</h4>

                    <!-- Step 1: Inputter -->
                    <div class="flex items-start gap-3 relative pl-2">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                            1
                        </div>
                        <div class="space-y-0.5 flex-1">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-slate-900">Diinput Oleh</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $auditTrailInfo['inputted_by']['created_at'] }}</span>
                            </div>
                            <p class="font-semibold text-blue-800">{{ $auditTrailInfo['inputted_by']['name'] }} <span class="text-slate-500 font-normal">({{ ucfirst($auditTrailInfo['inputted_by']['role']) }})</span></p>
                        </div>
                    </div>

                    <!-- Step 2: Approver -->
                    <div class="flex items-start gap-3 relative pl-2">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                            2
                        </div>
                        <div class="space-y-0.5 flex-1">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-slate-900">Diverifikasi Oleh</span>
                                <span class="text-[10px] text-emerald-700 font-bold bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200">{{ $auditTrailInfo['approved_by']['status'] }}</span>
                            </div>
                            <p class="font-semibold text-emerald-800">{{ $auditTrailInfo['approved_by']['name'] }} <span class="text-slate-500 font-normal">({{ $auditTrailInfo['approved_by']['role'] }})</span></p>
                            <p class="text-[11px] text-slate-600 italic mt-0.5">"{{ $auditTrailInfo['approved_by']['notes'] }}"</p>
                        </div>
                    </div>
                </div>

                <!-- Scope & Object Detail -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 text-xs space-y-2">
                    <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[10px]">Cakupan Objek Transaksi</h4>
                    
                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Proyek Properti:</span>
                            <strong class="text-slate-800 font-semibold">{{ $selectedTransaction->project->name ?? 'Global' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Kategori Mutasi:</span>
                            <strong class="text-slate-800 font-semibold capitalize">{{ str_replace('_', ' ', $selectedTransaction->category) }}</strong>
                        </div>
                    </div>

                    @if($auditTrailInfo['reference_detail'])
                        <div class="pt-2 border-t border-slate-200/80 text-[11px] space-y-1">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Tipe Referensi:</span>
                                <span class="font-bold text-teal-700">{{ $auditTrailInfo['reference_detail']['type'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Nomor Referensi:</span>
                                <span class="font-mono font-bold text-slate-800">{{ $auditTrailInfo['reference_detail']['number'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Penerima / Klien:</span>
                                <span class="font-bold text-slate-800">{{ $auditTrailInfo['reference_detail']['recipient'] }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="pt-2 border-t border-slate-200/80 text-[11px]">
                        <span class="text-slate-400 block text-[10px]">Keterangan Transaksi:</span>
                        <p class="text-slate-700 italic font-medium">{{ $selectedTransaction->description }}</p>
                    </div>
                </div>

                <div class="flex justify-end pt-2 border-t border-slate-100">
                    <button type="button" wire:click="closeDetailModal" class="btn-secondary text-xs px-4 py-2">Tutup Detail</button>
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initTrendChart();
        });

        document.addEventListener('livewire:navigated', function () {
            initTrendChart();
        });

        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            succeed(() => {
                setTimeout(() => {
                    initTrendChart();
                }, 100);
            });
        });

        function initTrendChart() {
            const chartEl = document.getElementById('cashflowTrendChart');
            if (!chartEl) return;

            chartEl.innerHTML = '';

            const options = {
                series: [{
                    name: 'Kas Masuk (Pemasukan)',
                    data: @json($chartMasuk)
                }, {
                    name: 'Kas Keluar (Pengeluaran)',
                    data: @json($chartKeluar)
                }],
                chart: {
                    type: 'area',
                    height: 280,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                colors: ['#10b981', '#f43f5e'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: {
                    categories: @json($chartLabels),
                    labels: { style: { colors: '#64748b', fontSize: '11px' } }
                },
                yaxis: {
                    labels: {
                        style: { colors: '#64748b', fontSize: '11px' },
                        formatter: function (val) {
                            return 'Rp ' + (val / 1000000).toFixed(0) + ' Jt';
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                        }
                    }
                },
                grid: { borderColor: '#f1f5f9' },
                legend: { position: 'top', horizontalAlign: 'right' }
            };

            const chart = new ApexCharts(chartEl, options);
            chart.render();
        }
    </script>
</div>
