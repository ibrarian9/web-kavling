<!-- Modal Form Payroll Payment for this Unit (Form Responsif Mobile) -->
@if($showPayrollPaymentModal && $selectedPayroll)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg sm:max-w-xl md:max-w-2xl w-full p-4 sm:p-7 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base sm:text-xl tracking-tight">{{ !empty($editingSalaryPaymentId) ? 'Edit Pembayaran Gaji Unit' : 'Pembayaran Gaji Unit' }} {{ $unit->code }}</h3>
                    <p class="text-slate-500 text-[11px] sm:text-xs mt-0.5">Pekerja: {{ $selectedPayroll->worker->name }}</p>
                </div>
                <button wire:click="$set('showPayrollPaymentModal', false)" class="p-1.5 sm:p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
            </div>

            <div class="bg-slate-50 p-3 sm:p-4 rounded-xl sm:rounded-2xl border border-slate-200 text-xs sm:text-sm grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 shrink-0">
                <div>
                    <span class="text-slate-500 block text-[11px] sm:text-xs">Total Gaji Borongan:</span>
                    <span class="font-bold text-slate-900 font-mono text-sm sm:text-base">Rp {{ number_format($selectedPayroll->agreed_salary, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-slate-500 block text-[11px] sm:text-xs">Sisa Kontrak Belum Dibayar:</span>
                    <span class="font-bold text-amber-600 font-mono text-sm sm:text-base">Rp {{ number_format($selectedPayroll->remaining_salary, 0, ',', '.') }}</span>
                </div>
            </div>

            <form wire:submit.prevent="savePayrollPayment" class="space-y-3 sm:space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Tanggal Pembayaran <span class="text-rose-500">*</span></label>
                        <input type="date" wire:model="payroll_payment_date" required class="input-clean w-full text-xs sm:text-sm font-mono">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Metode Pembayaran <span class="text-rose-500">*</span></label>
                        <select wire:model.live="payroll_payment_method" class="select-clean w-full">
                            <option value="transfer_bank">🏦 Transfer Bank</option>
                            <option value="tunai">💵 Tunai (Cash)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 text-xs uppercase tracking-wider">Nominal Gaji Dibayarkan <span class="text-rose-500">*</span></label>
                    <div class="flex rounded-xl shadow-xs">
                        <span class="bg-slate-100 border border-r-0 border-slate-200 px-3 py-2 text-slate-500 font-mono text-xs font-bold flex items-center rounded-l-xl">
                            Rp
                        </span>
                        <x-currency-input model="payroll_amount_gross" class="input-clean rounded-r-xl rounded-l-none font-mono font-bold text-slate-900 text-sm sm:text-base w-full" placeholder="Misal: 2.500.000" />
                    </div>
                    @error('payroll_amount_gross') <span class="text-xs text-rose-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                </div>

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
                    <button type="button" wire:click="$set('showPayrollPaymentModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Pembayaran & Cetak Resi</button>
                </div>
            </form>
        </div>
    </div>
@endif
