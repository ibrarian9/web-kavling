<div class="space-y-6">

    <!-- Header & Single-row Toolbar Section -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Data Unit Kavling & Rumah</h2>
            <p class="text-slate-500 text-xs mt-0.5">Penetapan HPP, kalkulasi otomatis kelebihan tanah (m²), penugasan mandor/tukang, dan booking unit</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Filter Proyek -->
            <select wire:model.live="project_id" class="input-clean text-xs font-semibold text-slate-700 py-2.5">
                <option value="">Semua Proyek Properti</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>

            <button wire:click="openModal" class="btn-primary whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Unit Baru</span>
            </button>
        </div>
    </div>

    <!-- Units Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($units as $unit)
            <div class="card-clean p-5 space-y-4 flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <!-- Top Badge & Code -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-extrabold text-slate-900 font-mono">{{ $unit->code }}</span>
                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-md {{ $unit->category === 'rumah' ? 'bg-purple-50 text-purple-800 border border-purple-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                {{ ucfirst($unit->category ?? $unit->type) }}
                            </span>
                        </div>

                        <!-- Status Badge -->
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

                    <p class="text-xs font-semibold text-slate-600 mt-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        {{ $unit->project->name }}
                    </p>

                    <!-- Mandor / Tukang Bertugas Badge (Req #6) -->
                    <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                        @forelse($unit->activeAssignments as $assignment)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-blue-50 text-blue-800 border border-blue-200/80">
                                <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>{{ $assignment->worker->name }} ({{ ucfirst($assignment->worker->type) }})</span>
                            </span>
                        @empty
                            <span class="text-[10px] text-slate-400 italic">Belum ada penugasan pekerja</span>
                        @endforelse
                    </div>

                    <!-- Land & Building Dimensions Info Box -->
                    <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-3.5 mt-3.5 text-xs space-y-1.5">
                        <div class="flex justify-between text-slate-600">
                            <span>Dimensi Tanah (P x L):</span>
                            <span class="font-mono font-medium text-slate-800">{{ $unit->land_length }}m &times; {{ $unit->land_width }}m</span>
                        </div>
                        <div class="flex justify-between text-slate-700 font-semibold">
                            <span>Luas Tanah Total:</span>
                            <span class="font-mono text-slate-900 font-bold">{{ number_format($unit->land_area, 0, ',', '.') }} m²</span>
                        </div>

                        @if($unit->category === 'rumah' && $unit->building_area)
                            <div class="flex justify-between text-purple-700 font-semibold pt-1 border-t border-slate-200/80">
                                <span>Luas Bangunan:</span>
                                <span class="font-mono font-bold">{{ number_format($unit->building_area, 0, ',', '.') }} m² ({{ $unit->floors_count ?? 1 }} Lt)</span>
                            </div>
                        @endif

                        @if($unit->excess_land_area > 0)
                            <div class="flex justify-between text-amber-700 font-medium pt-1.5 border-t border-slate-200/80">
                                <span>Kelebihan Luas:</span>
                                <span class="font-mono font-bold">+{{ number_format($unit->excess_land_area, 0, ',', '.') }} m² (+Rp {{ number_format($unit->excess_cost, 0, ',', '.') }})</span>
                            </div>
                        @else
                            <div class="text-[11px] text-slate-400 pt-1 border-t border-slate-200/80">
                                Ukuran standar proyek ({{ number_format($unit->project->standard_land_area, 0, ',', '.') }} m²)
                            </div>
                        @endif
                    </div>

                    <!-- Price Info (Req #1: Indonesian currency format) -->
                    <div class="mt-4 pt-3 border-t border-slate-100 space-y-1.5">
                        <div class="flex justify-between items-baseline text-xs">
                            <span class="text-slate-500 font-medium">HPP Pokok:</span>
                            <span class="font-mono font-bold text-slate-800">
                                {{ $unit->hpp ? 'Rp ' . number_format($unit->hpp, 0, ',', '.') : 'Belum Diset' }}
                            </span>
                        </div>

                        @if($unit->final_selling_price)
                            <div class="flex justify-between items-baseline text-xs text-emerald-700 font-bold">
                                <span>Harga Jual Final:</span>
                                <span class="font-mono">Rp {{ number_format($unit->final_selling_price, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Footer Actions & Booking Button (Req #2) -->
                <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('units.show', $unit->id) }}" class="btn-secondary text-xs px-2.5 py-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Detail</span>
                        </a>
                        <button wire:click="editUnit({{ $unit->id }})" class="btn-secondary text-xs px-2.5 py-1.5">
                            Edit
                        </button>
                    </div>

                    <div class="flex items-center gap-1.5">
                        @if(in_array($unit->status, ['tersedia', 'disetujui']))
                            <button wire:click="openBookingModal({{ $unit->id }})" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-3 py-1.5 rounded-lg transition shadow-sm flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Booking Unit</span>
                            </button>
                        @endif

                        @if(auth()->user()->isMarketing() && $unit->status === 'tersedia')
                            <a href="{{ route('proposals.index', ['create_unit_id' => $unit->id]) }}" class="btn-primary text-xs px-2.5 py-1.5">
                                <span>Ajukan Harga</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full card-clean p-12 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <p class="font-semibold text-slate-600">Belum Ada Unit Kavling Didaftarkan</p>
                <p class="text-xs text-slate-400 mt-1">Klik tombol "Tambah Unit Baru" di atas untuk menambahkan stok kavling/rumah.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $units->links() }}
    </div>

    <!-- Modal Form Unit -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">
                        {{ $editingUnitId ? 'Edit Data Unit' : 'Tambah Unit Kavling / Rumah' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveUnit" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Pilih Proyek</label>
                        <select wire:model.live="selected_project_id" class="input-clean w-full font-semibold">
                            <option value="">-- Pilih Proyek --</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Standar: {{ number_format($p->standard_land_area, 0, ',', '.') }}m²)</option>
                            @endforeach
                        </select>
                        @error('selected_project_id') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Kode Unit (e.g. A-01)</label>
                            <input type="text" wire:model="code" placeholder="A-01" class="input-clean w-full font-bold font-mono">
                            @error('code') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Kategori Properti</label>
                            <select wire:model.live="category" class="input-clean w-full font-semibold">
                                <option value="kavling">Kavling Tanah</option>
                                <option value="rumah">Bangunan Rumah</option>
                            </select>
                            @error('category') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if($category === 'rumah')
                        <div class="bg-purple-50/70 border border-purple-200/80 rounded-xl p-3.5 space-y-3">
                            <p class="font-bold text-[11px] uppercase tracking-wider text-purple-900">Spesifikasi Bangunan Rumah:</p>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block font-semibold text-purple-800 mb-1 uppercase tracking-wider">Luas Bangunan (m²)</label>
                                    <input type="number" step="0.01" wire:model="building_area" placeholder="36" class="input-clean w-full font-mono bg-white">
                                </div>
                                <div>
                                    <label class="block font-semibold text-purple-800 mb-1 uppercase tracking-wider">Jumlah Lantai</label>
                                    <input type="number" min="1" wire:model="floors_count" placeholder="1" class="input-clean w-full font-mono bg-white">
                                </div>
                            </div>

                            <div>
                                <label class="block font-semibold text-purple-800 mb-1 uppercase tracking-wider">Deskripsi Spesifikasi Fisik</label>
                                <textarea wire:model="specifications" rows="2" placeholder="Pondasi batu kali, granit 60x60, atap baja ringan, dll." class="input-clean w-full bg-white"></textarea>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Panjang (m)</label>
                            <input type="number" step="0.01" wire:model.live="land_length" class="input-clean w-full font-mono">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Lebar (m)</label>
                            <input type="number" step="0.01" wire:model.live="land_width" class="input-clean w-full font-mono">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Luas Tanah (m²)</label>
                            <input type="number" step="0.01" wire:model.live="land_area" class="input-clean w-full font-bold font-mono">
                            @error('land_area') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if($selected_project_id)
                        <div class="bg-emerald-50 border border-emerald-200/80 rounded-xl p-3.5 space-y-1.5 text-emerald-900">
                            <p class="font-bold text-[11px] uppercase tracking-wider text-emerald-800">Kalkulasi Otomatis Kelebihan Tanah:</p>
                            <div class="flex justify-between text-xs">
                                <span>Kelebihan Luas:</span>
                                <span class="font-mono font-bold">{{ number_format($previewExcessArea, 0, ',', '.') }} m²</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span>Biaya Kelebihan Tanah:</span>
                                <span class="font-mono font-bold">Rp {{ number_format($previewExcessCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xs font-bold pt-1.5 border-t border-emerald-200/80">
                                <span>Rekomendasi HPP Final:</span>
                                <span class="font-mono text-emerald-700">Rp {{ number_format($previewRecommendedHpp, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">HPP Pokok Final (Rp)</label>
                        <x-currency-input model="hpp" placeholder="Rp {{ number_format($previewRecommendedHpp, 0, ',', '.') }}" class="input-clean w-full font-bold font-mono text-slate-900" />
                        <p class="text-[10px] text-slate-500 mt-1">*HPP dapat disesuaikan ulang oleh bagian Finance.</p>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="closeModal" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Unit</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Form Booking Unit Langsung (Req #2) -->
    @if($showBookingModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Booking Unit {{ $bookingUnitCode }}</h3>
                        <p class="text-slate-500 text-[11px]">Pencatatan booking unit & bukti DP langsung di dalam sistem</p>
                    </div>
                    <button wire:click="$set('showBookingModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveBooking" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nama Pembeli</label>
                        <input type="text" wire:model="buyer_name" required placeholder="Contoh: Bpk. H. Hendra Wijaya" class="input-clean w-full font-bold">
                        @error('buyer_name') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nomor HP / WhatsApp Pembeli</label>
                        <input type="text" wire:model="buyer_phone" required placeholder="081234567890" class="input-clean w-full font-mono">
                        @error('buyer_phone') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nominal Tanda Jadi / Booking Fee (Rp)</label>
                        <x-currency-input model="booking_amount" class="input-clean w-full font-mono font-bold text-teal-700 text-sm" />
                        @error('booking_amount') <span class="text-rose-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>



                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Catatan Pembayaran & Bukti DP</label>
                        <textarea wire:model="booking_notes" rows="2" placeholder="Informasi bukti transfer DP, skema pelunasan, dll." class="input-clean w-full"></textarea>
                    </div>

                    <div class="p-3 bg-blue-50 border border-blue-200/80 rounded-xl text-blue-900 text-[11px] space-y-1">
                        <span class="font-bold">Info Verifikasi:</span> Setelah disimpan, unit otomatis menjadi status <strong>Booked</strong> dan data transaksi akan dikirim ke menu Booking untuk diverifikasi & disetujui DP-nya oleh Finance / Founder.
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
