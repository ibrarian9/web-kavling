<div class="space-y-6">

    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 font-bold text-xs rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-600 font-bold">✕</button>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold text-xs rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 font-bold">✕</button>
        </div>
    @endif

    <!-- Top Navigation & Header -->
    <div class="card-clean p-4 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div class="space-y-1.5">
            <!-- Breadcrumbs -->
            <nav class="flex items-center gap-1.5 text-xs text-slate-500 flex-wrap">
                <a href="{{ route('projects.show', $unit->project_id) }}" class="hover:text-emerald-700 font-semibold inline-flex items-center gap-1.5 transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>{{ $unit->project->name }}</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="font-bold text-slate-700">Detail Unit {{ $unit->code }}</span>
            </nav>

            <!-- Title & Status Badges -->
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-mono tracking-tight">UNIT {{ $unit->code }}</h1>
                
                @if($unit->category === 'infrastruktur' || $unit->status === 'infrastruktur')
                    <span class="text-xs uppercase font-extrabold px-3 py-1 rounded-xl bg-sky-100 text-sky-800 border border-sky-300 shadow-2xs">
                        FASUM: {{ strtoupper($unit->type) }}
                    </span>
                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-sky-950 text-sky-300 border border-sky-500/40 shadow-2xs">
                        Infrastruktur Proyek
                    </span>
                @else
                    <span class="text-xs uppercase font-extrabold px-3 py-1 rounded-xl shadow-2xs {{ $unit->category === 'rumah' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                        {{ ucfirst($unit->category ?? $unit->type) }}
                    </span>
                    
                    @if($unit->status === 'tersedia')
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
                @endif
            </div>
        </div>

        <!-- Header Action Toolbar -->
        <div class="flex items-center gap-2 flex-wrap pt-2 lg:pt-0 border-t lg:border-t-0 border-slate-100">
            <button onclick="history.back()" class="btn-action-back px-3.5 py-2 text-xs rounded-xl shadow-2xs" title="Kembali ke Halaman Sebelumnya">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali</span>
            </button>

            @if(auth()->user()->isFounder())
                <button wire:click="openEditUnitModal" class="btn-action-edit px-3.5 py-2 text-xs rounded-xl shadow-2xs" title="Edit Spesifikasi & Data Unit">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Edit Spesifikasi</span>
                </button>
            @endif

            @if(auth()->user()->isFounder())
                <button type="button" @click="confirmModalAction({
                    title: 'Hapus Unit Kavling/Rumah',
                    message: 'Yakin ingin menghapus unit {{ $unit->code }} dari sistem beserta seluruh histori terikatnya?',
                    confirmText: 'Hapus Unit',
                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                    onConfirm: () => $wire.deleteUnit()
                })" class="btn-action-delete px-3.5 py-2 text-xs rounded-xl shadow-2xs" title="Hapus Unit dari Sistem">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Hapus Unit</span>
                </button>
            @endif

            @if((auth()->user()->isFounder() || auth()->user()->isFinance()) && $unit->category !== 'infrastruktur' && $unit->status === 'tersedia')
                <button wire:click="openDirectSppModal" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold inline-flex items-center gap-1.5 transition shadow-sm active:scale-[0.98]" title="Terbitkan Surat Pesanan SPP & SPJB PDF (Pembelian Cash)">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Pembelian Cash</span>
                </button>
            @endif

            @if(!auth()->user()->isPengawasProject() && $unit->category !== 'infrastruktur' && $unit->status === 'tersedia')
                <button wire:click="openBookingModal" class="px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 transition shadow-2xs active:scale-[0.98]" title="Booking Unit Ini">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Booking Unit Ini</span>
                </button>
            @endif

            @if(auth()->user()->isMarketing() && $unit->category !== 'infrastruktur' && $unit->status === 'tersedia')
                <a href="{{ route('proposals.index', ['create_unit_id' => $unit->id]) }}" class="px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 transition shadow-2xs active:scale-[0.98]">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Ajukan Penawaran Harga</span> &rarr;
                </a>
            @endif
        </div>
    </div>

    <!-- Key Metrics Highlight Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- HPP Pokok (Founder & Finance Only) -->
        @if(auth()->user()->canViewHpp())
            <div class="kpi-card-blue transition-all duration-200 hover:-translate-y-0.5">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">HPP Pokok Unit</span>
                    <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">
                    Rp {{ number_format($unit->hpp, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1">Dasar: Rp {{ number_format($unit->project->base_price, 0, ',', '.') }} + Kelebihan</p>
            </div>
        @endif

        <!-- Harga Jual Final -->
        <div class="kpi-card-emerald transition-all duration-200 hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Harga Jual Disetujui</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">
                {{ $unit->final_selling_price ? 'Rp ' . number_format($unit->final_selling_price, 0, ',', '.') : 'Belum Disetujui' }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Status: {{ ucfirst($unit->status) }}</p>
        </div>

        <!-- Total Cash In (Financial Metric - Hidden from Pengawas) -->
        @if(!auth()->user()->isPengawasProject())
            <div class="kpi-card-emerald transition-all duration-200 hover:-translate-y-0.5">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Kas Masuk Terbayar</span>
                    <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">
                    Rp {{ number_format($totalCashIn, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1">
                    {{ $unit->installment ? 'Skema: ' . $unit->installment->installment_count . 'x Cicilan' : 'Setoran DP / Booking / Cicilan' }}
                </p>
            </div>

            <!-- Total Pengeluaran Unit -->
            <div class="kpi-card-rose transition-all duration-200 hover:-translate-y-0.5">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pengeluaran Unit</span>
                    <div class="p-2.5 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-rose-700 font-mono mt-2">
                    Rp {{ number_format($totalUnitExpenses ?? 0, 0, ',', '.') }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1">Material + Upah Gaji Worker</p>
            </div>
        @endif
    </div>

    <!-- Main Grid Structure: Left Column (Physical & Workers) & Right Column (Sales, Installment, Expenses) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Specs & Physical Details & Worker Management -->
        <div class="space-y-6 lg:col-span-1">
            
            <!-- Physical Specifications Card -->
            <div class="card-clean p-5 space-y-4">
                <h3 class="font-extrabold text-slate-900 text-sm border-b border-slate-100 pb-3 flex items-center gap-2">
                    <div class="p-1.5 rounded-lg bg-amber-50 text-amber-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span>Spesifikasi Fisik & Dimensi</span>
                </h3>

                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500 font-medium">Proyek Properti:</span>
                        <span class="font-bold text-slate-800 text-right">{{ $unit->project->name }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500 font-medium">Dimensi Tanah (P &times; L):</span>
                        <span class="font-mono font-bold text-slate-800">{{ $unit->land_length }}m &times; {{ $unit->land_width }}m</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500 font-medium">Luas Tanah Aktual:</span>
                        <span class="font-mono font-extrabold text-slate-900 text-xs">{{ number_format($unit->land_area, 0, ',', '.') }} m²</span>
                    </div>
                    <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                        <span class="text-slate-500 font-medium">Standar Proyek:</span>
                        <span class="font-mono text-slate-700">{{ number_format($unit->project->standard_land_area, 0, ',', '.') }} m²</span>
                    </div>

                    @if($unit->excess_land_area > 0)
                        <div class="flex items-center justify-between py-2 bg-amber-50/90 px-3 rounded-xl border border-amber-200/80 text-amber-900 font-semibold shadow-2xs">
                            <span class="text-[11px]">Kelebihan Tanah:</span>
                            <span class="font-mono font-extrabold text-xs text-amber-800">+{{ number_format($unit->excess_land_area, 0, ',', '.') }} m² (+Rp {{ number_format($unit->excess_cost, 0, ',', '.') }})</span>
                        </div>
                    @endif

                    @if($unit->category === 'rumah')
                        <div class="pt-2 mt-2 border-t border-purple-100 space-y-2">
                            <p class="font-extrabold text-purple-900 text-[11px] uppercase tracking-wider">Detail Bangunan Rumah:</p>
                            <div class="flex items-center justify-between py-1.5 border-b border-purple-50">
                                <span class="text-slate-500 font-medium">Luas Bangunan:</span>
                                <span class="font-mono font-extrabold text-purple-900 text-xs">{{ number_format($unit->building_area, 0, ',', '.') }} m²</span>
                            </div>
                            <div class="flex items-center justify-between py-1.5 border-b border-purple-50">
                                <span class="text-slate-500 font-medium">Jumlah Lantai:</span>
                                <span class="font-bold text-slate-800">{{ $unit->floors_count ?? 1 }} Lantai</span>
                            </div>
                            @if($unit->specifications)
                                <div class="pt-1.5 text-slate-600 text-[11px] italic bg-purple-50/50 p-2.5 rounded-xl border border-purple-100">
                                    "{{ $unit->specifications }}"
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Assigned Workers (Mandor & Tukang) Card -->
            <div class="card-clean p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 gap-2">
                    <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                        <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span>Mandor & Tukang Bertugas</span>
                    </h3>
                    
                    @if(auth()->user()->isSupervisor() || auth()->user()->isPengawasProject() || auth()->user()->isFounder())
                        <button wire:click="openWorkerModal" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 transition shadow-2xs active:scale-[0.98]">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Tugaskan Pekerja</span>
                        </button>
                    @endif
                </div>

                <div class="space-y-3 text-xs">
                    @forelse($unitAssignments as $assign)
                        @if($assign->worker)
                            <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 transition-all hover:bg-slate-100/50">
                                <div>
                                    <p class="font-extrabold text-slate-800">{{ $assign->worker->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-semibold mt-0.5">{{ $assign->assigned_role }} &bull; {{ ucfirst($assign->worker->type) }}</p>
                                    <p class="text-[10px] text-slate-400">Spesialis: {{ $assign->worker->specialty ?: '-' }}</p>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0 self-start sm:self-center">
                                    @if(auth()->user()->isFounder())
                                        <button wire:click="editWorkerAssignment({{ $assign->id }})" class="btn-action-edit" title="Edit Penugasan">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" @click="confirmModalAction({
                                            title: 'Hapus Penugasan Pekerja',
                                            message: 'Yakin ingin menghapus penugasan pekerja ini?',
                                            confirmText: 'Hapus Penugasan',
                                            btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                            onConfirm: () => $wire.deleteWorkerAssignment({{ $assign->id }})
                                        })" class="btn-action-delete" title="Hapus Penugasan">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    @endif
                                    <span class="status-tersedia text-[10px]">
                                        Active
                                    </span>
                                </div>
                            </div>
                        @elseif($assign->user)
                            <div class="p-3 bg-purple-50/70 rounded-2xl border border-purple-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 transition-all">
                                <div>
                                    <p class="font-extrabold text-purple-900">{{ $assign->user->name }}</p>
                                    <p class="text-[10px] text-purple-700 font-semibold mt-0.5">{{ $assign->assigned_role }} &bull; Pengawas System</p>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0 self-start sm:self-center">
                                    @if(auth()->user()->isFounder())
                                        <button wire:click="editWorkerAssignment({{ $assign->id }})" class="btn-action-edit" title="Edit Penugasan">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" @click="confirmModalAction({
                                            title: 'Hapus Penugasan Pekerja',
                                            message: 'Yakin ingin menghapus penugasan pekerja ini?',
                                            confirmText: 'Hapus Penugasan',
                                            btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                            onConfirm: () => $wire.deleteWorkerAssignment({{ $assign->id }})
                                        })" class="btn-action-delete" title="Hapus Penugasan">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    @endif
                                    <span class="badge-role-pengawas text-[10px]">
                                        Pengawas
                                    </span>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-5 text-slate-400 text-xs italic bg-slate-50/60 rounded-xl border border-dashed border-slate-200">
                            Belum ada penugasan mandor/tukang spesifik pada unit ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Gaji Borongan Worker Unit Card -->
            <div class="card-clean p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 gap-2">
                    <div class="flex items-center gap-2">
                        <div class="p-2 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-sm">Penggajian Borongan Unit</h3>
                            <p class="text-[10px] text-slate-400">Kesepakatan upah & progres pencairan gaji</p>
                        </div>
                    </div>

                    @if(auth()->user()->isSupervisor() || auth()->user()->isPengawasProject() || auth()->user()->isFounder())
                        <button wire:click="openPayrollSetupModal" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold inline-flex items-center gap-1.5 transition shadow-2xs active:scale-[0.98]">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Set Gaji Unit</span>
                        </button>
                    @endif
                </div>

                <div class="space-y-4 text-xs">
                    @forelse($unitPayrolls as $up)
                        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition-all duration-200 hover:border-slate-300">
                            <!-- Worker Header Bar -->
                            <div class="p-3.5 bg-slate-50/90 border-b border-slate-100 flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-xs flex items-center justify-center border border-emerald-200/60 uppercase shrink-0">
                                        {{ strtoupper(substr($up->worker->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-slate-900 text-xs">{{ $up->worker->name }}</h4>
                                        <p class="text-[10px] text-slate-500 font-semibold capitalize">{{ $up->worker->type }} {{ $up->worker->specialty ? '('.$up->worker->specialty.')' : '' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="{{ $up->status === 'lunas' ? 'status-lunas' : 'status-booked' }} text-[10px] uppercase font-bold">
                                        {{ strtoupper($up->status) }}
                                    </span>
                                    @if(auth()->user()->isFounder())
                                        <div class="flex items-center gap-1">
                                            <button wire:click="editPayrollSetup({{ $up->id }})" class="p-1 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit Penetapan Gaji">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button type="button" @click="confirmModalAction({
                                                title: 'Hapus Penetapan Gaji',
                                                message: 'Yakin ingin menghapus penetapan gaji unit ini beserta riwayat pembayarannya?',
                                                confirmText: 'Hapus Penetapan Gaji',
                                                btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                onConfirm: () => $wire.deletePayrollSetup({{ $up->id }})
                                            })" class="p-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Penetapan Gaji">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Financial Stats & Progress -->
                            <div class="p-3.5 space-y-3">
                                <div class="grid grid-cols-3 gap-2 text-center">
                                    <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                                        <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider">Total Kontrak</span>
                                        <span class="font-extrabold text-slate-800 font-mono text-[11px]">Rp {{ number_format($up->agreed_salary, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="bg-emerald-50/60 p-2 rounded-xl border border-emerald-100">
                                        <span class="text-emerald-700 block text-[9px] uppercase font-bold tracking-wider">Terbayar</span>
                                        <span class="font-extrabold text-emerald-800 font-mono text-[11px]">Rp {{ number_format($up->paid_amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="bg-amber-50/60 p-2 rounded-xl border border-amber-100">
                                        <span class="text-amber-800 block text-[9px] uppercase font-bold tracking-wider">Sisa Gaji</span>
                                        <span class="font-extrabold text-amber-800 font-mono text-[11px]">Rp {{ number_format($up->remaining_salary, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-[10px] text-slate-500 font-semibold">
                                        <span>Pencairan Progres Gaji</span>
                                        <span class="font-mono font-bold text-emerald-700">{{ $up->progress_percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden p-0.5 border border-slate-200/50">
                                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-1.5 rounded-full transition-all duration-300" style="width: {{ $up->progress_percentage }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Action Bar -->
                            <div class="px-3.5 py-2.5 bg-slate-50/60 border-t border-slate-100 flex items-center justify-between gap-2">
                                <button wire:click="openViewerModal('pdf', '{{ route('units.payroll.spk-pdf', $up->id) }}', 'Pratinjau Surat Perintah Kerja (SPK) - {{ $up->worker->name }}')" class="px-2.5 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-800 border border-sky-200 rounded-xl text-[11px] font-bold inline-flex items-center gap-1.5 transition shadow-2xs">
                                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span>Cetak SPK PDF</span>
                                </button>

                                @if($up->status !== 'lunas')
                                    <button wire:click="openPayrollPaymentModal({{ $up->id }})" class="btn-action-payment text-[11px] px-3 py-1.5 flex items-center gap-1 font-extrabold shadow-2xs">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        <span>Bayar Gaji</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 text-xs italic bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                            Belum ada penetapan gaji borongan worker untuk unit {{ $unit->code }}.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Proposals, SPP, Financials & Costs -->
        <div class="space-y-6 lg:col-span-2">
            
            <!-- Proposal & Official Document (SPP) Status Card (Hidden from Pengawas Project) -->
            @if(!auth()->user()->isPengawasProject())
                <div class="card-clean p-5 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3 gap-2">
                        <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                            <div class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span>Surat Pesanan Penjualan (SPP) & Proposal Harga</span>
                        </h3>
                    </div>

                    @if($unit->officialDocument)
                        <div class="bg-emerald-50/90 border border-emerald-200 rounded-2xl p-4 space-y-3 text-xs shadow-2xs">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 text-emerald-900 border-b border-emerald-200/70 pb-2.5">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-extrabold text-sm sm:text-base text-emerald-950">{{ $unit->officialDocument->document_number }}</span>
                                    <span class="bg-emerald-700 text-white font-extrabold text-[10px] px-2.5 py-0.5 rounded-lg shadow-2xs">Resmi Terbit</span>
                                </div>
                                <div class="flex items-center gap-2 flex-wrap shrink-0 self-start sm:self-center">
                                    <button wire:click="openViewerModal('pdf', '{{ route('documents.stream', ['id' => $unit->officialDocument->id]) }}', 'PDF Surat Pesanan Penjualan - {{ $unit->officialDocument->document_number }}')" class="btn-primary text-xs px-3 py-1.5 bg-sky-600 hover:bg-sky-700 shadow-xs flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <span>SPP PDF</span>
                                    </button>
                                    <button wire:click="openViewerModal('pdf', '{{ route('documents.spjb-pdf', ['id' => $unit->officialDocument->id]) }}', 'Pratinjau Surat Perjanjian Jual Beli (SPJB) - {{ $unit->code }}')" class="btn-primary text-xs px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 shadow-xs flex items-center gap-1.5 font-extrabold">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Cetak SPJB PDF</span>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-slate-700 pt-1">
                                <div class="bg-white/80 p-2.5 rounded-xl border border-emerald-100">
                                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Nama Pembeli:</span>
                                    <span class="font-extrabold text-slate-900">{{ $unit->officialDocument->buyer_name }}</span>
                                </div>
                                <div class="bg-white/80 p-2.5 rounded-xl border border-emerald-100">
                                    <span class="text-slate-500 block text-[10px] uppercase font-bold">No. KTP / NIK Pembeli:</span>
                                    <span class="font-mono font-bold text-slate-900">{{ $unit->officialDocument->effective_buyer_nik }}</span>
                                </div>
                                <div class="bg-white/80 p-2.5 rounded-xl border border-emerald-100">
                                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Kontak Pembeli:</span>
                                    <span class="font-mono font-bold text-slate-800">{{ $unit->officialDocument->buyer_contact }}</span>
                                </div>
                                <div class="bg-white/80 p-2.5 rounded-xl border border-emerald-100">
                                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Penjual & NIK Penjual:</span>
                                    <span class="font-bold text-slate-900">{{ $unit->officialDocument->effective_seller_name }}</span>
                                    <span class="text-[10px] font-mono text-slate-500 block">NIK: {{ $unit->officialDocument->effective_seller_nik }}</span>
                                </div>
                                <div class="sm:col-span-2 bg-white/80 p-2.5 rounded-xl border border-emerald-100">
                                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Alamat Pembeli:</span>
                                    <span class="font-medium text-slate-800">{{ $unit->officialDocument->buyer_address }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-xs text-slate-500 bg-slate-50/80 p-4 rounded-2xl border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                            <span class="font-medium">Belum ada dokumen SPP resmi terbit untuk unit ini.</span>
                            @if(auth()->user()->isMarketing() || auth()->user()->isFinance() || auth()->user()->isFounder())
                                <a href="{{ route('documents.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors shrink-0">Kelola Dokumen SPP &rarr;</a>
                            @endif
                        </div>
                    @endif

                    <!-- Proposals History -->
                    <div class="pt-2 space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs font-extrabold text-slate-800">Riwayat Proposal Harga Jual:</p>
                            @if(auth()->user()->isMarketing() && $unit->category !== 'infrastruktur' && $unit->status === 'tersedia')
                                <a href="{{ route('proposals.index', ['create_unit_id' => $unit->id]) }}" class="btn-action-detail text-[11px] px-2.5 py-1 inline-flex items-center gap-1 font-bold">
                                    <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Ajukan Proposal Baru</span>
                                </a>
                            @endif
                        </div>
                        <div class="space-y-2">
                            @forelse($unit->proposals as $prop)
                                <div class="p-3 bg-slate-50/80 rounded-2xl border border-slate-200/60 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 transition-all hover:bg-slate-100/60">
                                    <div>
                                        <span class="font-extrabold text-slate-900 font-mono">Pengajuan Rp {{ number_format($prop->proposed_price, 0, ',', '.') }}</span>
                                        <span class="text-slate-500 text-[10px] font-semibold ml-2">by {{ $prop->proposer->name }}</span>
                                        <p class="text-[10px] text-slate-500 mt-0.5 italic">Catatan: "{{ $prop->notes ?: '-' }}"</p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 self-start sm:self-center">
                                        @if($prop->status === 'disetujui')
                                            <span class="status-disetujui">ACC</span>
                                        @elseif($prop->status === 'ditolak')
                                            <span class="status-ditolak">Ditolak</span>
                                        @else
                                            <span class="status-menunggu">Menunggu Approval</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-400 text-xs italic bg-slate-50/60 p-3 rounded-xl border border-dashed border-slate-200 text-center">Belum ada riwayat pengajuan harga.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- Installment & Buyer Payments Card (Financial Data - Hidden from Pengawas Project) -->
            @if(!auth()->user()->isPengawasProject() && $unit->installment)
                <div class="card-clean p-5 space-y-4">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between border-b border-slate-100 pb-3 gap-3">
                        <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                            <div class="p-1.5 rounded-lg bg-blue-50 text-blue-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <span>Skema Cicilan & Pembayaran Pembeli</span>
                            @if($unit->installment->status === 'lunas')
                                <span class="text-[10px] uppercase font-extrabold px-2.5 py-0.5 rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">Lunas</span>
                            @elseif($unit->installment->status === 'konversi_cash')
                                <span class="text-[10px] uppercase font-extrabold px-2.5 py-0.5 rounded-lg bg-purple-100 text-purple-900 border border-purple-300">Lunas Cash</span>
                            @else
                                <span class="text-[10px] uppercase font-extrabold px-2.5 py-0.5 rounded-lg bg-amber-100 text-amber-800 border border-amber-200">{{ ucfirst($unit->installment->status) }}</span>
                            @endif
                        </h3>

                        @if(auth()->user()->isFounder())
                            <div class="flex items-center gap-2 flex-wrap">
                                @if(!in_array($unit->installment->status, ['lunas', 'konversi_cash']))
                                    <button wire:click="openInstallmentPaymentModal" class="btn-action-payment text-xs px-3 py-1.5 flex items-center gap-1.5 shadow-2xs font-extrabold" title="Input Setoran Cicilan Pembeli">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        <span>Input Setoran</span>
                                    </button>
                                @endif
                                <button wire:click="openSetupInstallmentModal" class="btn-action-edit text-xs px-3 py-1.5 flex items-center gap-1.5 shadow-2xs font-bold" title="Edit Skema Cicilan & Piutang Pembeli">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Edit Skema</span>
                                </button>
                                @if(!in_array($unit->installment->status, ['lunas', 'konversi_cash']))
                                    <button wire:click="openConvertToCashModal" class="btn-action-convert text-xs px-3 py-1.5 flex items-center gap-1.5 shadow-2xs font-bold" title="Batalkan skema cicilan & pelunasan Cash">
                                        <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        <span>Ganti Cash</span>
                                    </button>
                                    <button type="button" @click="confirmModalAction({
                                        title: 'Hapus Skema Cicilan Pembeli',
                                        message: 'Yakin ingin menghapus skema cicilan Unit {{ $unit->code }} beserta seluruh riwayat setoran terikatnya?',
                                        confirmText: 'Hapus Skema',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteInstallmentScheme()
                                    })" class="btn-action-delete text-xs px-3 py-1.5 flex items-center gap-1.5 shadow-2xs font-bold" title="Hapus Skema Cicilan Pembeli">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus</span>
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>

                    @php
                        $paidSoFar = (float)$unit->installment->down_payment + (float)$unit->installment->payments->sum('amount_paid');
                        $unpaidBalance = max(0, (float)$unit->installment->total_price - $paidSoFar);
                    @endphp

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-slate-50/90 p-4 rounded-2xl border border-slate-200/80">
                        <div class="bg-white p-2.5 rounded-xl border border-slate-100">
                            <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Total Harga Deal:</span>
                            <span class="font-extrabold text-slate-900 font-mono text-xs sm:text-sm">Rp {{ number_format($unit->installment->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div class="bg-white p-2.5 rounded-xl border border-slate-100">
                            <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Sudah Terbayar:</span>
                            <span class="font-extrabold text-emerald-700 font-mono text-xs sm:text-sm">Rp {{ number_format($paidSoFar, 0, ',', '.') }}</span>
                        </div>
                        <div class="bg-amber-50/80 p-2.5 rounded-xl border border-amber-200/80">
                            <span class="text-amber-800 block text-[10px] uppercase font-bold tracking-wider">Sisa Belum Terbayar:</span>
                            <span class="font-extrabold text-amber-700 font-mono text-xs sm:text-sm">Rp {{ number_format($unpaidBalance, 0, ',', '.') }}</span>
                        </div>
                        <div class="bg-white p-2.5 rounded-xl border border-slate-100">
                            <span class="text-slate-500 block text-[10px] uppercase font-bold tracking-wider">Skema Cicilan:</span>
                            <span class="font-bold text-slate-800 font-mono text-xs">{{ $unit->installment->installment_count }}x @ Rp {{ number_format($unit->installment->installment_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Payments list -->
                    <div class="space-y-2.5 text-xs">
                        <p class="font-extrabold text-slate-800">Setoran Cicilan Masuk:</p>
                        @forelse($unit->installment->payments as $pay)
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-white border border-slate-200/80 rounded-2xl gap-2 transition-all hover:bg-slate-50">
                                <div>
                                    <span class="font-extrabold font-mono text-emerald-700 text-sm">Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}</span>
                                    <span class="text-slate-500 text-[10px] font-semibold ml-2">({{ $pay->payment_method }})</span>
                                    <p class="text-[10px] text-slate-400 mt-0.5 italic">{{ $pay->notes ?: '-' }}</p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0 self-start sm:self-center">
                                    <span class="font-mono text-slate-500 text-[11px] font-semibold">{{ $pay->payment_date ? (is_string($pay->payment_date) ? $pay->payment_date : $pay->payment_date->format('d/m/Y')) : '-' }}</span>
                                    @if(auth()->user()->isFounder())
                                        @if($pay->uuid)
                                            <button wire:click="openViewerModal('pdf', '{{ route('installment.invoice', $pay->uuid) }}', 'Pratinjau Invoice Setoran Unit {{ $unit->code }}')" class="btn-action-pdf text-[11px]" title="Pratinjau Invoice / Kuitansi PDF (QR Verification)">
                                                <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <span>Invoice PDF</span>
                                            </button>
                                        @endif
                                        <button wire:click="editInstallmentPayment({{ $pay->id }})" class="btn-action-edit text-[11px]" title="Edit Setoran">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </button>
                                        <button type="button" @click="confirmModalAction({
                                            title: 'Hapus Setoran Cicilan',
                                            message: 'Yakin ingin menghapus setoran cicilan pembeli ini?',
                                            confirmText: 'Hapus Setoran',
                                            btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                            onConfirm: () => $wire.deleteInstallmentPayment({{ $pay->id }})
                                        })" class="btn-action-delete text-[11px]" title="Hapus Setoran">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-400 text-xs italic bg-slate-50/60 p-3 rounded-xl border border-dashed border-slate-200 text-center">Belum ada setoran cicilan pembeli.</p>
                        @endforelse
                    </div>
                </div>
            @elseif(!auth()->user()->isPengawasProject() && auth()->user()->isFounder() && in_array($unit->status, ['booked', 'disetujui', 'terjual', 'converted']))
                <div class="card-clean p-5 flex flex-col sm:flex-row sm:items-center justify-between bg-blue-50/50 border border-blue-100 gap-3">
                    <div>
                        <h4 class="font-extrabold text-slate-900 text-xs sm:text-sm">Skema Cicilan Pembeli Belum Dikonfigurasi</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Unit ini sudah terpesan/terjual. Klik tombol untuk mengonfigurasi skema harga & tenor cicilan.</p>
                    </div>
                    <button wire:click="openSetupInstallmentModal" class="px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-800 border border-blue-200 rounded-xl text-xs font-extrabold inline-flex items-center gap-1.5 transition shadow-2xs active:scale-[0.98] shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Buat Skema Cicilan Pembeli</span>
                    </button>
                </div>
            @endif

            <!-- Unit Expenses & Material Purchases Combined Table Card -->
            <div class="card-clean p-5 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-3">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                            <div class="p-1.5 rounded-lg bg-rose-50 text-rose-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span>Rincian Biaya Pengeluaran & Belanja Unit</span>
                        </h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Rekapitulasi gabungan belanja material, gaji worker terbayar, & biaya unit</p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap shrink-0">
                        @if(count($combinedExpenses) > 0)
                            <button wire:click="openViewerModal('pdf', '{{ route('units.expenses-pdf', $unit->id) }}', 'Pratinjau Laporan Rekapitulasi Biaya Unit {{ $unit->code }}')" class="btn-header-pdf text-xs font-bold px-3 py-1.5">
                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Lihat PDF Rekap</span>
                            </button>
                        @else
                            <button disabled class="btn-header-pdf-disabled text-xs px-3 py-1.5" title="Belum ada data pengeluaran/belanja unit untuk digenerate PDF">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span>PDF Rekap (Kosong)</span>
                            </button>
                        @endif

                        @if(auth()->user()->isFounder() || auth()->user()->isPengawasProject() || auth()->user()->isSupervisor())
                            <button wire:click="openMaterialModal" class="px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-xl text-xs font-extrabold inline-flex items-center gap-1.5 transition shadow-2xs active:scale-[0.98]">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Catat Belanja Material</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Responsive Scroll Table Container -->
                <div class="overflow-x-auto rounded-2xl border border-slate-200/80">
                    <table class="w-full text-left text-xs text-slate-600 min-w-[640px]">
                        <thead class="bg-slate-100/80 text-slate-500 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-200/80">
                            <tr>
                                <th class="px-3.5 py-3">Tanggal</th>
                                <th class="px-3.5 py-3">Jenis</th>
                                <th class="px-3.5 py-3">Uraian Pengeluaran</th>
                                <th class="px-3.5 py-3 text-right">Nominal</th>
                                <th class="px-3.5 py-3 text-center">Resi</th>
                                <th class="px-3.5 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($combinedExpenses as $exp)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-3.5 py-3 font-mono font-semibold text-slate-600 whitespace-nowrap">
                                        {{ $exp->date ? $exp->date->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-3.5 py-3 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold border shadow-2xs {{ $exp->badge_class }}">
                                            {{ $exp->category_badge }}
                                        </span>
                                    </td>
                                    <td class="px-3.5 py-3 font-semibold text-slate-800">
                                        {{ $exp->description }}
                                    </td>
                                    <td class="px-3.5 py-3 font-mono font-extrabold text-slate-900 text-right whitespace-nowrap">
                                        Rp {{ number_format($exp->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3.5 py-3 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if($exp->receipt_photo_path)
                                                <button wire:click="openViewerModal('image', '{{ asset('storage/' . $exp->receipt_photo_path) }}', 'Pratinjau Foto Struk Nota Belanja')" title="Pratinjau Foto Struk" class="btn-action-pdf text-[11px] px-2 py-1">
                                                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    <span>Struk</span>
                                                </button>
                                            @endif

                                            @if($exp->pdf_url)
                                                <button wire:click="openViewerModal('pdf', '{{ $exp->pdf_url }}', 'Pratinjau Resi Gaji PDF')" title="Pratinjau PDF Resi" class="btn-action-pdf text-[11px] px-2 py-1">
                                                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    <span>PDF</span>
                                                </button>
                                            @endif

                                            @if($exp->qr_url)
                                                <button wire:click="openViewerModal('qr', '{{ $exp->qr_url }}', 'Verifikasi Resi Gaji Publik (QR Code)')" title="Pratinjau QR Code Verifikasi" class="btn-action-qr text-[11px] px-2 py-1">
                                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                                    <span>QR</span>
                                                </button>
                                            @endif

                                            @if(!$exp->receipt_photo_path && !$exp->pdf_url && !$exp->qr_url)
                                                <span class="text-slate-400 text-[10px] italic">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3.5 py-3 text-center whitespace-nowrap">
                                        @if(isset($exp->source_type) && $exp->source_type === 'material')
                                            @if(auth()->user()->isFounder())
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <button wire:click="editMaterialPurchase({{ $exp->id }})" class="btn-action-edit text-[11px]" title="Edit Belanja Material">
                                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        <span>Edit</span>
                                                    </button>
                                                    <button type="button" @click="confirmModalAction({
                                                        title: 'Hapus Belanja Material',
                                                        message: 'Yakin ingin menghapus data belanja material ini?',
                                                        confirmText: 'Hapus Material',
                                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                        onConfirm: () => $wire.deleteMaterialPurchase({{ $exp->id }})
                                                    })" class="btn-action-delete text-[11px]" title="Hapus Belanja Material">
                                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-slate-400 text-[10px]">-</span>
                                            @endif
                                        @elseif(isset($exp->source_type) && $exp->source_type === 'salary_payment')
                                            @if(auth()->user()->isFounder())
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <button wire:click="editPayrollPayment({{ $exp->id }})" class="btn-action-edit text-[11px]" title="Edit Pembayaran Gaji Worker">
                                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        <span>Edit</span>
                                                    </button>
                                                    <button type="button" @click="confirmModalAction({
                                                        title: 'Hapus Pembayaran Gaji',
                                                        message: 'Yakin ingin menghapus pencatatan pembayaran gaji ini?',
                                                        confirmText: 'Hapus Gaji',
                                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                        onConfirm: () => $wire.deletePayrollPayment({{ $exp->id }})
                                                    })" class="btn-action-delete text-[11px]" title="Hapus Pembayaran Gaji Worker">
                                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-slate-400 text-[10px]">-</span>
                                            @endif
                                        @elseif(isset($exp->source_type) && $exp->source_type === 'payroll_setup')
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button wire:click="openViewerModal('pdf', '{{ route('units.payroll.spk-pdf', $exp->id) }}', 'Pratinjau Surat Perintah Kerja (SPK)')" class="btn-action-pdf text-[11px]" title="Pratinjau SPK PDF">
                                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    <span>SPK PDF</span>
                                                </button>
                                                @if(auth()->user()->isFounder())
                                                    <button wire:click="editPayrollSetup({{ $exp->id }})" class="btn-action-edit text-[11px]" title="Edit Kontrak Gaji Worker">
                                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        <span>Edit</span>
                                                    </button>
                                                    <button type="button" @click="confirmModalAction({
                                                        title: 'Hapus Kontrak Gaji',
                                                        message: 'Yakin ingin menghapus penetapan gaji unit ini beserta riwayat pembayarannya?',
                                                        confirmText: 'Hapus Kontrak Gaji',
                                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                        onConfirm: () => $wire.deletePayrollSetup({{ $exp->id }})
                                                    })" class="btn-action-delete text-[11px]" title="Hapus Kontrak Gaji Worker">
                                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-400 text-[10px]">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-400 italic">Belum ada rincian belanja material atau pengeluaran tercatat untuk unit ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Include Floating Modals -->
    @include('livewire.units.partials.modal-worker-assignment')
    @include('livewire.units.partials.modal-booking')
    @include('livewire.units.partials.modal-payroll-setup')
    @include('livewire.units.partials.modal-payroll-payment')
    @include('livewire.units.partials.modal-material-purchase')
    @include('livewire.units.partials.modal-installment-payment')
    @include('livewire.units.partials.modal-setup-installment')
    @include('livewire.units.partials.modal-viewer')
    @include('livewire.units.partials.modal-convert-to-cash')
    @include('livewire.units.partials.modal-edit-unit')
    @include('livewire.units.partials.modal-direct-spp')
</div>
