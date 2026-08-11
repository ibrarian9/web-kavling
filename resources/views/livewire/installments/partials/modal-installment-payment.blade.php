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

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Foto Resi Struk / Bukti Transfer Bank</label>
                    <input type="file" wire:model="payment_receipt_photo" accept="image/*,.pdf" class="input-clean w-full text-xs">
                    <div wire:loading wire:target="payment_receipt_photo" class="text-xs text-blue-600 font-semibold mt-1 flex items-center gap-1">
                        <svg class="animate-spin h-3.5 w-3.5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Mengunggah resi transfer...</span>
                    </div>
                    @error('payment_receipt_photo') <span class="text-rose-600 text-[10px] mt-1 block font-medium">{{ $message }}</span> @enderror
                    @if ($payment_receipt_photo)
                        <div class="mt-2 text-xs text-emerald-600 font-semibold flex items-center gap-1">
                            <span>✓ File resi terpilih: {{ $payment_receipt_photo->getClientOriginalName() }}</span>
                        </div>
                    @endif
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
