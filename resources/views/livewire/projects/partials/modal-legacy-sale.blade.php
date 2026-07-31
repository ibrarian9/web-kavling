<!-- Modal Form Input Penjualan Lalu (Historis Terjual & Lunas 100% - Responsif Mobile & Desktop) -->
@if($showLegacyModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200 rounded-2xl sm:rounded-3xl max-w-xl md:max-w-2xl w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200">HISTORIS LUNAS</span>
                        <h3 class="font-extrabold text-slate-900 text-base sm:text-lg tracking-tight">Input Penjualan Lalu</h3>
                    </div>
                    <p class="text-slate-500 text-xs mt-0.5">Proyek: <strong class="text-slate-800">{{ $project->name }}</strong></p>
                </div>
                <button wire:click="closeLegacyModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
            </div>

            <form wire:submit.prevent="submitLegacySale" class="space-y-4 text-xs flex-1 overflow-y-auto pr-1">
                <!-- Section 1: Spesifikasi Unit -->
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-3">
                    <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>1. Spesifikasi & Identitas Unit</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Kode Unit <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="legacy_code" placeholder="Misal: A-01" class="input-clean w-full font-bold uppercase">
                            @error('legacy_code') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Kategori Unit</label>
                            <select wire:model.live="legacy_category" class="input-clean w-full font-semibold">
                                <option value="kavling">Kavling Tanah</option>
                                <option value="rumah">Kavling + Rumah</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Tipe Unit</label>
                            <input type="text" wire:model="legacy_type" placeholder="Kavling Standar / Rumah Tipe 36" class="input-clean w-full">
                        </div>

                        @if($legacy_category === 'rumah')
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Luas Bangunan (m²)</label>
                                <input type="number" step="0.1" wire:model="legacy_building_area" placeholder="Misal: 36" class="input-clean w-full font-mono">
                            </div>
                        @endif
                    </div>

                    <!-- Dimensi Lahan -->
                    <div class="grid grid-cols-3 gap-2 pt-1">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 text-[10px]">Lebar (m)</label>
                            <input type="number" step="0.1" wire:model.live="legacy_land_width" class="input-clean w-full font-mono text-center">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 text-[10px]">Panjang (m)</label>
                            <input type="number" step="0.1" wire:model.live="legacy_land_length" class="input-clean w-full font-mono text-center">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 text-[10px]">Luas Tanah</label>
                            <div class="input-clean w-full font-mono font-bold text-center bg-slate-100 text-slate-800 py-2">
                                {{ $legacy_land_area }} m²
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Nilai Finansial -->
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-3">
                    <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>2. Nilai Finansial Penjualan</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Harga Pokok (HPP Unit) <span class="text-rose-500">*</span></label>
                            <x-currency-input model="legacy_hpp" class="input-clean w-full font-mono font-bold text-slate-800" />
                            @error('legacy_hpp') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Harga Jual Deal (Lunas) <span class="text-rose-500">*</span></label>
                            <x-currency-input model="legacy_final_selling_price" class="input-clean w-full font-mono font-bold text-emerald-700" />
                            @error('legacy_final_selling_price') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Data Pembeli & Transaksi -->
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-3">
                    <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[11px] flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>3. Data Pembeli & Pembayaran</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nama Pembeli <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="legacy_buyer_name" placeholder="Misal: Bapak H. Ahmad" class="input-clean w-full font-bold">
                            @error('legacy_buyer_name') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">No. Kontak / WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="legacy_buyer_phone" placeholder="08123456789" class="input-clean w-full font-mono">
                            @error('legacy_buyer_phone') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Tgl Transaksi Masa Lalu <span class="text-rose-500">*</span></label>
                            <input type="date" wire:model="legacy_sale_date" class="input-clean w-full font-mono">
                            @error('legacy_sale_date') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Metode Pembayaran Historis</label>
                            <select wire:model="legacy_payment_method" class="input-clean w-full font-semibold">
                                <option value="Tunai / Cash Lunas">Tunai / Cash Lunas</option>
                                <option value="Transfer Bank Lunas">Transfer Bank Lunas</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Alamat Pembeli</label>
                        <input type="text" wire:model="legacy_buyer_address" placeholder="Alamat domisili pembeli..." class="input-clean w-full">
                    </div>

                    <div class="pt-1">
                        <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-emerald-500 transition">
                            <input type="checkbox" wire:model="legacy_record_cashflow" class="rounded text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                            <div>
                                <span class="font-bold text-slate-800 text-xs block">Catat Penerimaan ke Arus Kas Proyek?</span>
                                <span class="text-[10px] text-slate-500 block">Centang jika dana penjualan ini ingin dicatat sebagai Arus Kas Masuk pada tanggal transaksi di atas.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
                    <textarea wire:model="legacy_notes" rows="2" placeholder="Catatan transaksi penjualan masa lalu..." class="input-clean w-full"></textarea>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="closeLegacyModal" class="btn-secondary py-2.5 px-4 rounded-xl text-xs">Batal</button>
                    <button type="submit" class="btn-primary bg-purple-700 hover:bg-purple-800 py-2.5 px-5 rounded-xl shadow-md text-xs text-center">Simpan Penjualan Masa Lalu</button>
                </div>
            </form>
        </div>
    </div>
@endif
