<!-- Modal Form Worker Assignment Directly from Unit -->
@if($showWorkerModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-md w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <h3 class="font-bold text-slate-900 text-sm sm:text-base">Tugaskan Mandor / Tukang ke Unit {{ $unit->code }}</h3>
                <button wire:click="$set('showWorkerModal', false)" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form wire:submit.prevent="saveWorkerAssignment" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
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
                    <button type="button" wire:click="$set('showWorkerModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Penugasan</button>
                </div>
            </form>
        </div>
    </div>
@endif
