<!-- MODAL 5: SETTLE UNIT COMMISSION -->
<x-modal-dialog show="showSettleCommissionModal" title="Bayar Cicilan Komisi Penjual Unit" subTitle="Proses pencatatan setoran komisi penjual ke arus kas keluar">
    <form wire:submit.prevent="processCommissionSettlement" class="space-y-4 text-xs">
        <x-currency-input
            label="Nominal Pembayaran Cicilan (Rp)"
            model="settle_comm_amount"
            :value="$settle_comm_amount"
            placeholder="2.500.000"
            badgeColor="purple"
            required
        />

        <div>
            <label class="block font-semibold text-slate-700 mb-1">Tanggal Bayar Komisi <span class="text-rose-500">*</span></label>
            <input type="date" wire:model="settle_comm_date" required class="input-clean w-full">
        </div>
        <div>
            <label class="block font-semibold text-slate-700 mb-1">Metode Pembayaran <span class="text-rose-500">*</span></label>
            <select wire:model="settle_comm_method" class="select-clean w-full font-semibold">
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="Cash / Tunai">Cash / Tunai</option>
            </select>
        </div>
        <div>
            <label class="block font-semibold text-slate-700 mb-1">Bukti Transfer / Kuitansi</label>
            <input type="file" wire:model="settle_comm_photo" accept="image/*,.pdf" class="input-clean w-full">
        </div>
        <div>
            <label class="block font-semibold text-slate-700 mb-1">Catatan</label>
            <input type="text" wire:model="settle_comm_notes" class="input-clean w-full">
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
            <button type="button" wire:click="$set('showSettleCommissionModal', false)" class="btn-secondary">Batal</button>
            <button type="submit" class="btn-primary bg-purple-600 hover:bg-purple-700">Konfirmasi Cicilan & Catat Kas Keluar</button>
        </div>
    </form>
</x-modal-dialog>
