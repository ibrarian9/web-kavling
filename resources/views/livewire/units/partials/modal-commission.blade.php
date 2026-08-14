<!-- Modal Form Catat Komisi Penjual Unit Baru -->
@if($showCommissionModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-5 border border-slate-100 animate-in fade-in zoom-in duration-200">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-800 text-lg">Catat Hutang Komisi Penjual</h3>
                        <p class="text-xs text-slate-500">Unit {{ $unit->code }} (Proyek {{ $unit->project->name }})</p>
                    </div>
                </div>
                <button type="button" wire:click="$set('showCommissionModal', false)" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="saveCommission" class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Agen / Marketing / Penjual <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="unit_comm_seller_name" placeholder="Contoh: Budi (Marketing Agen / Freelance)" class="input-clean w-full text-xs" required>
                    @error('unit_comm_seller_name') <span class="text-red-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No. Handphone / WhatsApp</label>
                        <input type="text" wire:model="unit_comm_seller_phone" placeholder="08123456789" class="input-clean w-full text-xs">
                        @error('unit_comm_seller_phone') <span class="text-red-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Persentase Komisi (%)</label>
                        <input type="number" step="0.1" min="0" max="100" wire:model.live="unit_comm_percentage" placeholder="2.5" class="input-clean w-full text-xs font-bold text-purple-700">
                        @error('unit_comm_percentage') <span class="text-red-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <x-currency-input
                    label="Nominal Komisi Disetujui (Rp)"
                    model="unit_comm_amount"
                    :value="$unit_comm_amount"
                    placeholder="3.750.000"
                    badgeColor="purple"
                    helpText="*Nominal ini akan menjadi Hutang Komisi Perusahaan yang dapat dicicil bertahap."
                    required
                />

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Catatan / Keterangan</label>
                    <textarea wire:model="unit_comm_notes" rows="2" placeholder="Catatan syarat komisi atau perjanjian closing..." class="input-clean w-full text-xs"></textarea>
                    @error('unit_comm_notes') <span class="text-red-500 text-[11px] mt-0.5 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="$set('showCommissionModal', false)" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold shadow-sm transition">Simpan Hutang Komisi</button>
                </div>
            </form>
        </div>
    </div>
@endif
