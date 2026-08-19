<!-- Modal Form Bayar Cicilan Komisi Penjual Unit -->
@if($showCommissionPaymentModal)
    <x-modal-dialog show="showCommissionPaymentModal" 
                    title="Bayar Cicilan Komisi Penjual" 
                    subTitle="Unit {{ $unit->code }} • Mencatat Kas Keluar di Arus Kas Global" 
                    maxWidth="max-w-lg">
        <form wire:submit.prevent="processCommissionPayment" class="space-y-4 text-xs">
            <x-currency-input
                label="Nominal Pembayaran Cicilan (Rp)"
                model="unit_pay_comm_amount"
                :value="$unit_pay_comm_amount"
                placeholder="1.500.000"
                badgeColor="emerald"
                required
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="unit_pay_comm_date" class="input-clean w-full text-xs" required>
                    @error('unit_pay_comm_date') <span class="text-red-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                    <select wire:model="unit_pay_comm_method" class="select-clean w-full text-xs font-semibold" required>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Cash / Tunai">Cash / Tunai</option>
                        <option value="Cek / Giro">Cek / Giro</option>
                    </select>
                    @error('unit_pay_comm_method') <span class="text-red-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <x-receipt-upload 
                model="unit_pay_comm_photo" 
                :photo="$unit_pay_comm_photo" 
                label="Upload Bukti Transfer / Resi (Opsional)"
            />

            <div>
                <label class="block font-bold text-slate-700 mb-1">Catatan / Keterangan</label>
                <textarea wire:model="unit_pay_comm_notes" rows="2" placeholder="Catatan termin cicilan..." class="input-clean w-full text-xs"></textarea>
                @error('unit_pay_comm_notes') <span class="text-red-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <x-button type="button" variant="secondary" wire:click="$set('showCommissionPaymentModal', false)">Batal</x-button>
                <x-button type="submit" variant="emerald" loadingTarget="processCommissionPayment">Konfirmasi & Catat Kas Keluar</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
