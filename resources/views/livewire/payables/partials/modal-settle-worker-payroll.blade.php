<!-- MODAL 2: WORKER SALARY PAYMENT -->
<x-modal-dialog show="showWorkerPaymentModal" title="Pembayaran Upah Pekerja Lapangan" subTitle="Proses pencatatan setoran upah tukang/mandor ke arus kas keluar">
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
</x-modal-dialog>
