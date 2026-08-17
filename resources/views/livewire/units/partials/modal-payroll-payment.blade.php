<!-- Modal Form Payroll Payment for this Unit (Form Responsif Mobile) -->
@if($showPayrollPaymentModal && $selectedPayroll)
    <x-modal-dialog show="showPayrollPaymentModal" 
                    title="{{ !empty($editingSalaryPaymentId) ? 'Edit Pembayaran Gaji Unit' : 'Pembayaran Gaji Unit' }} {{ $unit->code }}" 
                    subTitle="Pekerja: {{ $selectedPayroll->worker->name }}" 
                    maxWidth="max-w-2xl">
        <div class="bg-slate-50 p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200 text-xs sm:text-sm grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 shrink-0 mb-4">
            <div>
                <span class="text-slate-500 block text-[11px] sm:text-xs">Total Gaji Borongan:</span>
                <span class="font-bold text-slate-900 font-mono text-sm sm:text-base">Rp {{ number_format($selectedPayroll->agreed_salary, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="text-slate-500 block text-[11px] sm:text-xs">Sisa Kontrak Belum Dibayar:</span>
                <span class="font-bold text-amber-600 font-mono text-sm sm:text-base">Rp {{ number_format($selectedPayroll->remaining_salary, 0, ',', '.') }}</span>
            </div>
        </div>

        <form wire:submit.prevent="savePayrollPayment" class="space-y-3 sm:space-y-4 text-xs sm:text-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Pembayaran <span class="text-rose-500">*</span></label>
                    <input type="date" wire:model="payroll_payment_date" required class="input-clean w-full text-xs sm:text-sm font-mono">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Metode Pembayaran <span class="text-rose-500">*</span></label>
                    <select wire:model.live="payroll_payment_method" class="select-clean w-full">
                        <option value="transfer_bank">Transfer Bank</option>
                        <option value="tunai">Tunai (Cash)</option>
                    </select>
                </div>
            </div>

            <x-currency-input 
                label="Nominal Gaji Dibayarkan (Rp)" 
                model="payroll_amount_gross" 
                :value="$payroll_amount_gross"
                placeholder="2.500.000"
                badgeColor="blue"
                required
            />

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">
                    Upload Foto Struk Transfer {{ $payroll_payment_method === 'tunai' ? '(Opsional)' : '(Rekomendasi)' }} <span class="text-emerald-700 font-bold lowercase text-[10px] bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">(Maks. 2MB)</span>
                </label>
                <input type="file" wire:model="payroll_receipt_photo" accept="image/*,.heic,.heif,.pdf" class="input-clean w-full text-xs">
                @error('payroll_receipt_photo') <span class="text-rose-500 text-[10px] block mt-1 font-medium">{{ $message }}</span> @enderror
                @if($payroll_receipt_photo)
                    <div class="mt-3 text-center bg-slate-50 p-3 rounded-2xl border border-slate-200 space-y-2">
                        <div class="flex items-center justify-between text-xs text-slate-700 font-semibold">
                            <span class="text-emerald-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Struk Terpilih ({{ $payroll_receipt_photo->getClientOriginalName() }}):</span>
                            </span>
                            <button type="button" wire:click="$set('payroll_receipt_photo', null)" class="text-rose-500 hover:text-rose-700 text-[10px] underline font-bold">Hapus</button>
                        </div>
                        @if (method_exists($payroll_receipt_photo, 'isPreviewable') && $payroll_receipt_photo->isPreviewable())
                            <img src="{{ $payroll_receipt_photo->temporaryUrl() }}" class="max-h-48 mx-auto rounded-xl border border-slate-200 shadow-md">
                        @else
                            <div class="p-3 bg-amber-50 border border-amber-200/80 rounded-xl text-amber-800 text-xs font-semibold flex items-center justify-center gap-2 text-left">
                                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Format berkas ({{ strtoupper($payroll_receipt_photo->getClientOriginalExtension()) }}) tidak mendukung pratinjau langsung di browser, namun berkas tetap siap diunggah.</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Catatan Pembayaran</label>
                <input type="text" wire:model="payroll_payment_notes" placeholder="Catatan transaksi..." class="input-clean w-full text-xs sm:text-sm">
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 pt-3 sm:pt-4 border-t border-slate-100 shrink-0">
                <x-button type="button" variant="secondary" wire:click="$set('showPayrollPaymentModal', false)">Batal</x-button>
                <x-button type="submit" variant="primary" loadingTarget="savePayrollPayment">Simpan Pembayaran & Cetak Resi</x-button>
            </div>
        </form>
    </x-modal-dialog>
@endif
