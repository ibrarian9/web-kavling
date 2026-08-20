<!-- Top Navigation & Header -->
<x-card padding="p-4 sm:p-6">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
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
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-mono tracking-tight whitespace-nowrap">UNIT {{ $unit->code }}</h1>
                
                @if($unit->category === 'infrastruktur' || $unit->status === 'infrastruktur')
                    <span class="text-xs uppercase font-extrabold px-3 py-1 rounded-xl bg-sky-100 text-sky-800 border border-sky-300 shadow-2xs whitespace-nowrap">
                        FASUM: {{ strtoupper($unit->type) }}
                    </span>
                    <span class="px-3 py-1 rounded-xl text-xs font-bold bg-sky-950 text-sky-300 border border-sky-500/40 shadow-2xs whitespace-nowrap">
                        Infrastruktur Proyek
                    </span>
                @else
                    <span class="text-xs uppercase font-extrabold px-3 py-1 rounded-xl shadow-2xs whitespace-nowrap {{ $unit->category === 'rumah' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                        {{ ucfirst($unit->category ?? $unit->type) }}
                    </span>
                    
                    <x-status-badge :status="$unit->status" />
                @endif
            </div>
        </div>

        <!-- Header Action Toolbar -->
        <div class="flex items-center gap-2 flex-wrap pt-2 lg:pt-0 border-t lg:border-t-0 border-slate-100">
            <x-button variant="outline" size="sm" onclick="history.back()" icon="back" title="Kembali ke Halaman Sebelumnya">
                <span>Kembali</span>
            </x-button>

            <!-- Main Sales & Booking Action Group -->
            @if($unit->category !== 'infrastruktur' && $unit->status === 'tersedia')
                <div class="inline-flex rounded-xl shadow-2xs border border-slate-200 overflow-hidden bg-white divide-x divide-slate-100 flex-wrap">
                    @if((auth()->user()->isFounder() || auth()->user()->isFinance()))
                        <button type="button" wire:click="openDirectSppModal" title="Terbitkan Surat Pesanan SPP & SPJB PDF (Pembelian Cash)" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-2xs">
                            <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Pembelian Cash</span>
                        </button>
                    @endif

                    @if(!auth()->user()->isPengawasProject())
                        <button type="button" wire:click="openBookingModal" title="Booking Unit Ini" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-2xs">
                            <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Booking Unit Ini</span>
                        </button>
                    @endif

                    @if(auth()->user()->isMarketing())
                        <a href="{{ route('proposals.index', ['create_unit_id' => $unit->id]) }}" class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-2xs">
                            <svg class="w-4 h-4 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Ajukan Penawaran Harga &rarr;</span>
                        </a>
                    @endif
                </div>
            @endif

            <!-- Unit Management Action Group (Edit & Delete) -->
            @if(auth()->user()->isFounder() || auth()->user()->isFinance() || auth()->user()->isAdmin())
                <div class="inline-flex rounded-xl shadow-2xs border border-slate-200 overflow-hidden bg-white divide-x divide-slate-100">
                    <button type="button" wire:click="openEditUnitModal" title="Edit Spesifikasi Unit" class="px-3 py-2 text-slate-700 hover:text-amber-700 hover:bg-amber-50/60 font-bold text-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Edit Spesifikasi</span>
                    </button>

                    @if(auth()->user()->isSuperAdmin())
                        <button type="button" @click="confirmModalAction({
                            title: 'Hapus Unit Kavling/Rumah',
                            message: 'Yakin ingin menghapus unit {{ $unit->code }} dari sistem beserta seluruh histori terikatnya?',
                            confirmText: 'Hapus Unit',
                            btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                            onConfirm: () => $wire.deleteUnit()
                        })" title="Hapus Unit" class="px-3 py-2 text-slate-700 hover:text-rose-700 hover:bg-rose-50/60 font-bold text-xs transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Hapus</span>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-card>

