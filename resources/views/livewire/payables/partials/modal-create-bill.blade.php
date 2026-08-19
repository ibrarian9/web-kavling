<!-- MODAL 3: CREATE NEW MATERIAL / OPERATIONAL BILL -->
<x-modal-dialog show="showCreateBillModal" title="Catat Tagihan Belanja Material / Vendor" subTitle="Mencatat hutang ke toko material atau pengeluaran operasional proyek" maxWidth="max-w-xl">
    <form wire:submit.prevent="saveNewBill" class="space-y-4 text-xs">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Pilih Proyek (Opsional)</label>
                <select wire:model.live="new_project_id" class="select-clean w-full">
                    <option value="">Non-Proyek / Operasional Umum</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Pilih Unit (Opsional)</label>
                <select wire:model="new_unit_id" class="select-clean w-full">
                    <option value="">Semua Unit / Umum</option>
                    @foreach($availableUnits as $u)
                        <option value="{{ $u->id }}">Unit {{ $u->code }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Nama Toko / Supplier <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="new_store_name" placeholder="Contoh: TB Subur Jaya" required class="input-clean w-full">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Nama Barang / Tagihan <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="new_item_name" placeholder="Contoh: Semen Gresik 50 Sak" required class="input-clean w-full">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Jumlah (Qty) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.01" wire:model.live="new_quantity" required class="input-clean w-full font-mono font-bold">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Satuan <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="new_unit_measure" placeholder="sak / m3 / ret" required class="input-clean w-full">
            </div>

            <div>
                <x-currency-input 
                    label="Harga Satuan (Rp)" 
                    model="new_unit_price" 
                    :value="$new_unit_price"
                    placeholder="0"
                    badgeColor="amber"
                    required 
                />
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 p-3.5 rounded-2xl border border-slate-200">
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Total Nominal Tagihan (Rp)</label>
                <div class="flex items-center px-3 py-2 bg-white rounded-xl border border-slate-200 font-mono font-extrabold text-sm text-rose-700">
                    <span>Rp {{ number_format((float)($new_quantity ?? 0) * (float)($new_unit_price ?? 0), 0, ',', '.') }}</span>
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Status Pembayaran Awal</label>
                <select wire:model="new_payment_status" class="select-clean w-full font-bold">
                    <option value="belum_lunas">HUTANG TOKO / BELUM LUNAS</option>
                    <option value="lunas">LUNAS (LANGSUNG KAS KELUAR)</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block font-semibold text-slate-700 mb-1">Tanggal Belanja / Tagihan <span class="text-rose-500">*</span></label>
            <input type="date" wire:model="new_purchase_date" required class="input-clean w-full font-mono">
        </div>

        <x-receipt-upload 
            model="new_receipt_photo" 
            :photo="$new_receipt_photo" 
            label="Upload Bukti Nota / Foto (Opsional)"
        />

        <div>
            <label class="block font-semibold text-slate-700 mb-1">Catatan / Keterangan</label>
            <input type="text" wire:model="new_notes" placeholder="Catatan syarat tempo..." class="input-clean w-full">
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
            <x-button variant="secondary" size="md" type="button" wire:click="$set('showCreateBillModal', false)">Batal</x-button>
            <x-button variant="rose" size="md" type="submit">Simpan Tagihan Material</x-button>
        </div>
    </form>
</x-modal-dialog>
