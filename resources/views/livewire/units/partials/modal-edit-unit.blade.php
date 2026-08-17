<!-- Modal Form Edit Unit Spesifikasi & Status (Founder & Finance) -->
@if($showEditUnitModal)
    <x-modal-dialog show="showEditUnitModal" 
                    title="Edit Spesifikasi & Data Unit {{ $edit_unit_code }}" 
                    subTitle="Pembaruan spesifikasi fisik, harga final, dan status unit" 
                    maxWidth="max-w-lg">
        <form wire:submit.prevent="saveEditUnit" class="space-y-4 text-xs sm:text-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Kode Unit <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="edit_unit_code" required class="input-clean w-full font-mono font-bold text-xs">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Kategori Unit <span class="text-rose-500">*</span></label>
                    <select wire:model="edit_unit_category" class="select-clean w-full">
                        <option value="kavling">Kavling Tanah</option>
                        <option value="rumah">Rumah / Bangunan</option>
                        <option value="infrastruktur">Infrastruktur / Fasum</option>
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
                <x-currency-input 
                    label="Harga Jual Final (Rp)" 
                    model="edit_final_selling_price" 
                    :value="$edit_final_selling_price"
                    placeholder="150.000.000"
                    badgeColor="emerald"
                />
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Status Unit <span class="text-rose-500">*</span></label>
                    <select wire:model="edit_unit_status" class="select-clean w-full">
                        <option value="tersedia">Tersedia</option>
                        <option value="booked">Booked</option>
                        <option value="disetujui">Harga ACC</option>
                        <option value="terjual">Terjual</option>
                        <option value="infrastruktur">Infrastruktur</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Spesifikasi / Catatan Tambahan</label>
                <textarea wire:model="edit_specifications" rows="2" class="input-clean w-full text-xs"></textarea>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="secondary" wire:click="$set('showEditUnitModal', false)">Batal</x-button>
                <x-button type="submit" variant="primary" loadingTarget="saveEditUnit">Simpan Pembaruan Unit</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
