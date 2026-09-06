<!-- MODAL: CREATE COMPANY DEBT (SUB-KON, VENDOR & LUAR PROYEK) -->
<x-modal-dialog show="showCreateDebtModal" title="Catat Utang Perusahaan (Sub-kon, Vendor & Non-Proyek)" subTitle="Mencatat kewajiban utang kepada sub-kontraktor, vendor pengadaan, talangan modal, atau luar proyek" maxWidth="max-w-xl">
    <form wire:submit.prevent="saveDebt" class="space-y-4 text-xs">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Kategori Kreditur <span class="text-rose-500">*</span></label>
                <select wire:model="debt_creditor_type" class="select-clean w-full font-semibold">
                    <option value="subkon">Sub-kontraktor (Borongan Kerja)</option>
                    <option value="vendor">Vendor / Supplier Pengadaan</option>
                    <option value="operasional_luar">Biaya Operasional Luar Proyek</option>
                    <option value="talangan_modal">Pinjaman Modal / Talangan</option>
                    <option value="lainnya">Utang Lain-lain</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Terkait Proyek (Opsional)</label>
                <select wire:model="debt_project_id" class="select-clean w-full font-semibold">
                    <option value="">-- Di Luar Proyek (Non-Projek) --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Nama Sub-kon / Vendor / Kreditur <span class="text-rose-500">*</span></label>
                <input type="text" wire:model="debt_creditor_name" placeholder="Contoh: CV Maju Perkasa / Pak Joko" required class="input-clean w-full">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">No. WhatsApp / HP (Opsional)</label>
                <input type="text" wire:model="debt_creditor_phone" placeholder="Contoh: 08123456789" class="input-clean w-full">
            </div>
        </div>

        <div>
            <label class="block font-semibold text-slate-700 mb-1">Keperluan / Judul Utang <span class="text-rose-500">*</span></label>
            <input type="text" wire:model="debt_title" placeholder="Contoh: Borongan Pasang Paving Jalan & Saluran Air" required class="input-clean w-full">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <x-currency-input
                label="Total Pokok Utang (Rp)"
                model="debt_amount"
                :value="$debt_amount"
                placeholder="10.000.000"
                badgeColor="rose"
                required
            />

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Tanggal Terjadinya Utang / SPK <span class="text-rose-500">*</span></label>
                <input type="date" wire:model="debt_date" required class="input-clean w-full">
            </div>
        </div>

        <div>
            <label class="block font-semibold text-slate-700 mb-1">Tanggal Jatuh Tempo Pelunasan (Opsional)</label>
            <input type="date" wire:model="debt_due_date" class="input-clean w-full">
        </div>

        <x-receipt-upload 
            model="debt_attachment" 
            :photo="$debt_attachment" 
            label="Upload Berkas SPK / Surat Perjanjian / Nota Kesepakatan (Opsional)"
        />

        <div>
            <label class="block font-semibold text-slate-700 mb-1">Catatan Tambahan / Skema Termin</label>
            <input type="text" wire:model="debt_notes" placeholder="Contoh: Dibayar dalam 3 termin sesuai progres pekerjaan..." class="input-clean w-full">
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
            <x-button variant="secondary" size="md" type="button" wire:click="$set('showCreateDebtModal', false)">Batal</x-button>
            <x-button variant="rose" size="md" type="submit">Simpan Catatan Utang</x-button>
        </div>
    </form>
</x-modal-dialog>
