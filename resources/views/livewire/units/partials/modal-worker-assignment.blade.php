<!-- Modal Form Worker Assignment Directly from Unit -->
@if($showWorkerModal)
    <x-modal-dialog show="showWorkerModal" 
                    title="Tugaskan Mandor / Tukang ke Unit {{ $unit->code }}" 
                    subTitle="Penugasan pekerja pada unit proyek" 
                    maxWidth="max-w-md">
        <form wire:submit.prevent="saveWorkerAssignment" class="space-y-4 text-xs sm:text-sm">
            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Pilih Pekerja (Mandor / Tukang) <span class="text-rose-500">*</span></label>
                <select wire:model="worker_id" class="select-clean w-full">
                    @foreach($allWorkers as $w)
                        <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }} - {{ $w->specialty }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Peran Penugasan</label>
                <input type="text" wire:model="assigned_role" placeholder="Tukang Keramik / Mandor Finishing..." class="input-clean w-full font-bold text-xs sm:text-sm">
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="secondary" wire:click="$set('showWorkerModal', false)">Batal</x-button>
                <x-button type="submit" variant="primary" loadingTarget="saveWorkerAssignment">Simpan Penugasan</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
