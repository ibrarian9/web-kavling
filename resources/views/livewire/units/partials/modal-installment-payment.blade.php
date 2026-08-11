<!-- Modal Input Setoran Cicilan Pembeli (Khusus Finance & Founder) -->
@if($showInstallmentPaymentModal && $unit->installment)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <h3 class="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Input Setoran Cicilan Unit {{ $unit->code }}
                </h3>
                <button wire:click="$set('showInstallmentPaymentModal', false)" class="text-slate-400 hover:text-slate-600 text-sm">✕</button>
            </div>

            <form wire:submit.prevent="saveInstallmentPayment" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <div class="bg-blue-50 border border-blue-100 p-3 rounded-xl space-y-1">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Target Cicilan per Bulan:</span>
                        <span class="font-bold font-mono text-blue-800">Rp {{ number_format($unit->installment->installment_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Sisa Tagihan Cicilan:</span>
                        <span class="font-bold font-mono text-amber-700">Rp {{ number_format($unit->installment->remaining_balance, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Setoran <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="installment_payment_date" class="input-clean w-full font-mono text-xs sm:text-sm">
                    @error('installment_payment_date') <span class="text-rose-600 text-[10px] mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nominal Setoran <span class="text-rose-500">*</span></label>
                    <div class="flex rounded-xl shadow-xs">
                        <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                            Rp
                        </span>
                        <x-currency-input model="installment_payment_amount" class="input-clean rounded-r-xl rounded-l-none font-mono font-bold text-xs sm:text-sm w-full" placeholder="Contoh: 5.000.000" />
                    </div>
                    @error('installment_payment_amount') <span class="text-rose-600 text-[10px] mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Metode Pembayaran <span class="text-rose-500">*</span></label>
                    <select wire:model="installment_payment_method" class="select-clean w-full">
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Tunai">Tunai / Cash</option>
                        <option value="Cek / Giro">Cek / Giro</option>
                    </select>
                    @error('installment_payment_method') <span class="text-rose-600 text-[10px] mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan / Keterangan</label>
                    <textarea wire:model="installment_payment_notes" rows="2" class="input-clean w-full text-xs sm:text-sm" placeholder="Setoran cicilan bulan ke-X..."></textarea>
                </div>

                <!-- Upload & Live Photo Preview Area -->
                <div class="space-y-2">
                    <label class="block font-semibold text-slate-700 text-xs uppercase tracking-wider">
                        Foto Resi
                    </label>

                    <div class="bg-slate-50/80 border border-slate-200/90 rounded-2xl p-3 sm:p-4 space-y-3">
                        <input type="file" id="installment_payment_receipt_photo_input" wire:model="installment_payment_receipt_photo" accept="image/*,.pdf" class="hidden">

                        @if($installment_payment_receipt_photo)
                            <!-- Live Upload Preview Card -->
                            @php
                                $isNewImage = false;
                                try {
                                    $ext = strtolower($installment_payment_receipt_photo->getClientOriginalExtension());
                                    $isNewImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'heic', 'heif', 'gif']);
                                } catch (\Throwable $e) {}
                            @endphp

                            <div class="relative bg-white border border-slate-200 rounded-2xl p-3 space-y-2.5 shadow-2xs">
                                <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-xs font-bold text-slate-800 block truncate">{{ $installment_payment_receipt_photo->getClientOriginalName() }}</span>
                                            <span class="text-[10px] text-emerald-600 font-semibold block">✓ Foto resi baru terpilih (Siap diunggah)</span>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="$set('installment_payment_receipt_photo', null)" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-[11px] font-bold transition shrink-0">
                                        Hapus
                                    </button>
                                </div>

                                @if($isNewImage)
                                    <div class="relative group rounded-xl overflow-hidden bg-slate-950/5 border border-slate-200 flex items-center justify-center max-h-52 sm:max-h-64 p-1">
                                        <img src="{{ $installment_payment_receipt_photo->temporaryUrl() }}" alt="Pratinjau Resi Transfer Baru" class="max-h-48 sm:max-h-56 w-auto object-contain rounded-lg shadow-xs">
                                        <div class="absolute bottom-2 right-2 bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-mono px-2 py-0.5 rounded-md font-semibold">
                                            Pratinjau Foto Resi Baru
                                        </div>
                                    </div>
                                @else
                                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs font-semibold flex items-center gap-2">
                                        <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <span>Dokumen PDF Terpilih</span>
                                    </div>
                                @endif
                            </div>

                        @elseif(!empty($existing_installment_receipt_photo_path))
                            <!-- Existing Stored Photo Card -->
                            <div class="bg-white border border-slate-200 rounded-2xl p-3 space-y-2.5 shadow-2xs">
                                <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 rounded-lg bg-blue-50 text-blue-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div>
                                            <span class="text-xs font-bold text-slate-800 block">Foto Resi Transfer Tersimpan</span>
                                            <span class="text-[10px] text-slate-500 block">Sudah ada foto resi pada setoran ini</span>
                                        </div>
                                    </div>
                                    <label for="installment_payment_receipt_photo_input" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-[11px] font-bold cursor-pointer transition shrink-0">
                                        Ganti Foto
                                    </label>
                                </div>

                                <div class="relative group rounded-xl overflow-hidden bg-slate-950/5 border border-slate-200 flex items-center justify-center max-h-52 sm:max-h-64 p-1">
                                    <img src="{{ asset('storage/' . $existing_installment_receipt_photo_path) }}" alt="Foto Resi Transfer Saat Ini" class="max-h-48 sm:max-h-56 w-auto object-contain rounded-lg shadow-xs">
                                    <button type="button" wire:click="openViewerModal('image', '{{ asset('storage/' . $existing_installment_receipt_photo_path) }}', 'Foto Resi Transfer Bank - Unit {{ $unit->code }}')" class="absolute top-2 right-2 px-2 py-1 bg-slate-900/80 hover:bg-slate-900 text-white text-[10px] font-bold rounded-lg backdrop-blur-xs transition flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Perbesar</span>
                                    </button>
                                </div>
                            </div>

                        @else
                            <!-- Touch Upload Card (No Photo Selected Yet) -->
                            <label for="installment_payment_receipt_photo_input" class="border-2 border-dashed border-slate-300 hover:border-blue-500 bg-white rounded-2xl p-4 sm:p-5 flex flex-col items-center justify-center text-center cursor-pointer transition active:scale-[0.99] group shadow-2xs">
                                <div class="p-3 rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition duration-300 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-blue-700 block">Ketuk untuk Ambil Foto / Upload Resi</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Mendukung Kamera HP, Galeri (JPG, PNG, WEBP, HEIC, PDF)</span>
                            </label>
                        @endif

                        <!-- Loading Spinner -->
                        <div wire:loading wire:target="installment_payment_receipt_photo" class="text-xs text-blue-600 font-semibold p-2 bg-blue-50 rounded-xl border border-blue-200 flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-blue-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Sedang memproses & memuat foto resi...</span>
                        </div>
                        @error('installment_payment_receipt_photo') <span class="text-rose-600 text-[10px] mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <button type="button" wire:click="$set('showInstallmentPaymentModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary bg-blue-600 hover:bg-blue-700">Simpan Setoran</button>
                </div>
            </form>
        </div>
    </div>
@endif
