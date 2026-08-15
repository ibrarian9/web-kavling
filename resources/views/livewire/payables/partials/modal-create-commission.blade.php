<!-- MODAL 4: CREATE NEW UNIT COMMISSION -->
@if($showCreateCommissionModal)
    <div class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white border border-slate-200 rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <div class="p-2 rounded-xl bg-purple-50 text-purple-700 border border-purple-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Catat Hutang Komisi Penjual Unit</h3>
                        <p class="text-[11px] text-slate-500">Mencatat persenan / fee komisi untuk agen, marketing internal, atau broker</p>
                    </div>
                </div>
                <button wire:click="$set('showCreateCommissionModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
            </div>

            <form wire:submit.prevent="saveCommission" class="space-y-4 text-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Pilih Proyek (Opsional)</label>
                        <select wire:model.live="comm_project_id" class="select-clean w-full">
                            <option value="">Non-Proyek / Umum</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Pilih Unit (Opsional)</label>
                        <select wire:model="comm_unit_id" class="select-clean w-full">
                            <option value="">Semua Unit / Umum</option>
                            @foreach($commAvailableUnits as $u)
                                <option value="{{ $u->id }}">Unit {{ $u->code }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nama Penjual / Agent / Marketing <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="comm_seller_name" placeholder="Contoh: Pak Agus Broker Eksternal" required class="input-clean w-full">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">No. HP / Kontak Penjual</label>
                        <input type="text" wire:model="comm_seller_phone" placeholder="08123456789" class="input-clean w-full">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Persentase Komisi (%)</label>
                        <input type="number" step="0.1" wire:model="comm_percentage" placeholder="2.5" class="input-clean w-full font-bold">
                    </div>
                </div>

                <x-currency-input
                    label="Total Nominal Komisi (Rp)"
                    model="comm_amount"
                    :value="$comm_amount"
                    placeholder="5.000.000"
                    badgeColor="purple"
                    required
                />

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Catatan / Keterangan</label>
                    <input type="text" wire:model="comm_notes" placeholder="Catatan unit closing..." class="input-clean w-full">
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="$set('showCreateCommissionModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary bg-purple-600 hover:bg-purple-700">Simpan Hutang Komisi</button>
                </div>
            </form>
        </div>
    </div>
@endif
