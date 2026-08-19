<!-- Modal Direct Ajukan / Edit Proposal Harga (Detail Unit) -->
@if($showDirectProposalModal)
    <x-modal-dialog show="showDirectProposalModal" 
                    :title="$editingProposalId ? 'Edit Proposal Harga Unit ' . $unit->code : 'Ajukan Proposal Harga Unit ' . $unit->code" 
                    :subTitle="'Pengajuan nominal harga kesepakatan pembeli | Luas: ' . $unit->land_area . ' m²'" 
                    maxWidth="max-w-lg">
        <form wire:submit.prevent="saveDirectProposal" class="space-y-4 text-xs sm:text-sm">
            @if(auth()->user()->canViewHpp())
                <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl space-y-1">
                    <div class="flex justify-between font-bold text-slate-700">
                        <span>HPP / Biaya Dasar Unit:</span>
                        <span class="font-mono text-slate-900">Rp {{ number_format($prop_hpp_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endif

            <!-- Price Input Selection: Duit Deal Langsung vs Harga per Meter (m2) (Optimized with Instant Alpine.js Reactivity) -->
            <div x-data="{
                mode: @entangle('prop_price_mode'),
                landArea: {{ (float)$unit->land_area }},
                hppPrice: {{ (float)$prop_hpp_price }},
                proposedPrice: @entangle('prop_proposed_price'),
                pricePerSqm: @entangle('prop_price_per_sqm'),
                
                formatRp(val) {
                    if (val === null || val === undefined || val === '') return '0';
                    let num = typeof val === 'number' ? Math.round(val) : parseInt(String(val).replace(/[^0-9]/g, ''), 10);
                    if (isNaN(num)) return '0';
                    return num.toLocaleString('id-ID');
                },
                calcFromPerSqm() {
                    let num = typeof this.pricePerSqm === 'number' ? this.pricePerSqm : parseInt(String(this.pricePerSqm || '').replace(/[^0-9]/g, ''), 10);
                    if (!isNaN(num) && num > 0 && this.landArea > 0) {
                        this.proposedPrice = Math.round(num * this.landArea);
                    }
                },
                calcFromTotal() {
                    let num = typeof this.proposedPrice === 'number' ? this.proposedPrice : parseInt(String(this.proposedPrice || '').replace(/[^0-9]/g, ''), 10);
                    if (!isNaN(num) && num > 0 && this.landArea > 0) {
                        this.pricePerSqm = Math.round(num / this.landArea);
                    }
                },
                getMargin() {
                    let num = typeof this.proposedPrice === 'number' ? this.proposedPrice : parseInt(String(this.proposedPrice || '').replace(/[^0-9]/g, ''), 10);
                    return (isNaN(num) ? 0 : num) - this.hppPrice;
                }
            }" class="p-3 bg-emerald-50/70 border border-emerald-200/80 rounded-2xl space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-emerald-950 text-[11px] uppercase tracking-wider">Metode Usulan Harga</span>
                    <span class="text-[10px] text-emerald-700 font-bold">Luas: {{ number_format($unit->land_area, 2, ',', '.') }} m²</span>
                </div>

                <!-- Mode Switcher Tabs (Instant Client-Side Switch) -->
                <div class="grid grid-cols-2 gap-1.5 p-1 bg-white rounded-xl border border-emerald-100 shadow-2xs">
                    <button type="button" 
                            @click="mode = 'total'; calcFromPerSqm()" 
                            :class="mode === 'total' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-emerald-800'"
                            class="py-1.5 px-2 rounded-lg font-bold text-xs transition-all text-center flex items-center justify-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Total Nominal (Rp)</span>
                    </button>
                    <button type="button" 
                            @click="mode = 'per_sqm'; calcFromTotal()" 
                            :class="mode === 'per_sqm' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-emerald-800'"
                            class="py-1.5 px-2 rounded-lg font-bold text-xs transition-all text-center flex items-center justify-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <span>Hitung per m² (Rp)</span>
                    </button>
                </div>

                <!-- Panel Hitung per m2 -->
                <div x-show="mode === 'per_sqm'" class="space-y-2" style="display: none;">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                        <div @input="setTimeout(() => calcFromPerSqm(), 50)">
                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Tarif Tanah per m² (Rp) <span class="text-rose-500">*</span></label>
                            <x-currency-input 
                                model="prop_price_per_sqm" 
                                :value="$prop_price_per_sqm"
                                placeholder="1.500.000"
                                badgeColor="emerald"
                            />
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Total Proposal (Auto)</label>
                            <div class="h-10 px-3 flex items-center bg-white border border-emerald-300 rounded-xl font-mono font-extrabold text-emerald-800 text-sm">
                                <span>Rp&nbsp;</span><span x-text="formatRp(proposedPrice)"></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-[10px] text-emerald-800 font-medium bg-white/80 p-2 rounded-lg border border-emerald-100">
                        Rumus: {{ number_format($unit->land_area, 2, ',', '.') }} m² &times; Rp <span x-text="formatRp(pricePerSqm)"></span>/m² = <strong>Rp <span x-text="formatRp(proposedPrice)"></span></strong>
                    </div>
                </div>

                <!-- Panel Total Nominal -->
                <div x-show="mode === 'total'" class="space-y-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                        <div @input="setTimeout(() => calcFromTotal(), 50)">
                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Nominal Usulan Harga (Rp) <span class="text-rose-500">*</span></label>
                            <x-currency-input 
                                model="prop_proposed_price" 
                                :value="$prop_proposed_price"
                                placeholder="250.000.000"
                                badgeColor="emerald"
                            />
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 text-[11px] mb-1">Setara per m² (Auto)</label>
                            <div class="h-10 px-3 flex items-center bg-white border border-emerald-200 rounded-xl font-mono font-bold text-slate-700 text-xs">
                                <span>Rp&nbsp;</span><span x-text="landArea > 0 ? formatRp(Math.round(proposedPrice / landArea)) : '0'"></span><span>&nbsp;/ m²</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->canViewHpp())
                    <div x-show="Number(proposedPrice || 0) > 0" 
                         :class="getMargin() < 0 ? 'bg-rose-50/80 border-rose-200 text-rose-800' : 'bg-emerald-100/50 border-emerald-200 text-emerald-900'"
                         class="flex items-center justify-between p-2 rounded-lg border transition-colors">
                        <span class="text-[11px] font-semibold">Estimasi Margin Keuntungan:</span>
                        <span class="font-mono font-extrabold text-xs">
                            <span x-text="(getMargin() < 0 ? '-' : '+') + ' Rp ' + formatRp(Math.abs(getMargin()))"></span>
                            <span class="text-[10px] font-normal" x-text="getMargin() < 0 ? '(Di bawah HPP)' : '(Di atas HPP)'"></span>
                        </span>
                    </div>
                @endif
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan / Alasan Pengajuan (Opsional)</label>
                <textarea wire:model="prop_notes" rows="2" class="input-clean w-full text-xs sm:text-sm" placeholder="Contoh: Diskon khusus cash bertahap kesepakatan pembeli..."></textarea>
            </div>

            @if(auth()->user()->isFounder() && !$editingProposalId)
                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-900 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Sebagai Founder, pengajuan harga ini akan <strong>langsung disetujui otomatis</strong> & siap menerbitkan SPP PDF!</span>
                </div>
            @endif

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="secondary" wire:click="$set('showDirectProposalModal', false)">Batal</x-button>
                <x-button type="submit" variant="emerald" loadingTarget="saveDirectProposal">
                    {{ $editingProposalId ? 'Simpan Perubahan Proposal' : 'Simpan Proposal Harga' }}
                </x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
