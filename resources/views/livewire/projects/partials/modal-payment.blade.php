<!-- MODAL CATAT PEMBAYARAN LAHAN KE PENJUAL -->
@if($showPaymentModal)
    <x-modal-dialog show="showPaymentModal" 
                    closeAction="closePaymentModal" 
                    title="{{ !empty($editingPaymentId) ? 'Edit Pembayaran Lahan' : 'Catat Pembayaran Lahan ke Penjual' }}" 
                    subTitle="Proyek: {{ $project->name }}" 
                    maxWidth="max-w-md">
        <form wire:submit.prevent="saveProjectPayment" class="space-y-4 text-xs">
            <div>
                <x-currency-input 
                    label="Jumlah Dibayar (Rp)" 
                    model="payment_amount" 
                    placeholder="0" 
                    badgeColor="emerald"
                    required 
                />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Tanggal Pembayaran <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="payment_date" class="input-clean w-full font-mono">
                    @error('payment_date') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Metode Pembayaran</label>
                    <select wire:model="payment_method" class="select-clean w-full">
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
            
            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <x-button type="button" variant="secondary" wire:click="closePaymentModal">Batal</x-button>
                <x-button type="submit" variant="primary" loadingTarget="saveProjectPayment">{{ !empty($editingPaymentId) ? 'Simpan Perubahan' : 'Simpan & Catat Kas Keluar' }}</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
