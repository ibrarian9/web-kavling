<!-- Modal Batalkan Skema Cicilan & Ganti ke Pelunasan Cash -->
@if($showConvertToCashModal && $activeConvertToCashInstallment)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Batalkan Skema Cicilan & Ganti Ke Pelunasan Cash</h3>
                    <p class="text-slate-500 text-[11px]">Unit: <span class="font-bold text-slate-800 font-mono">{{ $activeConvertToCashInstallment->unit->code }}</span> - {{ $activeConvertToCashInstallment->buyer_name }}</p>
                </div>
                <button wire:click="$set('showConvertToCashModal', false)" class="text-slate-400 hover:text-slate-600 font-bold text-sm p-1">✕</button>
            </div>

            <!-- Form Body -->
            <form wire:submit.prevent="submitConvertToCash" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <div class="p-4 bg-purple-50/80 border border-purple-200/80 rounded-2xl space-y-2 text-purple-950 text-xs sm:text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Total Harga Unit:</span>
                        <span class="font-mono font-bold">Rp {{ number_format($activeConvertToCashInstallment->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Sudah Terbayar (DP & Cicilan):</span>
                        <span class="font-mono font-bold text-emerald-700">Rp {{ number_format($activeConvertToCashInstallment->total_paid, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-purple-200 font-extrabold">
                        <span class="text-purple-900">Sisa Pelunasan Cash:</span>
                        <span class="font-mono text-purple-800 text-sm sm:text-base">Rp {{ number_format($activeConvertToCashInstallment->remaining_balance, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div>
                    <x-currency-input 
                        label="Nominal Pelunasan Cash Diterima (Rp)"
                        model="cash_payment_amount" 
                        :value="$cash_payment_amount"
                        placeholder="0" 
                        badgeColor="purple"
                        helpText="Sisa saldo Rp {{ number_format($activeConvertToCashInstallment->remaining_balance, 0, ',', '.') }} akan dicatat lunas sekaligus dalam Arus Kas."
                        required
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Metode Pembayaran Cash <span class="text-rose-500">*</span></label>
                        <select wire:model="cash_payment_method" class="select-clean w-full font-semibold">
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Tunai / Cash">Tunai / Cash</option>
                            <option value="Cek / Giro">Cek / Giro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Pelunasan <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="cash_payment_date" required class="input-clean w-full font-mono text-xs sm:text-sm">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan Alasan Pembatalan & Konversi Cash</label>
                    <textarea wire:model="cash_notes" rows="2" class="input-clean w-full text-xs sm:text-sm" placeholder="Keterangan pembatalan skema cicilan..."></textarea>
                </div>

                <!-- Footer Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <button type="button" wire:click="$set('showConvertToCashModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary bg-purple-600 hover:bg-purple-700">Proses Pelunasan Cash & Batalkan Cicilan</button>
                </div>
            </form>
        </div>
    </div>
@endif
