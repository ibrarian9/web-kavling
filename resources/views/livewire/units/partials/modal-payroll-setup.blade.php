<!-- Modal Form Payroll Setup for this Unit -->
@if($showPayrollSetupModal)
    <x-modal-dialog show="showPayrollSetupModal" 
                    title="Set Gaji Borongan Unit {{ $unit->code }}" 
                    subTitle="Proyek: {{ $unit->project->name }}" 
                    maxWidth="max-w-md">
        <form wire:submit.prevent="savePayrollSetup" class="space-y-4 text-xs sm:text-sm">
            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Pilih Pekerja (Mandor / Tukang) <span class="text-rose-500">*</span></label>
                <select wire:model="payroll_worker_id" class="select-clean w-full">
                    @foreach($allWorkers as $w)
                        <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }} - {{ $w->specialty }})</option>
                    @endforeach
                </select>
            </div>

            <x-currency-input 
                label="Total Nominal Gaji (Rp)" 
                model="payroll_agreed_salary" 
                :value="$payroll_agreed_salary"
                placeholder="15.000.000"
                badgeColor="blue"
                required
            />

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Skema Pembayaran <span class="text-rose-500">*</span></label>
                <select wire:model="payroll_payment_frequency" class="select-clean w-full">
                    <option value="fleksibel">Fleksibel (Sesuai Permintaan Mandor)</option>
                    <option value="harian">Harian</option>
                    <option value="mingguan">Mingguan (Per-Minggu)</option>
                    <option value="bulanan">Bulanan</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan Tambahan</label>
                <textarea wire:model="payroll_notes" rows="2" placeholder="Lingkup kerja borongan unit..." class="input-clean w-full text-xs sm:text-sm"></textarea>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="secondary" wire:click="$set('showPayrollSetupModal', false)">Batal</x-button>
                <x-button type="submit" variant="primary" loadingTarget="savePayrollSetup">Simpan Kesepakatan Gaji</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
