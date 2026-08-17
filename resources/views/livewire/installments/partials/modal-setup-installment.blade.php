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
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Pilih Unit (Booking / Terjual) <span class="text-rose-500">*</span></label>
                    <select wire:change="selectUnitForInstallment($event.target.value)" wire:model="unit_id" class="select-clean w-full font-semibold">
                        <option value="">-- Pilih Unit Kavling (Booking / Terjual) --</option>
                        @foreach($eligibleUnits as $u)
                            @php
                                $buyer = ($u->relationLoaded('activeBooking') && $u->activeBooking) 
                                    ? $u->activeBooking->buyer_name 
                                    : (($u->relationLoaded('bookings') && $u->bookings->isNotEmpty()) 
                                        ? $u->bookings->first()->buyer_name 
                                        : (($u->relationLoaded('officialDocument') && $u->officialDocument) 
                                            ? $u->officialDocument->buyer_name 
                                            : null));
                            @endphp
                            <option value="{{ $u->id }}">Unit {{ $u->code }} - {{ $u->project->name ?? '-' }} {{ $buyer ? "({$buyer} • " . ucfirst($u->status) . ")" : "({$u->status_label})" }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <x-currency-input 
                            label="Total Harga Jual (Rp)" 
                            model="total_price" 
                            :value="$total_price"
                            placeholder="0" 
                            required 
                        />
                    </div>
                    <div>
                        <x-currency-input 
                            label="Total DP Kesepakatan (Rp)" 
                            model="down_payment" 
                            :value="$down_payment"
                            placeholder="0" 
                            badgeColor="emerald"
                            helpText="Total DP Keseluruhan (Termasuk Booking Fee yang sudah dibayar)."
                            required 
                        />
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

                @if(($already_paid_booking ?? 0) > 0)
                    <div class="p-3 bg-teal-50 border border-teal-200 rounded-xl text-teal-900 text-xs space-y-1">
                        <div class="flex items-center justify-between font-bold">
                            <span class="flex items-center gap-1.5 text-teal-800">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Booking Fee / DP Terbayar:</span>
                            </span>
                            <span class="font-mono text-teal-700">Rp {{ number_format($already_paid_booking, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-[11px] text-teal-700">
                            Tercatat otomatis dari booking unit. Arus kas masuk DP hanya mencatat net tambahan DP sebesar <strong class="font-mono text-emerald-800 font-bold">Rp {{ number_format(max(0, $down_payment - $already_paid_booking), 0, ',', '.') }}</strong> agar tidak terjadi *double-counting*.
                        </p>
                    </div>
                @endif

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
                    <x-button variant="secondary" size="md" type="button" wire:click="$set('showSetupModal', false)">Batal</x-button>
                    <x-button variant="primary" size="md" type="submit">Simpan Skema</x-button>
                </div>
            </form>
        </div>
    </div>
@endif
