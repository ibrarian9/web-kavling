<div class="space-y-6">

    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 font-bold text-xs rounded-2xl flex items-center justify-between shadow-xs">
            <span>⚠️ {{ session('error') }}</span>
        </div>
    @endif
    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold text-xs rounded-2xl flex items-center justify-between shadow-xs">
            <span>✓ {{ session('success') }}</span>
        </div>
    @endif

    <!-- Top Navigation & Header -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="javascript:history.back()" class="hover:text-emerald-700 font-medium inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali</span>
                </a>
                <span>/</span>
                <span class="font-semibold text-slate-700">{{ $unit->project->name }}</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-extrabold text-slate-900 font-mono tracking-tight">{{ $unit->code }}</h1>
                @if($unit->category === 'infrastruktur' || $unit->status === 'infrastruktur')
                    <span class="text-xs uppercase font-extrabold px-2.5 py-1 rounded-md bg-sky-100 text-sky-800 border border-sky-300">
                        FASUM: {{ strtoupper($unit->type) }}
                    </span>
                    <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-sky-950 text-sky-300 border border-sky-500/40">
                        Infrastruktur Proyek
                    </span>
                @else
                    <span class="text-xs uppercase font-bold px-2.5 py-1 rounded-md {{ $unit->category === 'rumah' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
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

        <div class="flex flex-wrap items-center gap-2">
            <button onclick="history.back()" class="btn-secondary text-xs flex items-center gap-1.5 shadow-xs" title="Kembali ke Halaman Sebelumnya">
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
                <button wire:click="deleteUnit" wire:confirm="Yakin ingin menghapus unit {{ $unit->code }} dari sistem beserta seluruh histori terikatnya?" class="btn-action-delete px-3.5 py-2 text-xs rounded-xl shadow-2xs" title="Hapus Unit dari Sistem">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Hapus Unit</span>
                </button>
            @endif

            @if(!auth()->user()->isPengawasProject() && $unit->category !== 'infrastruktur' && in_array($unit->status, ['tersedia', 'disetujui']))
                <button wire:click="openBookingModal" class="btn-primary text-xs flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>+ Booking Unit Ini</span>
                </button>
            @endif

            @if(auth()->user()->isMarketing() && $unit->category !== 'infrastruktur' && $unit->status === 'tersedia')
                <a href="{{ route('proposals.index', ['create_unit_id' => $unit->id]) }}" class="btn-primary text-xs px-3.5 py-2 shadow-sm">
                    <span>+ Ajukan Penawaran Harga</span> &rarr;
                </a>
            @endif
        </div>
    </div>

    <!-- Key Metrics Highlight Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- HPP Pokok (Founder & Finance Only) -->
        @if(auth()->user()->canViewHpp())
            <div class="kpi-card-blue">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">HPP Pokok Unit</span>
                    <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600 border border-blue-100">
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
        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Harga Jual Disetujui</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
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
            <div class="kpi-card-emerald">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Kas Masuk Terbayar</span>
                    <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
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
            <div class="kpi-card-rose">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pengeluaran Unit</span>
                    <div class="p-2.5 rounded-xl bg-rose-50 text-rose-600 border border-rose-100">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Specs & Physical Details -->
        <div class="space-y-6 lg:col-span-1">
            <!-- Physical Specifications -->
            <div class="card-clean p-5 space-y-4">
                <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Spesifikasi Fisik & Dimensi
                </h3>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Proyek Properti:</span>
                        <span class="font-bold text-slate-800">{{ $unit->project->name }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Dimensi Tanah (P x L):</span>
                        <span class="font-mono font-medium text-slate-800">{{ $unit->land_length }}m &times; {{ $unit->land_width }}m</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Luas Tanah Aktual:</span>
                        <span class="font-mono font-bold text-slate-900">{{ number_format($unit->land_area, 0, ',', '.') }} m²</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-50">
                        <span class="text-slate-500">Standar Proyek:</span>
                        <span class="font-mono text-slate-700">{{ number_format($unit->project->standard_land_area, 0, ',', '.') }} m²</span>
                    </div>

                    @if($unit->excess_land_area > 0)
                        <div class="flex justify-between py-1.5 bg-amber-50/80 px-2.5 rounded-lg border border-amber-200/60 text-amber-900 font-medium">
                            <span>Kelebihan Tanah:</span>
                            <span class="font-mono font-bold">+{{ number_format($unit->excess_land_area, 0, ',', '.') }} m² (+Rp {{ number_format($unit->excess_cost, 0, ',', '.') }})</span>
                        </div>
                    @endif

                    @if($unit->category === 'rumah')
                        <div class="pt-2 mt-2 border-t border-purple-100 space-y-2">
                            <p class="font-bold text-purple-900 text-[11px] uppercase tracking-wider">Detail Bangunan Rumah:</p>
                            <div class="flex justify-between py-1 border-b border-purple-50">
                                <span class="text-slate-500">Luas Bangunan:</span>
                                <span class="font-mono font-bold text-purple-900">{{ number_format($unit->building_area, 0, ',', '.') }} m²</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-purple-50">
                                <span class="text-slate-500">Jumlah Lantai:</span>
                                <span class="font-bold text-slate-800">{{ $unit->floors_count ?? 1 }} Lantai</span>
                            </div>
                            @if($unit->specifications)
                                <div class="pt-1 text-slate-600 text-[11px] italic bg-purple-50/40 p-2 rounded-lg border border-purple-100">
                                    "{{ $unit->specifications }}"
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Assigned Workers (Mandor & Tukang) + Direct Assignment Button (Req #3) -->
            <div class="card-clean p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Mandor & Tukang Bertugas
                    </h3>
                    
                    @if(auth()->user()->isSupervisor() || auth()->user()->isPengawasProject() || auth()->user()->isFounder())
                        <button wire:click="openWorkerModal" class="btn-primary text-[11px] px-2.5 py-1 flex items-center gap-1">
                            <span>+ Tugaskan</span>
                        </button>
                    @endif
                </div>

                <div class="space-y-3 text-xs">
                    @forelse($unitAssignments as $assign)
                        @if($assign->worker)
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $assign->worker->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium">{{ $assign->assigned_role }} &bull; {{ ucfirst($assign->worker->type) }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">Spesialis: {{ $assign->worker->specialty }}</p>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    @if(auth()->user()->isFounder())
                                        <button wire:click="editWorkerAssignment({{ $assign->id }})" class="btn-action-edit" title="Edit Penugasan">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </button>
                                        <button wire:click="deleteWorkerAssignment({{ $assign->id }})" wire:confirm="Yakin ingin menghapus penugasan pekerja ini?" class="btn-action-delete" title="Hapus Penugasan">
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
                            <div class="p-3 bg-purple-50/70 rounded-xl border border-purple-100 flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-purple-900">{{ $assign->user->name }}</p>
                                    <p class="text-[10px] text-purple-700 font-medium">{{ $assign->assigned_role }} &bull; Pengawas System</p>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    @if(auth()->user()->isFounder())
                                        <button wire:click="editWorkerAssignment({{ $assign->id }})" class="btn-action-edit" title="Edit Penugasan">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </button>
                                        <button wire:click="deleteWorkerAssignment({{ $assign->id }})" wire:confirm="Yakin ingin menghapus penugasan pekerja ini?" class="btn-action-delete" title="Hapus Penugasan">
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
                        <div class="text-center py-4 text-slate-400 text-xs">
                            Belum ada penugasan mandor/tukang spesifik pada unit ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Gaji Borongan Worker Unit -->
            <div class="card-clean p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Penggajian Borongan Unit
                    </h3>

                    @if(auth()->user()->isSupervisor() || auth()->user()->isPengawasProject() || auth()->user()->isFounder())
                        <button wire:click="openPayrollSetupModal" class="btn-primary text-[11px] px-2.5 py-1 flex items-center gap-1">
                            <span>+ Set Gaji Unit</span>
                        </button>
                    @endif
                </div>

                <div class="space-y-3 text-xs">
                    @forelse($unitPayrolls as $up)
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 space-y-2">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-slate-900">{{ $up->worker->name }}</p>
                                    <p class="text-[10px] text-slate-400 capitalize">{{ $up->worker->type }} {{ $up->worker->specialty ? '('.$up->worker->specialty.')' : '' }}</p>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    @if(auth()->user()->isFounder())
                                        <button wire:click="editPayrollSetup({{ $up->id }})" class="btn-action-edit" title="Edit Penetapan Gaji">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </button>
                                        <button wire:click="deletePayrollSetup({{ $up->id }})" wire:confirm="Yakin ingin menghapus penetapan gaji unit ini beserta riwayat pembayarannya?" class="btn-action-delete" title="Hapus Penetapan Gaji">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    @endif
                                    <span class="{{ $up->status === 'lunas' ? 'status-lunas' : 'status-booked' }} text-[10px]">
                                        {{ strtoupper($up->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-1 text-[11px]">
                                <div>
                                    <span class="text-slate-400 block text-[10px]">Kontrak Unit:</span>
                                    <span class="font-bold text-slate-800 font-mono">Rp {{ number_format($up->agreed_salary, 0, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px]">Sudah Dibayar:</span>
                                    <span class="font-bold text-emerald-600 font-mono">Rp {{ number_format($up->paid_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $up->progress_percentage }}%"></div>
                            </div>



                            <div class="flex items-center justify-between pt-1 border-t border-slate-200/60 text-[10px]">
                                <span class="text-slate-400">Sisa: <strong class="font-mono text-amber-700">Rp {{ number_format($up->remaining_salary, 0, ',', '.') }}</strong></span>
                                @if($up->status !== 'lunas')
                                    <button wire:click="openPayrollPaymentModal({{ $up->id }})" class="btn-primary text-[10px] px-2 py-0.5 bg-emerald-600 hover:bg-emerald-700">
                                        + Bayar Gaji
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-slate-400 text-xs">
                            Belum ada penetapan gaji borongan worker untuk unit {{ $unit->code }}.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Middle & Right Columns: Proposals, SPP, Financials & Costs -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Proposal & Official Document (SPP) Status (Hidden from Pengawas Project) -->
            @if(!auth()->user()->isPengawasProject())
                <div class="card-clean p-5 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                        <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Surat Pesanan Penjualan (SPP) & Proposal Harga</span>
                        </h3>
                    </div>

                    @if($unit->officialDocument)
                        <div class="bg-emerald-50/80 border border-emerald-200 rounded-xl p-4 space-y-2 text-xs">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-emerald-900">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-sm">{{ $unit->officialDocument->document_number }}</span>
                                    <span class="bg-emerald-700 text-white font-bold text-[10px] px-2 py-0.5 rounded">Resmi Terbit</span>
                                </div>
                                <button wire:click="openViewerModal('pdf', '{{ route('documents.stream', ['id' => $unit->officialDocument->id]) }}', 'PDF Surat Pesanan Penjualan - {{ $unit->officialDocument->document_number }}')" class="btn-primary text-xs px-2.5 py-1 bg-sky-600 hover:bg-sky-700 shadow-xs flex items-center gap-1 shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span>Lihat / Cetak SPP PDF</span>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-slate-700 pt-2 border-t border-emerald-200/60">
                                <div>
                                    <span class="text-slate-500 block text-[10px]">Nama Pembeli:</span>
                                    <span class="font-bold text-slate-900">{{ $unit->officialDocument->buyer_name }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-[10px]">Kontak:</span>
                                    <span class="font-mono font-semibold">{{ $unit->officialDocument->buyer_contact }}</span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="text-slate-500 block text-[10px]">Alamat Pembeli:</span>
                                    <span>{{ $unit->officialDocument->buyer_address }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-xs text-slate-500 bg-slate-50 p-3.5 rounded-xl border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <span>Belum ada dokumen SPP resmi terbit untuk unit ini.</span>
                            @if(auth()->user()->isMarketing() || auth()->user()->isFinance() || auth()->user()->isFounder())
                                <a href="{{ route('documents.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">Kelola Dokumen SPP &rarr;</a>
                            @endif
                        </div>
                    @endif

                    <!-- Proposals History -->
                    <div class="pt-2">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-bold text-slate-700">Riwayat Proposal Harga Jual:</p>
                            @if(auth()->user()->isMarketing() && $unit->category !== 'infrastruktur' && $unit->status === 'tersedia')
                                <a href="{{ route('proposals.index', ['create_unit_id' => $unit->id]) }}" class="text-[11px] font-semibold text-blue-600 hover:underline">
                                    + Ajukan Proposal Baru
                                </a>
                            @endif
                        </div>
                        <div class="space-y-2">
                            @forelse($unit->proposals as $prop)
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/60 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div>
                                        <span class="font-bold text-slate-900">Pengajuan Rp {{ number_format($prop->proposed_price, 0, ',', '.') }}</span>
                                        <span class="text-slate-500 text-[10px] ml-2">by {{ $prop->proposer->name }}</span>
                                        <p class="text-[10px] text-slate-500 mt-0.5">Catatan: "{{ $prop->notes }}"</p>
                                    </div>
                                    <div class="flex items-center gap-2">
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
                                <p class="text-slate-400 text-xs italic">Belum ada riwayat pengajuan harga.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            <!-- Installment & Buyer Payments (Financial Data - Hidden from Pengawas Project) -->
            @if(!auth()->user()->isPengawasProject() && $unit->installment)
                <div class="card-clean p-5 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-2.5 gap-2">
                        <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Skema Cicilan & Pembayaran Pembeli</span>
                            @if($unit->installment->status === 'lunas')
                                <span class="text-[11px] uppercase font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">Lunas</span>
                            @elseif($unit->installment->status === 'konversi_cash')
                                <span class="text-[11px] uppercase font-extrabold px-2 py-0.5 rounded bg-purple-100 text-purple-900 border border-purple-300">Lunas Cash</span>
                            @else
                                <span class="text-[11px] uppercase font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-800">{{ ucfirst($unit->installment->status) }}</span>
                            @endif
                        </h3>

                        @if(auth()->user()->isFounder())
                            <div class="flex items-center gap-2">
                                @if(!in_array($unit->installment->status, ['lunas', 'konversi_cash']))
                                    <button wire:click="openInstallmentPaymentModal" class="btn-primary text-xs px-3 py-1.5 flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        <span>+ Input Setoran</span>
                                    </button>
                                @endif
                                <button wire:click="openSetupInstallmentModal" class="btn-secondary text-xs px-3 py-1.5 flex items-center gap-1.5 text-amber-800 bg-amber-50 hover:bg-amber-100 border-amber-200 font-bold shadow-xs transition" title="Edit Skema Cicilan & Piutang Pembeli">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Edit Skema</span>
                                </button>
                                @if(!in_array($unit->installment->status, ['lunas', 'konversi_cash']))
                                    <button wire:click="openConvertToCashModal" class="btn-secondary text-xs px-3 py-1.5 flex items-center gap-1.5 text-purple-700 border-purple-200 hover:bg-purple-50 font-bold shadow-sm transition" title="Batalkan skema cicilan & pelunasan Cash">
                                        <span>Batalkan & Ganti Cash</span>
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>

                    @php
                        $paidSoFar = (float)$unit->installment->down_payment + (float)$unit->installment->payments->sum('amount_paid');
                        $unpaidBalance = max(0, (float)$unit->installment->total_price - $paidSoFar);
                    @endphp

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs bg-slate-50 p-3.5 rounded-xl border border-slate-200/80">
                        <div>
                            <span class="text-slate-500 block text-[10px] uppercase font-semibold tracking-wider">Total Harga Deal:</span>
                            <span class="font-bold text-slate-900 font-mono text-xs sm:text-sm">Rp {{ number_format($unit->installment->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[10px] uppercase font-semibold tracking-wider">Sudah Terbayar:</span>
                            <span class="font-bold text-emerald-700 font-mono text-xs sm:text-sm">Rp {{ number_format($paidSoFar, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[10px] uppercase font-semibold tracking-wider">Sisa Belum Terbayar:</span>
                            <span class="font-extrabold text-amber-700 font-mono text-xs sm:text-sm bg-amber-100/80 px-2 py-0.5 rounded border border-amber-200 inline-block">Rp {{ number_format($unpaidBalance, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500 block text-[10px] uppercase font-semibold tracking-wider">Skema Cicilan:</span>
                            <span class="font-bold text-slate-800 font-mono text-xs">{{ $unit->installment->installment_count }}x @ Rp {{ number_format($unit->installment->installment_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Payments list -->
                    <div class="space-y-2 text-xs">
                        <p class="font-bold text-slate-700">Setoran Cicilan Masuk:</p>
                        @forelse($unit->installment->payments as $pay)
                            <div class="flex items-center justify-between p-2.5 bg-white border border-slate-200/70 rounded-lg">
                                <div>
                                    <span class="font-bold font-mono text-emerald-700">Rp {{ number_format($pay->amount_paid, 0, ',', '.') }}</span>
                                    <span class="text-slate-500 text-[10px] ml-2">({{ $pay->payment_method }})</span>
                                    <p class="text-[10px] text-slate-400">{{ $pay->notes }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-slate-500 text-[11px]">{{ $pay->payment_date ? (is_string($pay->payment_date) ? $pay->payment_date : $pay->payment_date->format('d/m/Y')) : '-' }}</span>
                                    @if(auth()->user()->isFounder())
                                        @if($pay->uuid)
                                            <button wire:click="openViewerModal('pdf', '{{ route('installment.invoice', $pay->uuid) }}', 'Pratinjau Invoice Setoran Unit {{ $unit->code }}')" class="btn-action-pdf" title="Pratinjau Invoice / Kuitansi PDF (QR Verification)">
                                                <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <span>Invoice PDF</span>
                                            </button>
                                        @endif
                                        <button wire:click="editInstallmentPayment({{ $pay->id }})" class="btn-action-edit" title="Edit Setoran">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </button>
                                        <button wire:click="deleteInstallmentPayment({{ $pay->id }})" wire:confirm="Yakin ingin menghapus setoran cicilan pembeli ini?" class="btn-action-delete" title="Hapus Setoran">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-400 text-xs italic">Belum ada setoran cicilan pembeli.</p>
                        @endforelse
                    </div>
                </div>
            @elseif(!auth()->user()->isPengawasProject() && auth()->user()->isFounder() && in_array($unit->status, ['booked', 'disetujui', 'terjual', 'converted']))
                <div class="card-clean p-5 flex items-center justify-between bg-blue-50/50 border border-blue-100">
                    <div>
                        <h4 class="font-bold text-slate-900 text-xs">Skema Cicilan Pembeli Belum Dikonfigurasi</h4>
                        <p class="text-[11px] text-slate-500">Unit ini sudah terpesan/terjual. Klik tombol untuk mengonfigurasi skema harga & tenor cicilan.</p>
                    </div>
                    <button wire:click="openSetupInstallmentModal" class="btn-primary text-xs px-3 py-2 bg-blue-600 hover:bg-blue-700 shadow-xs flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Buat Skema Cicilan Pembeli</span>
                    </button>
                </div>
            @endif

            <!-- Unit Costs & Direct Cost Recording Button (Req #4) -->
            <!-- Combined Expenses Table: Material Purchases + Salary Payments + Unit Costs -->
            <div class="card-clean p-5 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 gap-2">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Rincian Biaya Pengeluaran & Belanja Unit
                        </h3>
                        <p class="text-[11px] text-slate-500">Rekapitulasi gabungan belanja material, gaji worker terbayar, & biaya unit</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        @if(count($combinedExpenses) > 0)
                            <button wire:click="openViewerModal('pdf', '{{ route('units.expenses-pdf', $unit->id) }}', 'Pratinjau Laporan Rekapitulasi Biaya Unit {{ $unit->code }}')" class="btn-header-pdf">
                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Lihat PDF Rekap</span>
                            </button>
                        @else
                            <button disabled class="btn-header-pdf-disabled" title="Belum ada data pengeluaran/belanja unit untuk digenerate PDF">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span>PDF Rekap (Belum Ada Data)</span>
                            </button>
                        @endif

                        @if(auth()->user()->isFounder() || auth()->user()->isPengawasProject() || auth()->user()->isSupervisor())
                            <button wire:click="openMaterialModal" class="btn-primary text-xs px-3 py-1.5 flex items-center gap-1.5 bg-amber-600 hover:bg-amber-700 shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>+ Catat Belanja Material</span>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-3 py-2.5">Tanggal</th>
                                <th class="px-3 py-2.5">Jenis</th>
                                <th class="px-3 py-2.5">Uraian Pengeluaran</th>
                                <th class="px-3 py-2.5 text-right">Nominal</th>
                                <th class="px-3 py-2.5 text-center">Resi</th>
                                <th class="px-3 py-2.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($combinedExpenses as $exp)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-3 py-3 font-mono font-medium text-slate-600 whitespace-nowrap">
                                        {{ $exp->date ? $exp->date->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border {{ $exp->badge_class }}">
                                            {{ $exp->category_badge }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 font-medium text-slate-800">
                                        {{ $exp->description }}
                                    </td>
                                    <td class="px-3 py-3 font-mono font-bold text-slate-900 text-right whitespace-nowrap">
                                        Rp {{ number_format($exp->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-3 py-3 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if($exp->receipt_photo_path)
                                                <button wire:click="openViewerModal('image', '{{ asset('storage/' . $exp->receipt_photo_path) }}', 'Pratinjau Foto Struk Nota Belanja')" title="Pratinjau Foto Struk" class="btn-action-pdf">
                                                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    <span>Struk</span>
                                                </button>
                                            @endif

                                            @if($exp->pdf_url)
                                                <button wire:click="openViewerModal('pdf', '{{ $exp->pdf_url }}', 'Pratinjau Resi Gaji PDF')" title="Pratinjau PDF Resi" class="btn-action-pdf">
                                                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    <span>PDF</span>
                                                </button>
                                            @endif

                                            @if($exp->qr_url)
                                                <button wire:click="openViewerModal('qr', '{{ $exp->qr_url }}', 'Verifikasi Resi Gaji Publik (QR Code)')" title="Pratinjau QR Code Verifikasi" class="btn-action-qr">
                                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                                    <span>QR</span>
                                                </button>
                                            @endif

                                            @if(!$exp->pdf_url && !$exp->qr_url)
                                                <span class="text-slate-400 text-[10px] italic">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center whitespace-nowrap">
                                        @if(isset($exp->source_type) && $exp->source_type === 'material')
                                            @if(auth()->user()->isFounder())
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <button wire:click="editMaterialPurchase({{ $exp->id }})" class="btn-action-edit" title="Edit Belanja Material">
                                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        <span>Edit</span>
                                                    </button>
                                                    <button wire:click="deleteMaterialPurchase({{ $exp->id }})" wire:confirm="Yakin ingin menghapus data belanja material ini?" class="btn-action-delete" title="Hapus Belanja Material">
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
                                                    <button wire:click="editPayrollPayment({{ $exp->id }})" class="btn-action-edit" title="Edit Pembayaran Gaji Worker">
                                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        <span>Edit</span>
                                                    </button>
                                                    <button wire:click="deletePayrollPayment({{ $exp->id }})" wire:confirm="Yakin ingin menghapus pencatatan pembayaran gaji ini?" class="btn-action-delete" title="Hapus Pembayaran Gaji Worker">
                                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-slate-400 text-[10px]">-</span>
                                            @endif
                                        @elseif(isset($exp->source_type) && $exp->source_type === 'payroll_setup')
                                            @if(auth()->user()->isFounder())
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <button wire:click="editPayrollSetup({{ $exp->id }})" class="btn-action-edit" title="Edit Kontrak Gaji Worker">
                                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                        <span>Edit</span>
                                                    </button>
                                                    <button wire:click="deletePayrollSetup({{ $exp->id }})" wire:confirm="Yakin ingin menghapus penetapan gaji unit ini beserta riwayat pembayarannya?" class="btn-action-delete" title="Hapus Kontrak Gaji Worker">
                                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        <span>Hapus</span>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-slate-400 text-[10px]">-</span>
                                            @endif
                                        @else
                                            <span class="text-slate-400 text-[10px]">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-6 text-center text-slate-400 italic">Belum ada rincian belanja material atau pengeluaran tercatat untuk unit ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
</div>
