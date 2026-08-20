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

            @if($edit_unit_category === 'infrastruktur')
                <div class="bg-sky-50/80 border border-sky-200/80 rounded-2xl p-3.5 space-y-2">
                    <p class="font-bold text-[11px] uppercase tracking-wider text-sky-900">Luas Pengerjaan Fasum / Infrastruktur:</p>
                    <div>
                        <label class="block font-semibold text-sky-800 mb-1 text-[11px] uppercase tracking-wider">
                            Luas Pengerjaan (m²) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" wire:model="edit_land_area" required placeholder="Contoh: 150" class="input-clean w-full h-10 font-extrabold font-mono text-xs bg-white text-sky-950">
                        <p class="text-[10px] text-slate-500 mt-1">Input manual luas area/panjang pengerjaan fisik fasilitas umum / infrastruktur.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5 text-[11px] uppercase tracking-wider truncate" title="Panjang Tanah (meter)">
                            Panjang (m)
                        </label>
                        <input type="number" step="0.01" min="0.1" wire:model.live.debounce.300ms="edit_land_length" class="input-clean w-full h-10 font-mono font-semibold text-xs text-center">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5 text-[11px] uppercase tracking-wider truncate" title="Lebar Tanah (meter)">
                            Lebar (m)
                        </label>
                        <input type="number" step="0.01" min="0.1" wire:model.live.debounce.300ms="edit_land_width" class="input-clean w-full h-10 font-mono font-semibold text-xs text-center">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1.5 text-[11px] uppercase tracking-wider truncate" title="Luas Tanah (meter persegi)">
                            Luas (m²) <span class="text-emerald-600 font-bold lowercase text-[10px]">(auto)</span>
                        </label>
                        <input type="number" step="0.01" wire:model="edit_land_area" readonly tabindex="-1" class="input-clean w-full h-10 font-extrabold font-mono text-xs text-center bg-slate-100/90 text-slate-800 border-slate-300 cursor-not-allowed" title="Luas tanah terhitung otomatis dari Panjang x Lebar">
                    </div>
                </div>
            @endif

            @if($edit_unit_category === 'rumah')
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Luas Bangunan (m²)</label>
                    <input type="number" step="0.1" wire:model="edit_building_area" class="input-clean w-full font-mono text-xs">
                </div>
            @endif

            @if($edit_unit_category !== 'infrastruktur')
                <!-- Kelebihan Tanah Section (Founder & Finance) -->
                <div class="bg-amber-50/80 border border-amber-200/80 rounded-xl p-3 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-amber-900 font-bold text-[11px] uppercase tracking-wider">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Perhitungan Kelebihan Tanah</span>
                        </div>
                        <span class="text-[10px] font-extrabold px-2 py-0.5 bg-amber-200/80 text-amber-900 rounded-md">
                            Kelebihan: {{ number_format($edit_excess_land_area, 2, ',', '.') }} m²
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <x-currency-input 
                            label="Tarif per m² (Rp)" 
                            model="edit_excess_price_per_sqm" 
                            :value="$edit_excess_price_per_sqm"
                            placeholder="1.500.000"
                            badgeColor="amber"
                        />

                        <x-currency-input 
                            label="Total Biaya Kelebihan (Rp)" 
                            model="edit_excess_cost" 
                            :value="$edit_excess_cost"
                            placeholder="0"
                            badgeColor="amber"
                        />
                    </div>
                    <p class="text-[10px] text-amber-800 leading-tight">
                        *Founder dapat menyesuaikan tarif per-m² maupun nominal biaya total kelebihan tanah untuk unit ini.
                    </p>
                </div>
            @endif

            <div class="grid grid-cols-1 {{ $edit_unit_category !== 'infrastruktur' ? 'sm:grid-cols-2' : '' }} gap-3">
                @if($edit_unit_category !== 'infrastruktur')
                    <x-currency-input 
                        label="Harga Jual Final (Rp)" 
                        model="edit_final_selling_price" 
                        :value="$edit_final_selling_price"
                        placeholder="150.000.000"
                        badgeColor="emerald"
                    />
                @endif
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
