<!-- MODAL 3: CREATE NEW MATERIAL / OPERATIONAL BILL -->
@if($showCreateBillModal)
    <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white border border-slate-200 rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="p-2 rounded-xl bg-rose-50 text-rose-700 border border-rose-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Catat Tagihan Belanja Material / Vendor</h3>
                        <p class="text-[11px] text-slate-500">Mencatat hutang ke toko material atau pengeluaran operasional proyek</p>
                    </div>
                </div>
                <button wire:click="$set('showCreateBillModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

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
                        <label class="block font-semibold text-slate-700 mb-1">Jumlah (Qty)</label>
                        <input type="number" step="0.01" wire:model.live="new_quantity" required class="input-clean w-full">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Satuan</label>
                        <input type="text" wire:model="new_unit_measure" placeholder="sak / m3 / ret" required class="input-clean w-full">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Harga Satuan (Rp)</label>
                        <input type="number" wire:model.live="new_unit_price" required class="input-clean w-full">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 p-3 rounded-2xl border border-slate-200">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Total Nominal Tagihan (Rp)</label>
                        <input type="number" wire:model="new_total_price" readonly class="input-clean w-full font-bold text-rose-700 text-sm bg-white">
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
                    <label class="block font-semibold text-slate-700 mb-1">Tanggal Belanja / Tagihan</label>
                    <input type="date" wire:model="new_purchase_date" required class="input-clean w-full">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Upload Bukti Nota / Foto (Opsional)</label>
                    <input type="file" wire:model="new_receipt_photo" accept="image/*,.pdf" class="input-clean w-full">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Catatan / Keterangan</label>
                    <input type="text" wire:model="new_notes" placeholder="Catatan syarat tempo..." class="input-clean w-full">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="$set('showCreateBillModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary bg-rose-600 hover:bg-rose-700">Simpan Tagihan Material</button>
                </div>
            </form>
        </div>
    </div>
@endif
