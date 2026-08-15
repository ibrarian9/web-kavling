<!-- MODAL 6: CREATE COMPANY RECEIVABLE / KASBON -->
<x-modal-dialog show="showCreateReceivableModal" title="Catat Piutang Perusahaan / Kasbon Staf & Worker" subTitle="Mencatat uang perusahaan yang dipinjam oleh Mandor, Tukang, atau Marketing" maxWidth="max-w-xl">
    <form wire:submit.prevent="saveReceivable" class="space-y-4 text-xs">
        <div>
            <label class="block font-semibold text-slate-700 mb-1">Pilih Kategori Peminjam <span class="text-rose-500">*</span></label>
            <select wire:model.live="rec_debtor_type" class="select-clean w-full font-semibold">
                <option value="worker">Pekerja Lapangan / Mandor / Tukang</option>
                <option value="user">Staf Internal / Marketing / User SIM</option>
                <option value="other">Peminjam Lainnya / Pihak Luar</option>
            </select>
        </div>

        @if($rec_debtor_type === 'worker')
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Pilih Mandor / Tukang <span class="text-rose-500">*</span></label>
                <select wire:model="rec_worker_id" class="select-clean w-full font-semibold">
                    <option value="">-- Pilih Pekerja --</option>
                    @foreach($allWorkers as $wk)
                        <option value="{{ $wk->id }}">{{ $wk->name }} ({{ ucfirst($wk->type) }})</option>
                    @endforeach
                </select>
            </div>
        @elseif($rec_debtor_type === 'user')
            <div>
                <label class="block font-semibold text-slate-700 mb-1">Pilih Staf / User SIM <span class="text-rose-500">*</span></label>
                <select wire:model="rec_user_id" class="select-clean w-full font-semibold">
                    <option value="">-- Pilih User Staf --</option>
                    @foreach($allUsers as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ ucfirst($u->role) }})</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div>
            <label class="block font-semibold text-slate-700 mb-1">Nama Peminjam / Kasbon <span class="text-rose-500">*</span></label>
            <input type="text" wire:model="rec_debtor_name" placeholder="Contoh: Mandor Slamet (Uang Muka Servis Motor)" required class="input-clean w-full">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <x-currency-input
                label="Nominal Pinjaman / Kasbon (Rp)"
                model="rec_amount"
                :value="$rec_amount"
                placeholder="1.000.000"
                badgeColor="emerald"
                required
            />

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Tanggal Pinjam <span class="text-rose-500">*</span></label>
                <input type="date" wire:model="rec_loan_date" required class="input-clean w-full">
            </div>
        </div>

        <div>
            <label class="block font-semibold text-slate-700 mb-1">Catatan / Perjanjian Pengembalian</label>
            <input type="text" wire:model="rec_notes" placeholder="Catatan skema potong gaji / tanggal janji bayar..." class="input-clean w-full">
        </div>

        <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
            <button type="button" wire:click="$set('showCreateReceivableModal', false)" class="btn-secondary">Batal</button>
            <button type="submit" class="btn-primary bg-emerald-600 hover:bg-emerald-700">Simpan Piutang Kasbon</button>
        </div>
    </form>
</x-modal-dialog>
