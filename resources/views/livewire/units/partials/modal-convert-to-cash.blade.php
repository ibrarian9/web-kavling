<!-- Modal Batalkan Skema Cicilan & Ganti Ke Pelunasan Cash (Founder & Finance) -->
@if($showConvertToCashModal && $unit->installment)
    <x-modal-dialog show="showConvertToCashModal" 
                    title="Batalkan Skema Cicilan & Ganti Ke Pelunasan Cash" 
                    subTitle="Unit: {{ $unit->code }} - {{ $unit->buyer_name }}" 
                    maxWidth="max-w-lg">
        <form wire:submit.prevent="saveConvertToCash" class="space-y-4 text-xs">
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

            <x-currency-input 
                label="Nominal Pelunasan Cash Diterima (Rp)" 
                model="cash_payment_amount" 
                :value="$cash_payment_amount"
                placeholder="0"
                badgeColor="purple"
                helpText="Sisa saldo Rp {{ number_format($unit->installment->remaining_balance, 0, ',', '.') }} akan dicatat lunas sekaligus dalam Arus Kas."
                required
            />

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Metode Pembayaran Cash <span class="text-rose-500">*</span></label>
                    <select wire:model="cash_payment_method" class="select-clean w-full">
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Tunai / Cash">Tunai / Cash</option>
                        <option value="Cek / Giro">Cek / Giro</option>
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

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="secondary" wire:click="$set('showConvertToCashModal', false)">Batal</x-button>
                <x-button type="submit" variant="purple" loadingTarget="saveConvertToCash">Proses Pelunasan Cash & Batalkan Cicilan</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
