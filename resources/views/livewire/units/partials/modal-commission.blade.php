<!-- Modal Form Catat Komisi Penjual Unit Baru -->
@if($showCommissionModal)
    <x-modal-dialog show="showCommissionModal" 
                    title="Catat Hutang Komisi Penjual" 
                    subTitle="Unit {{ $unit->code }} (Proyek {{ $unit->project->name }})" 
                    maxWidth="max-w-lg">
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

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <x-button type="button" variant="secondary" wire:click="$set('showCommissionModal', false)">Batal</x-button>
                <x-button type="submit" variant="purple" loadingTarget="saveCommission">Simpan Hutang Komisi</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
