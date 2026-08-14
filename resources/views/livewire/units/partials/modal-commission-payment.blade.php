<!-- Modal Form Bayar Cicilan Komisi Penjual Unit -->
@if($showCommissionPaymentModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5 border border-slate-100 animate-in fade-in zoom-in duration-200">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-lg">Bayar Cicilan Komisi Penjual</h3>
                        <p class="text-xs text-slate-500">Unit {{ $unit->code }} • Mencatat Kas Keluar di Arus Kas Global</p>
                    </div>
                </div>
                <button type="button" wire:click="$set('showCommissionPaymentModal', false)" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

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

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Upload Bukti Transfer / Resi (Opsional)</label>
                    <input type="file" wire:model="unit_pay_comm_photo" class="input-clean w-full text-xs">
                    @error('unit_pay_comm_photo') <span class="text-red-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Catatan / Keterangan</label>
                    <textarea wire:model="unit_pay_comm_notes" rows="2" placeholder="Catatan termin cicilan..." class="input-clean w-full text-xs"></textarea>
                    @error('unit_pay_comm_notes') <span class="text-red-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="$set('showCommissionPaymentModal', false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-sm transition">Konfirmasi & Catat Kas Keluar</button>
                </div>
            </form>
        </div>
    </div>
@endif
