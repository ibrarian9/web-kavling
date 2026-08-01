<div class="space-y-6">
    <!-- Header & Single-row Toolbar -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Booking Fee & DP (Proyek & Unit)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Modul pencatatan pemesanan, verifikasi DP langsung di sistem, dan konfirmasi penjualan unit</p>
        </div>
        @if(!auth()->user()->isPengawasProject())
            <button wire:click="create" class="btn-primary whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Catat Booking / DP Baru</span>
            </button>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-200/80 rounded-2xl text-rose-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- KPI Summary Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="kpi-card-teal">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Booking Fee Aktif</span>
                <div class="p-2.5 rounded-xl bg-teal-50 text-teal-600 border border-teal-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-teal-600 font-mono tracking-tight mt-2">Rp {{ number_format($totalBookingAmount, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Akumulasi tanda jadi pemesanan unit/proyek</p>
        </div>

        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total DP (Uang Muka) Aktif</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600 font-mono tracking-tight mt-2">Rp {{ number_format($totalDpAmount, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Total pembayaran uang muka yang disetujui</p>
        </div>
    </div>

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
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
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
                                        <button wire:click="approveDp({{ $b->id }})" wire:confirm="Konfirmasi persetujuan Tanda Jadi untuk {{ $b->buyer_name }}? Arus kas masuk akan dicatat secara otomatis." class="btn-action-payment" title="Setujui Tanda Jadi / DP">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <span>Setujui DP</span>
                                        </button>
                                        <button wire:click="rejectDp({{ $b->id }})" wire:confirm="Yakin ingin MENOLAK booking untuk {{ $b->buyer_name }}? Status unit akan dikembalikan menjadi tersedia." class="btn-action-delete" title="Tolak Booking">
                                            <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            <span>Tolak</span>
                                        </button>
                                    @endif

                                    @if($b->status === 'converted' && auth()->user()->isFounder())
                                        <button wire:click="cancelApprovedDp({{ $b->id }})" wire:confirm="Yakin ingin MEMBATALKAN / REFUND DP untuk {{ $b->buyer_name }}? Pengeluaran kas refund akan dicatat dan status unit akan dikembalikan menjadi tersedia." class="btn-action-convert" title="Batalkan / Refund DP">
                                            <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                            <span>Batalkan & Refund</span>
                                        </button>
                                    @endif

                                    @if(auth()->user()->isFounder())
                                        <button wire:click="editBooking({{ $b->id }})" class="btn-action-edit" title="Edit Data Booking">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Edit</span>
                                        </button>
                                        <button wire:click="deleteBooking({{ $b->id }})" wire:confirm="Yakin ingin MENGHAPUS data booking atas nama {{ $b->buyer_name }}? Transaksi arus kas terkait akan dihapus dan unit akan dikembalikan menjadi tersedia." class="btn-action-delete" title="Hapus Data Booking">
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

    <!-- Modal Form Catat Booking -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-3 sm:p-6">
            <div class="bg-white rounded-2xl sm:rounded-3xl max-w-lg w-full p-4 sm:p-6 shadow-2xl space-y-4 border border-slate-200/80 max-h-[88vh] sm:max-h-[85vh] flex flex-col my-auto">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                    <h3 class="text-base font-bold text-slate-900">
                        {{ $editingBookingId ? 'Edit Data Booking Pemesanan' : 'Catat Tanda Jadi / Booking Fee Pemesanan' }}
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <div class="space-y-4 text-xs flex-1 overflow-y-auto pr-1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 uppercase mb-1">Perumahan / Proyek</label>
                            <select wire:model.live="project_id" class="input-clean w-full">
                                <option value="">-- Pilih Proyek --</option>
                                @foreach ($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            @error('project_id') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 uppercase mb-1">Tingkat Booking</label>
                            <select wire:model.live="booking_type" class="input-clean w-full">
                                <option value="unit">Per Unit Spesifik</option>
                                <option value="project">Per Proyek Kolektif</option>
                            </select>
                        </div>
                    </div>

                    @if ($booking_type === 'unit')
                        <div>
                            <label class="block font-semibold text-slate-700 uppercase mb-1">Unit Spesifik</label>
                            <select wire:model="unit_id" class="input-clean w-full font-semibold" {{ !$project_id ? 'disabled' : '' }}>
                                <option value="">-- Pilih Unit --</option>
                                @foreach ($availableUnits as $u)
                                    <option value="{{ $u->id }}">Unit Kode: {{ $u->code }} ({{ ucfirst($u->category) }} - {{ $u->status }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 uppercase mb-1">Nama Calon Pembeli</label>
                            <input type="text" wire:model="buyer_name" class="input-clean w-full font-bold">
                            @error('buyer_name') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 uppercase mb-1">No. Kontak / WhatsApp</label>
                            <input type="text" wire:model="buyer_phone" placeholder="08123456789" class="input-clean w-full font-mono">
                            @error('buyer_phone') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Nominal Tanda Jadi / Booking Fee (Rp)</label>
                        <x-currency-input model="booking_amount" class="input-clean w-full font-mono font-bold text-teal-700 text-sm" />
                        @error('booking_amount') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Foto Struk / Bukti Transfer <span class="text-amber-600 font-bold lowercase text-[10px] bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">(Opsional, Maks. 2MB)</span></label>
                        <input type="file" wire:model="receipt_photo" accept="image/*,.heic,.heif,.pdf" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition cursor-pointer">
                        <div wire:loading wire:target="receipt_photo" class="text-[11px] text-amber-600 font-semibold mt-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Mengunggah foto resi...</span>
                        </div>
                        @error('receipt_photo') <span class="text-[10px] text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 uppercase mb-1 text-[11px] sm:text-xs">Tgl Pembayaran</label>
                            <input type="date" wire:model="booking_date" class="input-clean w-full font-mono">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 uppercase mb-1 text-[11px] sm:text-xs">Tgl Jatuh Tempo Booking</label>
                            <input type="date" wire:model="expiry_date" class="input-clean w-full font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Catatan Tambahan</label>
                        <textarea wire:model="notes" rows="2" placeholder="Catatan pembayaran tanda jadi..." class="input-clean w-full"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100 shrink-0">
                    <button wire:click="$set('showModal', false)" type="button" class="btn-secondary">Batal</button>
                    <button wire:click="save" type="button" class="btn-primary">{{ $editingBookingId ? 'Simpan Perubahan' : 'Simpan Pemesanan' }}</button>
                </div>
            </div>
        </div>
    @endif

    <!-- PDF Viewer Modal (Resi Booking PDF) -->
    @if($showViewerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-4xl w-full p-6 shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        {{ $viewerTitle }}
                    </h3>
                    <button wire:click="closeViewerModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
                </div>
                <div class="flex-1 overflow-hidden min-h-[500px]">
                    <iframe src="{{ $viewerUrl }}" class="w-full h-full rounded-2xl border border-slate-200 min-h-[500px]"></iframe>
                </div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                    <a href="{{ $viewerUrl }}" target="_blank" class="btn-secondary text-xs px-4 py-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>Buka di Tab Baru / Cetak Direct</span>
                    </a>
                    <button wire:click="closeViewerModal" class="btn-primary bg-slate-800 hover:bg-slate-900 text-xs px-5 py-2">Tutup Pratinjau</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Foto Struk Resi Viewer Modal -->
    @if($showImageModal ?? false)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $imageModalTitle }}
                    </h3>
                    <button wire:click="closeImageModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
                </div>
                <div class="flex-1 overflow-auto flex items-center justify-center p-2 bg-slate-50 rounded-2xl border border-slate-200 min-h-[300px]">
                    <img src="{{ $imageModalUrl }}" alt="Foto Struk Resi" class="max-h-[600px] w-auto max-w-full object-contain rounded-xl shadow-sm">
                </div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                    <a href="{{ $imageModalUrl }}" target="_blank" class="btn-secondary text-xs px-4 py-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>Buka Gambar Asli</span>
                    </a>
                    <button wire:click="closeImageModal" class="btn-primary bg-slate-800 hover:bg-slate-900 text-xs px-5 py-2">Tutup</button>
                </div>
            </div>
        </div>
    @endif
</div>
