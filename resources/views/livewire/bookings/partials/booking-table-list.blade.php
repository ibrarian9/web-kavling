<!-- Filters Toolbar -->
<div class="card-clean p-4 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-64">
        <select wire:model.live="projectFilter" class="input-clean w-full">
            <option value="">Semua Perumahan / Proyek</option>
            @foreach ($projects as $proj)
                <option value="{{ $proj->id }}">{{ $proj->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="w-full md:w-48">
        <select wire:model.live="typeFilter" class="input-clean w-full">
            <option value="">Semua Tingkat Booking</option>
            <option value="unit">Per Unit Spesifik</option>
            <option value="project">Per Proyek Perumahan</option>
        </select>
    </div>
    <div class="w-full md:w-48">
        <select wire:model.live="statusFilter" class="input-clean w-full">
            <option value="">Semua Status</option>
            <option value="active">Aktif (Booked)</option>
            <option value="converted">DP ACC / Terjual</option>
            <option value="cancelled">Batal</option>
        </select>
    </div>
</div>

<!-- Table Card -->
<div class="card-clean overflow-hidden">
    <div class="overflow-x-auto relative min-h-[260px]">
        <!-- Reusable System Centered Table Loading Component -->
        <x-table-loading target="projectFilter, typeFilter, statusFilter, gotoPage, nextPage, previousPage" text="Memuat & Menyaring Data Booking..." subtext="Mohon tunggu sebentar, sistem sedang memproses data pemesanan." />

        <table class="w-full text-left text-xs text-slate-600" wire:loading.class="opacity-30 pointer-events-none transition-opacity duration-300" wire:target="projectFilter, typeFilter, statusFilter, gotoPage, nextPage, previousPage">
            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3.5">Tgl Pemesanan</th>
                    <th class="px-5 py-3.5">Nama Pemesan</th>
                    <th class="px-5 py-3.5">Perumahan & Unit</th>
                    <th class="px-5 py-3.5">Tingkat Booking</th>
                    <th class="px-5 py-3.5 text-right">Nominal Tanda Jadi</th>
                    <th class="px-5 py-3.5 text-center">Status</th>
                    <th class="px-5 py-3.5 text-center">Foto Resi & Invoice</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($bookings as $b)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-4 font-mono font-medium text-slate-700">
                            {{ $b->booking_date ? $b->booking_date->format('d/m/Y') : '-' }}
                            @if ($b->expiry_date)
                                <span class="block text-[11px] text-slate-400">s/d {{ $b->expiry_date->format('d/m/Y') }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 font-bold text-slate-900">
                            {{ $b->buyer_name }}
                            <span class="block text-[11px] font-normal text-slate-400 font-mono">{{ $b->buyer_phone }}</span>
                        </td>
                        <td class="px-5 py-4 font-medium text-slate-800">
                            {{ $b->project->name }}
                            @if ($b->unit)
                                <span class="block text-emerald-600 font-bold font-mono">Unit: {{ $b->unit->code }} ({{ ucfirst($b->unit->category) }})</span>
                            @else
                                <span class="block text-slate-400 italic">Per Proyek Lahan</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="capitalize text-[10px] font-bold px-2.5 py-0.5 rounded-md border {{ $b->booking_type === 'unit' ? 'bg-teal-50 text-teal-800 border-teal-200' : 'bg-indigo-50 text-indigo-800 border-indigo-200' }}">
                                {{ $b->booking_type === 'unit' ? 'Per Unit' : 'Per Proyek' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right font-mono font-extrabold text-teal-700 text-sm">
                            Rp {{ number_format($b->booking_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if ($b->status === 'active')
                                <span class="status-booked">Active / Menunggu ACC</span>
                            @elseif ($b->status === 'converted')
                                <span class="status-terjual">DP ACC</span>
                            @elseif ($b->status === 'refunded')
                                <span class="status-batal">DP Refund / Batal</span>
                            @else
                                <span class="status-batal">Batal</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                @if ($b->receipt_photo_path)
                                    <button wire:click="openImageModal('{{ asset('storage/' . $b->receipt_photo_path) }}', 'Foto Struk Resi Booking - {{ $b->buyer_name }}')" class="btn-action-pdf bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100" title="Buka Foto Struk Bukti Transfer / DP">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Foto Struk</span>
                                    </button>
                                @endif
                                <button wire:click="openViewerModal('pdf', '{{ route('bookings.receipt', $b->id) }}', 'Pratinjau Invoice Booking - {{ $b->buyer_name }}')" class="btn-action-pdf" title="Lihat PDF Invoice Booking">
                                    <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span>PDF</span>
                                </button>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                @if($b->status === 'active' && auth()->user()->isFounder())
                                    <button type="button" @click="confirmModalAction({
                                        title: 'Persetujuan Tanda Jadi / DP',
                                        message: 'Konfirmasi persetujuan Tanda Jadi untuk {{ $b->buyer_name }}? Arus kas masuk akan dicatat secara otomatis.',
                                        confirmText: 'Setujui DP',
                                        btnClass: 'px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.approveDp({{ $b->id }})
                                    })" class="btn-action-payment" title="Setujui Tanda Jadi / DP">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>Setujui DP</span>
                                    </button>
                                    <button type="button" @click="confirmModalAction({
                                        title: 'Penolakan Booking Fee',
                                        message: 'Yakin ingin MENOLAK booking untuk {{ $b->buyer_name }}? Status unit akan dikembalikan menjadi tersedia.',
                                        confirmText: 'Tolak Booking',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.rejectDp({{ $b->id }})
                                    })" class="btn-action-delete" title="Tolak Booking">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        <span>Tolak</span>
                                    </button>
                                @endif

                                @if($b->status === 'converted' && auth()->user()->isFounder())
                                    <button type="button" @click="confirmModalAction({
                                        title: 'Pembatalan & Refund DP',
                                        message: 'Yakin ingin MEMBATALKAN / REFUND DP untuk {{ $b->buyer_name }}? Pengeluaran kas refund akan dicatat dan status unit akan dikembalikan menjadi tersedia.',
                                        confirmText: 'Refund DP',
                                        btnClass: 'px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.cancelApprovedDp({{ $b->id }})
                                    })" class="btn-action-convert" title="Batalkan / Refund DP">
                                        <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                        <span>Batalkan & Refund</span>
                                    </button>
                                @endif

                                @if(auth()->user()->isFounder())
                                    <button wire:click="editBooking({{ $b->id }})" class="btn-action-edit" title="Edit Data Booking">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </button>
                                    <button type="button" @click="confirmModalAction({
                                        title: 'Hapus Data Booking',
                                        message: 'Yakin ingin MENGHAPUS data booking atas nama {{ $b->buyer_name }}? Transaksi arus kas terkait akan dihapus dan unit akan dikembalikan menjadi tersedia.',
                                        confirmText: 'Hapus Booking',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteBooking({{ $b->id }})
                                    })" class="btn-action-delete" title="Hapus Data Booking">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus</span>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            <p class="font-semibold text-slate-600">Belum Ada Transaksi Booking Fee atau Tanda Jadi</p>
                            <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Catat Booking / Tanda Jadi Baru" di atas untuk menambahkan penerimaan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3.5 border-t border-slate-100">
        {{ $bookings->links() }}
    </div>
</div>
