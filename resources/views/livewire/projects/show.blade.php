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

            <a href="{{ route('cashflow.index') }}" class="btn-primary text-xs flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Arus Kas Global</span>
            </a>
        </div>
    </div>

    <!-- Project Specifications & Workers Strip -->
    <div class="card-clean p-4 bg-slate-900 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
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
            <div class="flex flex-wrap items-center gap-1">
                @forelse($project->assignments->where('status', 'active') as $assign)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500 text-white border border-emerald-500/30">
                        {{ $assign->worker->name }} ({{ ucfirst($assign->worker->type) }})
                    </span>
                @empty
                    <span class="text-slate-500 text-[11px] italic">Belum ada pekerja ditugaskan</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Financial KPI Dashboard Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Penjualan -->
        <div class="card-clean p-5 relative overflow-hidden bg-emerald-950/10 border-emerald-200/80">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-800">Total Penjualan Proyek</span>
                <div class="p-2.5 rounded-xl bg-emerald-500/20 text-emerald-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">Rp {{ number_format($totalSalesRevenue, 0, ',', '.') }}</p>
            <p class="text-[11px] text-emerald-700 mt-1 font-medium">{{ $soldUnits }} Unit Terjual / Booked</p>
        </div>

        <!-- Total Pengeluaran -->
        <div class="card-clean p-5 relative overflow-hidden bg-rose-950/10 border-rose-200/80">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-800">Total Biaya Pengeluaran</span>
                <div class="p-2.5 rounded-xl bg-rose-500/20 text-rose-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-rose-700 font-mono mt-2">Rp {{ number_format($totalProjectExpenses, 0, ',', '.') }}</p>
            <p class="text-[11px] text-rose-700 mt-1 font-medium">Tukang, Material, Perizinan & Kas Keluar</p>
        </div>

        <!-- Profit Bersih Proyek -->
        <div class="card-clean p-5 relative overflow-hidden bg-blue-950/10 border-blue-200/80">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-800">Estimasi Profit Proyek</span>
                <div class="p-2.5 rounded-xl bg-blue-500/20 text-blue-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono mt-2 {{ $totalProjectProfit >= 0 ? 'text-blue-700' : 'text-rose-700' }}">
                Rp {{ number_format($totalProjectProfit, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-blue-700 mt-1 font-medium">Selisih Penjualan - (HPP + Biaya)</p>
        </div>

        <!-- Progress Penjualan Unit -->
        <div class="card-clean p-5 relative overflow-hidden bg-purple-950/10 border-purple-200/80">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-purple-800">Okupansi Penjualan</span>
                <div class="p-2.5 rounded-xl bg-purple-500/20 text-purple-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-purple-700 font-mono mt-2">{{ $occupancyRate }}%</p>
            <p class="text-[11px] text-purple-700 mt-1 font-medium">{{ $soldUnits }} Terjual | {{ $availableUnits }} Tersedia | {{ $pendingUnits }} Pending</p>
        </div>
    </div>

    <!-- Navigation Tabs for Integrated View -->
    <div class="border-b border-slate-200 flex items-center gap-6 text-sm font-bold">
        <button wire:click="$set('activeTab', 'units')" class="pb-3 border-b-2 transition flex items-center gap-2 {{ $activeTab === 'units' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
            <span>Penjualan & Profit Per Unit</span>
            <span class="bg-slate-100 text-slate-700 text-xs px-2 py-0.5 rounded-full font-mono">{{ count($unitsList) }}</span>
        </button>

        <button wire:click="$set('activeTab', 'cashflow')" class="pb-3 border-b-2 transition flex items-center gap-2 {{ $activeTab === 'cashflow' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span>Laporan Arus Kas Proyek (Inflow & Outflow)</span>
            <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-0.5 rounded-full font-mono">{{ count($cashflowTransactions) }}</span>
        </button>
    </div>

    <!-- TAB 1: Penjualan & Profit Per Unit -->
    @if($activeTab === 'units')
        <div class="space-y-4">
            <div class="card-clean p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <h3 class="font-bold text-slate-900 text-sm whitespace-nowrap">Dashboard Penjualan & Profit Per Unit</h3>
                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">
                        {{ count($unitsList) }} Unit
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
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
                                <th class="px-4 py-3.5">Kode Unit & Tipe</th>
                                <th class="px-4 py-3.5">Luas Tanah</th>
                                <th class="px-4 py-3.5 text-center">Status Unit</th>
                                <th class="px-4 py-3.5">Nama Pembeli</th>
                                <th class="px-4 py-3.5 text-right">HPP Unit (Rp)</th>
                                <th class="px-4 py-3.5 text-right">Biaya Tambahan (Rp)</th>
                                <th class="px-4 py-3.5 text-right">Harga Jual / Penjualan</th>
                                <th class="px-4 py-3.5 text-right">Profit / Margin (Rp)</th>
                                <th class="px-4 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($unitsList as $u)
                                @php 
                                    $perf = $unitPerformances[$u->id] ?? [
                                        'selling_price' => 0,
                                        'hpp' => (float)$u->hpp,
                                        'unit_costs' => 0,
                                        'profit' => 0,
                                        'buyer_name' => '-',
                                        'is_sold' => false,
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-4 py-4">
                                        <span class="font-extrabold text-slate-900 text-sm font-mono text-emerald-700 block">{{ $u->code }}</span>
                                        <span class="text-[11px] text-slate-400 capitalize">{{ $u->category ?? $u->type }}</span>
                                    </td>
                                    <td class="px-4 py-4 font-mono text-slate-700">
                                        {{ number_format($u->land_area, 0, ',', '.') }} m²
                                        @if($u->excess_land_area > 0)
                                            <span class="block text-[10px] text-amber-600 font-semibold">(+{{ number_format($u->excess_land_area, 0) }} m² lebih)</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        @if ($u->status === 'tersedia')
                                            <span class="status-tersedia">Tersedia</span>
                                        @elseif ($u->status === 'menunggu_persetujuan')
                                            <span class="status-menunggu">Menunggu ACC</span>
                                        @elseif ($u->status === 'disetujui' || $u->status === 'converted' || $u->status === 'terjual')
                                            <span class="status-disetujui">Disetujui / Terjual</span>
                                        @elseif ($u->status === 'booked')
                                            <span class="status-booked">Booked</span>
                                        @else
                                            <span class="status-ditolak">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="font-bold text-slate-900">{{ $perf['buyer_name'] }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-right font-mono font-semibold text-slate-700">
                                        Rp {{ number_format($perf['hpp'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-right font-mono font-semibold text-rose-600">
                                        Rp {{ number_format($perf['unit_costs'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-4 text-right font-mono font-extrabold text-emerald-700">
                                        @if($perf['selling_price'] > 0)
                                            Rp {{ number_format($perf['selling_price'], 0, ',', '.') }}
                                        @else
                                            <span class="text-slate-400 font-normal italic">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-right font-mono font-extrabold">
                                        @if($perf['is_sold'])
                                            <span class="{{ $perf['profit'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $perf['profit'] >= 0 ? '+' : '' }} Rp {{ number_format($perf['profit'], 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 font-normal italic">Belum Terjual</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-right whitespace-nowrap">
                                        <a href="{{ route('units.show', $u->id) }}" class="btn-primary text-[11px] px-2.5 py-1">
                                            Lihat Detail Unit
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
                    <span class="text-[10px] text-rose-600 block">Upah Tukang, Material, & Operasional</span>
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

</div>
