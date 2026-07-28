<div class="space-y-6">

    <!-- Header Section -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-md bg-purple-100 text-purple-800 text-[10px] font-extrabold tracking-wider uppercase border border-purple-200">Khusus Founder</span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Input Penjualan Masa Lalu (Terjual & Lunas)</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1">Form khusus pendaftaran unit kavling/rumah yang sudah terjual & lunas 100% sebelum sistem SIM Properti dibuat.</p>
        </div>

        <button wire:click="openCreateModal" class="btn-primary bg-purple-700 hover:bg-purple-800 text-white whitespace-nowrap shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>+ Catat Unit Terjual Lunas (Masa Lalu)</span>
        </button>
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-200/80 rounded-2xl text-rose-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Highlight Information Banner -->
    <div class="bg-amber-50/80 border border-amber-200 rounded-2xl p-4 text-amber-900 text-xs flex items-start gap-3 shadow-sm">
        <div class="p-2 bg-amber-100 rounded-xl text-amber-700 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="space-y-1">
            <h4 class="font-bold text-amber-950 text-sm">Informasi Penting Pencatatan Unit Masa Lalu (Historis):</h4>
            <p class="text-slate-600 leading-relaxed">
                Form ini secara otomatis akan menandai unit sebagai <strong>STATUS TERJUAL</strong>, membuat data pemesanan (Booking ACC), menerbitkan dokumen <strong>SPP PDF Resmi Lunas</strong>, serta mencatat status skema cicilan/pembayaran menjadi <strong>LUNAS 100% (Sisa Rp 0)</strong>. Pengisian ini menjamin seluruh statistik dashboard & rekap data unit lengkap tanpa merusak siklus transaksi aktif.
            </p>
        </div>
    </div>

    <!-- Table Card List Legacy Sold Units -->
    <div class="card-clean overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Daftar Unit Terjual & Lunas (Termasuk Pencatatan Masa Lalu)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Kode Unit & Proyek</th>
                        <th class="px-5 py-3.5">Kategori & Ukuran</th>
                        <th class="px-5 py-3.5">Nama Pembeli</th>
                        <th class="px-5 py-3.5 text-right">Harga HPP</th>
                        <th class="px-5 py-3.5 text-right">Harga Jual Final</th>
                        <th class="px-5 py-3.5 text-center">Status Pembayaran</th>
                        <th class="px-5 py-3.5 text-right">Aksi & Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($legacyUnits as $unit)
                        <tr class="hover:bg-slate-50/60 transition duration-150">
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-900 font-mono text-sm block">{{ $unit->code }}</span>
                                <span class="text-slate-400 text-[11px] font-medium block">{{ $unit->project->name }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-800 capitalize">{{ $unit->category }} - {{ $unit->type }}</span>
                                <span class="text-slate-400 text-[11px] block font-mono">Luas: {{ number_format($unit->land_area, 0) }} m² ({{ $unit->land_width }}x{{ $unit->land_length }}m)</span>
                            </td>
                            <td class="px-5 py-4">
                                @if($unit->officialDocument)
                                    <span class="font-bold text-slate-900 block">{{ $unit->officialDocument->buyer_name }}</span>
                                    <span class="text-slate-400 text-[11px] font-mono block">{{ $unit->officialDocument->buyer_contact }}</span>
                                @else
                                    <span class="text-slate-400 italic">Terjual</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-medium text-slate-600">
                                Rp {{ number_format($unit->hpp, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-extrabold text-emerald-700 text-sm">
                                Rp {{ number_format($unit->final_selling_price ?? $unit->hpp, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    LUNAS 100%
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('units.show', $unit->id) }}" class="btn-secondary text-[11px] px-2.5 py-1.5 inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Detail Unit</span>
                                </a>

                                @if($unit->officialDocument)
                                    <button wire:click="openViewerModal('pdf', '{{ route('documents.stream', $unit->officialDocument->id) }}', 'Pratinjau Surat SPP Lunas - {{ $unit->code }}')" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-[11px] font-bold shadow inline-flex items-center gap-1 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>SPP PDF</span>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="font-semibold text-slate-600">Belum Ada Data Unit Terjual</p>
                                <p class="text-xs text-slate-400 mt-1">Gunakan tombol "+ Catat Unit Terjual Lunas (Masa Lalu)" untuk mendaftarkan penjualan historis.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100">
            {{ $legacyUnits->links() }}
        </div>
    </div>

    <!-- Modal Form Catat Unit Masa Lalu (Historis Lunas - Responsif Mobile & Desktop) -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-2xl sm:rounded-3xl max-w-xl md:max-w-2xl w-full p-4 sm:p-8 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col border border-slate-200/80">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 sm:pb-4 shrink-0">
                    <div>
                        <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Pendaftaran Unit Terjual & Lunas (Masa Lalu)</span>
                        </h3>
                        <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5">Khusus pendaftaran transaksi unit yang telah selesai sepenuhnya sebelum aplikasi aktif</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="p-1.5 sm:p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4 text-xs flex-1 overflow-y-auto pr-1">
                    <!-- Step 1: Lokasi Proyek & Kode Unit -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Perumahan / Proyek *</label>
                            <select wire:model="project_id" required class="input-clean w-full font-bold">
                                <option value="">-- Pilih Proyek --</option>
                                @foreach ($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            @error('project_id') <span class="text-[10px] text-rose-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Kode Unit Kavling / Rumah *</label>
                            <input type="text" wire:model="code" required placeholder="Contoh: A-01 / B-12" class="input-clean w-full font-mono font-bold uppercase">
                            @error('code') <span class="text-[10px] text-rose-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Step 2: Kategori & Tipe Unit -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Kategori Unit *</label>
                            <select wire:model.live="category" required class="input-clean w-full font-bold">
                                <option value="kavling">Kavling Tanah Lahan</option>
                                <option value="rumah">Rumah Siap Huni</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Tipe / Nama Spesifikasi Unit *</label>
                            <input type="text" wire:model="type" required placeholder="Contoh: Kavling Standar / Rumah Type 36/90" class="input-clean w-full font-semibold">
                        </div>
                    </div>

                    <!-- Step 3: Ukuran Fisik Lahan & Bangunan -->
                    <div class="grid grid-cols-3 gap-2 sm:gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1 text-[10px] sm:text-xs">Lebar (m) *</label>
                            <input type="number" step="0.01" wire:model.live="land_width" required class="input-clean w-full font-mono font-bold">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1 text-[10px] sm:text-xs">Panjang (m) *</label>
                            <input type="number" step="0.01" wire:model.live="land_length" required class="input-clean w-full font-mono font-bold">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1 text-[10px] sm:text-xs">Luas (m²) *</label>
                            <input type="number" step="0.01" wire:model="land_area" readonly class="input-clean w-full font-mono font-bold bg-slate-100 text-slate-800">
                        </div>
                    </div>

                    @if($category === 'rumah')
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Luas Bangunan (m²)</label>
                            <input type="number" step="0.01" wire:model="building_area" placeholder="36 / 45 / 54" class="input-clean w-full font-mono font-bold">
                        </div>
                    @endif

                    <!-- Step 4: Nilai Keuangan HPP & Harga Jual Deal -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Harga Pokok (HPP) (Rp) *</label>
                            <x-currency-input model="hpp" class="input-clean w-full font-mono font-bold text-slate-900 text-sm" placeholder="Contoh: 100.000.000" />
                            @error('hpp') <span class="text-[10px] text-rose-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Total Harga Jual Final / Deal (Rp) *</label>
                            <x-currency-input model="final_selling_price" class="input-clean w-full font-mono font-bold text-emerald-700 text-sm sm:text-base" placeholder="Contoh: 150.000.000" />
                            @error('final_selling_price') <span class="text-[10px] text-rose-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Step 5: Data Pembeli & Transaksi Lunas -->
                    <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-3.5 sm:p-4 space-y-3">
                        <p class="font-bold text-slate-800 text-xs uppercase tracking-wider">Identitas Pembeli & Transaksi Lunas:</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Nama Pembeli *</label>
                                <input type="text" wire:model="buyer_name" required placeholder="Bapak / Ibu Pembeli" class="input-clean w-full font-bold">
                                @error('buyer_name') <span class="text-[10px] text-rose-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">No. WhatsApp / HP *</label>
                                <input type="text" wire:model="buyer_phone" required placeholder="08123456789" class="input-clean w-full font-mono">
                                @error('buyer_phone') <span class="text-[10px] text-rose-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Tanggal Penjualan / Pelunasan *</label>
                                <input type="date" wire:model="sale_date" required class="input-clean w-full font-mono">
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1">Metode Pelunasan</label>
                                <select wire:model="payment_method" class="input-clean w-full font-semibold">
                                    <option value="Tunai / Cash Lunas">Tunai / Cash Lunas</option>
                                    <option value="Transfer Bank Lunas">Transfer Bank Lunas</option>
                                    <option value="Cicilan Completed">Cicilan Bertahap Lunas</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Alamat Lengkap Pembeli</label>
                            <input type="text" wire:model="buyer_address" placeholder="Jl. Raya No. 123, Pekanbaru" class="input-clean w-full text-xs">
                        </div>
                    </div>

                    <!-- Step 6: Opsi Arus Kas & Catatan -->
                    <div class="p-3.5 bg-purple-50/60 border border-purple-200/80 rounded-xl space-y-2">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" wire:model="record_cashflow" class="w-4 h-4 rounded text-purple-600 focus:ring-purple-500">
                            <span class="font-bold text-purple-950 text-xs">Catat juga nominal ini ke Jurnal Arus Kas Masuk Sistem?</span>
                        </label>
                        <p class="text-[11px] text-slate-500 pl-6">
                            *Biarkan <strong>tidak dicentang</strong> jika keuangan penjualan ini telah berlalu dan tidak ingin mempengaruhi statistik kas berjalan saat ini.
                        </p>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Catatan Tambahan (Opsional)</label>
                        <textarea wire:model="notes" rows="2" placeholder="Catatan berkas fisik / kwitansi manual..." class="input-clean w-full text-xs"></textarea>
                    </div>

                    <!-- Footer Action -->
                    <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('showModal', false)" class="btn-secondary px-5 py-2.5 rounded-xl">Batal</button>
                        <button type="submit" class="btn-primary bg-purple-700 hover:bg-purple-800 px-6 py-2.5 rounded-xl shadow-md font-bold text-center">Simpan Unit Terjual & Lunas</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- PDF Viewer Modal -->
    @if($showViewerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-4xl w-full p-6 shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        {{ $viewerTitle }}
                    </h3>
                    <button wire:click="closeViewerModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
                </div>
                <div class="flex-1 overflow-hidden min-h-[500px]">
                    <iframe src="{{ $viewerUrl }}" class="w-full h-full rounded-2xl border border-slate-200 min-h-[500px]"></iframe>
                </div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                    <a href="{{ $viewerUrl }}" target="_blank" class="btn-secondary text-xs px-4 py-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>Buka di Tab Baru / Cetak Direct</span>
                    </a>
                    <button wire:click="closeViewerModal" class="btn-primary bg-slate-800 hover:bg-slate-900 text-xs px-5 py-2">Tutup Pratinjau</button>
                </div>
            </div>
        </div>
    @endif
</div>
