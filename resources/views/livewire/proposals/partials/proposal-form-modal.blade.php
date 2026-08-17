<!-- Modal Buat / Edit Pengajuan (Marketing) -->
@if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
        <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-base">{{ $editingProposalId ? 'Edit Pengajuan Harga Unit' : 'Buat Pengajuan Harga Jual Unit' }}</h3>
                <button wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form wire:submit.prevent="submitProposal" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Pilih Unit Kavling / Rumah *</label>
                    <select wire:change="selectUnitForProposal($event.target.value)" wire:model="unit_id" required class="input-clean w-full font-bold">
                        <option value="">Pilih Unit Tersedia</option>
                        @foreach($availableUnits as $u)
                            <option value="{{ $u->id }}">{{ $u->code }} - {{ $u->project->name }} @if(auth()->user()->canViewHpp())(HPP: Rp {{ number_format($u->hpp, 0, ',', '.') }})@endif</option>
                        @endforeach
                    </select>
                    @error('unit_id') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                </div>

                <!-- Highlight Box HPP -->
                @if(auth()->user()->canViewHpp())
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Harga Pokok (HPP):</span>
                            <span class="font-mono font-bold text-slate-800">Rp {{ number_format($hpp_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Usulan Harga Jual / Penawaran (Rp) *</label>
                    <x-currency-input model="proposed_price" class="input-clean w-full font-bold text-base font-mono text-emerald-700" />
                    <p class="text-[10px] text-slate-400 mt-1 font-medium">*Dapat diajukan di bawah HPP untuk kebutuhan penawaran khusus/diskon.</p>
                    @error('proposed_price') <span class="text-rose-500 text-[10px] block mt-1 font-bold">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Catatan Pengajuan (Opsional)</label>
                    <textarea wire:model="proposal_notes" rows="2" placeholder="Catatan penawaran atau nego pembeli..." class="input-clean w-full"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <x-button variant="secondary" size="md" type="button" wire:click="$set('showCreateModal', false)">Batal</x-button>
                    <x-button variant="primary" size="md" type="submit" loadingTarget="submitProposal">
                        {{ $editingProposalId ? 'Simpan Perubahan' : 'Kirim Pengajuan' }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endif
