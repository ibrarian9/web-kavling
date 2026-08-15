<!-- Modal Form Generate SPP PDF Baru -->
@if($showGenerateModal)
    <x-modal-dialog show="showGenerateModal" 
                    :title="$editingDocumentId ? 'Edit Data Dokumen SPP' : 'Generate & Terbitkan SPP PDF Baru'" 
                    subTitle="Penerbitan Surat Pemesanan Properti resmi" 
                    maxWidth="max-w-lg">
        <form wire:submit.prevent="generateDocument" class="space-y-4 text-xs">
            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Pilih Unit Kavling / Rumah *</label>
                <select wire:model="selected_unit_id" required class="select-clean w-full font-semibold">
                    <option value="">Pilih Unit</option>
                    @foreach($allUnits as $u)
                        <option value="{{ $u->id }}">{{ $u->code }} - {{ $u->project->name }} (Status: {{ ucfirst($u->status) }})</option>
                    @endforeach
                </select>
                @error('selected_unit_id') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Nama Lengkap Pembeli *</label>
                    <input type="text" wire:model="buyer_name" required placeholder="Contoh: Bpk. H. Hendra Wijaya" class="input-clean w-full font-bold">
                    @error('buyer_name') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">NIK KTP Pembeli (16 Digit)</label>
                    <input type="text" wire:model="buyer_nik" placeholder="3271234567890001" class="input-clean w-full font-mono">
                    @error('buyer_nik') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">No. HP / WhatsApp Pembeli *</label>
                <input type="text" wire:model="buyer_contact" required placeholder="081234567890" class="input-clean w-full font-mono">
                @error('buyer_contact') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block font-bold text-slate-700 uppercase mb-1">Alamat Lengkap Pembeli</label>
                <textarea wire:model="buyer_address" rows="2" placeholder="Jl. Sudirman No. 123, Pekanbaru" class="input-clean w-full"></textarea>
            </div>

            <!-- Section Identitas Penjual / Founder -->
            <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-2xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-extrabold text-slate-800 text-[11px] uppercase tracking-wider">Identitas Penjual (Founder)</span>
                    <span class="text-[10px] text-slate-400">Otomatis Terisi & Bisa Diubah</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label class="block font-semibold text-slate-600 text-[10px] mb-0.5">Nama Founder / Penjual</label>
                        <input type="text" wire:model="seller_name" class="input-clean w-full font-bold text-xs" required>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 text-[10px] mb-0.5">NIK KTP Founder</label>
                        <input type="text" wire:model="seller_nik" class="input-clean w-full font-mono text-xs" placeholder="1471012304850001">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <x-button type="button" variant="outline" size="sm" wire:click="$set('showGenerateModal', false)">Batal</x-button>
                <x-button type="submit" variant="emerald" size="sm" icon="check">{{ $editingDocumentId ? 'Simpan Perubahan' : 'Generate SPP PDF' }}</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
