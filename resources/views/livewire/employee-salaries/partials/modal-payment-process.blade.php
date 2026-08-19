<!-- MODAL PEMBAYARAN GAJI BULANAN -->
@if($showPaymentModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
        <div class="bg-white border border-slate-200/80 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Pembayaran Gaji: {{ $payment_employee_name }}</h3>
                    <p class="text-xs text-slate-500">Proses pencairan gaji bulanan & terbitkan Slip Gaji PDF</p>
                </div>
                <button wire:click="$set('showPaymentModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form wire:submit.prevent="processPayment" class="space-y-4 text-xs">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Bulan Gaji *</label>
                        <select wire:model="payroll_month" class="input-clean w-full font-bold">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->isoFormat('MMMM') }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tahun Gaji *</label>
                        <select wire:model="payroll_year" class="input-clean w-full font-bold">
                            <option value="2026">2026</option>
                            <option value="2025">2025</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Tanggal Pembayaran *</label>
                    <input type="date" wire:model="payment_date" required class="input-clean w-full font-mono">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Gaji Pokok (Rp)</label>
                        <x-currency-input model="pay_basic_salary" class="input-clean w-full font-bold font-mono text-emerald-800" />
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tunjangan (Rp)</label>
                        <x-currency-input model="pay_allowance" class="input-clean w-full font-bold font-mono text-emerald-700" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Bonus / Insentif (Rp)</label>
                        <x-currency-input model="pay_bonus" class="input-clean w-full font-bold font-mono text-blue-700" />
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Potongan (Rp)</label>
                        <x-currency-input model="pay_deductions" class="input-clean w-full font-bold font-mono text-rose-600" />
                    </div>
                </div>

                <div class="p-3 bg-emerald-50 border border-emerald-200/80 rounded-xl space-y-1">
                    <div class="flex justify-between text-xs font-bold text-emerald-900">
                        <span>TOTAL NET GAJI DIBAYAR:</span>
                        <span class="font-mono text-base font-black">
                            Rp {{ number_format(max(0, (float)$pay_basic_salary + (float)$pay_allowance + (float)$pay_bonus - (float)$pay_deductions), 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Metode Pembayaran</label>
                        <select wire:model="payment_method" class="input-clean w-full font-bold">
                            <option value="transfer">Transfer Bank</option>
                            <option value="cash">Tunai / Cash</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Bank & No. Rekening</label>
                        <input type="text" wire:model="pay_bank_name" placeholder="BCA 1234567890" class="input-clean w-full font-mono">
                    </div>
                </div>

                <x-receipt-upload 
                    model="receipt_photo" 
                    :photo="$receipt_photo" 
                    label="Foto Struk / Bukti Transfer (Opsional)"
                />

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <x-button variant="secondary" size="md" type="button" wire:click="$set('showPaymentModal', false)">Batal</x-button>
                    <x-button variant="emerald" size="md" type="submit">Proses Bayar & Terbitkan Slip PDF</x-button>
                </div>
            </form>
        </div>
    </div>
@endif
