<!-- Proposal & Official Document (SPP) Status Card (Hidden from Pengawas Project) -->
@if(!auth()->user()->isPengawasProject())
    <div class="card-clean p-5 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3 gap-2">
            <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                <div class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span>Surat Pesanan Penjualan (SPP) & Proposal Harga</span>
            </h3>
        </div>

        <!-- Active Booking Fee Highlight Card -->
        @php
            $activeBooking = $unit->activeBooking ?? $unit->bookings->first();
        @endphp
        @if($activeBooking)
            <div class="bg-teal-50/90 border border-teal-200 rounded-2xl p-4 space-y-3 text-xs shadow-2xs">
                <div class="flex items-center justify-between border-b border-teal-200/80 pb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="p-1 rounded bg-teal-200 text-teal-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2zm0 8h14a2 2 0 012 2v3a2 2 0 01-2 2H5a2 2 0 01-2-2v-3a2 2 0 01-2-2z"/></svg>
                        </span>
                        <h4 class="font-extrabold text-teal-950 text-xs sm:text-sm">Rincian Booking Fee & Tanda Jadi Pembeli</h4>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-extrabold shadow-2xs {{ $activeBooking->status === 'converted' ? 'bg-emerald-600 text-white' : 'bg-teal-700 text-white' }}">
                        {{ $activeBooking->status === 'converted' ? 'DP ACC' : 'Booking Active' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-slate-700">
                    <div class="bg-white/90 p-2.5 rounded-xl border border-teal-100">
                        <span class="text-slate-500 block text-[10px] uppercase font-bold">Nominal Booking Fee:</span>
                        <span class="font-mono font-extrabold text-teal-800 text-sm">Rp {{ number_format($activeBooking->booking_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-white/90 p-2.5 rounded-xl border border-teal-100">
                        <span class="text-slate-500 block text-[10px] uppercase font-bold">Total DP / Uang Muka:</span>
                        <span class="font-mono font-extrabold text-emerald-800 text-sm">Rp {{ number_format(max($activeBooking->dp_amount, $activeBooking->booking_amount), 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-white/90 p-2.5 rounded-xl border border-teal-100">
                        <span class="text-slate-500 block text-[10px] uppercase font-bold">Nama & Kontak Pembeli:</span>
                        <span class="font-bold text-slate-900 block truncate">{{ $activeBooking->buyer_name }}</span>
                        <span class="font-mono text-[10px] text-slate-500 block">{{ $activeBooking->buyer_phone }}</span>
                    </div>
                </div>

                @if($activeBooking->receipt_photo_path)
                    <div class="pt-1 flex items-center justify-between text-[11px]">
                        <span class="text-slate-600">Bukti Struk Transfer / Resi Terlampir</span>
                        <button wire:click="openViewerModal('image', '{{ asset('storage/' . $activeBooking->receipt_photo_path) }}', 'Foto Struk Resi Booking - {{ $activeBooking->buyer_name }}')" class="text-teal-700 hover:text-teal-900 font-bold underline flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Lihat Foto Resi Bukti Transfer</span>
                        </button>
                    </div>
                @endif
            </div>
        @endif

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
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
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
