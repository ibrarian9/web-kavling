<!-- Modal Form Edit Unit Spesifikasi & Status (Founder & Finance) -->
@if($showEditUnitModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Edit Spesifikasi & Data Unit {{ $edit_unit_code }}</h3>
                    <p class="text-slate-500 text-[11px]">Pembaruan spesifikasi fisik, harga final, dan status unit</p>
                </div>
                <button wire:click="$set('showEditUnitModal', false)" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form wire:submit.prevent="saveEditUnit" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Kode Unit <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="edit_unit_code" required class="input-clean w-full font-mono font-bold text-xs">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Kategori Unit <span class="text-rose-500">*</span></label>
                        <select wire:model="edit_unit_category" class="select-clean w-full">
                            <option value="kavling">🏡 Kavling Tanah</option>
                            <option value="rumah">🏠 Rumah / Bangunan</option>
                            <option value="infrastruktur">🏗️ Infrastruktur / Fasum</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Panjang (m)</label>
                        <input type="number" step="0.1" wire:model="edit_land_length" class="input-clean w-full font-mono text-xs">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Lebar (m)</label>
                        <input type="number" step="0.1" wire:model="edit_land_width" class="input-clean w-full font-mono text-xs">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Luas Tanah (m²) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.1" wire:model="edit_land_area" required class="input-clean w-full font-mono font-bold text-xs">
                    </div>
                </div>

                @if($edit_unit_category === 'rumah')
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Luas Bangunan (m²)</label>
                        <input type="number" step="0.1" wire:model="edit_building_area" class="input-clean w-full font-mono text-xs">
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Harga Jual Final</label>
                        <div class="flex rounded-xl shadow-xs">
                            <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                                Rp
                            </span>
                            <x-currency-input model="edit_final_selling_price" class="input-clean rounded-r-xl rounded-l-none font-mono font-bold text-xs w-full" />
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Status Unit <span class="text-rose-500">*</span></label>
                        <select wire:model="edit_unit_status" class="select-clean w-full">
                            <option value="tersedia">🟢 Tersedia</option>
                            <option value="booked">🟠 Booked</option>
                            <option value="disetujui">🔵 Harga ACC</option>
                            <option value="terjual">🟣 Terjual</option>
                            <option value="infrastruktur">⚙️ Infrastruktur</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Spesifikasi / Catatan Tambahan</label>
                    <textarea wire:model="edit_specifications" rows="2" class="input-clean w-full text-xs"></textarea>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <button type="button" wire:click="$set('showEditUnitModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Pembaruan Unit</button>
                </div>
            </form>
        </div>
    </div>
@endif
