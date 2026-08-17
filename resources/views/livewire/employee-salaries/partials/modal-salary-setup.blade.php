<!-- MODAL FORM PENETAPAN STANDAR GAJI -->
@if($showSalaryModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
        <div class="bg-white border border-slate-200/80 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-base">
                    {{ $editingSalaryId ? 'Edit Standar Gaji Karyawan' : 'Penetapan Standar Gaji Karyawan Baru' }}
                </h3>
                <button wire:click="closeSalaryModal" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form wire:submit.prevent="saveSalaryStandard" class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Pilih Sumber Data Karyawan</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" wire:click="$set('target_type', 'user')" class="py-2 px-3 rounded-xl border text-xs font-bold transition {{ $target_type === 'user' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                            Staf / Akun User
                        </button>
                        <button type="button" wire:click="$set('target_type', 'worker')" class="py-2 px-3 rounded-xl border text-xs font-bold transition {{ $target_type === 'worker' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                            Pekerja Lapangan
                        </button>
                        <button type="button" wire:click="$set('target_type', 'custom')" class="py-2 px-3 rounded-xl border text-xs font-bold transition {{ $target_type === 'custom' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-slate-50 text-slate-700 border-slate-200' }}">
                            Custom / Manual
                        </button>
                    </div>
                </div>

                @if($target_type === 'user')
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pilih Akun Staf / User *</label>
                        <select wire:model.live="user_id" class="input-clean w-full font-bold">
                            <option value="">Pilih Staf / User...</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} (Role: {{ ucfirst($u->role) }})</option>
                            @endforeach
                        </select>
                    </div>
                @elseif($target_type === 'worker')
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pilih Pekerja Lapangan *</label>
                        <select wire:model.live="worker_id" class="input-clean w-full font-bold">
                            <option value="">Pilih Pekerja / Mandor...</option>
                            @foreach($workers as $w)
                                <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }})</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Lengkap Karyawan *</label>
                        <input type="text" wire:model="employee_name" required class="input-clean w-full font-bold">
                        @error('employee_name') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jabatan / Posisi *</label>
                        <input type="text" wire:model="position" required placeholder="misal: Supervisor / Finance / Mandor" class="input-clean w-full">
                        @error('position') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Gaji Pokok (Rp) *</label>
                        <x-currency-input model="basic_salary" class="input-clean w-full font-bold font-mono text-emerald-800" />
                        @error('basic_salary') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tunjangan Jabatan/Operasional (Rp)</label>
                        <x-currency-input model="allowance" class="input-clean w-full font-bold font-mono text-emerald-700" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Bonus / Insentif (Rp)</label>
                        <x-currency-input model="bonus" class="input-clean w-full font-bold font-mono text-blue-700" />
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Potongan BPJS/Kasbon (Rp)</label>
                        <x-currency-input model="deductions" class="input-clean w-full font-bold font-mono text-rose-600" />
                    </div>
                </div>

                <div class="p-3 bg-emerald-50 border border-emerald-200/80 rounded-xl space-y-1">
                    <div class="flex justify-between text-xs font-bold text-emerald-900">
                        <span>Estimasi Gaji Bersih (Net):</span>
                        <span class="font-mono text-sm">
                            Rp {{ number_format(max(0, (float)$basic_salary + (float)$allowance + (float)$bonus - (float)$deductions), 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nama Bank</label>
                        <input type="text" wire:model="bank_name" placeholder="BCA / Mandiri / BRI" class="input-clean w-full font-bold">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nomor Rekening</label>
                        <input type="text" wire:model="bank_account_number" placeholder="1234567890" class="input-clean w-full font-mono">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <x-button variant="secondary" size="md" type="button" wire:click="closeSalaryModal">Batal</x-button>
                    <x-button variant="primary" size="md" type="submit">Simpan Standar Gaji</x-button>
                </div>
            </form>
        </div>
    </div>
@endif
