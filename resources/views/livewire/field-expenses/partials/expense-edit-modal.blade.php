<!-- Modal Edit Transaksi Operasional (Belanja Material / Gaji Worker) -->
@if ($showEditModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 my-auto border border-slate-200/80">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Edit Transaksi {{ $editingType === 'material' ? 'Belanja Material' : 'Gaji Worker' }}</span>
                </h3>
                <button wire:click="closeEditModal" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form wire:submit.prevent="saveEdit" class="space-y-4 text-xs">
                @if ($editingType === 'material')
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Tanggal Transaksi Belanja *</label>
                        <input type="date" wire:model="edit_purchase_date" required class="input-clean w-full font-mono">
                        @error('edit_purchase_date') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Nama Rincian Barang / Material *</label>
                        <input type="text" wire:model="edit_item_name" required placeholder="Contoh: Semen Padang 50kg" class="input-clean w-full font-semibold">
                        @error('edit_item_name') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1 text-[10px]">Jumlah Qty *</label>
                            <input type="number" step="0.01" wire:model.live="edit_quantity" required class="input-clean w-full font-mono font-bold">
                            @error('edit_quantity') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1 text-[10px]">Satuan Unit *</label>
                            <input type="text" wire:model="edit_unit_measure" required placeholder="sak / m3 / pcs" class="input-clean w-full font-semibold">
                            @error('edit_unit_measure') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1 text-[10px]">Harga Satuan (Rp) *</label>
                            <x-currency-input model="edit_unit_price" class="input-clean w-full font-mono font-bold" />
                            @error('edit_unit_price') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between font-mono">
                        <span class="text-slate-500 text-xs font-bold uppercase">Estimasi Total Biaya:</span>
                        <strong class="text-slate-900 font-extrabold text-sm">
                            Rp {{ number_format((float)$edit_quantity * (float)$edit_unit_price, 0, ',', '.') }}
                        </strong>
                    </div>
                @elseif ($editingType === 'salary')
                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Tanggal Pembayaran Gaji *</label>
                        <input type="date" wire:model="edit_payment_date" required class="input-clean w-full font-mono">
                        @error('edit_payment_date') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Nominal Pembayaran Gaji (Rp) *</label>
                        <x-currency-input model="edit_amount_gross" class="input-clean w-full font-mono font-extrabold text-emerald-700 text-sm" />
                        @error('edit_amount_gross') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Metode Pembayaran *</label>
                        <select wire:model="edit_payment_method" class="input-clean w-full font-semibold">
                            <option value="transfer_bank">Transfer Bank</option>
                            <option value="tunai">Tunai / Cash</option>
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Catatan / Keterangan Tambahan</label>
                    <textarea wire:model="edit_notes" rows="2" placeholder="Catatan opsional..." class="input-clean w-full text-xs"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <x-button variant="secondary" size="md" type="button" wire:click="closeEditModal">Batal</x-button>
                    <x-button variant="primary" size="md" type="submit" loadingTarget="saveEdit">
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endif
