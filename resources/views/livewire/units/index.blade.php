<div class="space-y-6">

    <!-- Top Progress Bar during Livewire Network / View Mode Switch / Filter (Fixed Top Screen) -->
    <div wire:loading.delay.shortest wire:target="setViewMode, viewMode, search, status_filter, category_filter, project_id, page, deleteUnit, openBookingModal, openModal" 
         class="fixed top-0 left-0 right-0 z-[9999] h-1.5 bg-gradient-to-r from-emerald-500 via-teal-400 to-indigo-500 animate-livewire-progress shadow-md">
    </div>

    <!-- Header & Filter Toolbar Section -->
    <x-card padding="p-4 sm:p-5" class="mb-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 sm:gap-4 border-b border-slate-100 pb-3.5">
            <!-- Judul & Deskripsi Halaman -->
            <div class="space-y-1">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                    Data Unit Kavling, Rumah & Infrastruktur
                </h2>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Kelola unit kavling, bangunan rumah, fasum kawasan, filter status ketersediaan, serta visualisasi site plan.
                </p>
            </div>

            <!-- Tombol Tambah Unit & Mode View (Rapi & Responsif Mobile) -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-2.5 shrink-0 w-full sm:w-auto">
                <!-- Toggle View Mode Button (Tabel ↔ Site Plan Visual) -->
                <div class="inline-flex p-1 bg-slate-100/90 rounded-2xl border border-slate-200/80 shrink-0 w-full sm:w-auto">
                    <button wire:click="setViewMode('table')" type="button" class="flex-1 sm:flex-initial justify-center px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $viewMode === 'table' ? 'bg-white text-emerald-700 shadow-2xs border border-slate-200/60' : 'text-slate-500 hover:text-slate-800' }}">
                        <svg wire:loading.remove wire:target="setViewMode('table')" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        <svg wire:loading wire:target="setViewMode('table')" class="w-3.5 h-3.5 animate-spin text-emerald-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Tabel</span>
                    </button>
                    <button wire:click="setViewMode('siteplan')" type="button" class="flex-1 sm:flex-initial justify-center px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $viewMode === 'siteplan' ? 'bg-white text-emerald-700 shadow-2xs border border-slate-200/60' : 'text-slate-500 hover:text-slate-800' }}">
                        <svg wire:loading.remove wire:target="setViewMode('siteplan')" class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                        <svg wire:loading wire:target="setViewMode('siteplan')" class="w-3.5 h-3.5 animate-spin text-emerald-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Site Plan Visual</span>
                    </button>
                </div>

                @if(auth()->user()->isAdminOrFounder() || auth()->user()->isSupervisor())
                    <x-button variant="primary" size="sm" wire:click="openModal" icon="plus" class="w-full sm:w-auto justify-center min-h-[36px] rounded-xl font-bold shadow-xs">
                        <span>Tambah Unit Baru</span>
                    </x-button>
                @endif
            </div>
        </div>

        <!-- Filter Controls Container (Clean Grid with Bottom Active Reset Bar) -->
        <div class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                <!-- Search Bar -->
                <x-search-input placeholder="Cari kode unit, mandor..." containerClass="w-full sm:col-span-2 lg:col-span-1" />

                <!-- Filter Status Unit -->
                <div class="w-full">
                    <select wire:model.live="status_filter" 
                            class="w-full h-10 px-3 text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer shadow-2xs">
                        <option value="">Semua Status Unit</option>
                        <option value="tersedia">Unit Tersedia</option>
                        <option value="booked">Unit Booked</option>
                        <option value="terjual">Unit Terjual / ACC</option>
                        <option value="infrastruktur">Infrastruktur Kawasan</option>
                    </select>
                </div>

                <!-- Filter Kategori -->
                <div class="w-full">
                    <select wire:model.live="category_filter" 
                            class="w-full h-10 px-3 text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer shadow-2xs">
                        <option value="">Semua Kategori</option>
                        <option value="kavling">Kavling Tanah</option>
                        <option value="rumah">Bangunan Rumah</option>
                        <option value="infrastruktur">Fasum & Infrastruktur</option>
                    </select>
                </div>

                <!-- Filter Proyek -->
                <div class="w-full">
                    <select wire:model.live="project_id" 
                            class="w-full h-10 px-3 text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer shadow-2xs">
                        <option value="">Semua Proyek Properti</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($search || $status_filter || $category_filter || $project_id)
                <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                    <span class="text-[11px] font-semibold text-slate-500 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Filter aktif diterapkan</span>
                    </span>
                    <x-reset-filter-button 
                        size="sm"
                        wire:click="$set('search', ''); $set('status_filter', ''); $set('category_filter', ''); $set('project_id', '');" 
                    />
                </div>
            @endif
        </div>
    </x-card>

    <!-- View Mode Switch & Filter Loading Feedback Banner -->
    <div wire:loading.flex wire:target="setViewMode, viewMode, search, status_filter, category_filter, project_id" 
         class="items-center justify-between gap-3 px-4 py-2.5 rounded-2xl bg-emerald-50/95 border border-emerald-200 text-emerald-900 text-xs shadow-sm mb-4">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="font-bold">Memuat tampilan data unit... Mohon tunggu sebentar</span>
        </div>
        <span class="text-[10px] font-mono font-semibold bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">Livewire Sync</span>
    </div>

    <!-- Content Area Container with Loading Dimming -->
    <div wire:loading.class="opacity-40 pointer-events-none transition-opacity duration-200" wire:target="setViewMode, viewMode, search, status_filter, category_filter, project_id">
    
    <!-- Summary KPI Cards Grid (Rapi & Responsif Mobile 2x2 & Desktop 4 Kolom) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5 sm:gap-4 mb-6">
        <!-- Card 1: Total Stok Unit -->
        <div class="kpi-card-blue p-3.5 sm:p-4 xl:p-5 min-w-0 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-1">
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 leading-snug">Total Stok Unit</span>
                <div class="p-1.5 sm:p-2 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 shrink-0 shadow-2xs">
                    <svg class="w-3.5 h-3.5 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </div>
            </div>
            <div class="mt-2 min-w-0">
                <p class="text-base sm:text-xl xl:text-2xl font-black text-slate-900 font-mono tracking-tight leading-none">{{ $totalUnitsCount }} <span class="text-[10px] sm:text-xs font-normal font-sans text-slate-400">Unit</span></p>
                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-1.5 truncate">{{ $project_id ? 'Proyek terpilih' : ($category_filter ? 'Kategori: ' . ucfirst($category_filter) : 'Total stok unit') }}</p>
            </div>
        </div>

        <!-- Card 2: Unit Tersedia -->
        <div class="kpi-card-emerald p-3.5 sm:p-4 xl:p-5 min-w-0 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-1">
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 leading-snug">Unit Tersedia</span>
                <div class="p-1.5 sm:p-2 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shrink-0 shadow-2xs">
                    <svg class="w-3.5 h-3.5 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2 min-w-0">
                <p class="text-base sm:text-xl xl:text-2xl font-black text-emerald-700 font-mono tracking-tight leading-none">
                    {{ $availableUnitsCount }} <span class="text-[10px] sm:text-xs font-normal font-sans text-slate-400">Unit</span>
                </p>
                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-1.5 truncate">Siap dijual / dipesan</p>
            </div>
        </div>

        <!-- Card 3: Booked & Pending -->
        <div class="kpi-card-amber p-3.5 sm:p-4 xl:p-5 min-w-0 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-1">
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 leading-snug">Booked & Pending</span>
                <div class="p-1.5 sm:p-2 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 shrink-0 shadow-2xs">
                    <svg class="w-3.5 h-3.5 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2 min-w-0">
                <p class="text-base sm:text-xl xl:text-2xl font-black text-amber-700 font-mono tracking-tight leading-none">
                    {{ $bookedUnitsCount }} <span class="text-[10px] sm:text-xs font-normal font-sans text-slate-400">Unit</span>
                </p>
                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-1.5 truncate">Dalam proses transaksi</p>
            </div>
        </div>

        <!-- Card 4: Unit Terjual / ACC -->
        <div class="kpi-card-rose p-3.5 sm:p-4 xl:p-5 min-w-0 flex flex-col justify-between">
            <div class="flex items-start justify-between gap-1">
                <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-slate-400 leading-snug">Unit Terjual / ACC</span>
                <div class="p-1.5 sm:p-2 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 shrink-0 shadow-2xs">
                    <svg class="w-3.5 h-3.5 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <div class="mt-2 min-w-0">
                <p class="text-base sm:text-xl xl:text-2xl font-black text-rose-700 font-mono tracking-tight leading-none">
                    {{ $soldUnitsCount }} <span class="text-[10px] sm:text-xs font-normal font-sans text-slate-400">Unit</span>
                </p>
                <p class="text-[10px] sm:text-[11px] text-slate-400 mt-1.5 truncate">Penjualan lunas / cicilan</p>
            </div>
        </div>
    </div>

    @if($viewMode === 'siteplan')
        <div class="p-4 sm:p-6 bg-slate-100/90 rounded-3xl border border-slate-200 shadow-inner mb-6">
            <x-siteplan-visual-grid :units="$units" :interactiveModal="false" :showProjectName="true" />
        </div>
    @else
        <!-- Units Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 sm:gap-5">
        @forelse($units as $unit)
            <div x-data 
                 @click="if (!$event.target.closest('button, a, input, select, textarea')) { Livewire.navigate('{{ route('units.show', $unit->id) }}') }"
                 class="bg-white border-2 border-slate-200/90 hover:border-emerald-300 rounded-3xl p-4 sm:p-5 space-y-3.5 flex flex-col justify-between hover:shadow-md transition-all duration-200 shadow-xs cursor-pointer group">
                <div class="space-y-3">
                    <!-- Top Badge & Code -->
                    <div class="flex items-start justify-between gap-2">
                        <div class="space-y-1 min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-base sm:text-lg font-extrabold text-slate-900 font-mono tracking-tight">{{ $unit->code }}</span>
                                @if($unit->category === 'infrastruktur' || $unit->status === 'infrastruktur')
                                    <span class="text-[10px] uppercase font-extrabold px-2 py-0.5 rounded-md bg-sky-100 text-sky-800 border border-sky-300 inline-block">
                                        {{ $unit->type ? ucwords(str_replace('_', ' ', $unit->type)) : 'Fasum' }}
                                    </span>
                                @elseif($unit->category === 'rumah')
                                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-md bg-purple-50 text-purple-800 border border-purple-200 inline-block">
                                        Rumah
                                    </span>
                                @else
                                    <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-md bg-amber-50 text-amber-800 border border-amber-200 inline-block">
                                        Kavling
                                    </span>
                                @endif

                                @php
                                    $expInfo = $unitExpensesData[$unit->id] ?? [
                                        'has_expenses' => false,
                                        'total_realized' => 0,
                                        'material_cost' => 0,
                                        'salary_cost' => 0,
                                        'contract_cost' => 0,
                                    ];
                                @endphp

                                @if(auth()->user()->canViewUnitExpenses() && $expInfo['has_expenses'])
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-0.5 rounded-md bg-amber-100/90 text-amber-900 border border-amber-300 shadow-2xs" title="Terdapat catatan biaya pengeluaran di detail unit">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                        <span>Ada Biaya</span>
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs font-semibold text-slate-600 flex items-center gap-1.5 truncate" title="{{ $unit->project->name ?? '' }}">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                <span class="truncate">{{ $unit->project->name ?? '-' }}</span>
                            </p>
                        </div>

                        <!-- Status Badge -->
                        <div class="shrink-0">
                            @if($unit->status === 'infrastruktur' || $unit->category === 'infrastruktur')
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-sky-950 text-sky-300 border border-sky-500/40 inline-block shadow-2xs">
                                    Infrastruktur Proyek
                                </span>
                            @else
                                <x-status-badge :status="$unit->status" />
                            @endif
                        </div>
                    </div>

                    <!-- Mandor / Tukang Bertugas Badge -->
                    @php
                        $activeWorkers = $unit->activeAssignments->where('status', 'active');
                        $firstWorker = $activeWorkers->first();
                        $workerCount = $activeWorkers->count();
                    @endphp
                    <div class="flex items-center gap-1.5">
                        @if($firstWorker && $firstWorker->worker)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-blue-50 text-blue-800 border border-blue-200/80 max-w-full truncate" title="{{ $firstWorker->worker->name }} ({{ ucfirst($firstWorker->worker->type) }})">
                                <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="truncate font-medium">{{ $firstWorker->worker->name }}</span>
                                <span class="text-blue-500 text-[9px] shrink-0">({{ ucfirst($firstWorker->worker->type) }})</span>
                                @if($workerCount > 1)
                                    <span class="bg-blue-200 text-blue-900 text-[9px] px-1 py-0.2 rounded font-bold shrink-0">+{{ $workerCount - 1 }}</span>
                                @endif
                            </span>
                        @else
                            <span class="text-[10px] text-slate-400 italic flex items-center gap-1">
                                <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="text-slate-400">Belum ada penugasan pekerja</span>
                            </span>
                        @endif
                    </div>

                    <!-- Details Box (Rapi, Terstruktur, Dimensi & Luas Jelas) -->
                    <div class="bg-slate-50/90 border border-slate-200/70 rounded-2xl p-3 text-xs space-y-2">
                        @if($unit->category === 'infrastruktur' || $unit->status === 'infrastruktur')
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-slate-700">
                                    <span class="text-slate-500 font-medium">Jenis Fasum:</span>
                                    <span class="font-bold text-sky-900 uppercase bg-sky-100/80 px-2 py-0.5 rounded border border-sky-200 text-[11px]">
                                        {{ str_replace('_', ' ', $unit->type ?? 'Fasum Lainnya') }}
                                    </span>
                                </div>
                                @if($unit->work_area)
                                    <div class="flex items-center justify-between text-slate-700 pt-1.5 border-t border-slate-200/60">
                                        <span class="text-slate-500 font-medium">Luas Pengerjaan:</span>
                                        <span class="font-mono font-bold text-slate-900">{{ number_format($unit->work_area, 0, ',', '.') }} m²</span>
                                    </div>
                                @endif
                                @if($unit->specifications)
                                    <div class="pt-1.5 border-t border-slate-200/60 text-[11px]">
                                        <span class="font-bold text-slate-700 block mb-1">Keterangan:</span>
                                        <p class="text-slate-600 leading-relaxed bg-white p-2 rounded-lg border border-slate-200/60 text-[11px]">{{ $unit->specifications }}</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="grid grid-cols-2 gap-2">
                                <div class="bg-white p-2.5 rounded-xl border border-slate-200/70 shadow-2xs">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Dimensi (P × L)</span>
                                    <span class="font-mono font-bold text-slate-800 text-xs block mt-1">
                                        @if($unit->land_length || $unit->land_width)
                                            {{ (float)$unit->land_length }}m × {{ (float)$unit->land_width }}m
                                        @else
                                            <span class="text-slate-400 font-normal italic">-</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="bg-white p-2.5 rounded-xl border border-slate-200/70 shadow-2xs">
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Luas Tanah Total</span>
                                    <span class="font-mono font-bold text-emerald-700 text-xs block mt-1">
                                        {{ number_format($unit->land_area, 0, ',', '.') }} m²
                                    </span>
                                </div>
                            </div>

                            @if($unit->category === 'rumah' && $unit->building_area)
                                <div class="flex items-center justify-between text-xs pt-1.5 border-t border-slate-200/60 text-purple-800 font-medium">
                                    <span class="text-slate-500">Luas Bangunan:</span>
                                    <span class="font-mono font-bold">{{ number_format($unit->building_area, 0, ',', '.') }} m² ({{ $unit->floors_count ?? 1 }} Lt)</span>
                                </div>
                            @endif

                            @if($unit->excess_land_area > 0)
                                <div class="flex items-center justify-between text-xs pt-1.5 border-t border-slate-200/60 text-amber-800 font-medium">
                                    <span class="text-slate-500">Kelebihan Luas:</span>
                                    <span class="font-mono font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200/70">
                                        +{{ number_format($unit->excess_land_area, 0, ',', '.') }} m²
                                    </span>
                                </div>
                            @else
                                <div class="text-[10px] text-slate-400 pt-1.5 border-t border-slate-200/60 flex items-center justify-between">
                                    <span>Standar Proyek:</span>
                                    <span class="font-mono font-medium text-slate-600">{{ number_format($unit->project->standard_land_area ?? 0, 0, ',', '.') }} m²</span>
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
                @if(auth()->user()->canViewSalesPrices())
                    <div class="pt-3 border-t border-slate-100 space-y-1.5 text-xs">
                        @if($unit->category === 'infrastruktur')
                            <div class="flex justify-between items-baseline bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-200/60">
                                <span class="text-slate-500 font-medium">Anggaran Infra:</span>
                                <span class="font-mono font-bold text-slate-800">
                                    {{ $unit->hpp ? 'Rp ' . number_format($unit->hpp, 0, ',', '.') : 'Belum Diset' }}
                                </span>
                            </div>
                        @else
                            <div class="flex justify-between items-baseline text-slate-900 font-extrabold pt-0.5">
                                <span class="text-slate-600">Harga Total:</span>
                                <span class="font-mono text-emerald-700 font-bold text-sm">
                                    Rp {{ number_format($unit->total_price, 0, ',', '.') }}
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
                @endif

                <!-- Footer Actions & Booking Button -->
                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2 whitespace-nowrap flex-nowrap">
                    <div class="flex items-center gap-1 text-[11px] font-semibold text-slate-400 group-hover:text-emerald-700 transition-colors">
                        <span>Lihat detail</span>
                        <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform text-slate-400 group-hover:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>

                    <div class="flex items-center gap-1.5 whitespace-nowrap flex-nowrap">
                        @if(!auth()->user()->isPengawasProject() && $unit->category !== 'infrastruktur' && $unit->status === 'tersedia')
                            <x-button variant="blue" size="xs" wire:click="openBookingModal({{ $unit->id }})">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Booking</span>
                            </x-button>
                        @endif

                        <!-- Dropdown Opsi Unit Card -->
                        <x-action-dropdown title="Opsi Tindakan Unit" size="xs">
                            <div class="py-1">
                                @if((auth()->user()->isAdminOrFounder() || auth()->user()->isFinance()) && $unit->category !== 'infrastruktur' && $unit->status === 'tersedia')
                                    <x-dropdown-item icon="plus" variant="success" href="{{ route('units.show', $unit->id) }}">
                                        Pembelian Cash
                                    </x-dropdown-item>
                                @endif

                                @if(auth()->user()->isMarketing() && $unit->category !== 'infrastruktur' && $unit->status === 'tersedia')
                                    <x-dropdown-item icon="plus" variant="info" href="{{ route('proposals.index', ['create_unit_id' => $unit->id]) }}">
                                        Ajukan Harga
                                    </x-dropdown-item>
                                @endif

                                @if(auth()->user()->isAdminOrFounder() || auth()->user()->isSupervisor())
                                    <x-dropdown-item icon="edit" wire:click="editUnit({{ $unit->id }})">
                                        Edit Unit
                                    </x-dropdown-item>
                                @endif
                            </div>

                            @if(auth()->user()->isSuperAdmin())
                                <div class="py-1">
                                    <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                        title: 'Hapus Unit Kavling/Rumah',
                                        message: 'Yakin ingin menghapus unit {{ $unit->code }} dari sistem beserta seluruh histori terikatnya?',
                                        confirmText: 'Hapus Unit',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteUnit({{ $unit->id }})
                                    })">
                                        Hapus Unit
                                    </x-dropdown-item>
                                </div>
                            @endif
                        </x-action-dropdown>
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

    @if($viewMode === 'table')
        <div class="mt-4">
            {{ $units->links() }}
        </div>
    @endif </div>

    <!-- Modal Form Unit -->
    @if($showModal)
        <x-modal-dialog show="showModal" 
                        closeAction="closeModal" 
                        title="{{ $editingUnitId ? 'Edit Data Unit' : 'Tambah Unit Baru' }}" 
                        subTitle="Kelola spesifikasi unit kavling, bangunan rumah, dan fasilitas umum" 
                        maxWidth="max-w-lg">
            <form wire:submit.prevent="saveUnit" class="space-y-4 text-xs">
                <!-- Proyek & Kode Unit -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5 text-[11px] uppercase tracking-wider truncate">Pilih Proyek <span class="text-rose-500">*</span></label>
                        <select wire:model.live="selected_project_id" class="select-clean w-full h-10 text-xs font-semibold">
                            <option value="">-- Pilih Proyek --</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Std: {{ number_format($p->standard_land_area, 0, ',', '.') }}m²)</option>
                            @endforeach
                        </select>
                        @error('selected_project_id') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5 text-[11px] uppercase tracking-wider truncate">Kode Unit <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="code" placeholder="Contoh: A-01 / B-05" class="input-clean w-full h-10 font-bold font-mono text-xs uppercase">
                        @error('code') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Kategori Unit -->
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5 text-[11px] uppercase tracking-wider">Kategori Unit <span class="text-rose-500">*</span></label>
                    <select wire:model.live="category" class="select-clean w-full h-10 text-xs font-semibold">
                        <option value="kavling">Kavling Tanah (Komersial)</option>
                        <option value="rumah">Bangunan Rumah (Komersial)</option>
                        <option value="infrastruktur">Fasilitas Umum & Infrastruktur Kawasan</option>
                    </select>
                    @error('category') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <!-- Infrastruktur Options -->
                @if($category === 'infrastruktur')
                    <div class="bg-sky-50/80 border border-sky-200/80 rounded-2xl p-3.5 space-y-3">
                        <p class="font-bold text-[11px] uppercase tracking-wider text-sky-900">Detail Infrastruktur & Fasilitas Umum Kawasan:</p>
                        
                        <div>
                            <label class="block font-semibold text-sky-800 mb-1 text-[11px] uppercase tracking-wider">Sub-Jenis Infrastruktur</label>
                            <select wire:model="infra_type" class="select-clean w-full h-10 font-bold bg-white text-xs">
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
                            <label class="block font-semibold text-sky-800 mb-1 text-[11px] uppercase tracking-wider">Luas Pengerjaan (m²) <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.01" min="0" wire:model="land_area" placeholder="Contoh: 150" required class="input-clean w-full h-10 font-extrabold font-mono text-xs bg-white text-sky-950">
                            @error('land_area') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-sky-800 mb-1 text-[11px] uppercase tracking-wider">Keterangan / Deskripsi Pengerjaan</label>
                            <textarea wire:model="specifications" rows="2" placeholder="Detail lokasi, spesifikasi cor parit, lebar jalan, dll." class="input-clean w-full bg-white text-xs"></textarea>
                        </div>
                    </div>
                @elseif($category === 'rumah')
                    <div class="bg-purple-50/70 border border-purple-200/80 rounded-2xl p-3.5 space-y-3">
                        <p class="font-bold text-[11px] uppercase tracking-wider text-purple-900">Spesifikasi Bangunan Rumah:</p>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-purple-800 mb-1 text-[11px] uppercase tracking-wider">Luas Bangunan (m²)</label>
                                <input type="number" step="0.01" wire:model="building_area" placeholder="36" class="input-clean w-full h-10 font-mono bg-white text-xs">
                            </div>
                            <div>
                                <label class="block font-semibold text-purple-800 mb-1 text-[11px] uppercase tracking-wider">Jumlah Lantai</label>
                                <input type="number" min="1" wire:model="floors_count" placeholder="1" class="input-clean w-full h-10 font-mono bg-white text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-purple-800 mb-1 text-[11px] uppercase tracking-wider">Deskripsi Spesifikasi Fisik</label>
                            <textarea wire:model="specifications" rows="2" placeholder="Pondasi batu kali, granit 60x60, atap baja ringan, dll." class="input-clean w-full bg-white text-xs"></textarea>
                        </div>
                    </div>
                @endif

                <!-- Dimensi Tanah: Panjang, Lebar, Luas Tanah (Sejajar & Rapi) -->
                @if($category !== 'infrastruktur')
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5 text-[11px] uppercase tracking-wider truncate" title="Panjang Tanah (meter)">
                                Panjang (m) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0.1" wire:model.live="land_length" placeholder="10" required class="input-clean w-full h-10 font-mono font-semibold text-xs text-center">
                            @error('land_length') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5 text-[11px] uppercase tracking-wider truncate" title="Lebar Tanah (meter)">
                                Lebar (m) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" step="0.01" min="0.1" wire:model.live="land_width" placeholder="10" required class="input-clean w-full h-10 font-mono font-semibold text-xs text-center">
                            @error('land_width') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1.5 text-[11px] uppercase tracking-wider truncate" title="Luas Tanah (meter persegi)">
                                Luas (m²) <span class="text-emerald-600 font-bold lowercase text-[10px]">(auto)</span>
                            </label>
                            <input type="number" step="0.01" wire:model="land_area" readonly tabindex="-1" class="input-clean w-full h-10 font-extrabold font-mono text-xs text-center bg-slate-100/90 text-slate-800 border-slate-300 cursor-not-allowed" title="Luas tanah terhitung otomatis dari Panjang x Lebar">
                            @error('land_area') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif

                @if($selected_project_id && $category !== 'infrastruktur')
                    <div class="bg-emerald-50 border border-emerald-200/80 rounded-2xl p-3.5 space-y-1.5 text-emerald-900">
                        <p class="font-bold text-[11px] uppercase tracking-wider text-emerald-800">Kalkulasi Otomatis Harga Unit:</p>
                        <div class="flex justify-between text-xs">
                            <span>Harga Jual Standar:</span>
                            <span class="font-mono font-bold">Rp {{ number_format($previewBasePrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span>Kelebihan Luas Tanah:</span>
                            <span class="font-mono font-bold">{{ number_format($previewExcessArea, 0, ',', '.') }} m²</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span>Harga Kelebihan Luas Tanah:</span>
                            <span class="font-mono font-bold">Rp {{ number_format($previewExcessCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold pt-1.5 border-t border-emerald-200/80 text-emerald-950">
                            <span>Harga Total (Harga Jual + Kelebihan):</span>
                            <span class="font-mono text-emerald-700 text-sm font-extrabold">Rp {{ number_format($previewRecommendedHpp, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endif

                @if(auth()->user()->canViewHpp())
                    <x-currency-input 
                        label="Harga Total Unit (Rp)" 
                        model="hpp" 
                        :value="$hpp" 
                        placeholder="{{ number_format($previewRecommendedHpp, 0, ',', '.') }}" 
                        helpText="*Harga Total = Harga Jual + Harga Kelebihan Luas Tanah (dapat disesuaikan jika diperlukan)."
                    />
                @endif

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <x-button type="button" variant="secondary" wire:click="closeModal">Batal</x-button>
                    <x-button type="submit" variant="primary" loadingTarget="saveUnit">Simpan Unit</x-button>
                </div>
            </form>
        </x-modal-dialog>
    @endif

    <!-- Modal Form Booking Unit Langsung (Req #2) -->
    @if($showBookingModal)
        <x-modal-dialog show="showBookingModal" 
                        title="Booking Unit {{ $bookingUnitCode }}" 
                        subTitle="Pencatatan booking unit & bukti DP langsung di dalam sistem" 
                        maxWidth="max-w-md">
            <form wire:submit.prevent="saveBooking" class="space-y-4.5 sm:space-y-5 text-xs sm:text-sm">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5 text-xs uppercase tracking-wider">Nama Pembeli <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="buyer_name" required placeholder="Contoh: Bpk. H. Hendra Wijaya" class="input-clean w-full font-bold text-xs sm:text-sm h-10.5">
                    @error('buyer_name') <span class="text-rose-500 text-[10px] mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5 text-xs uppercase tracking-wider">Nomor HP / WhatsApp Pembeli <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="buyer_phone" required placeholder="081234567890" class="input-clean w-full font-mono text-xs sm:text-sm h-10.5">
                    @error('buyer_phone') <span class="text-rose-500 text-[10px] mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5 text-xs uppercase tracking-wider">Tgl Pembayaran / Booking <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="booking_date" required class="input-clean w-full font-mono text-xs sm:text-sm h-10.5">
                    @error('booking_date') <span class="text-rose-500 text-[10px] mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-currency-input 
                        label="Nominal Tanda Jadi / Booking Fee (Rp)"
                        model="booking_amount" 
                        :value="$booking_amount"
                        placeholder="5.000.000"
                        badgeColor="teal"
                        required 
                    />
                    @error('booking_amount') <span class="text-rose-500 text-[10px] mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1.5 text-xs uppercase tracking-wider">Catatan Pembayaran & Bukti DP</label>
                    <textarea wire:model="booking_notes" rows="2" placeholder="Informasi bukti transfer DP, skema pelunasan, dll." class="input-clean w-full text-xs sm:text-sm"></textarea>
                </div>

                <x-receipt-upload 
                    model="receipt_photo" 
                    :photo="$receipt_photo" 
                    label="Foto Struk / Bukti Transfer DP"
                />

                <div class="p-3.5 bg-blue-50 border border-blue-200/80 rounded-2xl text-blue-900 text-[11px] leading-relaxed">
                    <span class="font-bold">Info Verifikasi:</span> Setelah disimpan, unit otomatis menjadi status <strong>Booked</strong> dan data transaksi akan dikirim ke menu Booking untuk diverifikasi & disetujui DP-nya oleh Finance / Founder.
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-4 border-t border-slate-100">
                    <x-button type="button" variant="secondary" wire:click="$set('showBookingModal', false)">Batal</x-button>
                    <x-button type="submit" variant="primary" loadingTarget="saveBooking">Proses Booking Unit</x-button>
                </div>
            </form>
        </x-modal-dialog>
    @endif

</div>
