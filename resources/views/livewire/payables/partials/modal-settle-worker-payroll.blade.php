<!-- MODAL 2: WORKER SALARY PAYMENT -->
@if($showWorkerPaymentModal)
    <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white border border-slate-200 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-slate-900 text-base">Pembayaran Upah Pekerja Lapangan</h3>
                <button wire:click="$set('showWorkerPaymentModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form wire:submit.prevent="processWorkerPayment" class="space-y-4 text-xs">
                <x-currency-input
                    label="Nominal Pembayaran Upah (Rp)"
                    model="worker_payment_amount"
                    :value="$worker_payment_amount"
                    placeholder="1.000.000"
                    badgeColor="blue"
                    required
                />
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Tanggal Pembayaran <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="worker_payment_date" required class="input-clean w-full">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Metode Pembayaran <span class="text-rose-500">*</span></label>
                    <select wire:model="worker_payment_method" class="select-clean w-full font-semibold">
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Cash / Tunai">Cash / Tunai</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Catatan / Termin</label>
                    <input type="text" wire:model="worker_payment_notes" class="input-clean w-full">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="$set('showWorkerPaymentModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary bg-blue-600 hover:bg-blue-700">Konfirmasi Pembayaran & Catat Kas Keluar</button>
                </div>
            </form>
        </div>
    </div>
@endif
