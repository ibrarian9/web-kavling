<div class="space-y-6">
    <!-- Header & Single-row Toolbar -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Piutang & Pinjaman Mandor / Tukang</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pencatatan kas bon, pinjaman worker, dan pemotongan opname mingguan</p>
        </div>
        <button wire:click="createLoan" class="btn-primary whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Catat Pinjaman Baru</span>
        </button>
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="card-clean p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Pinjaman Dicatat</span>
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">Rp {{ number_format($totalAllLoans, 0, ',', '.') }}</p>
        </div>

        <div class="card-clean p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Dibayar / Dipotong</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600 font-mono mt-2">Rp {{ number_format($totalPaidLoans, 0, ',', '.') }}</p>
        </div>

        <div class="card-clean p-5 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sisa Piutang Berjalan</span>
                <div class="p-2.5 rounded-xl bg-rose-50 text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-rose-600 font-mono mt-2">Rp {{ number_format($totalRemainingLoans, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filters Toolbar -->
    <div class="card-clean p-4 flex flex-col md:flex-row gap-3">
        <div class="w-full md:w-64">
            <select wire:model.live="workerFilter" class="input-clean w-full">
                <option value="">Semua Pekerja (Mandor/Tukang)</option>
                @foreach ($workers as $w)
                    <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }})</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-48">
            <select wire:model.live="statusFilter" class="input-clean w-full">
                <option value="">Semua Status Piutang</option>
                <option value="approved">Belum Dicicil</option>
                <option value="partially_paid">Sebagian Dicicil</option>
                <option value="paid">Lunas</option>
            </select>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-clean overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Tgl Pinjaman</th>
                        <th class="px-5 py-3.5">Pekerja</th>
                        <th class="px-5 py-3.5">Proyek / Unit</th>
                        <th class="px-5 py-3.5">Keperluan</th>
                        <th class="px-5 py-3.5 text-right">Total Pinjaman</th>
                        <th class="px-5 py-3.5 text-right">Sisa Piutang</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($loans as $loan)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-5 py-4 font-mono font-medium text-slate-700">
                                {{ $loan->loan_date ? $loan->loan_date->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-900">
                                {{ $loan->worker->name }}
                                <span class="block text-[11px] font-normal text-slate-400 capitalize">{{ $loan->worker->type }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-semibold text-slate-800">{{ $loan->project->name ?? 'Global' }}</span>
                                @if ($loan->unit)
                                    <span class="block text-slate-400 font-mono text-[11px]">Unit: {{ $loan->unit->code }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-slate-700 max-w-xs truncate">
                                {{ $loan->purpose ?: '-' }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-bold text-slate-800">
                                Rp {{ number_format($loan->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-extrabold text-rose-600">
                                Rp {{ number_format($loan->remaining_balance, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if ($loan->status === 'paid')
                                    <span class="status-disetujui">Lunas</span>
                                @elseif ($loan->status === 'partially_paid')
                                    <span class="status-booked">Dicicil</span>
                                @else
                                    <span class="status-ditolak">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if ($loan->remaining_balance > 0)
                                    <button wire:click="openPaymentModal({{ $loan->id }})" class="btn-primary text-[11px] px-2.5 py-1">
                                        Bayar / Potong
                                    </button>
                                @else
                                    <span class="text-[11px] font-semibold text-slate-400">Tuntas</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="font-semibold text-slate-600">Belum Ada Data Pinjaman Worker</p>
                                <p class="text-xs text-slate-400 mt-1">Catat pinjaman atau kas bon baru menggunakan tombol di atas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100">
            {{ $loans->links() }}
        </div>
    </div>

    <!-- Modal Catat Pinjaman -->
    @if ($showLoanModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200/80">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">
                        Form Catat Pinjaman Worker
                    </h3>
                    <button wire:click="$set('showLoanModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Pilih Pekerja</label>
                        <select wire:model="worker_id" class="input-clean w-full font-semibold">
                            <option value="">-- Pilih Pekerja --</option>
                            @foreach ($workers as $w)
                                <option value="{{ $w->id }}">{{ $w->name }} ({{ ucfirst($w->type) }})</option>
                            @endforeach
                        </select>
                        @error('worker_id') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Proyek Terkait (Opsional)</label>
                        <select wire:model.live="project_id" class="input-clean w-full">
                            <option value="">-- Pilih Proyek --</option>
                            @foreach ($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Unit Terkait (Opsional)</label>
                        <select wire:model="unit_id" class="input-clean w-full" {{ !$project_id ? 'disabled' : '' }}>
                            <option value="">-- Pilih Unit --</option>
                            @foreach ($availableUnits as $u)
                                <option value="{{ $u->id }}">Unit Kode: {{ $u->code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Tanggal Pinjaman</label>
                        <input type="date" wire:model="loan_date" class="input-clean w-full font-mono">
                        @error('loan_date') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Nominal Pinjaman (Rp)</label>
                        <x-currency-input model="amount" class="input-clean w-full font-mono font-bold text-slate-900" />
                        @error('amount') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Keperluan / Keterangan</label>
                        <textarea wire:model="purpose" rows="2" placeholder="Kas bon pribadi, pemotongan opname, dll" class="input-clean w-full"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button wire:click="$set('showLoanModal', false)" type="button" class="btn-secondary">Batal</button>
                    <button wire:click="saveLoan" type="button" class="btn-primary">Simpan Pinjaman</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Potong/Bayar Piutang -->
    @if ($showPaymentModal && $selectedLoan)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200/80">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-base font-bold text-slate-900">
                        Potongan / Pembayaran Piutang Worker
                    </h3>
                    <button wire:click="$set('showPaymentModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-xl text-xs space-y-1">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Nama Pekerja:</span>
                        <span class="font-bold text-slate-900">{{ $selectedLoan->worker->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Sisa Piutang Berjalan:</span>
                        <span class="font-mono font-extrabold text-rose-600">Rp {{ number_format($selectedLoan->remaining_balance, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Nominal Yang Dibayar / Dipotong (Rp)</label>
                        <x-currency-input model="amount_paid" class="input-clean w-full font-mono font-bold text-emerald-700" />
                        @error('amount_paid') <span class="text-[10px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>


                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Metode Pembayaran</label>
                        <select wire:model="payment_method" class="input-clean w-full font-semibold">
                            <option value="potong_opname">Potong Gaji / Opname</option>
                            <option value="tunai">Setor Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 uppercase mb-1">Catatan Pembayaran</label>
                        <textarea wire:model="payment_notes" rows="2" placeholder="Potongan opname minggu ke-x..." class="input-clean w-full"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-slate-100">
                    <button wire:click="$set('showPaymentModal', false)" type="button" class="btn-secondary">Batal</button>
                    <button wire:click="savePayment" type="button" class="btn-primary">Simpan Pembayaran</button>
                </div>
            </div>
        </div>
    @endif
</div>
