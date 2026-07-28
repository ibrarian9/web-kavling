<div class="space-y-6">

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
            <button onclick="history.back()" class="btn-secondary text-xs px-3.5 py-2 flex items-center gap-1.5 hover:bg-slate-200 transition shadow-xs" title="Kembali ke Halaman Sebelumnya">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Kembali</span>
            </button>

            @if(!auth()->user()->isPengawasProject() && $unit->category !== 'infrastruktur' && in_array($unit->status, ['tersedia', 'disetujui']))
                <button wire:click="openBookingModal" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-3 py-2 rounded-lg transition shadow-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Booking Unit Ini</span>
                </button>
            @endif

            @if(auth()->user()->isMarketing() && $unit->category !== 'infrastruktur' && $unit->status === 'tersedia')
                <a href="{{ route('proposals.index', ['create_unit_id' => $unit->id]) }}" class="btn-primary text-xs px-3 py-2">
                    <span>Ajukan Penawaran Harga</span> &rarr;
                </a>
            @endif
        </div>
    </div>

    <!-- Key Metrics Highlight Cards (Req #1: dot currency formatting) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- HPP Pokok (Founder & Finance Only) -->
        @if(auth()->user()->canViewHpp())
            <div class="card-clean p-4">
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">HPP Pokok Unit</p>
                <p class="text-xl font-black text-slate-900 font-mono mt-1">
                    Rp {{ number_format($unit->hpp, 0, ',', '.') }}
                </p>
                <p class="text-[10px] text-slate-400 mt-1">Dasar: Rp {{ number_format($unit->project->base_price, 0, ',', '.') }} + Kelebihan</p>
            </div>
        @endif

        <!-- Harga Jual Final -->
        <div class="card-clean p-4">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Harga Jual Disetujui</p>
            <p class="text-xl font-black text-emerald-700 font-mono mt-1">
                {{ $unit->final_selling_price ? 'Rp ' . number_format($unit->final_selling_price, 0, ',', '.') : 'Belum Disetujui' }}
            </p>
            <p class="text-[10px] text-emerald-600 mt-1">Status: {{ ucfirst($unit->status) }}</p>
        </div>

        <!-- Total Cash In (Financial Metric - Hidden from Pengawas) -->
        @if(!auth()->user()->isPengawasProject())
            <div class="card-clean p-4">
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Kas Masuk (DP + Cicilan)</p>
                <p class="text-xl font-black text-blue-700 font-mono mt-1">
                    Rp {{ number_format($totalCashIn, 0, ',', '.') }}
                </p>
                <p class="text-[10px] text-slate-400 mt-1">
                    {{ $unit->installment ? 'Skema: ' . $unit->installment->installment_count . 'x Cicilan' : 'Belum Ada Cicilan' }}
                </p>
            </div>
        @endif

        <!-- Total Biaya Unit -->
        @php
            $unitTotalExpenses = $combinedExpenses->sum('amount');
        @endphp
        <div class="card-clean p-4">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Pengeluaran / Belanja</p>
            <p class="text-xl font-black text-rose-700 font-mono mt-1">
                Rp {{ number_format($unitTotalExpenses, 0, ',', '.') }}
            </p>
            <p class="text-[10px] text-slate-400 mt-1">Akumulasi Gaji Worker & Belanja Material</p>
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
                        @if($assign->worker)
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
                        @elseif($assign->user)
                            <div class="p-3 bg-purple-50/70 rounded-xl border border-purple-100 flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-purple-900">{{ $assign->user->name }}</p>
                                    <p class="text-[10px] text-purple-700 font-medium">{{ $assign->assigned_role }} &bull; Pengawas System</p>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-purple-100 text-purple-800">
                                    Pengawas
                                </span>
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
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $up->status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ strtoupper($up->status) }}
                                </span>
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
                            <span class="text-[11px] uppercase font-bold px-2 py-0.5 rounded {{ $unit->installment->status === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ ucfirst($unit->installment->status) }}
                            </span>
                        </h3>

                        @if(auth()->user()->isFinance() || auth()->user()->isFounder())
                            @if($unit->installment->status !== 'lunas')
                                <button wire:click="openInstallmentPaymentModal" class="btn-primary text-xs px-3 py-1.5 flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>+ Input Setoran Cicilan</span>
                                </button>
                            @endif
                        @endif
                    </div>

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
            @elseif(!auth()->user()->isPengawasProject() && (auth()->user()->isFinance() || auth()->user()->isFounder()) && in_array($unit->status, ['booked', 'disetujui', 'terjual', 'converted']))
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

                    <div class="flex items-center gap-2">
                        @if(auth()->user()->isFinance() || auth()->user()->isFounder() || auth()->user()->isPengawasProject() || auth()->user()->isSupervisor())
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
                                <th class="px-3 py-2.5 text-center">Resi / Bukti</th>
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
                                                <button wire:click="openViewerModal('image', '{{ asset('storage/' . $exp->receipt_photo_path) }}', 'Pratinjau Foto Struk Nota Belanja')" title="Pratinjau Foto Struk" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 text-[11px] font-bold transition">
                                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    Struk
                                                </button>
                                            @endif

                                            @if($exp->pdf_url)
                                                <button wire:click="openViewerModal('pdf', '{{ $exp->pdf_url }}', 'Pratinjau Resi Gaji PDF')" title="Pratinjau PDF Resi" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-sky-50 text-sky-800 hover:bg-sky-100 border border-sky-200 text-[11px] font-bold transition">
                                                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    PDF
                                                </button>
                                            @endif

                                            @if($exp->qr_url)
                                                <button wire:click="openViewerModal('qr', '{{ $exp->qr_url }}', 'Verifikasi Resi Gaji Publik (QR Code)')" title="Pratinjau QR Code Verifikasi" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 text-[11px] font-bold transition">
                                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                                    QR
                                                </button>
                                            @endif

                                            @if(!$exp->receipt_photo_path && !$exp->pdf_url && !$exp->qr_url)
                                                <span class="text-slate-400 text-[10px] italic">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-6 text-center text-slate-400 italic">Belum ada rincian belanja material atau pengeluaran tercatat untuk unit ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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

    <!-- Modal Form Payroll Setup for this Unit -->
    @if($showPayrollSetupModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Set Gaji Borongan Unit {{ $unit->code }}</h3>
                        <p class="text-slate-500 text-[11px]">Proyek: {{ $unit->project->name }}</p>
                    </div>
                    <button wire:click="$set('showPayrollSetupModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="savePayrollSetup" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Pilih Pekerja (Mandor / Tukang)</label>
                        <select wire:model="payroll_worker_id" class="input-clean w-full font-bold">
                            @foreach($allWorkers as $w)
                                <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }} - {{ $w->specialty }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Total Nominal Gaji (Rp)</label>
                        <x-currency-input model="payroll_agreed_salary" class="input-clean w-full font-mono font-bold text-slate-900 text-sm" placeholder="Contoh: 15.000.000" />
                        @error('payroll_agreed_salary') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Skema Pembayaran</label>
                        <select wire:model="payroll_payment_frequency" class="input-clean w-full font-semibold">
                            <option value="fleksibel">Fleksibel (Sesuai Permintaan Mandor)</option>
                            <option value="harian">Harian</option>
                            <option value="mingguan">Mingguan (Per-Minggu)</option>
                            <option value="bulanan">Bulanan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Catatan Tambahan</label>
                        <textarea wire:model="payroll_notes" rows="2" placeholder="Lingkup kerja borongan unit..." class="input-clean w-full"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showPayrollSetupModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Kesepakatan Gaji</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Form Payroll Payment for this Unit (Form Responsif Mobile) -->
    @if($showPayrollPaymentModal && $selectedPayroll)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
            <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg sm:max-w-xl md:max-w-2xl w-full p-4 sm:p-8 shadow-2xl space-y-4 sm:space-y-6 my-auto max-h-[92vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 sm:pb-4">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base sm:text-xl tracking-tight">Pembayaran Gaji Unit {{ $unit->code }}</h3>
                        <p class="text-slate-500 text-[11px] sm:text-xs mt-0.5">Pekerja: {{ $selectedPayroll->worker->name }}</p>
                    </div>
                    <button wire:click="$set('showPayrollPaymentModal', false)" class="p-1.5 sm:p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
                </div>

                <div class="bg-slate-50 p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200 text-xs sm:text-sm grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                    <div>
                        <span class="text-slate-500 block text-[11px] sm:text-xs">Total Gaji Borongan:</span>
                        <span class="font-bold text-slate-900 font-mono text-sm sm:text-base">Rp {{ number_format($selectedPayroll->agreed_salary, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[11px] sm:text-xs">Sisa Kontrak Belum Dibayar:</span>
                        <span class="font-bold text-amber-600 font-mono text-sm sm:text-base">Rp {{ number_format($selectedPayroll->remaining_salary, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form wire:submit.prevent="savePayrollPayment" class="space-y-3 sm:space-y-4 text-xs sm:text-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1 text-[11px] sm:text-xs">Tanggal Pembayaran</label>
                            <input type="date" wire:model="payroll_payment_date" required class="input-clean w-full text-xs sm:text-sm font-mono py-2 sm:py-2.5 px-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500">
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1 text-[11px] sm:text-xs">Metode Pembayaran</label>
                            <select wire:model.live="payroll_payment_method" class="input-clean w-full text-xs sm:text-sm font-semibold py-2 sm:py-2.5 px-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500">
                                <option value="transfer_bank">Transfer Bank</option>
                                <option value="tunai">Tunai (Cash)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-[11px] sm:text-xs">Nominal Gaji Dibayarkan (Rp)</label>
                        <x-currency-input model="payroll_amount_gross" class="input-clean w-full font-mono font-bold text-slate-900 text-sm sm:text-base py-2.5 sm:py-3 px-3 sm:px-4 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500" placeholder="Misal: 2.500.000" />
                        @error('payroll_amount_gross') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-[11px] sm:text-xs">
                            Upload Foto Struk Transfer {{ $payroll_payment_method === 'tunai' ? '(Opsional)' : '(Rekomendasi)' }}
                        </label>
                        <input type="file" wire:model="payroll_receipt_photo" accept="image/*" class="input-clean w-full text-xs py-1.5 sm:py-2 px-3 rounded-xl border border-slate-200">
                        @if($payroll_receipt_photo)
                            <div class="mt-3 text-center bg-slate-50 p-3 rounded-2xl border border-slate-200">
                                <p class="text-xs text-slate-500 mb-2 font-semibold">Preview Struk Transfer Upload:</p>
                                <img src="{{ $payroll_receipt_photo->temporaryUrl() }}" class="max-h-48 mx-auto rounded-xl border border-slate-200 shadow-md">
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1 text-[11px] sm:text-xs">Catatan Pembayaran</label>
                        <input type="text" wire:model="payroll_payment_notes" placeholder="Catatan transaksi..." class="input-clean w-full text-xs sm:text-sm py-2 sm:py-2.5 px-3 rounded-xl border border-slate-200">
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('showPayrollPaymentModal', false)" class="btn-secondary py-2 sm:py-2.5 px-4 sm:px-5 rounded-xl text-xs sm:text-sm">Batal</button>
                        <button type="submit" class="btn-primary bg-emerald-600 hover:bg-emerald-700 py-2.5 px-4 sm:px-6 rounded-xl shadow-md text-xs sm:text-sm text-center">Simpan Pembayaran & Cetak Resi</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Form Material Purchase (Catat Belanja Barang Unit - Form Lebih Besar & Responsif) -->
    @if($showMaterialModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
            <div class="bg-white border border-slate-200/80 rounded-3xl max-w-2xl md:max-w-3xl w-full p-6 sm:p-8 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-lg sm:text-xl tracking-tight">Catat Belanja Barang / Material Unit {{ $unit->code }}</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Proyek: {{ $unit->project->name }}</p>
                    </div>
                    <button wire:click="$set('showMaterialModal', false)" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
                </div>

                <form wire:submit.prevent="saveMaterialPurchase" class="space-y-4 text-xs sm:text-sm">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-xs">Pekerja / Mandor Pembeli (Opsional)</label>
                        <select wire:model="material_worker_id" class="input-clean w-full text-sm font-bold py-2.5 px-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500">
                            @foreach($allWorkers as $w)
                                <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }} - {{ $w->specialty }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-xs">Tanggal Pembelian</label>
                            <input type="date" wire:model="material_purchase_date" required class="input-clean w-full text-sm font-mono py-2.5 px-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-xs">Nama Barang / Material</label>
                            <input type="text" wire:model="material_item_name" required placeholder="Contoh: Semen Gresik / Pasir / Cat" class="input-clean w-full text-sm font-bold py-2.5 px-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-xs">Jumlah (Qty)</label>
                            <input type="number" step="0.01" wire:model.live="material_quantity" required class="input-clean w-full text-sm font-mono font-bold py-2.5 px-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-xs">Satuan</label>
                            <input type="text" wire:model="material_unit_measure" required placeholder="sak / m3 / btg" class="input-clean w-full text-sm font-semibold py-2.5 px-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1.5 uppercase tracking-wider text-xs">Harga Satuan (Rp)</label>
                            <x-currency-input model="material_unit_price" class="input-clean w-full text-sm font-mono font-bold py-2.5 px-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500" placeholder="65.000" />
                        </div>
                    </div>

                    <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200/80 flex justify-between items-center text-amber-900 font-bold">
                        <span class="text-sm">Total Belanja Material:</span>
                        <span class="text-lg sm:text-xl font-mono text-amber-700">Rp {{ number_format(((float)($material_quantity ?? 0)) * ((float)($material_unit_price ?? 0)), 0, ',', '.') }}</span>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1.5 text-xs">Upload Foto Struk / Nota Pembelian</label>
                        <input type="file" wire:model="material_receipt_photo" accept="image/*" class="input-clean w-full text-xs py-2 px-3 rounded-xl border border-slate-200">
                        <span class="text-[11px] text-slate-400 mt-1 block">Foto nota akan dikompresi otomatis & disimpan di sistem.</span>
                        @if($material_receipt_photo)
                            <div class="mt-3 text-center bg-slate-50 p-3 rounded-2xl border border-slate-200">
                                <p class="text-xs text-slate-500 mb-2 font-semibold">Preview Struk Nota Upload:</p>
                                <img src="{{ $material_receipt_photo->temporaryUrl() }}" class="max-h-48 mx-auto rounded-xl border border-slate-200 shadow-md">
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1.5 text-xs">Catatan Belanja</label>
                        <input type="text" wire:model="material_notes" placeholder="Catatan supplier / lokasi toko..." class="input-clean w-full text-sm py-2.5 px-3 rounded-xl border border-slate-200">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('showMaterialModal', false)" class="btn-secondary px-5 py-2.5 rounded-xl">Batal</button>
                        <button type="submit" class="btn-primary bg-amber-600 hover:bg-amber-700 px-6 py-2.5 rounded-xl shadow-md">Simpan Pembelian Material</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Input Setoran Cicilan Pembeli (Khusus Finance & Founder) -->
    @if($showInstallmentPaymentModal && $unit->installment)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl border border-slate-100">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Input Setoran Cicilan Unit {{ $unit->code }}
                    </h3>
                    <button wire:click="$set('showInstallmentPaymentModal', false)" class="text-slate-400 hover:text-slate-600 text-sm">✕</button>
                </div>

                <form wire:submit.prevent="saveInstallmentPayment" class="space-y-4 text-xs">
                    <div class="bg-blue-50 border border-blue-100 p-3 rounded-xl space-y-1">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Target Cicilan per Bulan:</span>
                            <span class="font-bold font-mono text-blue-800">Rp {{ number_format($unit->installment->installment_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Sisa Tagihan Cicilan:</span>
                            <span class="font-bold font-mono text-amber-700">Rp {{ number_format($unit->installment->remaining_balance, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Tanggal Setoran</label>
                        <input type="date" wire:model="installment_payment_date" class="w-full input-clean text-xs">
                        @error('installment_payment_date') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Nominal Setoran (Rp)</label>
                        <x-currency-input model="installment_payment_amount" class="w-full input-clean font-mono text-xs font-bold" placeholder="Contoh: 5.000.000" />
                        @error('installment_payment_amount') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Metode Pembayaran</label>
                        <select wire:model="installment_payment_method" class="w-full input-clean text-xs">
                            <option value="Transfer Bank">Transfer Bank (BRK Syariah / Mandiri / BRI / BCA)</option>
                            <option value="Tunai">Tunai / Cash</option>
                            <option value="Cek / Giro">Cek / Giro</option>
                        </select>
                        @error('installment_payment_method') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Catatan / Keterangan (Opsional)</label>
                        <textarea wire:model="installment_payment_notes" rows="2" class="w-full input-clean text-xs" placeholder="Setoran cicilan bulan ke-X..."></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" wire:click="$set('showInstallmentPaymentModal', false)" class="btn-secondary text-xs px-4 py-2">Batal</button>
                        <button type="submit" class="btn-primary text-xs px-4 py-2 bg-blue-600 hover:bg-blue-700">Simpan Setoran</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Setup Skema Cicilan Baru (Khusus Finance & Founder) -->
    @if($showSetupInstallmentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl border border-slate-100">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Konfigurasi Skema Cicilan Unit {{ $unit->code }}
                    </h3>
                    <button wire:click="$set('showSetupInstallmentModal', false)" class="text-slate-400 hover:text-slate-600 text-sm">✕</button>
                </div>

                <form wire:submit.prevent="saveSetupInstallment" class="space-y-4 text-xs">
                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Total Harga Deal Unit (Rp)</label>
                        <x-currency-input model="setup_total_price" class="w-full input-clean font-mono text-xs font-bold" placeholder="Contoh: 150.000.000" />
                        @error('setup_total_price') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-slate-700 font-bold mb-1">Uang Muka / DP (Rp)</label>
                        <x-currency-input model="setup_down_payment" class="w-full input-clean font-mono text-xs font-bold" placeholder="Contoh: 30.000.000" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Tenor (Berapa Kali Cicilan)</label>
                            <input type="number" min="1" max="120" wire:model.live="setup_installment_count" wire:change="calculateMonthlyInstallment" class="w-full input-clean text-xs">
                        </div>
                        <div>
                            <label class="block text-slate-700 font-bold mb-1">Tanggal Mulai Skema</label>
                            <input type="date" wire:model="setup_start_date" class="w-full input-clean text-xs">
                        </div>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl space-y-1">
                        <div class="flex justify-between font-bold text-slate-800">
                            <span>Estimasi Cicilan per Bulan:</span>
                            <span class="font-mono text-blue-700">Rp {{ number_format($setup_installment_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                        <button type="button" wire:click="$set('showSetupInstallmentModal', false)" class="btn-secondary text-xs px-4 py-2">Batal</button>
                        <button type="submit" class="btn-primary text-xs px-4 py-2 bg-blue-600 hover:bg-blue-700">Simpan Skema Cicilan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Jendela Melayang (Viewer Modal: Foto Struk / PDF Resi / QR Verifikasi) -->
    @if($showViewerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-md">
            <div class="bg-white rounded-3xl max-w-4xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden border border-slate-200">
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        @if($viewerType === 'image')
                            <span class="p-2 rounded-xl bg-amber-500/20 text-amber-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                        @elseif($viewerType === 'pdf')
                            <span class="p-2 rounded-xl bg-sky-500/20 text-sky-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </span>
                        @else
                            <span class="p-2 rounded-xl bg-emerald-500/20 text-emerald-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            </span>
                        @endif
                        <div>
                            <h3 class="font-bold text-base tracking-tight text-white">{{ $viewerTitle }}</h3>
                            <p class="text-[11px] text-slate-400">Pratinjau langsung di dalam aplikasi</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ $viewerUrl }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold flex items-center gap-1 transition">
                            <span>Buka Tab Baru ↗</span>
                        </a>
                        <button wire:click="closeViewerModal" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition">
                            ✕
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 bg-slate-950 p-4 overflow-auto flex items-center justify-center min-h-[60vh]">
                    @if($viewerType === 'image')
                        <img src="{{ $viewerUrl }}" class="max-h-[75vh] w-auto max-w-full object-contain rounded-2xl shadow-2xl border border-slate-800" alt="Foto Struk / Resi">
                    @elseif($viewerType === 'pdf')
                        <iframe src="{{ $viewerUrl }}" class="w-full h-[75vh] rounded-2xl bg-white border-0 shadow-lg"></iframe>
                    @elseif($viewerType === 'qr')
                        <iframe src="{{ $viewerUrl }}" class="w-full h-[75vh] rounded-2xl bg-white border-0 shadow-lg"></iframe>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
