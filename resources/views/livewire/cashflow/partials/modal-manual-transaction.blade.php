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

            <x-receipt-upload 
                model="receipt_photo" 
                :photo="$receipt_photo" 
                label="Foto Struk / Bukti Transaksi"
            />

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="outline" size="sm" wire:click="closeManualModal">Batal</x-button>
                <x-button type="submit" variant="emerald" size="sm" icon="check">Simpan Transaksi Kas</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
