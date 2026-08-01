<div class="space-y-6">

    <!-- Header & Single-row Toolbar Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-5 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            
            <!-- Judul & Deskripsi Halaman -->
            <div class="space-y-1">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                    Data Unit Kavling, Rumah & Infrastruktur
                </h2>
                <p class="text-xs text-slate-500 leading-relaxed max-w-2xl">
                    Penetapan HPP, unit infrastruktur kawasan (parit, jalan, pos), penugasan mandor/tukang, dan booking unit.
                </p>
            </div>

            <!-- Filter & Action Control Container -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:flex lg:items-center gap-2.5 w-full lg:w-auto">
                
                <!-- Input Pencarian Unit -->
                <div class="relative w-full sm:col-span-2 lg:w-60">
                    <input type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari unit, proyek, mandor..." 
                        class="w-full h-10 pl-9 pr-3 text-xs font-medium text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder:text-slate-400">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>

                <!-- Filter Kategori -->
                <div class="w-full lg:w-auto">
                    <select wire:model.live="category_filter" 
                            class="w-full h-10 px-3 text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                        <option value="">Semua Kategori</option>
                        <option value="kavling">Kavling Tanah</option>
                        <option value="rumah">Bangunan Rumah</option>
                        <option value="infrastruktur">Fasum & Infrastruktur</option>
                    </select>
                </div>

                <!-- Filter Proyek -->
                <div class="w-full lg:w-auto">
                    <select wire:model.live="project_id" 
                            class="w-full h-10 px-3 text-xs font-semibold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                        <option value="">Semua Proyek Properti</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Toggle View Mode Button (Tabel ↔ Site Plan Visual) -->
                <div class="flex items-center bg-slate-100 p-1 rounded-xl border border-slate-200 shrink-0">
                    <button wire:click="$set('viewMode', 'table')" type="button" class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $viewMode === 'table' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        <span>Tabel</span>
                    </button>
                    <button wire:click="$set('viewMode', 'siteplan')" type="button" class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5 {{ $viewMode === 'siteplan' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-800' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                        <span>Site Plan Visual</span>
                    </button>
                </div>

                <!-- Tombol Tambah Unit -->
                @if(auth()->user()->isFounder() || auth()->user()->isSupervisor())
                    <button wire:click="openModal" 
                            class="btn-primary h-10 text-xs font-bold whitespace-nowrap shadow-sm">
                        <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Unit Baru</span>
                    </button>
                @endif

            </div>
        </div>
    </div>

    <!-- Summary KPI Cards Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="kpi-card-blue">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Stok Unit</span>
                <div class="p-2 rounded-xl bg-purple-50 text-purple-600 border border-purple-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 font-mono mt-1.5">{{ $units->total() }} Unit</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Total unit kavling & rumah</p>
        </div>

        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Unit Tersedia</span>
                <div class="p-2 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-1.5">
                {{ \App\Models\Unit::where('status', 'tersedia')->count() }} Unit
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Siap dijual / dipesan</p>
        </div>

        <div class="kpi-card-amber">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Booked & Pending</span>
                <div class="p-2 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-700 font-mono mt-1.5">
                {{ \App\Models\Unit::whereIn('status', ['booked', 'menunggu_persetujuan'])->count() }} Unit
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Dalam proses transaksi</p>
        </div>

        <div class="kpi-card-rose">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Unit Terjual / ACC</span>
                <div class="p-2 rounded-xl bg-rose-50 text-rose-600 border border-rose-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-rose-700 font-mono mt-1.5">
                {{ \App\Models\Unit::whereIn('status', ['disetujui', 'terjual', 'converted'])->count() }} Unit
            </p>
            <p class="text-[10px] text-slate-400 mt-0.5">Penjualan lunas / cicilan</p>
        </div>
    </div>

    @if($viewMode === 'siteplan')
        <div class="card-clean p-4 sm:p-6 bg-slate-900/5 border border-slate-200 mb-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-3 sm:gap-4">
                @forelse($units as $unit)
                    @php
                        $isInfra = ($unit->category === 'infrastruktur' || $unit->type === 'infrastruktur');
                        $isSold = in_array($unit->status, ['terjual', 'disetujui']);
                        $isBooked = in_array($unit->status, ['booked', 'menunggu_persetujuan']);
                        $isAvailable = in_array($unit->status, ['tersedia', 'draft']);

                        if ($isInfra) {
                            $cardBg = 'bg-indigo-50/90 border-indigo-200 hover:border-indigo-400 text-indigo-950';
                            $badgeBg = 'bg-indigo-100 text-indigo-800 border-indigo-200';
                            $statusLabel = 'Fasum';
                        } elseif ($isSold) {
                            $cardBg = 'bg-rose-50/90 border-rose-200 hover:border-rose-400 text-rose-950';
                            $badgeBg = 'bg-rose-100 text-rose-800 border-rose-200';
                            $statusLabel = 'Terjual';
                        } elseif ($isBooked) {
                            $cardBg = 'bg-amber-50/90 border-amber-200 hover:border-amber-400 text-amber-950';
                            $badgeBg = 'bg-amber-100 text-amber-800 border-amber-200';
                            $statusLabel = 'Booked';
                        } else {
                            $cardBg = 'bg-emerald-50/90 border-emerald-200 hover:border-emerald-400 text-emerald-950';
                            $badgeBg = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                            $statusLabel = 'Tersedia';
                        }
                    @endphp

                    <a href="{{ route('units.show', $unit->id) }}" class="{{ $cardBg }} border rounded-2xl p-3 flex flex-col justify-between transition-all duration-200 transform hover:-translate-y-1 hover:shadow-md cursor-pointer group relative overflow-hidden min-h-[128px]">
                        <div class="flex items-center justify-between gap-1">
                            <span class="text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-full border {{ $badgeBg }}">
                                {{ $statusLabel }}
                            </span>
                            <span class="text-[10px] font-mono text-slate-500 font-semibold">
                                {{ (float)$unit->land_area }} m²
                            </span>
                        </div>

                        <div class="my-2">
                            <p class="text-base sm:text-lg font-black font-mono tracking-tight group-hover:text-emerald-700 transition">
                                {{ $unit->code }}
                            </p>
                            <p class="text-[10px] text-slate-500 font-medium capitalize truncate">
                                {{ $unit->project->name ?? 'Proyek' }}
                            </p>
                        </div>

                        <div class="pt-1.5 border-t border-slate-200/60 flex items-center justify-between text-[11px]">
                            <span class="font-mono font-bold text-slate-800">
                                Rp {{ number_format($unit->final_selling_price ?? $unit->hpp ?? 0, 0, ',', '.') }}
                            </span>
                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-emerald-600 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                        <p class="font-bold text-slate-600">Tidak ada unit yang sesuai dengan filter site plan</p>
                    </div>
                @endforelse
            </div>
        </div>
    @else
        <!-- Units Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($units as $unit)
            <div class="card-clean p-5 space-y-4 flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <!-- Top Badge & Code -->
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-extrabold text-slate-900 font-mono">{{ $unit->code }}</span>
                            @if($unit->category === 'infrastruktur' || $unit->status === 'infrastruktur')
                                <span class="text-[10px] uppercase font-extrabold px-2 py-0.5 rounded-md bg-sky-100 text-sky-800 border border-sky-300">
                                    FASUM: {{ strtoupper($unit->type) }}
                                </span>
                            @elseif($unit->category === 'rumah')
                                <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-md bg-purple-50 text-purple-800 border border-purple-200">
                                    Rumah
                                </span>
                            @else
                                <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200">
                                    Kavling
                                </span>
                            @endif
                        </div>

                        <!-- Status Badge -->
                        @if($unit->status === 'infrastruktur' || $unit->category === 'infrastruktur')
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-sky-950 text-sky-300 border border-sky-500/40">
                                Infrastruktur Proyek
                            </span>
                        @elseif($unit->status === 'tersedia')
                            <span class="status-tersedia">Tersedia</span>
                        @elseif($unit->status === 'booked')
                            <span class="status-booked">Booked</span>
                        @elseif($unit->status === 'menunggu_persetujuan')
                            <span class="status-menunggu">Pending Approval</span>
                        @elseif($unit->status === 'disetujui')
                            <span class="status-disetujui">Harga ACC</span>
                        @elseif($unit->status === 'terjual')
                            <span class="status-terjual">Terjual</span>
                        @else
                            <span class="status-draft">{{ ucfirst($unit->status) }}</span>
                        @endif
                    </div>

                    <p class="text-xs font-semibold text-slate-600 mt-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        {{ $unit->project->name }}
                    </p>

                    <!-- Mandor / Tukang Bertugas Badge (Ringkas: 1 Pekerja + ...) -->
                    @php
                        $activeWorkers = $unit->activeAssignments->where('status', 'active');
                        $firstWorker = $activeWorkers->first();
                        $workerCount = $activeWorkers->count();
                    @endphp
                    <div class="mt-2.5 flex items-center gap-1.5">
                        @if($firstWorker && $firstWorker->worker)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-blue-50 text-blue-800 border border-blue-200/80 max-w-[220px] truncate" title="{{ $firstWorker->worker->name }} ({{ ucfirst($firstWorker->worker->type) }})">
                                <svg class="w-3 h-3 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="truncate">{{ $firstWorker->worker->name }} ({{ ucfirst($firstWorker->worker->type) }})</span>
                                @if($workerCount > 1)
                                    <span class="text-blue-600 font-bold shrink-0">...</span>
                                @endif
                            </span>
                        @else
                            <span class="text-[10px] text-slate-400 italic">Belum ada penugasan pekerja</span>
                        @endif
                    </div>

                    <!-- Details Box -->
                    <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-3.5 mt-3.5 text-xs space-y-1.5">
                        @if($unit->category === 'infrastruktur' || $unit->status === 'infrastruktur')
                            <div class="text-sky-900 font-semibold">
                                <span class="text-slate-500 font-medium">Jenis Fasum:</span> {{ strtoupper($unit->type) }}
                            </div>
                            @if($unit->specifications)
                                <div class="text-[11px] text-slate-600 pt-1 border-t border-slate-200/80">
                                    <span class="font-bold text-slate-700">Keterangan:</span> {{ $unit->specifications }}
                                </div>
                            @endif
                        @else
                            <div class="flex justify-between text-slate-600">
                                <span>Dimensi Tanah (P x L):</span>
                                <span class="font-mono font-medium text-slate-800">{{ $unit->land_length }}m &times; {{ $unit->land_width }}m</span>
                            </div>
                            <div class="flex justify-between text-slate-700 font-semibold">
                                <span>Luas Tanah Total:</span>
                                <span class="font-mono text-slate-900 font-bold">{{ number_format($unit->land_area, 0, ',', '.') }} m²</span>
                            </div>

                            @if($unit->category === 'rumah' && $unit->building_area)
                                <div class="flex justify-between text-purple-700 font-semibold pt-1 border-t border-slate-200/80">
                                    <span>Luas Bangunan:</span>
                                    <span class="font-mono font-bold">{{ number_format($unit->building_area, 0, ',', '.') }} m² ({{ $unit->floors_count ?? 1 }} Lt)</span>
                                </div>
                            @endif

                            @if($unit->excess_land_area > 0)
                                <div class="flex justify-between text-amber-700 font-medium pt-1.5 border-t border-slate-200/80">
                                    <span>Kelebihan Luas:</span>
                                    <span class="font-mono font-bold">+{{ number_format($unit->excess_land_area, 0, ',', '.') }} m² (+Rp {{ number_format($unit->excess_cost, 0, ',', '.') }})</span>
                                </div>
                            @else
                                <div class="text-[11px] text-slate-400 pt-1 border-t border-slate-200/80">
                                    Ukuran standar proyek ({{ number_format($unit->project->standard_land_area, 0, ',', '.') }} m²)
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Price Info & Financial Status -->
                @php
                    $payInfo = $unitPaymentsData[$unit->id] ?? [
                        'deal_price' => 0,
                        'paid_amount' => 0,
                        'remaining_amount' => 0,
                        'is_sold' => false,
                    ];
                @endphp
                <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5 text-xs">
                    @if(auth()->user()->canViewHpp())
                        <div class="flex justify-between items-baseline">
                            <span class="text-slate-500 font-medium">{{ $unit->category === 'infrastruktur' ? 'Anggaran / HPP Infra:' : 'HPP Pokok:' }}</span>
                            <span class="font-mono font-bold text-slate-800">
                                {{ $unit->hpp ? 'Rp ' . number_format($unit->hpp, 0, ',', '.') : 'Belum Diset' }}
                            </span>
                        </div>
                    @endif

                    @if($payInfo['deal_price'] > 0 && $unit->category !== 'infrastruktur')
                        <div class="flex justify-between items-baseline text-emerald-700 font-bold">
                            <span>Harga Deal Unit:</span>
                            <span class="font-mono">Rp {{ number_format($payInfo['deal_price'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-baseline text-sky-700 font-semibold">
                            <span>Sudah Terbayar:</span>
                            <span class="font-mono">Rp {{ number_format($payInfo['paid_amount'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-baseline text-amber-700 font-semibold">
                            <span>Sisa Tagihan:</span>
                            @if($payInfo['is_sold'] && $payInfo['remaining_amount'] == 0)
                                <span class="text-emerald-600 font-bold">LUNAS</span>
                            @else
                                <span class="font-mono font-bold">Rp {{ number_format($payInfo['remaining_amount'], 0, ',', '.') }}</span>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Footer Actions & Booking Button -->
                <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('units.show', $unit->id) }}" class="btn-action-detail">
                            <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Detail</span>
                        </a>
                        @if(auth()->user()->isFounder() || auth()->user()->isSupervisor())
                            <button wire:click="editUnit({{ $unit->id }})" class="btn-action-edit">
                                <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Edit</span>
                            </button>
                        @endif
                        @if(auth()->user()->isFounder())
                            <button wire:click="deleteUnit({{ $unit->id }})" wire:confirm="Yakin ingin menghapus unit {{ $unit->code }} dari sistem beserta seluruh histori terikatnya?" class="btn-action-delete" title="Hapus Unit">
                                <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus</span>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-1.5">
                        @if(!auth()->user()->isPengawasProject() && $unit->category !== 'infrastruktur' && in_array($unit->status, ['tersedia', 'disetujui']))
                            <button wire:click="openBookingModal({{ $unit->id }})" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-3 py-1.5 rounded-lg transition shadow-sm flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Booking Unit</span>
                            </button>
                        @endif

                        @if(auth()->user()->isMarketing() && $unit->category !== 'infrastruktur' && $unit->status === 'tersedia')
                            <a href="{{ route('proposals.index', ['create_unit_id' => $unit->id]) }}" class="btn-primary text-xs px-2.5 py-1.5">
                                <span>Ajukan Harga</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full card-clean p-12 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <p class="font-semibold text-slate-600">Belum Ada Unit Kavling / Infrastruktur Didaftarkan</p>
                <p class="text-xs text-slate-400 mt-1">Klik tombol "Tambah Unit Baru" di atas untuk menambahkan unit kavling, rumah, atau infrastruktur (parit, jalan, pos).</p>
            </div>
        @endforelse
    </div>
    @endif

    <div class="mt-4">
        {{ $units->links() }}
    </div>

    <!-- Modal Form Unit -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">
                        {{ $editingUnitId ? 'Edit Data Unit' : 'Tambah Unit (Kavling, Rumah & Infrastruktur)' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveUnit" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Pilih Proyek</label>
                        <select wire:model.live="selected_project_id" class="input-clean w-full font-semibold">
                            <option value="">Pilih Proyek...</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Standar: {{ number_format($p->standard_land_area, 0, ',', '.') }}m²)</option>
                            @endforeach
                        </select>
                        @error('selected_project_id') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Kode Unit</label>
                            <input type="text" wire:model="code" placeholder="Contoh: A-01 / INF-PARIT-01" class="input-clean w-full font-bold font-mono">
                            @error('code') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Kategori Unit</label>
                            <select wire:model.live="category" class="input-clean w-full font-semibold">
                                <option value="kavling">Kavling Tanah (Komersial)</option>
                                <option value="rumah">Bangunan Rumah (Komersial)</option>
                                <option value="infrastruktur">Fasilitas Umum & Infrastruktur Kawasan</option>
                            </select>
                            @error('category') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if($category === 'infrastruktur')
                        <div class="bg-sky-50/80 border border-sky-200/80 rounded-xl p-3.5 space-y-3">
                            <p class="font-bold text-[11px] uppercase tracking-wider text-sky-900">Detail Infrastruktur & Fasilitas Umum Kawasan:</p>
                            
                            <div>
                                <label class="block font-semibold text-sky-800 mb-1 uppercase tracking-wider">Sub-Jenis Infrastruktur</label>
                                <select wire:model="infra_type" class="input-clean w-full font-bold bg-white">
                                    <option value="parit">Pembuatan Parit / Drainase Utama Kawasan</option>
                                    <option value="jalan">Pengerasan / Paving / Aspal Jalan Perumahan</option>
                                    <option value="taman">Taman Kawasan & Ruang Terbuka Hijau (RTH)</option>
                                    <option value="pos_satpam">Gerbang Utama & Pos Security / Satpam</option>
                                    <option value="pju">Penerangan Jalan Umum (PJU) & Listrik</option>
                                    <option value="air">Jaringan Air Bersih & Sanitasi</option>
                                    <option value="fasum_lainnya">Infrastruktur / Fasum Kawasan Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-semibold text-sky-800 mb-1 uppercase tracking-wider">Keterangan / Deskripsi Pengerjaan</label>
                                <textarea wire:model="specifications" rows="2" placeholder="Detail lokasi, spesifikasi cor parit, lebar jalan, dll." class="input-clean w-full bg-white"></textarea>
                            </div>
                        </div>
                    @elseif($category === 'rumah')
                        <div class="bg-purple-50/70 border border-purple-200/80 rounded-xl p-3.5 space-y-3">
                            <p class="font-bold text-[11px] uppercase tracking-wider text-purple-900">Spesifikasi Bangunan Rumah:</p>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold text-purple-800 mb-1 uppercase tracking-wider">Luas Bangunan (m²)</label>
                                    <input type="number" step="0.01" wire:model="building_area" placeholder="36" class="input-clean w-full font-mono bg-white">
                                </div>
                                <div>
                                    <label class="block font-semibold text-purple-800 mb-1 uppercase tracking-wider">Jumlah Lantai</label>
                                    <input type="number" min="1" wire:model="floors_count" placeholder="1" class="input-clean w-full font-mono bg-white">
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-purple-800 mb-1 uppercase tracking-wider">Deskripsi Spesifikasi Fisik</label>
                                <textarea wire:model="specifications" rows="2" placeholder="Pondasi batu kali, granit 60x60, atap baja ringan, dll." class="input-clean w-full bg-white"></textarea>
                            </div>
                        </div>
                    @endif

                    @if($category !== 'infrastruktur')
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Panjang (m)</label>
                                <input type="number" step="0.01" wire:model.live="land_length" class="input-clean w-full font-mono">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Lebar (m)</label>
                                <input type="number" step="0.01" wire:model.live="land_width" class="input-clean w-full font-mono">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Luas Tanah (m²)</label>
                                <input type="number" step="0.01" wire:model.live="land_area" class="input-clean w-full font-bold font-mono">
                                @error('land_area') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif

                    @if($selected_project_id && $category !== 'infrastruktur')
                        <div class="bg-emerald-50 border border-emerald-200/80 rounded-xl p-3.5 space-y-1.5 text-emerald-900">
                            <p class="font-bold text-[11px] uppercase tracking-wider text-emerald-800">Kalkulasi Otomatis Kelebihan Tanah:</p>
                            <div class="flex justify-between text-xs">
                                <span>Kelebihan Luas:</span>
                                <span class="font-mono font-bold">{{ number_format($previewExcessArea, 0, ',', '.') }} m²</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span>Biaya Kelebihan Tanah:</span>
                                <span class="font-mono font-bold">Rp {{ number_format($previewExcessCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xs font-bold pt-1.5 border-t border-emerald-200/80">
                                <span>Rekomendasi HPP Final:</span>
                                <span class="font-mono text-emerald-700">Rp {{ number_format($previewRecommendedHpp, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->canViewHpp())
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">HPP Pokok Final (Rp)</label>
                            <x-currency-input model="hpp" placeholder="Rp {{ number_format($previewRecommendedHpp, 0, ',', '.') }}" class="input-clean w-full font-bold font-mono text-slate-900" />
                            <p class="text-[10px] text-slate-500 mt-1">*HPP dapat disesuaikan ulang oleh bagian Finance.</p>
                        </div>
                    @endif

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Unit</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Form Booking Unit Langsung (Req #2) -->
    @if($showBookingModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Booking Unit {{ $bookingUnitCode }}</h3>
                        <p class="text-slate-500 text-[11px]">Pencatatan booking unit & bukti DP langsung di dalam sistem</p>
                    </div>
                    <button wire:click="$set('showBookingModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveBooking" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nama Pembeli</label>
                        <input type="text" wire:model="buyer_name" required placeholder="Contoh: Bpk. H. Hendra Wijaya" class="input-clean w-full font-bold">
                        @error('buyer_name') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nomor HP / WhatsApp Pembeli</label>
                        <input type="text" wire:model="buyer_phone" required placeholder="081234567890" class="input-clean w-full font-mono">
                        @error('buyer_phone') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nominal Tanda Jadi / Booking Fee (Rp)</label>
                        <x-currency-input model="booking_amount" class="input-clean w-full font-mono font-bold text-teal-700 text-sm" />
                        @error('booking_amount') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Catatan Pembayaran & Bukti DP</label>
                        <textarea wire:model="booking_notes" rows="2" placeholder="Informasi bukti transfer DP, skema pelunasan, dll." class="input-clean w-full"></textarea>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Foto Struk / Bukti Transfer <span class="text-amber-600 font-bold lowercase text-[10px] bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">(Opsional, Maks. 2MB)</span></label>
                        <input type="file" wire:model="receipt_photo" accept="image/*,.heic,.heif,.pdf" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition cursor-pointer">
                        <div wire:loading wire:target="receipt_photo" class="text-[11px] text-amber-600 font-semibold mt-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Mengunggah foto resi...</span>
                        </div>
                        @error('receipt_photo') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                        @if ($receipt_photo ?? false)
                            <div class="mt-2.5 p-2.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                                <div class="flex items-center justify-between text-[11px] font-semibold text-slate-700">
                                    <span class="flex items-center gap-1 text-emerald-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Berkas Terpilih ({{ method_exists($receipt_photo, 'getClientOriginalName') ? $receipt_photo->getClientOriginalName() : 'Foto Resi' }}):</span>
                                    </span>
                                    <button type="button" wire:click="$set('receipt_photo', null)" class="text-rose-500 hover:text-rose-700 text-[10px] underline font-bold">Hapus Foto</button>
                                </div>
                                @if (is_object($receipt_photo) && method_exists($receipt_photo, 'isPreviewable') && $receipt_photo->isPreviewable())
                                    <div class="relative max-h-36 sm:max-h-40 overflow-y-auto rounded-xl border border-slate-200 bg-slate-900 flex items-center justify-center p-1.5">
                                        <img src="{{ $receipt_photo->temporaryUrl() }}" alt="Preview Resi" class="max-h-32 sm:max-h-36 w-auto max-w-full object-contain rounded-lg shadow-sm">
                                    </div>
                                @else
                                    <div class="p-2.5 bg-amber-50 border border-amber-200/80 rounded-xl text-amber-800 text-[11px] font-semibold flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Format berkas siap diunggah. Pratinjau langsung didukung untuk file gambar (JPG/PNG).</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="p-3 bg-blue-50 border border-blue-200/80 rounded-xl text-blue-900 text-[11px] space-y-1">
                        <span class="font-bold">Info Verifikasi:</span> Setelah disimpan, unit otomatis menjadi status <strong>Booked</strong> dan data transaksi akan dikirim ke menu Booking untuk diverifikasi & disetujui DP-nya oleh Finance / Founder.
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showBookingModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Proses Booking Unit</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
