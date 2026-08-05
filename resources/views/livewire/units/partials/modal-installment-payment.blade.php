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
                        <option value="Transfer Bank">Transfer Bank (BRK Syariah / Mandiri / BRI / BCA)</option>
                        <option value="Tunai">Tunai / Cash</option>
                        <option value="Cek / Giro">Cek / Giro</option>
                    </select>
                    @error('installment_payment_method') <span class="text-rose-600 text-[10px] mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan / Keterangan</label>
                    <textarea wire:model="installment_payment_notes" rows="2" class="input-clean w-full text-xs sm:text-sm" placeholder="Setoran cicilan bulan ke-X..."></textarea>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <button type="button" wire:click="$set('showInstallmentPaymentModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary bg-blue-600 hover:bg-blue-700">Simpan Setoran</button>
                </div>
            </form>
        </div>
    </div>
@endif
