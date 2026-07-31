<!-- Modal Setup Skema Cicilan Baru -->
@if($showSetupModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 rounded-xl bg-blue-50 text-blue-700 border border-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Setup Skema Cicilan Pembeli</h3>
                        <p class="text-slate-500 text-[11px]">Konfigurasi skema kredit & batas termin pembayaran unit</p>
                    </div>
                </div>
                <button wire:click="$set('showSetupModal', false)" class="text-slate-400 hover:text-slate-600 font-bold text-sm p-1">✕</button>
            </div>

            <!-- Form Body -->
            <form wire:submit.prevent="saveSetup" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Pilih Unit Terjual <span class="text-rose-500">*</span></label>
                    <select wire:change="selectUnitForInstallment($event.target.value)" wire:model="unit_id" class="select-clean w-full font-semibold">
                        <option value="">🏡 -- Pilih Unit Terjual --</option>
                        @foreach($eligibleUnits as $u)
                            <option value="{{ $u->id }}">Unit {{ $u->code }} ({{ $u->project->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Total Harga Jual <span class="text-rose-500">*</span></label>
                        <div class="flex rounded-xl shadow-xs">
                            <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                                Rp
                            </span>
                            <x-currency-input model="total_price" class="input-clean rounded-r-xl rounded-l-none font-bold text-xs sm:text-sm font-mono w-full" placeholder="0" />
                        </div>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Uang Muka / DP <span class="text-rose-500">*</span></label>
                        <div class="flex rounded-xl shadow-xs">
                            <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                                Rp
                            </span>
                            <x-currency-input model="down_payment" class="input-clean rounded-r-xl rounded-l-none font-bold text-xs sm:text-sm font-mono text-emerald-700 w-full" placeholder="0" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Jumlah Bulan Termin <span class="text-rose-500">*</span></label>
                        <input type="number" wire:model.live="installment_count" min="1" max="120" class="input-clean w-full font-bold text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Mulai Tanggal <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="start_date" class="input-clean w-full font-mono text-xs sm:text-sm">
                    </div>
                </div>

                <!-- Highlight Box Kalkulasi Cicilan -->
                <div class="bg-emerald-50/90 border border-emerald-200/80 rounded-xl p-3.5 space-y-1.5 text-emerald-950 shadow-2xs">
                    <div class="flex justify-between text-xs sm:text-sm">
                        <span class="text-emerald-800">Sisa Pokok Piutang:</span>
                        <span class="font-mono font-bold">Rp {{ number_format(max(0, $total_price - $down_payment), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs sm:text-sm font-bold pt-1.5 border-t border-emerald-200/80">
                        <span>Nominal Cicilan Per Bulan:</span>
                        <span class="font-mono text-emerald-700 text-sm sm:text-base">Rp {{ number_format($installment_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <button type="button" wire:click="$set('showSetupModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Skema</button>
                </div>
            </form>
        </div>
    </div>
@endif
