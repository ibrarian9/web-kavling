<!-- Modal Terbitkan / Edit SPP & SPJB PDF Direct (Khusus Founder / Admin) -->
@if($showDirectSppModal)
    <x-modal-dialog show="showDirectSppModal" 
                    :title="$editingSppId ? 'Edit Dokumen SPP & SPJB PDF' : 'Terbitkan SPP & SPJB PDF (Cash Direct)'" 
                    :subTitle="'Unit: ' . $unit->code . ' | Luas Tanah: ' . $unit->land_area . ' m²'" 
                    maxWidth="max-w-lg">
        <form wire:submit.prevent="saveDirectSpp" class="space-y-3.5 text-xs">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Pembeli <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="spp_buyer_name" placeholder="misal: Bpk. Hendra Wijaya" class="input-clean w-full" required>
                    @error('spp_buyer_name') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">NIK KTP Pembeli (16 Digit)</label>
                    <input type="text" wire:model="spp_buyer_nik" placeholder="misal: 3271234567890001" class="input-clean w-full font-mono">
                    @error('spp_buyer_nik') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">No Telp / WA Pembeli <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="spp_buyer_contact" placeholder="misal: 081234567890" class="input-clean w-full" required>
                @error('spp_buyer_contact') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
            </div>

            <!-- Price Input Selection: Duit Deal Langsung vs Harga per Meter (m2) (Optimized with Instant Alpine.js Reactivity) -->
            <div x-data="{
                mode: @entangle('spp_price_mode'),
                landArea: {{ (float)$unit->land_area }},
                cashPrice: @entangle('spp_cash_price'),
                pricePerSqm: @entangle('spp_price_per_sqm'),
                
                formatRp(val) {
                    if (val === null || val === undefined || val === '') return '0';
                    let num = typeof val === 'number' ? Math.round(val) : parseInt(String(val).replace(/[^0-9]/g, ''), 10);
                    if (isNaN(num)) return '0';
                    return num.toLocaleString('id-ID');
                },
                calcFromPerSqm() {
                    let num = typeof this.pricePerSqm === 'number' ? this.pricePerSqm : parseInt(String(this.pricePerSqm || '').replace(/[^0-9]/g, ''), 10);
                    if (!isNaN(num) && num > 0 && this.landArea > 0) {
                        this.cashPrice = Math.round(num * this.landArea);
                    }
                },
                calcFromTotal() {
                    let num = typeof this.cashPrice === 'number' ? this.cashPrice : parseInt(String(this.cashPrice || '').replace(/[^0-9]/g, ''), 10);
                    if (!isNaN(num) && num > 0 && this.landArea > 0) {
                        this.pricePerSqm = Math.round(num / this.landArea);
                    }
                }
            }" class="p-3 bg-emerald-50/70 border border-emerald-200/80 rounded-2xl space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-emerald-950 text-[11px] uppercase tracking-wider">Metode Penentuan Harga Jual</span>
                    <span class="text-[10px] text-emerald-700 font-bold">Luas: {{ number_format($unit->land_area, 2, ',', '.') }} m²</span>
                </div>

                <!-- Mode Switcher Tabs (Instant Client-Side Switch) -->
                <div class="grid grid-cols-2 gap-1.5 p-1 bg-white rounded-xl border border-emerald-100 shadow-2xs">
                    <button type="button" 
                            @click="mode = 'total'; calcFromPerSqm()" 
                            :class="mode === 'total' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-emerald-800'"
                            class="py-1.5 px-2 rounded-lg font-bold text-xs transition-all text-center flex items-center justify-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Deal Langsung (Rp)</span>
                    </button>
                    <button type="button" 
                            @click="mode = 'per_sqm'; calcFromTotal()" 
                            :class="mode === 'per_sqm' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-emerald-800'"
                            class="py-1.5 px-2 rounded-lg font-bold text-xs transition-all text-center flex items-center justify-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <span>Hitung per m² (Rp)</span>
                    </button>
                </div>

                <!-- Panel Hitung per m2 -->
                <div x-show="mode === 'per_sqm'" class="space-y-2" style="display: none;">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                        <div @input="setTimeout(() => calcFromPerSqm(), 50)">
                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Tarif Tanah per m² (Rp) <span class="text-rose-500">*</span></label>
                            <x-currency-input 
                                model="spp_price_per_sqm" 
                                :value="$spp_price_per_sqm"
                                placeholder="1.500.000"
                                badgeColor="emerald"
                            />
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Total Kesepakatan (Auto)</label>
                            <div class="h-10 px-3 flex items-center bg-white border border-emerald-300 rounded-xl font-mono font-extrabold text-emerald-800 text-sm">
                                <span>Rp&nbsp;</span><span x-text="formatRp(cashPrice)"></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-[10px] text-emerald-800 font-medium bg-white/80 p-2 rounded-lg border border-emerald-100">
                        Rumus: {{ number_format($unit->land_area, 2, ',', '.') }} m² &times; Rp <span x-text="formatRp(pricePerSqm)"></span>/m² = <strong>Rp <span x-text="formatRp(cashPrice)"></span></strong>
                    </div>
                </div>

                <!-- Panel Deal Langsung Total -->
                <div x-show="mode === 'total'" class="space-y-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                        <div @input="setTimeout(() => calcFromTotal(), 50)">
                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Nominal Deal Langsung (Rp) <span class="text-rose-500">*</span></label>
                            <x-currency-input 
                                model="spp_cash_price" 
                                :value="$spp_cash_price"
                                placeholder="250.000.000"
                                badgeColor="emerald"
                            />
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Setara per m² (Auto)</label>
                            <div class="h-10 px-3 flex items-center bg-white border border-emerald-200 rounded-xl font-mono font-bold text-slate-700 text-xs">
                                <span>Rp&nbsp;</span><span x-text="landArea > 0 ? formatRp(Math.round(cashPrice / landArea)) : '0'"></span><span>&nbsp;/ m²</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Alamat Lengkap Pembeli</label>
                <textarea wire:model="spp_buyer_address" rows="2" placeholder="misal: Jl. Raya Merdeka No. 12, Kel. Cibiru, Bandung" class="input-clean w-full"></textarea>
            </div>

            <!-- Section Identitas Penjual / Founder -->
            <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-2xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-extrabold text-slate-800 text-[11px] uppercase tracking-wider">Identitas Penjual (Founder)</span>
                    <span class="text-[10px] text-slate-400">Otomatis Terisi & Bisa Diubah</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label class="block font-semibold text-slate-600 text-[10px] mb-0.5">Nama Founder / Penjual</label>
                        <input type="text" wire:model="spp_seller_name" class="input-clean w-full font-bold text-xs" required>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 text-[10px] mb-0.5">NIK KTP Founder</label>
                        <input type="text" wire:model="spp_seller_nik" class="input-clean w-full font-mono text-xs" placeholder="1471012304850001">
                    </div>
                </div>
            </div>

            <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-900 text-[11px] leading-relaxed">
                <strong class="font-bold">Info Founder:</strong> Berkas SPP & SPJB PDF akan langsung diterbitkan secara sah lengkap dengan NIK Pembeli, NIK Founder Penjual, dan Pasal 1-5 tanpa perlu melalui proses booking fee terlebih dahulu.
            </div>

            <div class="pt-2 flex items-center justify-end gap-2">
                <x-button type="button" variant="secondary" wire:click="$set('showDirectSppModal', false)">Batal</x-button>
                <x-button type="submit" variant="emerald" loadingTarget="saveDirectSpp">
                    <span>{{ $editingSppId ? 'Simpan Perubahan SPP' : 'Terbitkan & Lihat PDF' }}</span> &rarr;
                </x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
