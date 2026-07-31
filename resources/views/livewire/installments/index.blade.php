<div class="space-y-6">

    <!-- Header Section & Action -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Pengelolaan Cicilan & Piutang Pembeli</h2>
            <p class="text-slate-500 text-xs mt-0.5">Pantau skema pembayaran berkala pembeli, sisa saldo piutang, dan riwayat setoran</p>
        </div>

        @if(auth()->user()->isFounder())
            <button wire:click="openSetupModal" class="btn-header-setup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Setup Skema Cicilan Baru</span>
            </button>
        @endif
    </div>

    <!-- Summary KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="kpi-card-blue">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Unit Berjalan Cicilan</span>
                <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 border border-purple-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">{{ $installments->total() }} Skema</p>
            <p class="text-[11px] text-slate-400 mt-1">Skema kredit & cicilan terdaftar</p>
        </div>

        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Terbayar Pembeli</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">
                Rp {{ number_format(\App\Models\UnitInstallment::all()->sum('total_paid'), 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Uang muka DP & setoran bulanan</p>
        </div>

        <div class="kpi-card-amber">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sisa Piutang Berjalan</span>
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-700 font-mono mt-2">
                Rp {{ number_format(\App\Models\UnitInstallment::all()->sum(fn($i) => $i->remaining_balance), 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Sisa tagihan pembeli belum lunas</p>
        </div>
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
                                @elseif($inst->status === 'konversi_cash')
                                    <span class="bg-purple-100 text-purple-900 border border-purple-300 font-extrabold px-2.5 py-0.5 rounded-full text-[10px]" title="Skema cicilan dibatalkan & dialihkan ke Pelunasan Cash">
                                        LUNAS CASH
                                    </span>
                                @elseif($inst->status === 'menunggak')
                                    <span class="status-ditolak">MENUNGGAK</span>
                                @else
                                    <span class="status-menunggu">BERJALAN</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    <button wire:click="openDetailModal({{ $inst->id }})" class="btn-action-detail">
                                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Detail</span>
                                    </button>

                                    <a href="{{ route('units.show', $inst->unit_id) }}" class="btn-action-unit" title="Lihat Halaman Detail Unit">
                                        <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        <span>Unit</span>
                                    </a>

                                    @if(!in_array($inst->status, ['lunas', 'konversi_cash']) && auth()->user()->isFounder())
                                        <button wire:click="openPaymentModal({{ $inst->id }})" class="btn-action-payment" title="Catat Setoran Cicilan Pembeli">
                                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            <span>Setoran</span>
                                        </button>
                                        <button wire:click="openConvertToCashModal({{ $inst->id }})" class="btn-action-convert" title="Batalkan skema cicilan & lunasi Cash">
                                            <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                            <span>Batalkan & Ganti Cash</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
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

    <!-- Modal Setup Skema Cicilan Baru -->
    @include('livewire.installments.partials.modal-setup-installment')

    <!-- Modal Catat Pembayaran Setoran -->
    @include('livewire.installments.partials.modal-installment-payment')

    <!-- Modal Batalkan Skema Cicilan & Ganti ke Pelunasan Cash -->
    @include('livewire.installments.partials.modal-convert-to-cash')

    <!-- Modal Detail Rincian Skema Cicilan & Riwayat Setoran -->
    @include('livewire.installments.partials.modal-installment-detail')

</div>
