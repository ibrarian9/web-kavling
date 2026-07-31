<!-- Modal Batalkan Skema Cicilan & Ganti Ke Pelunasan Cash (Founder & Finance) -->
@if($showConvertToCashModal && $unit->installment)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Batalkan Skema Cicilan & Ganti Ke Pelunasan Cash</h3>
                    <p class="text-slate-500 text-[11px]">Unit: <span class="font-bold text-slate-800 font-mono">{{ $unit->code }}</span> - {{ $unit->officialDocument->buyer_name ?? 'Pembeli' }}</p>
                </div>
                <button wire:click="$set('showConvertToCashModal', false)" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form wire:submit.prevent="saveConvertToCash" class="space-y-4 text-xs flex-1 overflow-y-auto pr-1">
                <div class="p-4 bg-purple-50/80 border border-purple-200/80 rounded-2xl space-y-2 text-purple-950">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Total Harga Unit:</span>
                        <span class="font-mono font-bold">Rp {{ number_format($unit->installment->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Sudah Terbayar (DP & Cicilan):</span>
                        <span class="font-mono font-bold text-emerald-700">Rp {{ number_format($unit->installment->total_paid, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-purple-200 font-extrabold">
                        <span class="text-purple-900">Sisa Pelunasan Cash:</span>
                        <span class="font-mono text-purple-800 text-sm">Rp {{ number_format($unit->installment->remaining_balance, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nominal Pelunasan Cash Diterima <span class="text-rose-500">*</span></label>
                    <div class="flex rounded-xl shadow-xs">
                        <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                            Rp
                        </span>
                        <x-currency-input model="cash_payment_amount" class="input-clean rounded-r-xl rounded-l-none font-bold text-sm font-mono text-purple-900 bg-purple-50/30 w-full" placeholder="0" />
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1 font-medium">Sisa saldo Rp {{ number_format($unit->installment->remaining_balance, 0, ',', '.') }} akan dicatat lunas sekaligus dalam Arus Kas.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Metode Pembayaran Cash <span class="text-rose-500">*</span></label>
                        <select wire:model="cash_payment_method" class="select-clean w-full">
                            <option value="Transfer Bank">🏦 Transfer Bank</option>
                            <option value="Tunai / Cash">💵 Tunai / Cash</option>
                            <option value="Cek / Giro">📜 Cek / Giro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Pelunasan <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="cash_payment_date" required class="input-clean w-full font-mono">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan Alasan Pembatalan & Konversi Cash</label>
                    <textarea wire:model="cash_notes" rows="2" class="input-clean w-full" placeholder="Keterangan pembatalan skema cicilan..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 shrink-0">
                    <button type="button" wire:click="$set('showConvertToCashModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary bg-purple-600 hover:bg-purple-700">Proses Pelunasan Cash & Batalkan Cicilan</button>
                </div>
            </form>
        </div>
    </div>
@endif
