<div class="space-y-6">

    <!-- Top Navigation & Header -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('units.index') }}" class="hover:text-slate-800 font-medium">&larr; Daftar Unit Kavling</a>
                <span>/</span>
                <span class="font-semibold text-slate-700">{{ $unit->project->name }}</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-extrabold text-slate-900 font-mono tracking-tight">{{ $unit->code }}</h1>
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
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('units.index') }}" class="btn-secondary text-xs px-3 py-2">
                &larr; Kembali
            </a>

            @if(in_array($unit->status, ['tersedia', 'disetujui']))
                <button wire:click="openBookingModal" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-3 py-2 rounded-lg transition shadow-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Booking Unit Ini</span>
                </button>
            @endif

            @if(auth()->user()->isMarketing() && $unit->status === 'tersedia')
                <a href="{{ route('proposals.index', ['create_unit_id' => $unit->id]) }}" class="btn-primary text-xs px-3 py-2">
                    <span>Ajukan Penawaran Harga</span> &rarr;
                </a>
            @endif
        </div>
    </div>

    <!-- Key Metrics Highlight Cards (Req #1: dot currency formatting) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- HPP Pokok -->
        <div class="card-clean p-4">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">HPP Pokok Unit</p>
            <p class="text-xl font-black text-slate-900 font-mono mt-1">
                Rp {{ number_format($unit->hpp, 0, ',', '.') }}
            </p>
            <p class="text-[10px] text-slate-400 mt-1">Dasar: Rp {{ number_format($unit->project->base_price, 0, ',', '.') }} + Kelebihan</p>
        </div>

        <!-- Harga Jual Final -->
        <div class="card-clean p-4">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Harga Jual Disetujui</p>
            <p class="text-xl font-black text-emerald-700 font-mono mt-1">
                {{ $unit->final_selling_price ? 'Rp ' . number_format($unit->final_selling_price, 0, ',', '.') : 'Belum Disetujui' }}
            </p>
            <p class="text-[10px] text-emerald-600 mt-1">Status: {{ ucfirst($unit->status) }}</p>
        </div>

        <!-- Total Cash In -->
        <div class="card-clean p-4">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Kas Masuk (DP + Cicilan)</p>
            <p class="text-xl font-black text-blue-700 font-mono mt-1">
                Rp {{ number_format($totalCashIn, 0, ',', '.') }}
            </p>
            <p class="text-[10px] text-slate-400 mt-1">
                {{ $unit->installment ? 'Skema: ' . $unit->installment->installment_count . 'x Cicilan' : 'Belum Ada Cicilan' }}
            </p>
        </div>

        <!-- Total Biaya Unit -->
        <div class="card-clean p-4">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Pengeluaran / Biaya</p>
            <p class="text-xl font-black text-rose-700 font-mono mt-1">
                Rp {{ number_format($totalCosts, 0, ',', '.') }}
            </p>
            <p class="text-[10px] text-slate-400 mt-1">Terbayar: Rp {{ number_format($paidCosts, 0, ',', '.') }} | Belum: Rp {{ number_format($unpaidCosts, 0, ',', '.') }}</p>
        </div>
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
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                            <div>
                                <p class="font-bold text-slate-800">{{ $assign->worker->name }}</p>
                                <p class="text-[10px] text-slate-500 font-medium">{{ $assign->assigned_role }} &bull; {{ ucfirst($assign->worker->type) }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Spesialis: {{ $assign->worker->specialty }}</p>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">
                                Active
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-4 text-slate-400 text-xs">
                            Belum ada penugasan mandor/tukang spesifik pada unit ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Middle & Right Columns: Proposals, SPP, Financials & Costs -->
        <div class="space-y-6 lg:col-span-2">

            <!-- Proposal & Official Document (SPP) Status -->
            <div class="card-clean p-5 space-y-4">
                <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-2.5 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Surat Pesanan Penjualan (SPP) & Proposal Harga
                </h3>

                @if($unit->officialDocument)
                    <div class="bg-emerald-50/80 border border-emerald-200 rounded-xl p-4 space-y-2 text-xs">
                        <div class="flex items-center justify-between text-emerald-900">
                            <span class="font-mono font-bold text-sm">{{ $unit->officialDocument->document_number }}</span>
                            <span class="bg-emerald-700 text-white font-bold text-[10px] px-2 py-0.5 rounded">Resmi Terbit</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-slate-700 pt-2 border-t border-emerald-200/60">
                            <div>
                                <span class="text-slate-500 block text-[10px]">Nama Pembeli:</span>
                                <span class="font-bold text-slate-900">{{ $unit->officialDocument->buyer_name }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block text-[10px]">Kontak:</span>
                                <span class="font-mono font-semibold">{{ $unit->officialDocument->buyer_contact }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-slate-500 block text-[10px]">Alamat Pembeli:</span>
                                <span>{{ $unit->officialDocument->buyer_address }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-xs text-slate-500 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        Belum ada dokumen SPP resmi terbit untuk unit ini.
                    </div>
                @endif

                <!-- Proposals History (Req #5: Without profit margin display) -->
                <div class="pt-2">
                    <p class="text-xs font-bold text-slate-700 mb-2">Riwayat Proposal Harga Jual:</p>
                    <div class="space-y-2">
                        @forelse($unit->proposals as $prop)
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/60 text-xs flex flex-col md:flex-row md:items-center justify-between gap-2">
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
                                        <span class="status-menunggu">Menunggu</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-400 text-xs italic">Belum ada riwayat pengajuan harga.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Installment & Buyer Payments -->
            @if($unit->installment)
                <div class="card-clean p-5 space-y-4">
                    <h3 class="font-bold text-slate-900 text-sm border-b border-slate-100 pb-2.5 flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Skema Cicilan & Pembayaran Pembeli
                        </span>
                        <span class="text-xs uppercase font-bold px-2 py-0.5 rounded {{ $unit->installment->status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ ucfirst($unit->installment->status) }}
                        </span>
                    </h3>

                    <div class="grid grid-cols-3 gap-3 text-xs bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Total Harga:</span>
                            <span class="font-bold text-slate-900 font-mono">Rp {{ number_format($unit->installment->total_price, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Uang Muka (DP):</span>
                            <span class="font-bold text-blue-700 font-mono">Rp {{ number_format($unit->installment->down_payment, 0, ',', '.') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Cicilan Per Bulan:</span>
                            <span class="font-bold text-slate-800 font-mono">{{ $unit->installment->installment_count }}x @ Rp {{ number_format($unit->installment->installment_amount, 0, ',', '.') }}</span>
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
                                <span class="font-mono text-slate-500 text-[11px]">{{ $pay->payment_date }}</span>
                            </div>
                        @empty
                            <p class="text-slate-400 text-xs italic">Belum ada setoran cicilan pembeli.</p>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- Unit Costs & Direct Cost Recording Button (Req #4) -->
            <div class="card-clean p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Rincian Biaya Pengeluaran & Belanja Unit
                    </h3>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-mono font-bold text-rose-700 mr-2">Rp {{ number_format($totalCosts, 0, ',', '.') }}</span>
                        @if(auth()->user()->isFinance() || auth()->user()->isFounder() || auth()->user()->isPengawasProject())
                            <button wire:click="openCostModal" class="btn-primary text-[11px] px-2.5 py-1 flex items-center gap-1">
                                <span>+ Catat Biaya</span>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                            <tr>
                                <th class="px-3 py-2">Kategori & Deskripsi</th>
                                <th class="px-3 py-2">Vendor / Tukang</th>
                                <th class="px-3 py-2">Nominal</th>
                                <th class="px-3 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($unitCosts as $cost)
                                <tr>
                                    <td class="px-3 py-2.5">
                                        <span class="font-bold text-slate-800 capitalize">{{ $cost->category }}</span>
                                        <p class="text-[10px] text-slate-500">{{ $cost->description }}</p>
                                    </td>
                                    <td class="px-3 py-2.5 font-medium text-slate-700">{{ $cost->vendor_name ?? '-' }}</td>
                                    <td class="px-3 py-2.5 font-mono font-bold text-slate-900">Rp {{ number_format($cost->amount, 0, ',', '.') }}</td>
                                    <td class="px-3 py-2.5">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $cost->status === 'dibayar' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ ucfirst($cost->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-3 py-4 text-center text-slate-400 italic">Belum ada pengeluaran biaya tercatat pada unit ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Material Purchases Log -->
                @if($materialPurchases->isNotEmpty())
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <p class="font-bold text-slate-700 text-xs">Log Pembelian Material Lapangan oleh Tukang/Mandor:</p>
                        <div class="space-y-2 text-xs">
                            @foreach($materialPurchases as $mp)
                                <div class="p-2.5 bg-slate-50 rounded-lg border border-slate-100 flex items-center justify-between">
                                    <div>
                                        <span class="font-bold text-slate-900">{{ $mp->item_name }}</span>
                                        <span class="text-slate-500 font-mono text-[11px]">({{ number_format($mp->quantity, 0, ',', '.') }} {{ $mp->unit_measure }})</span>
                                        <p class="text-[10px] text-slate-500">Oleh: {{ $mp->worker->name }} &bull; Pengawas: {{ $mp->pengawas->name ?? '-' }}</p>
                                    </div>
                                    <span class="font-mono font-bold text-slate-800">Rp {{ number_format($mp->total_price, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </div>

    <!-- Modal Form Worker Assignment Directly from Unit (Req #3) -->
    @if($showWorkerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">Tugaskan Mandor / Tukang ke Unit {{ $unit->code }}</h3>
                    <button wire:click="$set('showWorkerModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveWorkerAssignment" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Pilih Pekerja (Mandor / Tukang)</label>
                        <select wire:model="worker_id" class="input-clean w-full font-semibold">
                            @foreach($allWorkers as $w)
                                <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }} - {{ $w->specialty }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Peran Penugasan</label>
                        <input type="text" wire:model="assigned_role" placeholder="Tukang Keramik / Mandor Finishing..." class="input-clean w-full font-bold">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showWorkerModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Penugasan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Form Direct Unit Cost Recording (Req #4) -->
    @if($showCostModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">Catat Biaya Pengeluaran Unit {{ $unit->code }}</h3>
                    <button wire:click="$set('showCostModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveUnitCost" class="space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Kategori Biaya</label>
                            <select wire:model="cost_category" class="input-clean w-full font-semibold">
                                <option value="tukang">Upah Tukang</option>
                                <option value="material">Material / Bahan</option>
                                <option value="perizinan">Perizinan / IMB</option>
                                <option value="lainnya">Lain-lain</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Status Bayar</label>
                            <select wire:model="cost_status" class="input-clean w-full font-semibold">
                                <option value="dibayar">Sudah Dibayar</option>
                                <option value="belum_dibayar">Belum Dibayar</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Deskripsi Biaya</label>
                        <input type="text" wire:model="cost_description" required placeholder="Batu kali pondasi / Upah borongan..." class="input-clean w-full">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nominal Biaya (Rp)</label>
                            <x-currency-input model="cost_amount" class="input-clean w-full font-bold font-mono text-rose-700" />
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Tanggal Biaya</label>
                            <input type="date" wire:model="cost_date" required class="input-clean w-full font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Vendor / Mandor Terkait</label>
                        <input type="text" wire:model="vendor_name" placeholder="Mandor Supri / Toko Bangunan Berkah..." class="input-clean w-full">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showCostModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Biaya Unit</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Form Booking Unit Directly from Detail Page (Req #2) -->
    @if($showBookingModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Booking Unit {{ $unit->code }}</h3>
                        <p class="text-slate-500 text-[11px]">Pencatatan booking & DP langsung di dalam sistem</p>
                    </div>
                    <button wire:click="$set('showBookingModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveBooking" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nama Pembeli</label>
                        <input type="text" wire:model="buyer_name" required placeholder="Bpk. H. Hendra Wijaya" class="input-clean w-full font-bold">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nomor HP / WhatsApp Pembeli</label>
                        <input type="text" wire:model="buyer_phone" required placeholder="081234567890" class="input-clean w-full font-mono">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nominal Tanda Jadi / Booking Fee (Rp)</label>
                        <x-currency-input model="booking_amount" class="input-clean w-full font-mono font-bold text-teal-700 text-sm" />
                        @error('booking_amount') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>



                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Catatan Pembayaran & Bukti DP</label>
                        <textarea wire:model="booking_notes" rows="2" placeholder="Informasi bukti transfer DP..." class="input-clean w-full"></textarea>
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
