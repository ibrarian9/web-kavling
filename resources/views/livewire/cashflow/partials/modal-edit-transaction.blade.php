<!-- Modal Edit Transaksi Mutasi Kas -->
@if($showEditModal && $editingTransactionId)
    <x-modal-dialog show="showEditModal" 
                    closeAction="closeEditModal"
                    title="Edit Mutasi Transaksi Kas #TRX-{{ $editingTransactionId }}" 
                    subTitle="Ubah keterangan, nominal, atau kategori transaksi" 
                    maxWidth="max-w-md">
        <form wire:submit.prevent="updateTransaction" class="space-y-4 text-xs sm:text-sm">
            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Terkait Proyek (Opsional)</label>
                <select wire:model="edit_project_id" class="select-clean w-full font-semibold">
                    <option value="">Non-Proyek / Kas Kantor Pusat / Operasional Umum</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">Proyek: {{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Deskripsi / Keterangan Transaksi <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="edit_description" required placeholder="Contoh: Pembayaran Uang Muka (DP) Unit BLOK D 7..." class="input-clean w-full text-xs sm:text-sm font-medium">
                <span class="text-[10px] text-slate-400 block mt-1">Ganti kode unit, nama pembeli, atau keterangan catatan di sini.</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Kategori Mutasi <span class="text-rose-500">*</span></label>
                    <select wire:model="edit_category" class="select-clean w-full font-semibold">
                        <optgroup label="-- Kategori Kas Masuk --">
                            <option value="pembayaran_cicilan_pembeli">Setoran Cicilan Pembeli</option>
                            <option value="booking_fee">Booking Fee</option>
                            <option value="pembayaran_dp">Pembayaran Uang Muka (DP)</option>
                            <option value="penjualan_unit">Penjualan Unit Cash</option>
                            <option value="pemasukan_lain">Pemasukan Lain-lain</option>
                        </optgroup>
                        <optgroup label="-- Kategori Kas Keluar --">
                            <option value="operasional">Operasional Kantor / Proyek</option>
                            <option value="gaji_karyawan">Gaji Karyawan Staf</option>
                            <option value="upah_tukang">Upah Pekerja / Tukang</option>
                            <option value="material">Pembelian Material</option>
                            <option value="pembelian_lahan">Pembelian Lahan Proyek</option>
                            <option value="pengeluaran_lain">Pengeluaran Lain-lain</option>
                        </optgroup>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Transaksi <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="edit_transaction_date" required class="input-clean w-full font-mono text-xs sm:text-sm">
                </div>
            </div>

            <div>
                <x-currency-input 
                    label="Nominal Transaksi (Rp)"
                    model="edit_amount" 
                    :value="$edit_amount"
                    placeholder="0" 
                    badgeColor="amber"
                    required
                />
            </div>

            <!-- Footer Action Buttons -->
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="outline" size="sm" wire:click="closeEditModal">Batal</x-button>
                <x-button type="submit" variant="amber" size="sm" icon="check">Simpan Perubahan</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
