<!-- Modal Setup Skema Cicilan Baru (Khusus Finance & Founder) -->
@if($showSetupInstallmentModal)
    <x-modal-dialog show="showSetupInstallmentModal" 
                    title="Konfigurasi Skema Cicilan Unit {{ $unit->code }}" 
                    subTitle="Pengaturan harga deal, DP, tenor, dan cicilan bulanan" 
                    maxWidth="max-w-lg">
        <form wire:submit.prevent="saveSetupInstallment" class="space-y-4 text-xs sm:text-sm">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block font-semibold text-slate-700 text-xs uppercase tracking-wider">Total Harga Deal Unit <span class="text-rose-500">*</span></label>
                    @php
                        $approvedProposal = $unit->proposals->where('status', 'disetujui')->first();
                        $approvedPrice = $unit->officialDocument->proposal->proposed_price ?? ($approvedProposal->proposed_price ?? null);
                    @endphp
                    @if($approvedPrice && (float)$approvedPrice !== (float)$setup_total_price)
                        <button type="button" wire:click="$set('setup_total_price', {{ (float)$approvedPrice }})" class="text-[10px] font-extrabold text-teal-700 hover:text-teal-900 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-lg transition" title="Gunakan Harga dari Proposal / SPP yang telah disetujui">
                            ⚡ Use Approved Price (Rp {{ number_format($approvedPrice, 0, ',', '.') }})
                        </button>
                    @endif
                </div>
                <x-currency-input 
                    label="Total Harga Unit Kesepakatan (Rp)" 
                    model="setup_total_price" 
                    :value="$setup_total_price"
                    placeholder="150.000.000"
                    badgeColor="blue"
                    required
                />
            </div>

            <x-currency-input 
                label="Total Uang Muka / DP Kesepakatan (Rp)" 
                model="setup_down_payment" 
                :value="$setup_down_payment"
                placeholder="30.000.000"
                badgeColor="emerald"
                helpText="Total DP Keseluruhan (Termasuk Booking Fee / DP yang sudah dibayar saat booking unit)."
                required
            />

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
                <x-button type="button" variant="secondary" wire:click="$set('showSetupInstallmentModal', false)">Batal</x-button>
                <x-button type="submit" variant="blue" loadingTarget="saveSetupInstallment">Simpan Skema Cicilan</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
