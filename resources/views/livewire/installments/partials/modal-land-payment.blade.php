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
            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-700 mb-1.5">
                    Foto Resi / Bukti Transfer (Opsional)
                </label>
                <input type="file" wire:model="land_payment_receipt_photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition border border-slate-200 rounded-xl p-1 bg-slate-50">
                @error('land_payment_receipt_photo') <span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span> @enderror

                @if($land_payment_receipt_photo)
                    <div class="mt-2 text-xs text-emerald-600 font-bold flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Foto siap diupload: {{ $land_payment_receipt_photo->getClientOriginalName() }}</span>
                    </div>
                @elseif($existing_land_receipt_photo_path)
                    <div class="mt-2 text-xs text-slate-500">
                        <a href="{{ asset('storage/' . $existing_land_receipt_photo_path) }}" target="_blank" class="text-sky-600 font-bold hover:underline flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Lihat Bukti Foto Saat Ini</span>
                        </a>
                    </div>
                @endif
            </div>

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
