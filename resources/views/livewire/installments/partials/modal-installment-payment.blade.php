<!-- Modal Catat Pembayaran Setoran -->
@if($showPaymentModal && $activeInstallment)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-md w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">Catat Setoran Cicilan</h3>
                    <p class="text-slate-500 text-[11px]">Unit: <span class="font-bold text-slate-800 font-mono">{{ $activeInstallment->unit->code }}</span> (Pembeli: {{ $activeInstallment->buyer_name }})</p>
                </div>
                <button wire:click="$set('showPaymentModal', false)" class="text-slate-400 hover:text-slate-600 font-bold text-sm p-1">✕</button>
            </div>

            <!-- Form Body -->
            <form wire:submit.prevent="submitPayment" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <div class="bg-slate-900 text-white rounded-xl p-3.5 space-y-1.5 shadow-inner text-xs sm:text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Total Sisa Piutang:</span>
                        <span class="font-mono font-bold text-rose-400">Rp {{ number_format($activeInstallment->remaining_balance, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Standar Cicilan Bulanan:</span>
                        <span class="font-mono text-emerald-400">Rp {{ number_format($activeInstallment->installment_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nominal Pembayaran Diterima <span class="text-rose-500">*</span></label>
                    <div class="flex rounded-xl shadow-xs">
                        <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                            Rp
                        </span>
                        <x-currency-input model="payment_amount" class="input-clean rounded-r-xl rounded-l-none font-bold text-xs sm:text-sm font-mono w-full" placeholder="0" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Metode Bayar <span class="text-rose-500">*</span></label>
                        <select wire:model="payment_method" class="select-clean w-full font-semibold">
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Tunai / Cash">Tunai / Cash</option>
                            <option value="Cek / Giro">Cek / Giro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Bayar <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="payment_date" required class="input-clean w-full font-mono text-xs sm:text-sm">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan Pembayaran</label>
                    <input type="text" wire:model="payment_notes" placeholder="Setoran bulan ke-2..." class="input-clean w-full text-xs sm:text-sm">
                </div>

                <!-- Upload & Live Photo Preview Area -->
                <div class="space-y-2">
                    <label class="block font-semibold text-slate-700 text-xs uppercase tracking-wider">
                        Foto Resi
                    </label>

                    <div class="bg-slate-50/80 border border-slate-200/90 rounded-2xl p-3 sm:p-4 space-y-3">
                        <input type="file" id="installment_payment_receipt_photo_input_inst" wire:model="payment_receipt_photo" accept="image/*,.pdf" class="hidden">

                        @if($payment_receipt_photo)
                            <!-- Live Upload Preview Card -->
                            @php
                                $isNewImageInst = false;
                                try {
                                    $ext = strtolower($payment_receipt_photo->getClientOriginalExtension());
                                    $isNewImageInst = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'heic', 'heif', 'gif']);
                                } catch (\Throwable $e) {}
                            @endphp

                            <div class="relative bg-white border border-slate-200 rounded-2xl p-3 space-y-2.5 shadow-2xs">
                                <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-2">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-xs font-bold text-slate-800 block truncate">{{ $payment_receipt_photo->getClientOriginalName() }}</span>
                                            <span class="text-[10px] text-emerald-600 font-semibold block">✓ Foto resi baru terpilih (Siap diunggah)</span>
                                        </div>
                                    </div>
                                    <button type="button" wire:click="$set('payment_receipt_photo', null)" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-[11px] font-bold transition shrink-0">
                                        Hapus
                                    </button>
                                </div>

                                @if($isNewImageInst)
                                    <div class="relative group rounded-xl overflow-hidden bg-slate-950/5 border border-slate-200 flex items-center justify-center max-h-52 sm:max-h-64 p-1">
                                        <img src="{{ $payment_receipt_photo->temporaryUrl() }}" alt="Pratinjau Resi Transfer Baru" class="max-h-48 sm:max-h-56 w-auto object-contain rounded-lg shadow-xs">
                                        <div class="absolute bottom-2 right-2 bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-mono px-2 py-0.5 rounded-md font-semibold">
                                            Pratinjau Foto Resi
                                        </div>
                                    </div>
                                @else
                                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs font-semibold flex items-center gap-2">
                                        <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <span>Dokumen PDF Terpilih</span>
                                    </div>
                                @endif
                            </div>

                        @else
                            <!-- Touch Upload Card (No Photo Selected Yet) -->
                            <label for="installment_payment_receipt_photo_input_inst" class="border-2 border-dashed border-slate-300 hover:border-blue-500 bg-white rounded-2xl p-4 sm:p-5 flex flex-col items-center justify-center text-center cursor-pointer transition active:scale-[0.99] group shadow-2xs">
                                <div class="p-3 rounded-2xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition duration-300 mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <span class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-blue-700 block">Ketuk untuk Ambil Foto / Upload Resi</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">Mendukung Kamera HP, Galeri (JPG, PNG, WEBP, HEIC, PDF)</span>
                            </label>
                        @endif

                        <!-- Loading Spinner -->
                        <div wire:loading wire:target="payment_receipt_photo" class="text-xs text-blue-600 font-semibold p-2 bg-blue-50 rounded-xl border border-blue-200 flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-blue-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Sedang memproses foto resi...</span>
                        </div>
                        @error('payment_receipt_photo') <span class="text-rose-600 text-[10px] mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <button type="button" wire:click="$set('showPaymentModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan & Masukkan Kas</button>
                </div>
            </form>
        </div>
    </div>
@endif
