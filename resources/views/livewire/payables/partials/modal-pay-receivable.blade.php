<!-- MODAL 7: PAY RECEIVABLE / TERIMA PENGEMBALIAN KASBON -->
<x-modal-dialog show="showPayReceivableModal" title="Terima Pengembalian Piutang / Kasbon" subTitle="Pencatatan setoran pengembalian kasbon otomatis menambah Kas Masuk Global">
    <form wire:submit.prevent="processReceivablePayment" class="space-y-4 text-xs">
        <x-currency-input
            label="Nominal Setoran Pengembalian (Rp)"
            model="pay_rec_amount"
            :value="$pay_rec_amount"
            placeholder="1.000.000"
            badgeColor="emerald"
            helpText="*Nominal ini akan dicatat otomatis sebagai KAS MASUK GLOBAL di Arus Kas Keuangan."
            required
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Tanggal Terima Setoran <span class="text-rose-500">*</span></label>
                <input type="date" wire:model="pay_rec_date" required class="input-clean w-full">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Metode Setoran <span class="text-rose-500">*</span></label>
                <select wire:model="pay_rec_method" class="select-clean w-full font-semibold">
                    <option value="Cash / Tunai">Cash / Tunai</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block font-semibold text-slate-700 mb-1">Upload Bukti Transfer / Resi Setoran (Opsional)</label>
            <input type="file" wire:model="pay_rec_photo" accept="image/*,.pdf" class="input-clean w-full">
        </div>

        <div>
            <label class="block font-semibold text-slate-700 mb-1">Catatan Setoran</label>
            <input type="text" wire:model="pay_rec_notes" placeholder="Setoran tunai via kasir kantor..." class="input-clean w-full">
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
            <button type="button" wire:click="$set('showPayReceivableModal', false)" class="btn-secondary">Batal</button>
            <button type="submit" class="btn-primary bg-emerald-600 hover:bg-emerald-700">Konfirmasi Setoran & Catat Kas Masuk</button>
        </div>
    </form>
</x-modal-dialog>
