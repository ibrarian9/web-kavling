<!-- MODAL CATAT / EDIT PEMBAYARAN LAHAN KE PENJUAL TANAH -->
@if($showLandPaymentModal)
    <x-modal-dialog show="showLandPaymentModal" 
                    :title="!empty($editingLandPaymentId) ? 'Edit Pembayaran Lahan Proyek' : 'Catat Pembayaran Lahan ke Penjual Tanah'" 
                    maxWidth="max-w-lg">
        <form wire:submit.prevent="submitLandPayment" class="space-y-4">
            
            <!-- Pilihan Proyek -->
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                    Pilih Proyek Properti <span class="text-rose-500">*</span>
                </label>
                <select wire:model.live="land_project_id" class="w-full text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-emerald-500 focus:bg-white text-slate-800 transition">
                    <option value="">-- Pilih Proyek --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} (Sisa: Rp {{ number_format($p->remaining_balance, 0, ',', '.') }})</option>
                    @endforeach
                </select>
                @error('land_project_id') <span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
            </div>

            <!-- Tanggal Pembayaran & Metode -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                    Tanggal Bayar <span class="text-rose-500">*</span>
                </label>
                    <input type="date" wire:model="land_payment_date" class="w-full text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-emerald-500 focus:bg-white text-slate-800 transition">
                    @error('land_payment_date') <span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                        Metode Pembayaran <span class="text-rose-500">*</span>
                    </label>
                    <select wire:model="land_payment_method" class="w-full text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-emerald-500 focus:bg-white text-slate-800 transition">
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Tunai / Cash">Tunai / Cash</option>
                        <option value="Cek / Bilyet Giro">Cek / Bilyet Giro</option>
                    </select>
                    @error('land_payment_method') <span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Jumlah Pembayaran (Rp) -->
            <div>
                <x-currency-input 
                    label="Jumlah Pembayaran Lahan (Rp)"
                    model="land_payment_amount"
                    :value="$land_payment_amount"
                    placeholder="0"
                    badgeColor="emerald"
                    required
                />
            </div>

            <!-- Catatan / Keterangan Pembayaran -->
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                    Keterangan / Berita Acara
                </label>
                <textarea wire:model="land_payment_notes" rows="2" placeholder="Contoh: Pembayaran termin 2 pembelian tanah ke Bapak H. Ahmad..." class="w-full text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl p-3 focus:outline-none focus:border-emerald-500 focus:bg-white text-slate-800 transition"></textarea>
                @error('land_payment_notes') <span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror
            </div>

            <!-- Upload Foto Resi / Bukti Transfer -->
            <x-receipt-upload 
                model="land_payment_receipt_photo" 
                :photo="$land_payment_receipt_photo" 
                :existingPath="$existing_land_receipt_photo_path" 
                label="Foto Resi / Bukti Transfer"
            />

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                <x-button type="button" variant="outline" size="sm" wire:click="closeLandPaymentModal">
                    Batal
                </x-button>
                <x-button type="submit" variant="emerald" size="sm" icon="check">
                    {{ !empty($editingLandPaymentId) ? 'Simpan Perubahan' : 'Simpan Pembayaran' }}
                </x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
