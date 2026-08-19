<!-- MODAL 1: SETTLEMENT MATERIAL BILL -->
<x-modal-dialog show="showSettleModal" title="Pelunasan Tagihan Material Toko" subTitle="Proses pelunasan tagihan material dan catat ke arus kas keluar">
    <form wire:submit.prevent="processMaterialSettlement" class="space-y-4 text-xs">
        <div>
            <label class="block font-semibold text-slate-700 mb-1">Tanggal Bayar Lunas <span class="text-rose-500">*</span></label>
            <input type="date" wire:model="settle_payment_date" required class="input-clean w-full">
        </div>
        <div>
            <label class="block font-semibold text-slate-700 mb-1">Metode Pembayaran <span class="text-rose-500">*</span></label>
            <select wire:model="settle_payment_method" class="select-clean w-full font-semibold">
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="Cash / Tunai">Cash / Tunai</option>
            </select>
        </div>
        <x-receipt-upload 
            model="settle_receipt_photo" 
            :photo="$settle_receipt_photo" 
            label="Bukti Transfer / Resi Nota (Opsional)"
        />
        <div>
            <label class="block font-semibold text-slate-700 mb-1">Catatan</label>
            <input type="text" wire:model="settle_notes" class="input-clean w-full">
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
            <x-button type="button" variant="secondary" wire:click="$set('showSettleModal', false)">Batal</x-button>
            <x-button type="submit" variant="emerald">Pelunasan Lunas & Catat Kas Keluar</x-button>
        </div>
    </form>
</x-modal-dialog>
