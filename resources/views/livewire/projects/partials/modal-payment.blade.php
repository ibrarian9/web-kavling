<!-- MODAL CATAT PEMBAYARAN LAHAN KE PENJUAL -->
@if($showPaymentModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white border border-slate-200 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">{{ !empty($editingPaymentId) ? 'Edit Pembayaran Lahan' : 'Catat Pembayaran Lahan ke Penjual' }}</h3>
                        <p class="text-slate-500 text-xs mt-0.5">Proyek: <strong class="text-slate-800">{{ $project->name }}</strong></p>
                    </div>
                </div>
                <button wire:click="closePaymentModal" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">✕</button>
            </div>

            <form wire:submit.prevent="saveProjectPayment" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Jumlah Dibayar (Rp) <span class="text-rose-500">*</span></label>
                    <x-currency-input model="payment_amount" placeholder="0" class="input-clean w-full font-mono text-sm font-bold text-emerald-700" />
                    @error('payment_amount') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Tanggal Pembayaran <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="payment_date" class="input-clean w-full font-mono">
                        @error('payment_date') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Metode Pembayaran</label>
                        <select wire:model="payment_method" class="input-clean w-full">
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Tunai / Cash">Tunai / Cash</option>
                            <option value="Giro / Cek">Giro / Cek</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Foto Resi / Bukti Transfer <span class="text-purple-700 font-bold lowercase text-[10px] bg-purple-50 px-1.5 py-0.5 rounded border border-purple-200">(Opsional, Maks. 2MB)</span></label>
                    <input type="file" wire:model="payment_receipt_photo" accept="image/*,.heic,.heif,.pdf" class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    @error('payment_receipt_photo') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                    @if ($payment_receipt_photo)
                        <div class="mt-2.5 p-2.5 bg-slate-50 border border-slate-200 rounded-xl space-y-2">
                            <div class="flex items-center justify-between text-[11px] font-semibold text-slate-700">
                                <span class="flex items-center gap-1 text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Berkas Terpilih ({{ $payment_receipt_photo->getClientOriginalName() }}):</span>
                                </span>
                                <button type="button" wire:click="$set('payment_receipt_photo', null)" class="text-rose-500 hover:text-rose-700 text-[10px] underline font-bold">Hapus Foto</button>
                            </div>
                            @if (method_exists($payment_receipt_photo, 'isPreviewable') && $payment_receipt_photo->isPreviewable())
                                <div class="relative max-h-36 sm:max-h-40 overflow-y-auto rounded-xl border border-slate-200 bg-slate-900 flex items-center justify-center p-1.5">
                                    <img src="{{ $payment_receipt_photo->temporaryUrl() }}" alt="Preview Resi" class="max-h-32 sm:max-h-36 w-auto max-w-full object-contain rounded-lg shadow-sm">
                                </div>
                            @else
                                <div class="p-3 bg-amber-50 border border-amber-200/80 rounded-xl text-amber-800 text-xs font-semibold flex items-center gap-2">
                                    <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Format berkas ({{ strtoupper($payment_receipt_photo->getClientOriginalExtension()) }}) tidak mendukung pratinjau langsung di browser, namun berkas tetap siap diunggah.</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan / Keterangan</label>
                    <textarea wire:model="payment_notes" rows="2" placeholder="Pembayaran termin 1 lahan ke Pak Pemilik Tanah..." class="input-clean w-full"></textarea>
                </div>
                
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="closePaymentModal" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">{{ !empty($editingPaymentId) ? 'Simpan Perubahan' : 'Simpan & Catat Kas Keluar' }}</button>
                </div>
            </form>
        </div>
    </div>
@endif
