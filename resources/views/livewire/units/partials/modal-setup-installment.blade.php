<!-- Modal Setup Skema Cicilan Baru (Khusus Finance & Founder) -->
@if($showSetupInstallmentModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <h3 class="font-extrabold text-slate-900 text-sm sm:text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Konfigurasi Skema Cicilan Unit {{ $unit->code }}
                </h3>
                <button wire:click="$set('showSetupInstallmentModal', false)" class="text-slate-400 hover:text-slate-600 text-sm">✕</button>
            </div>

            <form wire:submit.prevent="saveSetupInstallment" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Total Harga Deal Unit <span class="text-rose-500">*</span></label>
                    <div class="flex rounded-xl shadow-xs">
                        <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                            Rp
                        </span>
                        <x-currency-input model="setup_total_price" class="input-clean rounded-r-xl rounded-l-none font-mono font-bold text-xs sm:text-sm w-full" placeholder="Contoh: 150.000.000" />
                    </div>
                    @error('setup_total_price') <span class="text-rose-600 text-[10px] mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Total Uang Muka / DP Kesepakatan <span class="text-rose-500">*</span></label>
                    <div class="flex rounded-xl shadow-xs">
                        <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                            Rp
                        </span>
                        <x-currency-input model="setup_down_payment" class="input-clean rounded-r-xl rounded-l-none font-mono font-bold text-xs sm:text-sm w-full" placeholder="Contoh: 30.000.000" />
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Total DP Keseluruhan (Termasuk Booking Fee / DP yang sudah dibayar saat booking unit).</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tenor (Kali Cicilan) <span class="text-rose-500">*</span></label>
                        <input type="number" min="1" max="120" wire:model.live="setup_installment_count" wire:change="calculateMonthlyInstallment" class="input-clean w-full text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Mulai Skema <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="setup_start_date" class="input-clean w-full text-xs sm:text-sm font-mono">
                    </div>
                </div>

                @php
                    $bookingPaid = $unit->activeBooking ? max((float)$unit->activeBooking->dp_amount, (float)$unit->activeBooking->booking_amount) : 0;
                @endphp
                @if($bookingPaid > 0)
                    <div class="p-3 bg-teal-50 border border-teal-200 rounded-xl text-teal-900 text-xs space-y-1">
                        <div class="flex items-center justify-between font-bold">
                            <span class="flex items-center gap-1.5 text-teal-800">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Booking Fee / DP Sudah Terbayar:</span>
                            </span>
                            <span class="font-mono text-teal-700">Rp {{ number_format($bookingPaid, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-[11px] text-teal-700">
                            Pencatatan kas masuk DP di Arus Kas hanya mencatat selisih tambahan DP sebesar <strong class="font-mono text-emerald-800 font-bold">Rp {{ number_format(max(0, $setup_down_payment - $bookingPaid), 0, ',', '.') }}</strong> untuk mencegah pencatatan ganda.
                        </p>
                    </div>
                @endif

                <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl space-y-1">
                    <div class="flex justify-between font-bold text-slate-800">
                        <span>Estimasi Cicilan per Bulan:</span>
                        <span class="font-mono text-blue-700">Rp {{ number_format($setup_installment_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                    <button type="button" wire:click="$set('showSetupInstallmentModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary bg-blue-600 hover:bg-blue-700">Simpan Skema Cicilan</button>
                </div>
            </form>
        </div>
    </div>
@endif
