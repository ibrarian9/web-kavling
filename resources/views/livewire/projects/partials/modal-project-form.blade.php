<!-- Modal Form Tambah / Edit Data Proyek -->
@if($showModal)
    <x-modal-dialog show="showModal" 
                    :title="$editingProjectId ? 'Edit Data Proyek' : 'Tambah Proyek Baru'" 
                    subTitle="Konfigurasi parameter lahan, harga dasar HPP, dan luas tanah" 
                    maxWidth="max-w-md">
        <form wire:submit.prevent="saveProject" class="space-y-4 text-xs">
            <div>
                <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nama Proyek <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="name" placeholder="Grand Kavling..." class="input-clean w-full font-bold">
                @error('name') <span class="text-rose-500 text-[10px] block mt-1 font-semibold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Lokasi Proyek <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="location" placeholder="Panam, Pekanbaru, Riau" class="input-clean w-full">
                @error('location') <span class="text-rose-500 text-[10px] block mt-1 font-semibold">{{ $message }}</span> @enderror
            </div>

            <div>
                <x-currency-input 
                    label="Harga Beli Lahan Proyek (Rp)" 
                    model="total_project_price" 
                    :value="$total_project_price"
                    placeholder="0" 
                    badgeColor="purple"
                    helpText="Harga kesepakatan akuisisi / pembelian lahan tanah dari penjual"
                />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Luas Standar (m²) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" wire:model="standard_land_area" class="input-clean w-full font-mono">
                    @error('standard_land_area') <span class="text-rose-500 text-[10px] block mt-1 font-semibold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <x-currency-input 
                        label="Harga Dasar Kavling (Rp)" 
                        model="base_price" 
                        :value="$base_price"
                        placeholder="0" 
                    />
                </div>
            </div>

            <div>
                <x-currency-input 
                    label="Harga per m² Kelebihan Tanah (Rp)" 
                    model="excess_price_per_sqm" 
                    :value="$excess_price_per_sqm"
                    placeholder="0" 
                />
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <x-button type="button" variant="outline" size="sm" wire:click="closeModal">Batal</x-button>
                <x-button type="submit" variant="emerald" size="sm" icon="check">Simpan Proyek</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
