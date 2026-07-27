<div class="space-y-6">

    <!-- Header Section & Action -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Pengelolaan Cicilan & Piutang Pembeli</h2>
            <p class="text-slate-500 text-xs mt-0.5">Pantau skema pembayaran berkala pembeli, sisa saldo piutang, dan riwayat setoran</p>
        </div>

        @if(auth()->user()->isFinance() || auth()->user()->isFounder())
            <button wire:click="openSetupModal" class="btn-primary whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Setup Skema Cicilan Baru</span>
            </button>
        @endif
    </div>

    <!-- Table Card -->
    <div class="card-clean overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Unit & Pembeli</th>
                        <th class="px-5 py-3.5">Total Harga Jual</th>
                        <th class="px-5 py-3.5">Uang Muka (DP)</th>
                        <th class="px-5 py-3.5">Termin Cicilan</th>
                        <th class="px-5 py-3.5">Total Terbayar</th>
                        <th class="px-5 py-3.5">Sisa Piutang</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($installments as $inst)
                        <tr class="hover:bg-slate-50/60 transition duration-150">
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-900 font-mono text-sm">{{ $inst->unit->code }}</p>
                                <p class="text-slate-500 text-[11px] font-medium">{{ $inst->officialDocument->buyer_name ?? 'Pembeli' }}</p>
                            </td>
                            <td class="px-5 py-4 font-mono font-extrabold text-slate-800">
                                Rp {{ number_format($inst->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 font-mono text-emerald-700 font-bold">
                                Rp {{ number_format($inst->down_payment, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-800">{{ $inst->installment_count }}x Termin</span>
                                <p class="text-slate-400 text-[10px] font-mono">Rp {{ number_format($inst->installment_amount, 0, ',', '.') }} / bln</p>
                            </td>
                            <td class="px-5 py-4 font-mono font-bold text-emerald-600">
                                Rp {{ number_format($inst->total_paid, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 font-mono font-extrabold text-rose-600">
                                Rp {{ number_format($inst->remaining_balance, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4">
                                @if($inst->status === 'lunas')
                                    <span class="status-disetujui">LUNAS</span>
                                @elseif($inst->status === 'menunggak')
                                    <span class="status-ditolak">MENUNGGAK</span>
                                @else
                                    <span class="status-menunggu">BERJALAN</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if($inst->status !== 'lunas' && (auth()->user()->isFinance() || auth()->user()->isFounder()))
                                    <button wire:click="openPaymentModal({{ $inst->id }})" class="btn-primary text-[11px] px-2.5 py-1">
                                        + Catat Setoran
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <p class="font-semibold text-slate-600">Belum Ada Skema Cicilan / Piutang</p>
                                <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Setup Skema Cicilan Baru" untuk memproses unit kredit.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-slate-100">
            {{ $installments->links() }}
        </div>
    </div>

    <!-- Modal Setup Skema Cicilan -->
    @if($showSetupModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">Setup Skema Cicilan Pembeli</h3>
                    <button wire:click="$set('showSetupModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveSetup" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Pilih Unit Terjual</label>
                        <select wire:change="selectUnitForInstallment($event.target.value)" wire:model="unit_id" class="input-clean w-full font-bold">
                            <option value="">-- Pilih Unit --</option>
                            @foreach($eligibleUnits as $u)
                                <option value="{{ $u->id }}">{{ $u->code }} - {{ $u->project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Total Harga Jual (Rp)</label>
                            <x-currency-input model="total_price" class="input-clean w-full font-bold font-mono" placeholder="Rp 0" />
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Uang Muka / DP (Rp)</label>
                            <x-currency-input model="down_payment" class="input-clean w-full font-bold font-mono text-emerald-700" placeholder="Rp 0" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Jumlah Bulan Termin</label>
                            <input type="number" wire:model.live="installment_count" min="1" max="60" class="input-clean w-full font-bold">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Mulai Tanggal</label>
                            <input type="date" wire:model="start_date" class="input-clean w-full font-mono">
                        </div>
                    </div>

                    <!-- Highlight Box Kalkulasi Cicilan -->
                    <div class="bg-emerald-50 border border-emerald-200/80 rounded-xl p-3.5 space-y-1.5 text-emerald-900">
                        <div class="flex justify-between text-xs">
                            <span class="text-emerald-800">Sisa Pokok Piutang:</span>
                            <span class="font-mono font-bold">Rp {{ number_format(max(0, $total_price - $down_payment), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold pt-1.5 border-t border-emerald-200/80">
                            <span>Nominal Cicilan Per Bulan:</span>
                            <span class="font-mono text-emerald-700 text-sm">Rp {{ number_format($installment_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showSetupModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Skema</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Catat Pembayaran Setoran -->
    @if($showPaymentModal && $activeInstallment)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Catat Setoran Cicilan</h3>
                        <p class="text-slate-500 text-[11px]">Unit: {{ $activeInstallment->unit->code }} (Pembeli: {{ $activeInstallment->officialDocument->buyer_name ?? '-' }})</p>
                    </div>
                    <button wire:click="$set('showPaymentModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="submitPayment" class="space-y-4 text-xs">
                    <div class="bg-slate-900 text-white rounded-xl p-3.5 space-y-1.5 shadow-inner">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400">Total Sisa Piutang:</span>
                            <span class="font-mono font-bold text-rose-400">Rp {{ number_format($activeInstallment->remaining_balance, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400">Standar Cicilan Bulanan:</span>
                            <span class="font-mono text-emerald-400">Rp {{ number_format($activeInstallment->installment_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nominal Pembayaran Diterima (Rp)</label>
                        <x-currency-input model="payment_amount" class="input-clean w-full font-bold text-sm font-mono" placeholder="Rp 0" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Metode Bayar</label>
                            <select wire:model="payment_method" class="input-clean w-full font-semibold">
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Tunai / Cash">Tunai / Cash</option>
                                <option value="Cek / Giro">Cek / Giro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Tanggal Bayar</label>
                            <input type="date" wire:model="payment_date" required class="input-clean w-full font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Catatan Pembayaran</label>
                        <input type="text" wire:model="payment_notes" placeholder="Setoran bulan ke-2..." class="input-clean w-full">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showPaymentModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan & Masukkan Kas</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
