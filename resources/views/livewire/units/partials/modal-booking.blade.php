<!-- Modal Form Booking Unit Directly from Detail Page -->
@if($showBookingModal)
    <x-modal-dialog show="showBookingModal" 
                    title="Booking Unit {{ $unit->code }}" 
                    subTitle="Pencatatan booking & DP langsung di dalam sistem" 
                    maxWidth="max-w-md">
        <form wire:submit.prevent="saveBooking" class="space-y-4 text-xs sm:text-sm">
            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nama Pembeli <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="buyer_name" required placeholder="Bpk. H. Hendra Wijaya" class="input-clean w-full font-bold text-xs sm:text-sm">
                @error('buyer_name') <span class="text-rose-500 text-[10px] mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nomor HP / WhatsApp Pembeli <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="buyer_phone" required placeholder="081234567890" class="input-clean w-full font-mono text-xs sm:text-sm">
                @error('buyer_phone') <span class="text-rose-500 text-[10px] mt-1 block font-medium">{{ $message }}</span> @enderror
            </div>

            <x-currency-input
                label="Nominal Tanda Jadi / Booking Fee (Rp)"
                model="booking_amount"
                :value="$booking_amount"
                placeholder="5.000.000"
                badgeColor="blue"
                required
            />

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan Pembayaran & Bukti DP</label>
                <textarea wire:model="booking_notes" rows="2" placeholder="Informasi bukti transfer DP..." class="input-clean w-full text-xs sm:text-sm"></textarea>
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
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Berkas Terpilih ({{ method_exists($receipt_photo, 'getClientOriginalName') ? $receipt_photo->getClientOriginalName() : 'Foto Resi' }}):</span>
                            </span>
                            <button type="button" wire:click="$set('receipt_photo', null)" class="text-rose-500 hover:text-rose-700 text-[10px] underline font-bold">Hapus Foto</button>
                        </div>
                        @if (is_object($receipt_photo) && method_exists($receipt_photo, 'isPreviewable') && $receipt_photo->isPreviewable())
                            <div class="relative max-h-36 sm:max-h-40 overflow-y-auto rounded-xl border border-slate-200 bg-slate-900 flex items-center justify-center p-1.5">
                                <img src="{{ $receipt_photo->temporaryUrl() }}" alt="Preview Resi" class="max-h-32 sm:max-h-36 w-auto max-w-full object-contain rounded-lg shadow-sm">
                            </div>
                        @else
                            <div class="p-2.5 bg-amber-50 border border-amber-200/80 rounded-xl text-amber-800 text-[11px] font-semibold flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Format berkas siap diunggah. Pratinjau langsung didukung untuk file gambar (JPG/PNG).</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="secondary" wire:click="$set('showBookingModal', false)">Batal</x-button>
                <x-button type="submit" variant="primary" loadingTarget="saveBooking">Proses Booking Unit</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
