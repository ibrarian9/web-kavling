<!-- Modal Catat Kas Manual -->
@if($showManualModal)
    <x-modal-dialog show="showManualModal" 
                    closeAction="closeManualModal"
                    title="Catat Mutasi Kas Manual" 
                    subTitle="Pencatatan pemasukan atau pengeluaran kas baru" 
                    maxWidth="max-w-md">
        <form wire:submit.prevent="saveTransaction" class="space-y-4 text-xs sm:text-sm">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block font-semibold text-slate-700 text-xs uppercase tracking-wider">Terkait Proyek</label>
                    <span class="text-[10px] text-slate-400 font-medium">Opsional</span>
                </div>
                <select wire:model="project_id" class="select-clean w-full font-semibold">
                    <option value="">Non-Proyek / Kas Kantor Pusat / Operasional Umum</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">Proyek: {{ $p->name }}</option>
                    @endforeach
                </select>
                <span class="text-[10px] text-slate-500 block mt-1">Pilih <em>Non-Proyek</em> untuk mencatat kas operasional umum kantor atau transaksi di luar proyek.</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tipe Arus Kas <span class="text-rose-500">*</span></label>
                    <select wire:model.live="type" class="select-clean w-full font-bold">
                        <option value="masuk">Pemasukan (Kas Masuk)</option>
                        <option value="keluar">Pengeluaran (Kas Keluar)</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Kategori Mutasi <span class="text-rose-500">*</span></label>
                    <select wire:model="category" class="select-clean w-full font-semibold">
                        @if($type === 'masuk')
                            <optgroup label="-- Kategori Kas Masuk --">
                                <option value="pembayaran_cicilan_pembeli">Setoran Cicilan Pembeli</option>
                                <option value="booking_fee">Booking Fee</option>
                                <option value="pembayaran_dp">Pembayaran Uang Muka (DP)</option>
                                <option value="penjualan_unit">Penjualan Unit Cash</option>
                                <option value="pemasukan_lain">Pemasukan Lain-lain</option>
                            </optgroup>
                        @else
                            <optgroup label="-- Kategori Kas Keluar --">
                                <option value="operasional">Operasional Kantor / Proyek</option>
                                <option value="gaji_karyawan">Gaji Karyawan Staf</option>
                                <option value="upah_tukang">Upah Pekerja / Tukang</option>
                                <option value="material">Pembelian Material</option>
                                <option value="pembelian_lahan">Pembelian Lahan Proyek</option>
                                <option value="pengeluaran_lain">Pengeluaran Lain-lain</option>
                            </optgroup>
                        @endif
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Mutasi <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="transaction_date" required class="input-clean w-full font-mono">
                </div>
                <div>
                    <x-currency-input 
                        label="Nominal Kas (Rp)"
                        model="amount" 
                        :value="$amount"
                        placeholder="0" 
                        required
                    />
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Keterangan Transaksi <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="description" required placeholder="Contoh: Pembayaran internet & listrik kantor..." class="input-clean w-full text-xs sm:text-sm font-medium">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Foto Struk / Bukti Transfer <span class="text-slate-400 font-normal lowercase text-[10px]">(Opsional, Maks. 5MB)</span></label>
                <input type="file" wire:model="receipt_photo" accept="image/*,.heic,.heif,.pdf" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition cursor-pointer">
                <div wire:loading wire:target="receipt_photo" class="text-[11px] text-amber-600 font-semibold mt-1 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span>Mengunggah foto bukti...</span>
                </div>
                @error('receipt_photo') <span class="text-rose-500 text-[10px] mt-1 block font-semibold">{{ $message }}</span> @enderror
                
                @if ($receipt_photo)
                    <div class="mt-2.5 p-3 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-emerald-600 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>File Siap Diunggah</span>
                            </span>
                            <button type="button" wire:click="$set('receipt_photo', null)" class="text-rose-600 hover:text-rose-800 text-xs font-bold transition flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                        @if (str_starts_with($receipt_photo->getMimeType(), 'image/'))
                            <div class="relative w-full max-h-48 overflow-hidden rounded-xl border border-slate-200 bg-white flex items-center justify-center p-1">
                                <img src="{{ $receipt_photo->temporaryUrl() }}" alt="Preview Foto Bukti" class="max-h-44 object-contain rounded-lg">
                            </div>
                        @else
                            <div class="p-2.5 bg-white border border-slate-200 rounded-xl flex items-center gap-2 text-slate-700 text-xs">
                                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span class="font-mono truncate">{{ $receipt_photo->getClientOriginalName() }}</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="outline" size="sm" wire:click="closeManualModal">Batal</x-button>
                <x-button type="submit" variant="emerald" size="sm" icon="check">Simpan Transaksi Kas</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
