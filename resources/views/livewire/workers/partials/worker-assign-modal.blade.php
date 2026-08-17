<!-- Modal Form Penugasan Cepat Pekerja -->
@if ($showAssignModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200/80">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-base font-extrabold text-slate-900">
                    Penugasan Pekerja ke Proyek & Unit
                </h3>
                <button wire:click="$set('showAssignModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form wire:submit.prevent="saveAssignment" class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Perumahan / Proyek Properti *</label>
                    <select wire:model.live="assignProjectId" required class="input-clean w-full font-semibold">
                        <option value="">Pilih Proyek</option>
                        @foreach ($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Unit Spesifik (Opsional)</label>
                    <select wire:model="assignUnitId" class="input-clean w-full font-semibold" {{ !$assignProjectId ? 'disabled' : '' }}>
                        <option value="">Semua Unit / General Proyek</option>
                        @foreach ($availableUnits as $u)
                            <option value="{{ $u->id }}">Unit Kode: {{ $u->code }} ({{ ucfirst($u->category) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 uppercase mb-1">Peran / Penugasan Khusus</label>
                    <input type="text" wire:model="assignedRole" placeholder="Mandor Utama Proyek / Tukang Pasang Keramik..." class="input-clean w-full text-sm font-semibold">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <x-button variant="secondary" size="md" type="button" wire:click="$set('showAssignModal', false)">Batal</x-button>
                    <x-button variant="emerald" size="md" type="submit" loadingTarget="saveAssignment">
                        Simpan Penugasan
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endif
