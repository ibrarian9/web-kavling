<!-- Modal Create/Edit Data Pekerja -->
@if ($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-200/80">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-bold text-slate-900">
                    {{ $editingId ? 'Edit Data Pekerja' : 'Pendaftaran Pekerja Baru' }}
                </h3>
                <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form wire:submit.prevent="save" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Nama Lengkap Pekerja *</label>
                    <input type="text" wire:model="name" required class="input-clean w-full font-bold">
                    @error('name') <span class="text-[10px] text-rose-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Tipe Pekerja *</label>
                        <select wire:model="type" required class="input-clean w-full">
                            <option value="tukang">Tukang</option>
                            <option value="mandor">Mandor</option>
                            <option value="kontraktor">Kontraktor</option>
                        </select>
                        @error('type') <span class="text-[10px] text-rose-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Status Pekerja *</label>
                        <select wire:model="status" required class="input-clean w-full">
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                        @error('status') <span class="text-[10px] text-rose-500 mt-1 block font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Spesialisasi Keahlian</label>
                    <input type="text" wire:model="specialty" placeholder="Contoh: Batu & Keramik, Kayu & Atap, Dll" class="input-clean w-full">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">No. Telepon / WhatsApp</label>
                    <input type="text" wire:model="phone" placeholder="08123456789" class="input-clean w-full font-mono">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Alamat Tempat Tinggal</label>
                    <textarea wire:model="address" rows="2" class="input-clean w-full"></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 uppercase mb-1">Catatan Tambahan</label>
                    <textarea wire:model="notes" rows="2" class="input-clean w-full"></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="$set('showModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" wire:loading.attr="disabled" class="btn-primary flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="save" class="w-4 h-4 animate-spin text-white shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span wire:loading.remove wire:target="save">Simpan Data Pekerja</span>
                        <span wire:loading wire:target="save">Menyimpan Data...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
