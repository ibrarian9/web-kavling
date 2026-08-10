<!-- Modal Direct Ajukan Proposal Harga (Detail Unit) -->
@if($showDirectProposalModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <h3 class="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Ajukan Proposal Harga Unit {{ $unit->code }}</span>
                </h3>
                <button wire:click="$set('showDirectProposalModal', false)" class="text-slate-400 hover:text-slate-600 text-sm">✕</button>
            </div>

            <form wire:submit.prevent="saveDirectProposal" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl space-y-1">
                    <div class="flex justify-between font-bold text-slate-700">
                        <span>HPP / Biaya Dasar Unit:</span>
                        <span class="font-mono text-slate-900">Rp {{ number_format($prop_hpp_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nominal Proposal Harga Jual (Rp) <span class="text-rose-500">*</span></label>
                    <div class="flex rounded-xl shadow-xs">
                        <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                            Rp
                        </span>
                        <x-currency-input model="prop_proposed_price" class="input-clean rounded-r-xl rounded-l-none font-mono font-bold text-xs sm:text-sm w-full" placeholder="Contoh: 250.000.000" />
                    </div>
                    @error('prop_proposed_price') <span class="text-rose-600 text-[10px] mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan / Alasan Pengajuan (Opsional)</label>
                    <textarea wire:model="prop_notes" rows="2" class="input-clean w-full text-xs sm:text-sm" placeholder="Contoh: Diskon khusus cash bertahap kesepakatan pembeli..."></textarea>
                </div>

                @if(auth()->user()->isFounder())
                    <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-900 text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Sebagai Founder, pengajuan harga ini akan <strong>langsung disetujui otomatis</strong> & siap menerbitkan SPP PDF!</span>
                    </div>
                @endif

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <button type="button" wire:click="$set('showDirectProposalModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary bg-teal-600 hover:bg-teal-700 font-bold">Simpan Proposal Harga</button>
                </div>
            </form>
        </div>
    </div>
@endif
