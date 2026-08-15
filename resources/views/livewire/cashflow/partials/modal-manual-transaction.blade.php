<!-- Modal Catat Kas Manual -->
@if($showManualModal)
    <x-modal-dialog show="showManualModal" 
                    title="Catat Mutasi Kas Manual" 
                    subTitle="Pencatatan pemasukan atau pengeluaran kas baru" 
                    maxWidth="max-w-md">
        <form wire:submit.prevent="saveTransaction" class="space-y-4 text-xs sm:text-sm">
            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Pilih Proyek <span class="text-rose-500">*</span></label>
                <select wire:model="project_id" class="select-clean w-full font-semibold">
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tipe Arus Kas <span class="text-rose-500">*</span></label>
                    <select wire:model="type" class="select-clean w-full font-bold">
                        <option value="masuk">Pemasukan (Kas Masuk)</option>
                        <option value="keluar">Pengeluaran (Kas Keluar)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Mutasi <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="transaction_date" required class="input-clean w-full font-mono">
                </div>
            </div>

            <div>
                <x-currency-input 
                    label="Nominal Mutasi Kas (Rp)"
                    model="amount" 
                    :value="$amount"
                    placeholder="0" 
                    required
                />
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Keterangan Transaksi <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="description" required placeholder="Pendapatan lain / Konsumsi tukang..." class="input-clean w-full text-xs sm:text-sm font-medium">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Foto Struk / Bukti Transfer <span class="text-amber-600 font-bold lowercase text-[10px] bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">(Opsional, Maks. 2MB)</span></label>
                <input type="file" wire:model="receipt_photo" accept="image/*,.heic,.heif,.pdf" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition cursor-pointer">
                <div wire:loading wire:target="receipt_photo" class="text-[11px] text-amber-600 font-semibold mt-1 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>Mengunggah foto resi...</span>
                </div>
                @error('receipt_photo') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                @if ($receipt_photo ?? false)
                    <div class="mt-2.5 p-2.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                        <div class="flex items-center justify-between text-[11px] font-semibold text-slate-700">
                            <span class="flex items-center gap-1 text-emerald-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>File siap diunggah</span>
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="outline" size="sm" wire:click="$set('showManualModal', false)">Batal</x-button>
                <x-button type="submit" variant="emerald" size="sm" icon="check">Simpan Transaksi Kas</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
