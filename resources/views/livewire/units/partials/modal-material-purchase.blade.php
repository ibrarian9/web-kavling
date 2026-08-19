<!-- Modal Form Material Purchase (Catat Belanja Barang Unit - BULK INPUT) -->
@if($showMaterialModal)
    <x-modal-dialog show="showMaterialModal" 
                    title="{{ $editingMaterialId ? 'Edit' : 'Catat' }} Belanja Barang / Material Unit {{ $unit->code }}" 
                    subTitle="Proyek: {{ $unit->project->name }}" 
                    maxWidth="max-w-3xl">
        <form wire:submit.prevent="saveMaterialPurchase" class="space-y-4 text-xs sm:text-sm">
            {{-- ===== SHARED METADATA (applies to all items) ===== --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Pekerja / Mandor</label>
                    <select wire:model="material_worker_id" class="select-clean w-full">
                        @foreach($allWorkers as $w)
                            <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Pembelian <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="material_purchase_date" required class="input-clean w-full text-xs sm:text-sm font-mono">
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

            {{-- ===== REPEATABLE ITEM ROWS ===== --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block font-semibold text-slate-700 text-xs uppercase tracking-wider">
                        Daftar Barang / Material
                        <span class="text-slate-400 font-normal lowercase">({{ count($materialRows) }} item)</span>
                    </label>
                </div>

                <div class="space-y-2 max-h-[320px] overflow-y-auto pr-1">
                    @foreach($materialRows as $idx => $row)
                        <div class="relative bg-slate-50/80 border border-slate-200/80 rounded-xl p-3 space-y-2 transition-all" wire:key="material-row-{{ $idx }}">
                            {{-- Row header with number and delete button --}}
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Item #{{ $idx + 1 }}</span>
                                @if(count($materialRows) > 1 && !$editingMaterialId)
                                    <button type="button" wire:click="removeMaterialRow({{ $idx }})" 
                                            class="p-1 rounded-lg text-rose-400 hover:bg-rose-50 hover:text-rose-600 transition-colors"
                                            title="Hapus item ini">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            </div>

                            {{-- Item fields --}}
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                                <div class="sm:col-span-2">
                                    <label class="block text-[10px] font-semibold text-slate-400 mb-0.5 uppercase tracking-wider">Nama Barang <span class="text-rose-400">*</span></label>
                                    <input type="text" wire:model.live="materialRows.{{ $idx }}.item_name" required
                                           placeholder="Semen, Pasir, Cat..." 
                                           class="input-clean w-full text-xs sm:text-sm font-bold">
                                    @error("materialRows.{$idx}.item_name") <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[10px] font-semibold text-slate-400 mb-0.5 uppercase tracking-wider">Qty <span class="text-rose-400">*</span></label>
                                        <input type="number" step="0.01" wire:model.live="materialRows.{{ $idx }}.quantity" required
                                               placeholder="1" class="input-clean w-full text-xs sm:text-sm font-mono font-bold">
                                        @error("materialRows.{$idx}.quantity") <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-semibold text-slate-400 mb-0.5 uppercase tracking-wider">Satuan</label>
                                        <input type="text" wire:model.live="materialRows.{{ $idx }}.unit_measure" required
                                               placeholder="sak / m3" class="input-clean w-full text-xs sm:text-sm font-semibold">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-slate-400 mb-0.5 uppercase tracking-wider">Harga Satuan <span class="text-rose-400">*</span></label>
                                    <div x-data="{
                                            displayVal: '',
                                            init() {
                                                let initial = $wire.get('materialRows.{{ $idx }}.unit_price');
                                                this.displayVal = this.format(initial);
                                            },
                                            format(num) {
                                                let val = parseInt(String(num).replace(/[^0-9]/g, '') || '0', 10);
                                                return val ? val.toLocaleString('id-ID') : '';
                                            },
                                            onInput(e) {
                                                let digits = e.target.value.replace(/[^0-9]/g, '');
                                                let val = digits ? parseInt(digits, 10) : 0;
                                                this.displayVal = digits ? val.toLocaleString('id-ID') : '';
                                                $wire.set('materialRows.{{ $idx }}.unit_price', val);
                                            }
                                         }"
                                         class="flex rounded-xl shadow-2xs border border-slate-200 overflow-hidden focus-within:border-amber-400 focus-within:ring-2 focus-within:ring-amber-400/20 bg-white transition">
                                        <span class="font-mono font-extrabold text-[11px] px-2.5 flex items-center shrink-0 border-r select-none bg-amber-50 text-amber-700 border-amber-200">Rp</span>
                                        <input type="text" inputmode="numeric" x-model="displayVal" @input="onInput($event)"
                                               placeholder="65.000" required
                                               class="w-full px-2.5 py-2 font-mono font-bold text-xs text-slate-800 bg-transparent focus:outline-none">
                                    </div>
                                    @error("materialRows.{$idx}.unit_price") <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Subtotal per row --}}
                            @php
                                $rowQty = is_numeric($row['quantity'] ?? 0) ? (float)$row['quantity'] : 0;
                                $rowPrice = is_numeric($row['unit_price'] ?? 0) ? (float)$row['unit_price'] : 0;
                                $rowSubtotal = $rowQty * $rowPrice;
                            @endphp
                            @if($rowSubtotal > 0)
                                <div class="text-right text-[11px] font-mono font-bold text-slate-500">
                                    Subtotal: <span class="text-amber-700">Rp {{ number_format($rowSubtotal, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Add Row Button (only in create mode) --}}
                @if(!$editingMaterialId)
                    <button type="button" wire:click="addMaterialRow"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border-2 border-dashed border-slate-300 text-slate-500 hover:border-teal-400 hover:text-teal-600 hover:bg-teal-50/50 transition-all text-xs font-bold uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Tambah Barang Lain
                    </button>
                @endif
            </div>

            {{-- ===== GRAND TOTAL ===== --}}
            <div class="p-3.5 bg-amber-50 rounded-2xl border border-amber-200/80 flex justify-between items-center text-amber-900 font-bold">
                <span class="text-xs sm:text-sm">
                    Total Belanja ({{ count($materialRows) }} item):
                </span>
                <span class="text-base sm:text-xl font-mono text-amber-700">Rp {{ number_format($material_grand_total, 0, ',', '.') }}</span>
            </div>

            <x-receipt-upload 
                model="material_receipt_photo" 
                :photo="$material_receipt_photo" 
                label="Foto Struk / Nota Pembelian Material"
            />

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan Belanja</label>
                <input type="text" wire:model="material_notes" placeholder="Catatan supplier / lokasi toko..." class="input-clean w-full text-xs sm:text-sm">
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="secondary" wire:click="$set('showMaterialModal', false)">Batal</x-button>
                <x-button type="submit" variant="amber" loadingTarget="saveMaterialPurchase">
                    {{ $editingMaterialId ? 'Simpan Perubahan' : 'Simpan ' . count($materialRows) . ' Item Sekaligus' }}
                </x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
