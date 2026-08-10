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
                            <option value="">Pilih Proyek</option>
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
                    <button type="submit" wire:loading.attr="disabled" class="btn-primary bg-purple-700 hover:bg-purple-800 px-6 py-2.5 rounded-xl shadow-md font-bold text-center flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="save" class="w-4 h-4 animate-spin text-white shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span wire:loading.remove wire:target="save">Simpan Unit Terjual & Lunas</span>
                        <span wire:loading wire:target="save">Menyimpan Unit & SPP...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
