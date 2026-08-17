<!-- Modal Terbitkan SPP & SPJB PDF Direct (Khusus Founder / Admin) -->
@if($showDirectSppModal)
    <x-modal-dialog show="showDirectSppModal" 
                    title="Terbitkan SPP & SPJB PDF (Cash Direct)" 
                    subTitle="Unit: {{ $unit->code }} (Tanpa Booking Fee)" 
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

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">No Telp / WA Pembeli <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="spp_buyer_contact" placeholder="misal: 081234567890" class="input-clean w-full" required>
                    @error('spp_buyer_contact') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <x-currency-input 
                    label="Harga Kesepakatan Cash (Rp)" 
                    model="spp_cash_price" 
                    :value="$spp_cash_price"
                    placeholder="250.000.000"
                    badgeColor="emerald"
                    required
                />
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
                    <span>Terbitkan & Lihat PDF</span> &rarr;
                </x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
