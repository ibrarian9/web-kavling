<!-- Gaji / Kontrak Borongan Worker Unit Card -->
<x-card padding="p-5">
    <div class="flex flex-wrap items-center justify-between border-b border-slate-100 pb-3 gap-2">
        <div class="flex items-center gap-2">
            <div class="p-2 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="font-extrabold text-slate-900 text-sm">Pembayaran Kontrak Pekerja</h3>
                <p class="text-[10px] text-slate-400">Kesepakatan kontrak borongan & progres pembayaran</p>
            </div>
        </div>

        @if(auth()->user()->isSupervisor() || auth()->user()->isPengawasProject() || auth()->user()->isFounder())
            <x-button variant="emerald" size="xs" wire:click="openPayrollSetupModal" icon="plus">
                <span>Set Kontrak Kerja</span>
            </x-button>
        @endif
    </div>

    <div class="space-y-4 text-xs mt-4">
        @forelse($unitPayrolls as $up)
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden transition-all duration-200 hover:border-slate-300">
                <!-- Worker Header Bar -->
                <div class="p-3.5 bg-slate-50/90 border-b border-slate-100 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-extrabold text-xs flex items-center justify-center border border-emerald-200/60 uppercase shrink-0">
                            {{ strtoupper(substr($up->worker->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-extrabold text-slate-900 text-xs truncate">{{ $up->worker->name }}</h4>
                            <p class="text-[10px] text-slate-500 font-semibold capitalize truncate">{{ $up->worker->type }} {{ $up->worker->specialty ? '('.$up->worker->specialty.')' : '' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 shrink-0">
                        <x-status-badge :status="$up->status" />
                        @if(auth()->user()->isFounder())
                            <x-action-dropdown size="xs" icon="dots" title="Menu Opsi Kontrak">
                                <div class="py-1">
                                    <x-dropdown-item icon="edit" wire:click="editPayrollSetup({{ $up->id }})">
                                        Edit Penetapan Kontrak
                                    </x-dropdown-item>
                                    <x-dropdown-item icon="pdf" wire:click="openViewerModal('pdf', '{{ route('units.payroll.spk-pdf', $up->id) }}', 'Pratinjau Surat Perintah Kerja (SPK) - {{ $up->worker->name }}')">
                                        Cetak SPK PDF
                                    </x-dropdown-item>
                                </div>
                                <div class="py-1">
                                    <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                        title: 'Hapus Penetapan Kontrak',
                                        message: 'Yakin ingin menghapus penetapan kontrak kerja unit ini beserta riwayat pembayarannya?',
                                        confirmText: 'Hapus Penetapan Kontrak',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deletePayrollSetup({{ $up->id }})
                                    })">
                                        Hapus Penetapan Kontrak
                                    </x-dropdown-item>
                                </div>
                            </x-action-dropdown>
                        @endif
                    </div>
                </div>

                <!-- Financial Stats & Progress -->
                <div class="p-3.5 space-y-3">
                    <div class="grid grid-cols-3 gap-1.5 sm:gap-2 text-center">
                        <div class="bg-slate-50 p-2 rounded-xl border border-slate-100 min-w-0">
                            <span class="text-slate-400 block text-[9px] uppercase font-bold tracking-wider truncate">Total Kontrak</span>
                            <span class="font-extrabold text-slate-800 font-mono text-[10px] sm:text-xs block truncate" title="Rp {{ number_format($up->agreed_salary, 0, ',', '.') }}">Rp {{ number_format($up->agreed_salary, 0, ',', '.') }}</span>
                        </div>
                        <div class="bg-emerald-50/60 p-2 rounded-xl border border-emerald-100 min-w-0">
                            <span class="text-emerald-700 block text-[9px] uppercase font-bold tracking-wider truncate">Terbayar</span>
                            <span class="font-extrabold text-emerald-800 font-mono text-[10px] sm:text-xs block truncate" title="Rp {{ number_format($up->paid_amount, 0, ',', '.') }}">Rp {{ number_format($up->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="bg-amber-50/60 p-2 rounded-xl border border-amber-100 min-w-0">
                            <span class="text-amber-800 block text-[9px] uppercase font-bold tracking-wider truncate">Sisa Kontrak</span>
                            <span class="font-extrabold text-amber-800 font-mono text-[10px] sm:text-xs block truncate" title="Rp {{ number_format($up->remaining_salary, 0, ',', '.') }}">Rp {{ number_format($up->remaining_salary, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-[10px] text-slate-500 font-semibold">
                            <span>Pencairan Progres Pembayaran Kontrak</span>
                            <span class="font-mono font-bold text-emerald-700">{{ $up->progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden p-0.5 border border-slate-200/50">
                            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-1.5 rounded-full transition-all duration-300" style="width: {{ $up->progress_percentage }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Footer Action Bar -->
                <div class="px-3.5 py-2.5 bg-slate-50/60 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2">
                    <x-button variant="pdf" size="xs" wire:click="openViewerModal('pdf', '{{ route('units.payroll.spk-pdf', $up->id) }}', 'Pratinjau Surat Perintah Kerja (SPK) - {{ $up->worker->name }}')" icon="pdf">
                        <span>Cetak SPK PDF</span>
                    </x-button>

                    @if($up->status !== 'lunas')
                        <x-button variant="emerald" size="xs" wire:click="openPayrollPaymentModal({{ $up->id }})">
                            <svg class="w-3.5 h-3.5 text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Bayar Kontrak</span>
                        </x-button>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-6 text-slate-400 text-xs italic bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                Belum ada penetapan kontrak kerja borongan worker untuk unit {{ $unit->code }}.
            </div>
        @endforelse
    </div>
</x-card>
