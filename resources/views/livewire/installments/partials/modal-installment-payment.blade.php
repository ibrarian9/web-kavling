<!-- Modal Catat / Edit Pembayaran Setoran -->
@if($showPaymentModal && $activeInstallment)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-md w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div>
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">{{ !empty($editingPaymentId) ? 'Edit Setoran Cicilan' : 'Catat Setoran Cicilan' }}</h3>
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
                    <x-currency-input 
                        label="Nominal Pembayaran Diterima (Rp)"
                        model="payment_amount" 
                        :value="$payment_amount"
                        placeholder="0" 
                        badgeColor="emerald"
                        required
                    />
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
                <x-receipt-upload 
                    model="payment_receipt_photo" 
                    :photo="$payment_receipt_photo" 
                    :existingPath="$existing_receipt_photo_path" 
                    label="Foto Resi / Bukti Transfer"
                />

                <!-- Footer Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <x-button variant="secondary" size="md" type="button" wire:click="$set('showPaymentModal', false)">Batal</x-button>
                    <x-button variant="primary" size="md" type="submit">{{ !empty($editingPaymentId) ? 'Simpan Perubahan Setoran' : 'Simpan & Masukkan Kas' }}</x-button>
                </div>
            </form>
        </div>
    </div>
@endif
