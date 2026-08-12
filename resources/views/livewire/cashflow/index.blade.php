<div class="space-y-6">

    <!-- Header Section & Actions -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Arus Kas & Konsolidasi Keuangan Global</h2>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-extrabold border border-emerald-200">Real-Time Financial Log</span>
            </div>
            <p class="text-slate-500 text-xs mt-0.5">Pemantauan mutasi kas masuk/keluar per-proyek perumahan, per-unit, dan konsolidasi kas global perusahaan.</p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            @if(auth()->user()->isAdminOrFounder() || auth()->user()->isFinance())
                <button wire:click="openManualModal" class="btn-header-setup bg-emerald-600 hover:bg-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Catat Transaksi Kas Baru</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Filter Control Panel Header -->
    <div class="card-clean p-5 space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <!-- View Mode Switcher -->
            <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-xl">
                <button wire:click="$set('view_mode', 'global')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $view_mode === 'global' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    <span>Global</span>
                </button>
                <button wire:click="$set('view_mode', 'project')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $view_mode === 'project' ? 'bg-white text-purple-700 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M9 8h.01M15 16h.01M15 12h.01M15 8h.01M12 16h.01M12 12h.01M12 8h.01"/></svg>
                    <span>Per-Proyek</span>
                </button>
                <button wire:click="$set('view_mode', 'unit')" class="px-3 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $view_mode === 'unit' ? 'bg-white text-teal-700 shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Per-Unit Kavling</span>
                </button>
            </div>

            <!-- Month Quick Filter -->
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-xs text-slate-500 font-medium">Periode:</span>
                <input type="month" wire:model.live="filter_month" class="py-1.5 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-mono text-slate-800 font-bold focus:outline-none focus:border-emerald-500" />
                <button wire:click="setCurrentMonth" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">Bulan Ini</button>
                <button wire:click="setAllTime" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">Semua Waktu</button>
            </div>
        </div>

        <!-- Secondary Filters (Project & Unit Selector) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @if ($view_mode === 'project' || $view_mode === 'unit')
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Pilih Proyek Properti:</label>
                    <select wire:model.live="filter_project_id" class="w-full py-2 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-emerald-500">
                        <option value="">Semua Proyek</option>
                        @foreach ($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->location }})</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if ($view_mode === 'unit')
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Pilih Unit Kavling & Rumah:</label>
                    <select wire:model.live="filter_unit_id" class="w-full py-2 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl font-semibold text-slate-800 focus:outline-none focus:border-emerald-500">
                        <option value="">Semua Unit</option>
                        @foreach ($availableUnits as $u)
                            <option value="{{ $u->id }}">Unit {{ $u->code }} - Tipe {{ $u->type }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    <!-- Summary KPI Cards Grid (Filtered View Balance) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- Card 1: Total Pemasukan -->
        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Kas Masuk (Pemasukan)</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">
                Rp {{ number_format($totalMasuk, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Penjualan unit, booking fee & DP</p>
        </div>

        <!-- Card 2: Total Pengeluaran -->
        <div class="kpi-card-rose">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Kas Keluar (Pengeluaran)</span>
                <div class="p-2.5 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-rose-700 font-mono mt-2">
                Rp {{ number_format($totalKeluar, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Belanja material, upah & operasional</p>
        </div>

        <!-- Card 3: Saldo Kas Bersih -->
        <div class="kpi-card-blue">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Saldo Bersih (Net Cashflow)</span>
                <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono mt-2 {{ $netCashflow >= 0 ? 'text-slate-900' : 'text-rose-600' }}">
                Rp {{ number_format($netCashflow, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Akumulasi bersih periode terpilih</p>
        </div>
    </div>

    <!-- Real-Time Interactive ApexCharts Section (Fixed Auto Re-render) -->
    <div class="card-clean p-5 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
            <div>
                <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18"/></svg>
                    <span>Grafik Tren Mutasi Kas (Pemasukan vs Pengeluaran 6 Bulan)</span>
                </h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Otomatik menyesuaikan grafik saat filter proyek atau bulan diubah</p>
            </div>
        </div>

        <!-- Alpine Component with Dynamic Event-driven ApexCharts rendering -->
        <div x-data="{
                 chart: null,
                 init() {
                     this.$nextTick(() => {
                         this.initOrUpdateChart(@js($chartLabels), @js($chartMasuk), @js($chartKeluar));
                     });
                 },
                 initOrUpdateChart(labels, masuk, keluar) {
                     if (typeof ApexCharts === 'undefined') {
                         setTimeout(() => this.initOrUpdateChart(labels, masuk, keluar), 100);
                         return;
                     }
                     const chartEl = this.$refs.chartCanvas;
                     if (!chartEl) return;
                     if (this.chart) {
                         try { this.chart.destroy(); } catch(e) {}
                         this.chart = null;
                     }
                     const options = {
                         series: [{
                             name: 'Kas Masuk (Pemasukan)',
                             data: masuk || []
                         }, {
                             name: 'Kas Keluar (Pengeluaran)',
                             data: keluar || []
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
                             categories: labels || [],
                             labels: { style: { colors: '#64748b', fontSize: '11px', fontWeight: 600 } }
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
                         legend: { position: 'top', horizontalAlign: 'right', fontWeight: 600 }
                     };
                     this.chart = new ApexCharts(chartEl, options);
                     this.chart.render();
                 }
             }"
             @cashflow-chart-updated.window="const payload = Array.isArray($event.detail) ? $event.detail[0] : $event.detail; initOrUpdateChart(payload.labels, payload.masuk, payload.keluar);"
             wire:ignore.self>
            <div x-ref="chartCanvas" class="w-full min-h-[280px]"></div>
        </div>
    </div>

    <!-- Category Breakdown Summary Tabs -->
    <div class="card-clean p-5 space-y-3" x-data="{ chartTab: 'masuk' }">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wider">Komposisi & Distribusi Kategori Kas</h3>
            <div class="flex items-center gap-1.5 p-1 bg-slate-100 rounded-xl text-xs">
                <button @click="chartTab = 'masuk'" :class="chartTab === 'masuk' ? 'bg-white text-emerald-700 shadow-2xs' : 'text-slate-600'" class="px-3 py-1 rounded-lg font-bold transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    <span>Pemasukan</span>
                </button>
                <button @click="chartTab = 'keluar'" :class="chartTab === 'keluar' ? 'bg-white text-rose-700 shadow-2xs' : 'text-slate-600'" class="px-3 py-1 rounded-lg font-bold transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                    <span>Pengeluaran</span>
                </button>
            </div>
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
                        <span class="font-mono text-emerald-700 font-bold">Rp {{ number_format($cat->total_amount, 0, ',', '.') }} ({{ $pct }}%)</span>
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
                        <span class="font-mono text-rose-700 font-bold">Rp {{ number_format($cat->total_amount, 0, ',', '.') }} ({{ $pct }}%)</span>
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

    @if ($view_mode === 'global')
        <!-- Breakdown per perumahan -->
        <div class="card-clean p-5 space-y-4">
            <h3 class="font-extrabold text-slate-900 text-xs uppercase tracking-wider">Ringkasan Kas Konsolidasi per Perumahan / Proyek</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($projectBreakdown as $pb)
                    <div class="p-4 bg-slate-50/80 border border-slate-200/80 rounded-2xl space-y-2 shadow-2xs hover:bg-white transition">
                        <div class="font-extrabold text-slate-900 text-xs flex justify-between items-center">
                            <span>{{ $pb['name'] }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">ID: #{{ $pb['id'] }}</span>
                        </div>
                        <div class="text-xs space-y-1.5 font-mono pt-1">
                            <div class="flex justify-between text-emerald-600 font-medium">
                                <span class="text-slate-500 font-sans">Kas Masuk:</span>
                                <span class="font-bold">Rp {{ number_format($pb['masuk'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-rose-600 font-medium">
                                <span class="text-slate-500 font-sans">Kas Keluar:</span>
                                <span class="font-bold">Rp {{ number_format($pb['keluar'], 0, ',', '.') }}</span>
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

    <!-- Mutasi Arus Kas Table Card (Ultra User-Friendly) -->
    <div class="card-clean overflow-hidden space-y-0">
        <!-- Table Search & Filters Control Bar -->
        <div class="p-4 bg-slate-50/60 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="relative w-full sm:w-80">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Cari keterangan, proyek, atau pencatat..." 
                       class="w-full pl-9 pr-3.5 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-medium text-slate-800 placeholder-slate-400 shadow-2xs" />
            </div>

            <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end flex-wrap">
                <!-- Type Filter -->
                <select wire:model.live="typeFilter" class="py-2 px-3 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 text-slate-700 font-bold shadow-2xs">
                    <option value="">Semua Tipe Kas</option>
                    <option value="masuk">Kas Masuk</option>
                    <option value="keluar">Kas Keluar</option>
                </select>

                <!-- Category Filter -->
                <select wire:model.live="categoryFilter" class="py-2 px-3 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 text-slate-700 font-bold shadow-2xs">
                    <option value="">Semua Kategori</option>
                    <option value="pembayaran_cicilan_pembeli">Cicilan Pembeli</option>
                    <option value="booking_fee">Booking Fee</option>
                    <option value="belanja_material">Belanja Material</option>
                    <option value="gaji_karyawan">Gaji Karyawan</option>
                    <option value="operasional">Operasional</option>
                    <option value="lahan_proyek">Pembayaran Lahan</option>
                </select>
            </div>
        </div>

        <!-- Table Data Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/90 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Proyek Properti</th>
                        <th class="px-5 py-3.5">Tipe & Kategori</th>
                        <th class="px-5 py-3.5">Keterangan Transaksi</th>
                        <th class="px-5 py-3.5">Petugas Pencatat</th>
                        <th class="px-5 py-3.5 text-right">Nominal (Rp)</th>
                        <th class="px-5 py-3.5 text-center">Aksi & Resi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50/70 transition duration-150">
                            <!-- Tanggal -->
                            <td class="px-5 py-4 font-mono">
                                <span class="font-bold text-slate-800 block text-xs">{{ $trx->transaction_date->format('d M Y') }}</span>
                                <span class="text-[10px] text-slate-400 font-sans block">{{ $trx->transaction_date->locale('id')->isoFormat('dddd') }}</span>
                            </td>

                            <!-- Proyek -->
                            <td class="px-5 py-4 font-bold text-slate-800">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m3 0h1m-1-4h.01M9 16h.01M9 12h.01M9 8h.01M15 16h.01M15 12h.01M15 8h.01M12 16h.01M12 12h.01M12 8h.01"/></svg>
                                    <span>{{ $trx->project->name ?? 'Global / Kantor Pusat' }}</span>
                                </div>
                            </td>

                            <!-- Tipe & Kategori -->
                            <td class="px-5 py-4">
                                <div class="space-y-1">
                                    @if($trx->type === 'masuk')
                                        <span class="status-disetujui text-[10px]">Kas Masuk</span>
                                    @else
                                        <span class="status-ditolak text-[10px]">Kas Keluar</span>
                                    @endif
                                    <span class="capitalize font-semibold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md text-[10px] border border-slate-200/60 block w-fit">
                                        {{ str_replace('_', ' ', $trx->category) }}
                                    </span>
                                </div>
                            </td>

                            <!-- Keterangan Transaksi -->
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-800 leading-relaxed text-xs">{{ $trx->description }}</p>
                                @if($trx->reference_type)
                                    <span class="text-[10px] font-mono text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200 mt-1 inline-block">
                                        Ref: {{ class_basename($trx->reference_type) }} #{{ $trx->reference_id }}
                                    </span>
                                @endif
                            </td>

                            <!-- Pencatat -->
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-700 text-xs">{{ $trx->creator->name ?? 'System' }}</p>
                                <span class="text-[10px] text-slate-400 font-mono uppercase">{{ $trx->creator->role ?? 'System' }}</span>
                            </td>

                            <!-- Nominal -->
                            <td class="px-5 py-4 text-right font-mono font-extrabold text-sm {{ $trx->type === 'masuk' ? 'text-emerald-600' : 'text-rose-600' }}">
                                <span>Rp {{ number_format($trx->amount, 0, ',', '.') }}</span>
                            </td>

                            <!-- Aksi -->
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if ($trx->receipt_photo_url)
                                        <button wire:click="openImageModal('{{ $trx->receipt_photo_url }}', 'Foto Struk Resi Kas - {{ $trx->description }}')" 
                                                class="btn-action-pdf bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100 text-xs px-2.5 py-1" 
                                                title="Buka Foto Struk Bukti Transfer / Transaksi">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>Struk</span>
                                        </button>
                                    @endif
                                     <button wire:click="openDetailModal({{ $trx->id }})" class="btn-action-detail text-xs px-2.5 py-1" title="Audit Trail Detail Transaksi">
                                         <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                         <span>Detail</span>
                                     </button>
                                     @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                         <button type="button" wire:click="editTransaction({{ $trx->id }})" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200/90 rounded-lg text-xs font-bold transition flex items-center gap-1 shadow-2xs" title="Edit Transaksi Kas Ini">
                                             <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                             <span>Edit</span>
                                         </button>
                                     @endif
                                     @if(auth()->user()->isFounder())
                                         <button type="button" 
                                                 @click="confirmModalAction({
                                                     title: 'Hapus Mutasi Transaksi Kas',
                                                     message: 'Yakin ingin menghapus mutasi transaksi kas #TRX-{{ $trx->id }} ini?',
                                                     confirmText: 'Hapus Transaksi',
                                                     btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                     onConfirm: () => $wire.deleteTransaction({{ $trx->id }})
                                                 })" 
                                                 class="btn-action-delete text-xs px-2.5 py-1" 
                                                 title="Hapus Transaksi Kas">
                                             <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                             <span>Hapus</span>
                                         </button>
                                     @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="font-semibold text-slate-600">Belum Ada Mutasi Transaksi Kas Ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau catat transaksi kas baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Edit Transaksi Mutasi Kas -->
    @if($showEditModal && $editingTransactionId)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
            <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-md w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                    <div class="flex items-center gap-2">
                        <div class="p-2 rounded-xl bg-amber-50 text-amber-700 border border-amber-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm sm:text-base">Edit Mutasi Transaksi Kas #TRX-{{ $editingTransactionId }}</h3>
                            <p class="text-slate-500 text-[11px]">Ubah keterangan, nominal, atau kategori transaksi</p>
                        </div>
                    </div>
                    <button wire:click="$set('showEditModal', false)" class="text-slate-400 hover:text-slate-600 font-bold text-sm p-1">✕</button>
                </div>

                <!-- Form Body -->
                <form wire:submit.prevent="updateTransaction" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Deskripsi / Keterangan Transaksi <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="edit_description" required placeholder="Contoh: Pembayaran Uang Muka (DP) Unit BLOK D 7..." class="input-clean w-full text-xs sm:text-sm font-medium">
                        <span class="text-[10px] text-slate-400 block mt-1">Ganti kode unit, nama pembeli, atau keterangan catatan di sini.</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Kategori Mutasi <span class="text-rose-500">*</span></label>
                            <select wire:model="edit_category" class="select-clean w-full font-semibold">
                                <option value="operasional">Operasional</option>
                                <option value="booking_fee">Booking Fee</option>
                                <option value="pembayaran_dp">Pembayaran Uang Muka (DP)</option>
                                <option value="pembayaran_cicilan_pembeli">Setoran Cicilan Pembeli</option>
                                <option value="penjualan_unit">Penjualan Unit Cash</option>
                                <option value="upah_tukang">Upah Pekerja / Tukang</option>
                                <option value="material">Pembelian Material</option>
                                <option value="pembelian_lahan">Pembelian Lahan Proyek</option>
                                <option value="gaji_karyawan">Gaji Karyawan Staf</option>
                                <option value="pemasukan_lain">Pemasukan Lain-lain</option>
                                <option value="pengeluaran_lain">Pengeluaran Lain-lain</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                            <input type="date" wire:model="edit_transaction_date" required class="input-clean w-full font-mono text-xs sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nominal Transaksi <span class="text-rose-500">*</span></label>
                        <div class="flex rounded-xl shadow-xs">
                            <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                                Rp
                            </span>
                            <x-currency-input model="edit_amount" class="input-clean rounded-r-xl rounded-l-none font-bold text-xs sm:text-sm font-mono w-full" placeholder="0" />
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                        <button type="button" wire:click="$set('showEditModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary bg-amber-600 hover:bg-amber-700">Simpan Perubahan Arus Kas</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Catat Kas Manual -->
    @include('livewire.cashflow.partials.modal-manual-transaction')

    <!-- Modal Detail Alur Keuangan & Audit Trail -->
    @include('livewire.cashflow.partials.modal-detail-transaction')

    <!-- Foto Struk Resi Viewer Modal -->
    @if($showImageModal ?? false)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $imageModalTitle }}
                    </h3>
                    <button wire:click="closeImageModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
                </div>
                <div class="flex-1 overflow-auto flex items-center justify-center p-2 bg-slate-50 rounded-2xl border border-slate-200 min-h-[300px]">
                    <img src="{{ $imageModalUrl }}" alt="Foto Struk Resi Kas" class="max-h-[600px] w-auto max-w-full object-contain rounded-xl shadow-sm">
                </div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                    <a href="{{ $imageModalUrl }}" target="_blank" class="btn-secondary text-xs px-4 py-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>Buka Gambar Asli</span>
                    </a>
                    <button wire:click="closeImageModal" class="btn-primary bg-slate-800 hover:bg-slate-900 text-xs px-5 py-2">Tutup</button>
                </div>
            </div>
        </div>
    @endif

</div>
