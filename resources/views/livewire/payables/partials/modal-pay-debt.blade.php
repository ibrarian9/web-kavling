<!-- MODAL: PAY COMPANY DEBT / BAYAR CICILAN UTANG -->
<x-modal-dialog show="showPayDebtModal" title="Pembayaran Cicilan / Pelunasan Utang" subTitle="Pencatatan pembayaran cicilan atau pelunasan utang otomatis dicatat sebagai KAS KELUAR di Arus Kas Global">
    <form wire:submit.prevent="processDebtPayment" class="space-y-4 text-xs">
        <x-currency-input
            label="Nominal Pembayaran / Cicilan (Rp)"
            model="pay_debt_amount"
            :value="$pay_debt_amount"
            placeholder="5.000.000"
            badgeColor="rose"
            helpText="*Nominal ini akan dicatat otomatis sebagai KAS KELUAR di Arus Kas Global."
            required
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Tanggal Pembayaran <span class="text-rose-500">*</span></label>
                <input type="date" wire:model="pay_debt_date" required class="input-clean w-full">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Metode Pembayaran <span class="text-rose-500">*</span></label>
                <select wire:model="pay_debt_method" class="select-clean w-full font-semibold">
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="Cash / Tunai">Cash / Tunai</option>
                    <option value="Giro / Cek">Giro / Cek</option>
                </select>
            </div>
        </div>

        <x-receipt-upload 
            model="pay_debt_photo" 
            :photo="$pay_debt_photo" 
            label="Upload Bukti Transfer / Resi Pembayaran (Opsional)"
        />

        <div>
            <label class="block font-semibold text-slate-700 mb-1">Catatan Pembayaran</label>
            <input type="text" wire:model="pay_debt_notes" placeholder="Contoh: Pembayaran termin ke-1 pekerjaan saluran..." class="input-clean w-full">
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
            <x-button type="button" variant="secondary" wire:click="$set('showPayDebtModal', false)">Batal</x-button>
            <x-button type="submit" variant="rose">Konfirmasi Pembayaran & Catat Kas Keluar</x-button>
        </div>
    </form>
</x-modal-dialog>
