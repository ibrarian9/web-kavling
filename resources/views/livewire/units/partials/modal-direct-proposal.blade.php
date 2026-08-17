<!-- Modal Direct Ajukan Proposal Harga (Detail Unit) -->
@if($showDirectProposalModal)
    <x-modal-dialog show="showDirectProposalModal" 
                    title="Ajukan Proposal Harga Unit {{ $unit->code }}" 
                    subTitle="Pengajuan nominal harga jual kesepakatan pembeli" 
                    maxWidth="max-w-lg">
        <form wire:submit.prevent="saveDirectProposal" class="space-y-4 text-xs sm:text-sm">
            <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl space-y-1">
                <div class="flex justify-between font-bold text-slate-700">
                    <span>HPP / Biaya Dasar Unit:</span>
                    <span class="font-mono text-slate-900">Rp {{ number_format($prop_hpp_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <x-currency-input 
                label="Nominal Proposal Harga Jual (Rp)" 
                model="prop_proposed_price" 
                :value="$prop_proposed_price"
                placeholder="250.000.000"
                badgeColor="emerald"
                required
            />

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
                <x-button type="button" variant="secondary" wire:click="$set('showDirectProposalModal', false)">Batal</x-button>
                <x-button type="submit" variant="emerald" loadingTarget="saveDirectProposal">Simpan Proposal Harga</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
