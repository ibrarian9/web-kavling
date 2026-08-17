<!-- Modal Form Material Purchase (Catat Belanja Barang Unit - Responsif Mobile & Desktop Mulus Scroll) -->
@if($showMaterialModal)
    <x-modal-dialog show="showMaterialModal" 
                    title="Catat Belanja Barang / Material Unit {{ $unit->code }}" 
                    subTitle="Proyek: {{ $unit->project->name }}" 
                    maxWidth="max-w-2xl">
        <form wire:submit.prevent="saveMaterialPurchase" class="space-y-4 text-xs sm:text-sm">
            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Pekerja / Mandor Pembeli</label>
                <select wire:model="material_worker_id" class="select-clean w-full">
                    @foreach($allWorkers as $w)
                        <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }} - {{ $w->specialty }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Pembelian <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="material_purchase_date" required class="input-clean w-full text-xs sm:text-sm font-mono">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nama Barang / Material <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="material_item_name" required placeholder="Contoh: Semen Gresik / Pasir / Cat" class="input-clean w-full text-xs sm:text-sm font-bold">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nama Toko / Supplier</label>
                    <input type="text" wire:model="material_store_name" placeholder="TB Harapan Jaya / Toko Semen" class="input-clean w-full text-xs sm:text-sm font-semibold">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Status Pembayaran Material <span class="text-rose-500">*</span></label>
                <select wire:model="material_payment_status" class="select-clean w-full font-bold">
                    <option value="lunas">LUNAS / CASH (Langsung Potong Kas Keluar)</option>
                    <option value="belum_lunas">HUTANG TOKO / BELUM LUNAS (Tercatat sebagai Tagihan)</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Jumlah (Qty) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" wire:model.live="material_quantity" required class="input-clean w-full text-xs sm:text-sm font-mono font-bold">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Satuan <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="material_unit_measure" required placeholder="sak / m3 / btg" class="input-clean w-full text-xs sm:text-sm font-semibold">
                </div>
                <x-currency-input 
                    label="Harga Satuan (Rp)" 
                    model="material_unit_price" 
                    :value="$material_unit_price"
                    placeholder="65.000"
                    badgeColor="amber"
                    required
                />
            </div>

            <div class="p-3.5 bg-amber-50 rounded-2xl border border-amber-200/80 flex justify-between items-center text-amber-900 font-bold">
                <span class="text-xs sm:text-sm">Total Belanja Material:</span>
                <span class="text-base sm:text-xl font-mono text-amber-700">Rp {{ number_format(((float)($material_quantity ?? 0)) * ((float)($material_unit_price ?? 0)), 0, ',', '.') }}</span>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Upload Foto Struk / Nota Pembelian <span class="text-amber-700 font-bold lowercase text-[10px] bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">(Opsional, Maks. 2MB)</span></label>
                <input type="file" wire:model="material_receipt_photo" accept="image/*,.heic,.heif,.pdf" class="input-clean w-full text-xs">
                <span class="text-[11px] text-slate-400 mt-1 block">Foto nota maksimal 2MB (JPG, PNG, HEIC, PDF) dan akan disimpan secara aman di sistem.</span>
                @error('material_receipt_photo') <span class="text-rose-500 text-[10px] block mt-1 font-medium">{{ $message }}</span> @enderror
                @if($material_receipt_photo)
                    <div class="mt-3 text-center bg-slate-50 p-3 rounded-2xl border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between text-xs text-slate-700 font-semibold">
                            <span class="text-emerald-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Struk Terpilih ({{ $material_receipt_photo->getClientOriginalName() }}):</span>
                            </span>
                            <button type="button" wire:click="$set('material_receipt_photo', null)" class="text-rose-500 hover:text-rose-700 text-[10px] underline font-bold">Hapus</button>
                        </div>
                        @if (method_exists($material_receipt_photo, 'isPreviewable') && $material_receipt_photo->isPreviewable())
                            <img src="{{ $material_receipt_photo->temporaryUrl() }}" class="max-h-48 mx-auto rounded-xl border border-slate-200 shadow-md">
                        @else
                            <div class="p-3 bg-amber-50 border border-amber-200/80 rounded-xl text-amber-800 text-xs font-semibold flex items-center justify-center gap-2 text-left">
                                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Format berkas ({{ strtoupper($material_receipt_photo->getClientOriginalExtension()) }}) tidak mendukung pratinjau langsung di browser, namun berkas tetap siap diunggah.</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan Belanja</label>
                <input type="text" wire:model="material_notes" placeholder="Catatan supplier / lokasi toko..." class="input-clean w-full text-xs sm:text-sm">
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="secondary" wire:click="$set('showMaterialModal', false)">Batal</x-button>
                <x-button type="submit" variant="amber" loadingTarget="saveMaterialPurchase">Simpan Pembelian Material</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
