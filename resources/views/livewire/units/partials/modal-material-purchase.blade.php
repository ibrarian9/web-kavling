<!-- Modal Form Material Purchase (Catat Belanja Barang Unit - Responsif Mobile & Desktop Mulus Scroll) -->
@if($showMaterialModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-2xl md:max-w-3xl w-full p-4 sm:p-7 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base sm:text-lg tracking-tight">Catat Belanja Barang / Material Unit {{ $unit->code }}</h3>
                    <p class="text-slate-500 text-xs mt-0.5">Proyek: {{ $unit->project->name }}</p>
                </div>
                <button wire:click="$set('showMaterialModal', false)" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
            </div>

            <form wire:submit.prevent="saveMaterialPurchase" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Pekerja / Mandor Pembeli</label>
                    <select wire:model="material_worker_id" class="select-clean w-full">
                        @foreach($allWorkers as $w)
                            <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }} - {{ $w->specialty }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Pembelian <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="material_purchase_date" required class="input-clean w-full text-xs sm:text-sm font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nama Barang / Material <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="material_item_name" required placeholder="Contoh: Semen Gresik / Pasir / Cat" class="input-clean w-full text-xs sm:text-sm font-bold">
                    </div>
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
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Harga Satuan <span class="text-rose-500">*</span></label>
                        <div class="flex rounded-xl shadow-xs">
                            <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                                Rp
                            </span>
                            <x-currency-input model="material_unit_price" class="input-clean rounded-r-xl rounded-l-none font-mono font-bold text-xs sm:text-sm w-full" placeholder="65.000" />
                        </div>
                    </div>
                </div>

                <div class="p-3.5 bg-amber-50 rounded-2xl border border-amber-200/80 flex justify-between items-center text-amber-900 font-bold">
                    <span class="text-xs sm:text-sm">Total Belanja Material:</span>
                    <span class="text-base sm:text-xl font-mono text-amber-700">Rp {{ number_format(((float)($material_quantity ?? 0)) * ((float)($material_unit_price ?? 0)), 0, ',', '.') }}</span>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Upload Foto Struk / Nota Pembelian</label>
                    <input type="file" wire:model="material_receipt_photo" accept="image/*,.heic,.heif,.pdf" class="input-clean w-full text-xs">
                    <span class="text-[11px] text-slate-400 mt-1 block">Foto nota akan dikompresi otomatis & disimpan di sistem.</span>
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
                    <button type="button" wire:click="$set('showMaterialModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary bg-amber-600 hover:bg-amber-700">Simpan Pembelian Material</button>
                </div>
            </form>
        </div>
    </div>
@endif
