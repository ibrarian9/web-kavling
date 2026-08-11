<!-- Top Navigation & Header -->
<div class="card-clean p-4 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
    <div class="space-y-1.5">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-1.5 text-xs text-slate-500 flex-wrap">
            <a href="{{ route('projects.show', $unit->project_id) }}" wire:navigate.hover class="hover:text-emerald-700 font-semibold inline-flex items-center gap-1.5 transition-colors">
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
            <!-- Dropdown Opsi Unit -->
            <div x-data="{ open: false }" @click.outside="open = false" class="relative inline-block text-left">
                <button @click="open = !open" type="button" class="btn-action-edit px-3.5 py-2 text-xs rounded-xl shadow-2xs font-bold inline-flex items-center gap-1.5" title="Opsi Pengelolaan Unit">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Opsi Unit</span>
                    <svg class="w-3 h-3 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 rounded-2xl bg-white shadow-xl border border-slate-200/90 py-1.5 z-30 divide-y divide-slate-100 text-xs font-semibold" style="display: none;">
                    <div class="py-1">
                        <button wire:click="openEditUnitModal" @click="open = false" class="w-full text-left px-3.5 py-2 text-slate-700 hover:bg-slate-50 flex items-center gap-2 transition">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Edit Spesifikasi</span>
                        </button>
                    </div>
                    <div class="py-1">
                        <button type="button" @click="open = false; confirmModalAction({
                            title: 'Hapus Unit Kavling/Rumah',
                            message: 'Yakin ingin menghapus unit {{ $unit->code }} dari sistem beserta seluruh histori terikatnya?',
                            confirmText: 'Hapus Unit',
                            btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                            onConfirm: () => $wire.deleteUnit()
                        })" class="w-full text-left px-3.5 py-2 text-rose-600 hover:bg-rose-50 flex items-center gap-2 transition">
                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Hapus Unit</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if((auth()->user()->isFounder() || auth()->user()->isFinance()) && $unit->category !== 'infrastruktur' && $unit->status === 'tersedia')
            <button wire:click="openDirectSppModal" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold inline-flex items-center gap-1.5 transition shadow-sm active:scale-[0.98]" title="Terbitkan Surat Pesanan SPP & SPJB PDF (Pembelian Cash)">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
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
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
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

    <!-- Booking Fee / Tanda Jadi Unit (Financial Metric) -->
    @php
        $activeB = $unit->activeBooking ?? $unit->bookings->first();
        $bookingFeeAmount = $activeB ? $activeB->booking_amount : 5000000;
    @endphp
    <div class="card-clean p-5 transition-all duration-200 hover:-translate-y-0.5 border-teal-200/80 bg-gradient-to-br from-white to-teal-50/30">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-teal-800 uppercase tracking-wider">Booking Fee / Tanda Jadi</span>
            <div class="p-2.5 rounded-xl bg-teal-50 text-teal-600 border border-teal-100 shadow-2xs">
                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2zm0 8h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 01-2-2z"/></svg>
            </div>
        </div>
        <p class="text-2xl font-extrabold text-teal-800 font-mono mt-2">
            Rp {{ number_format($bookingFeeAmount, 0, ',', '.') }}
        </p>
        <p class="text-[11px] text-slate-500 mt-1 truncate">
            @if($activeB)
                <span class="font-semibold text-teal-900">{{ $activeB->buyer_name }}</span> ({{ ucfirst($activeB->status) }})
            @else
                <span class="text-slate-400">Patokan Standar Unit</span>
            @endif
        </p>
    </div>

    <!-- Total Cash In (Financial Metric - Hidden from Pengawas) -->
    @if(!auth()->user()->isPengawasProject())
        <div class="card-clean p-5 transition-all duration-200 hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Setoran Pembeli</span>
                <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            @php
                $cashIn = 0;
                if ($unit->installment) {
                    $cashIn = (float)$unit->installment->down_payment + (float)$unit->installment->payments->sum('amount_paid');
                }
            @endphp
            <p class="text-2xl font-extrabold text-purple-900 font-mono mt-2">
                Rp {{ number_format($cashIn, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Total masuk dari DP & Cicilan</p>
        </div>
    @endif

    <!-- Total Construction & Material Expenses -->
    <div class="card-clean p-5 transition-all duration-200 hover:-translate-y-0.5">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Biaya Bangun / Material</span>
            <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
        </div>
        @php
            $totalExpenses = 0;
            if (isset($combinedExpenses)) {
                $totalExpenses = collect($combinedExpenses)->sum('amount');
            }
        @endphp
        <p class="text-2xl font-extrabold text-amber-800 font-mono mt-2">
            Rp {{ number_format($totalExpenses, 0, ',', '.') }}
        </p>
        <p class="text-[11px] text-slate-400 mt-1">Material & Upah Worker Terbayar</p>
    </div>
</div>
