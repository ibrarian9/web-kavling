<!-- Modal Input Setoran Cicilan Pembeli (Khusus Finance & Founder) -->
@if($showInstallmentPaymentModal && $unit->installment)
    <x-modal-dialog show="showInstallmentPaymentModal" 
                    title="Input Setoran Cicilan Unit {{ $unit->code }}" 
                    subTitle="Pencatatan pembayaran angsuran konsumen" 
                    maxWidth="max-w-lg">
        <form wire:submit.prevent="saveInstallmentPayment" class="space-y-4 text-xs sm:text-sm">
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

            <x-currency-input 
                label="Nominal Setoran (Rp)" 
                model="installment_payment_amount" 
                :value="$installment_payment_amount"
                placeholder="5.000.000"
                badgeColor="emerald"
                required
            />

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
            <x-receipt-upload 
                model="installment_payment_receipt_photo" 
                :photo="$installment_payment_receipt_photo" 
                :existingPath="$existing_installment_receipt_photo_path" 
                label="Foto Resi / Bukti Transfer"
            />

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="secondary" wire:click="$set('showInstallmentPaymentModal', false)">Batal</x-button>
                <x-button type="submit" variant="blue" loadingTarget="saveInstallmentPayment">Simpan Setoran</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
