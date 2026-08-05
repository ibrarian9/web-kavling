<!-- Modal Form Payroll Setup for this Unit -->
@if($showPayrollSetupModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-md w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Set Gaji Borongan Unit {{ $unit->code }}</h3>
                    <p class="text-slate-500 text-[11px]">Proyek: {{ $unit->project->name }}</p>
                </div>
                <button wire:click="$set('showPayrollSetupModal', false)" class="p-1 rounded-lg text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form wire:submit.prevent="savePayrollSetup" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Pilih Pekerja (Mandor / Tukang) <span class="text-rose-500">*</span></label>
                    <select wire:model="payroll_worker_id" class="select-clean w-full">
                        @foreach($allWorkers as $w)
                            <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }} - {{ $w->specialty }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Total Nominal Gaji <span class="text-rose-500">*</span></label>
                    <div class="flex rounded-xl shadow-xs">
                        <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                            Rp
                        </span>
                        <x-currency-input model="payroll_agreed_salary" class="input-clean rounded-r-xl rounded-l-none font-mono font-bold text-slate-900 text-xs sm:text-sm w-full" placeholder="Contoh: 15.000.000" />
                    </div>
                    @error('payroll_agreed_salary') <span class="text-rose-500 text-[10px] mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

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
                    <button type="button" wire:click="$set('showPayrollSetupModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Kesepakatan Gaji</button>
                </div>
            </form>
        </div>
    </div>
@endif
