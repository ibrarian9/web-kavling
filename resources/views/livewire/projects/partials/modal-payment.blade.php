<!-- MODAL CATAT PEMBAYARAN LAHAN KE PENJUAL -->
@if($showPaymentModal)
    <x-modal-dialog show="showPaymentModal" 
                    closeAction="closePaymentModal" 
                    title="{{ !empty($editingPaymentId) ? 'Edit Pembayaran Lahan' : 'Catat Pembayaran Lahan ke Penjual' }}" 
                    subTitle="Proyek: {{ $project->name }}" 
                    maxWidth="max-w-md">
        <form wire:submit.prevent="saveProjectPayment" class="space-y-4 text-xs">
            <div>
                <x-currency-input 
                    label="Jumlah Dibayar (Rp)" 
                    model="payment_amount" 
                    placeholder="0" 
                    badgeColor="emerald"
                    required 
                />
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Tanggal Pembayaran <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="payment_date" class="input-clean w-full font-mono">
                    @error('payment_date') <span class="text-rose-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Metode Pembayaran</label>
                    <select wire:model="payment_method" class="select-clean w-full">
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Tunai / Cash">Tunai / Cash</option>
                        <option value="Giro / Cek">Giro / Cek</option>
                    </select>
                </div>
            </div>

            <x-receipt-upload 
                model="payment_receipt_photo" 
                :photo="$payment_receipt_photo" 
                label="Foto Resi / Bukti Transfer Lahan"
            />

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan / Keterangan</label>
                <textarea wire:model="payment_notes" rows="2" placeholder="Pembayaran termin 1 lahan ke Pak Pemilik Tanah..." class="input-clean w-full"></textarea>
            </div>
            
            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <x-button type="button" variant="secondary" wire:click="closePaymentModal">Batal</x-button>
                <x-button type="submit" variant="primary" loadingTarget="saveProjectPayment">{{ !empty($editingPaymentId) ? 'Simpan Perubahan' : 'Simpan & Catat Kas Keluar' }}</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