@if($unit->category === 'infrastruktur')
    <!-- Key Metrics for Infrastructure / Fasum -->
    <div class="grid grid-cols-1 sm:grid-cols-2 {{ auth()->user()->canViewHpp() ? 'lg:grid-cols-3' : 'lg:grid-cols-2' }} gap-4">
        <!-- Anggaran / HPP Infra (Founder & Finance Only) -->
        @if(auth()->user()->canViewHpp())
            <div class="kpi-card-blue transition-all duration-200 hover:-translate-y-0.5">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Anggaran / HPP Infra</span>
                    <div class="p-2.5 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">
                    {{ $unit->hpp ? 'Rp ' . number_format($unit->hpp, 0, ',', '.') : 'Belum Diset' }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1">Alokasi Budget Pembangunan</p>
            </div>
        @endif

        <!-- Total Construction & Material Expenses -->
        @php
            $totalExpenses = 0;
            if (isset($combinedExpenses)) {
                $totalExpenses = collect($combinedExpenses)->sum('amount');
            }
        @endphp
        <div class="card-clean p-5 transition-all duration-200 hover:-translate-y-0.5 border-amber-200/80 bg-gradient-to-br from-white to-amber-50/30">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-amber-800 uppercase tracking-wider">Realisasi Biaya Lapangan</span>
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-800 font-mono mt-2">
                Rp {{ number_format($totalExpenses, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-500 mt-1">Material & Upah Tukang Terbayar</p>
        </div>

        <!-- Worker / Mandor Bertugas -->
        @php
            $activeWorkersCount = $unit->assignments->where('status', 'active')->count();
        @endphp
        <div class="card-clean p-5 transition-all duration-200 hover:-translate-y-0.5 border-sky-200/80 bg-gradient-to-br from-white to-sky-50/30">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-sky-800 uppercase tracking-wider">Worker & Mandor Bertugas</span>
                <div class="p-2.5 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 shadow-2xs">
                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-sky-900 font-mono mt-2">
                {{ $activeWorkersCount }} Pekerja
            </p>
            <p class="text-[11px] text-slate-500 mt-1">Tenaga kerja aktif di unit ini</p>
        </div>
    </div>
@else
    <!-- Key Metrics Highlight Cards (Standard Kavling & Rumah) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3.5">
        <!-- 1. Harga Total Unit (Harga Jual + Kelebihan Luas) -->
        <div class="card-clean p-4 sm:p-5 transition-all duration-200 hover:-translate-y-0.5 border-indigo-200/80 bg-gradient-to-br from-white to-indigo-50/30">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-indigo-800 uppercase tracking-wider">Harga Total Unit</span>
                <div class="p-2 rounded-xl bg-indigo-50 text-indigo-600 border border-indigo-100 shadow-2xs shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-xl sm:text-2xl font-extrabold text-indigo-900 font-mono mt-2 tracking-tight">
                Rp {{ number_format($unit->total_price, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-indigo-600 font-medium mt-1 truncate">
                @if($unit->excess_cost > 0)
                    +Kelebihan Rp {{ number_format($unit->excess_cost, 0, ',', '.') }}
                @else
                    Ukuran Standar Proyek
                @endif
            </p>
        </div>

        <!-- 2. Harga Jual Final -->
        <div class="kpi-card-emerald p-4 sm:p-5 transition-all duration-200 hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Harga Jual Disetujui</span>
                <div class="p-2 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-xl sm:text-2xl font-extrabold text-emerald-700 font-mono mt-2 tracking-tight">
                {{ $unit->final_selling_price ? 'Rp ' . number_format($unit->final_selling_price, 0, ',', '.') : 'Belum Disetujui' }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1 truncate">Status: {{ ucfirst($unit->status) }}</p>
        </div>

        <!-- 3. Booking Fee / Tanda Jadi Unit (Financial Metric) -->
        @php
            $activeB = $unit->activeBooking ?? $unit->bookings->first();
            $bookingFeeAmount = $activeB ? $activeB->booking_amount : 5000000;
        @endphp
        <div class="card-clean p-4 sm:p-5 transition-all duration-200 hover:-translate-y-0.5 border-teal-200/80 bg-gradient-to-br from-white to-teal-50/30">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-teal-800 uppercase tracking-wider">Booking Fee</span>
                <div class="p-2 rounded-xl bg-teal-50 text-teal-600 border border-teal-100 shadow-2xs shrink-0">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2zm0 8h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <p class="text-xl sm:text-2xl font-extrabold text-teal-800 font-mono mt-2 tracking-tight">
                Rp {{ number_format($bookingFeeAmount, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-500 mt-1 truncate">
                @if($activeB)
                    <span class="font-semibold text-teal-900">{{ $activeB->buyer_name }}</span> ({{ ucfirst($activeB->status) }})
                @else
                    <span class="text-slate-400">Patokan Standar</span>
                @endif
            </p>
        </div>

        <!-- 4. Total Setoran Pembeli -->
        <div class="card-clean p-4 sm:p-5 transition-all duration-200 hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Setoran</span>
                <div class="p-2 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 shadow-2xs shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            @php
                $cashIn = 0;
                if ($unit->installment) {
                    $cashIn = (float)$unit->installment->down_payment + (float)$unit->installment->payments->sum('amount_paid');
                }
            @endphp
            <p class="text-xl sm:text-2xl font-extrabold text-purple-900 font-mono mt-2 tracking-tight">
                Rp {{ number_format($cashIn, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1 truncate">Total DP & Cicilan Masuk</p>
        </div>

        <!-- 5. Total Construction & Material Expenses -->
        <div class="card-clean p-4 sm:p-5 transition-all duration-200 hover:-translate-y-0.5">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Realisasi Biaya</span>
                <div class="p-2 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            @php
                $totalExpenses = 0;
                if (isset($combinedExpenses)) {
                    $totalExpenses = collect($combinedExpenses)->sum('amount');
                }
            @endphp
            <p class="text-xl sm:text-2xl font-extrabold text-amber-800 font-mono mt-2 tracking-tight">
                Rp {{ number_format($totalExpenses, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1 truncate">Material & Tukang Terbayar</p>
        </div>
    </div>
@endif
