<!-- MODAL 4: CREATE NEW UNIT COMMISSION -->
<x-modal-dialog show="showCreateCommissionModal" title="Catat Hutang Komisi Penjual Unit" subTitle="Mencatat persenan / fee komisi untuk agen, marketing internal, atau broker" maxWidth="max-w-xl">
    <form wire:submit.prevent="saveCommission" class="space-y-4 text-xs">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Pilih Proyek (Opsional)</label>
                <select wire:model.live="comm_project_id" class="select-clean w-full">
                    <option value="">Non-Proyek / Umum</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Pilih Unit (Opsional)</label>
                <select wire:model="comm_unit_id" class="select-clean w-full">
                    <option value="">Semua Unit / Umum</option>
                    @foreach($commAvailableUnits as $u)
                        <option value="{{ $u->id }}">Unit {{ $u->code }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block font-semibold text-slate-700 mb-1">Nama Penjual / Agent / Marketing <span class="text-rose-500">*</span></label>
            <input type="text" wire:model="comm_seller_name" placeholder="Contoh: Pak Agus Broker Eksternal" required class="input-clean w-full">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block font-semibold text-slate-700 mb-1">No. HP / Kontak Penjual</label>
                <input type="text" wire:model="comm_seller_phone" placeholder="08123456789" class="input-clean w-full">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Persentase Komisi (%)</label>
                <input type="number" step="0.1" wire:model="comm_percentage" placeholder="2.5" class="input-clean w-full font-bold">
            </div>
        </div>

        <x-currency-input
            label="Total Nominal Komisi (Rp)"
            model="comm_amount"
            :value="$comm_amount"
            placeholder="5.000.000"
            badgeColor="purple"
            required
        />

        <div>
            <label class="block font-semibold text-slate-700 mb-1">Catatan / Keterangan</label>
            <input type="text" wire:model="comm_notes" placeholder="Catatan unit closing..." class="input-clean w-full">
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
            <x-button variant="secondary" size="md" type="button" wire:click="$set('showCreateCommissionModal', false)">Batal</x-button>
            <x-button variant="purple" size="md" type="submit">Simpan Hutang Komisi</x-button>
        </div>
    </form>
</x-modal-dialog>
