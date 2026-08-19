<!-- TAB 1: CICILAN & PIUTANG PEMBELI UNIT -->
<div class="space-y-6">

    <!-- Summary KPI Cards Grid (4 Responsive Columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="kpi-card-blue bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Unit Berjalan</span>
                <div class="p-2.5 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">{{ $installments->total() }} Skema</p>
            <p class="text-[11px] text-slate-400 mt-1">Skema kredit & cicilan terdaftar</p>
        </div>

        <div class="kpi-card-rose bg-rose-50/40 border border-rose-200/60 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600">Belum Bayar ({{ $currentMonthName }})</span>
                <div class="p-2.5 rounded-2xl bg-rose-100 text-rose-700 border border-rose-200 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-rose-700 font-mono mt-2">{{ $unpaidThisMonthCount }} Pembeli</p>
            <p class="text-[11px] text-rose-600 font-semibold mt-1">Rp {{ number_format($unpaidThisMonthAmount, 0, ',', '.') }} est. tagihan</p>
        </div>

        <div class="kpi-card-emerald bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sudah Masuk ({{ $currentMonthName }})</span>
                <div class="p-2.5 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">Rp {{ number_format($paidThisMonthAmount, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 mt-1">{{ $paidThisMonthCount }} pembeli telah bayar</p>
        </div>

        <div class="kpi-card-amber bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Sisa Piutang Berjalan</span>
                <div class="p-2.5 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-700 font-mono mt-2">
                Rp {{ number_format(\App\Models\UnitInstallment::all()->sum(fn($i) => $i->remaining_balance), 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Sisa tagihan belum lunas</p>
        </div>
    </div>

    <!-- 2-Baris Search & Filter Controls Bar -->
    <div class="card-clean p-4 border border-slate-200/80 rounded-3xl space-y-3 shadow-xs">
        <!-- Baris 1: Full-Width Search Input -->
        <div class="w-full">
            <x-search-input placeholder="Cari kode unit (contoh: A-01, B-05), nama pembeli, atau proyek..." containerClass="w-full" />
        </div>

        <!-- Baris 2: Filter Controls Grid/Flex -->
        <div class="flex items-center justify-between gap-2.5 flex-wrap pt-1 border-t border-slate-100/80">
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Global Date Period Filter -->
                <x-date-period-filter 
                    periodModel="datePeriod" 
                    startModel="startDate" 
                    endModel="endDate" 
                    :periodValue="$datePeriod" 
                />

                <!-- Skema & Monthly Status Dropdown Filter -->
                <select wire:model.live="monthlyFilter" class="select-clean text-xs font-bold">
                    <option value="all">Semua Skema Pembayaran</option>
                    <option value="unpaid_this_month">Belum Bayar Bulan Ini (Tunggakan {{ $unpaidThisMonthCount > 0 ? "[$unpaidThisMonthCount]" : '' }})</option>
                    <option value="paid_this_month">Sudah Bayar Bulan Ini</option>
                    <option value="lunas">Lunas / Konversi Cash</option>
                </select>

                <!-- Project Filter Dropdown -->
                <select wire:model.live="projectIdFilter" class="select-clean text-xs font-bold">
                    <option value="">Semua Proyek Properti</option>
                    @foreach(($projects ?? \App\Models\Project::orderBy('name')->get()) as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>

                <!-- Status Filter -->
                <select wire:model.live="statusFilter" class="select-clean text-xs font-bold">
                    <option value="">Semua Status Cicilan</option>
                    <option value="berjalan">Berjalan (Aktif)</option>
                    <option value="lunas">Lunas</option>
                    <option value="konversi_cash">Konversi Cash</option>
                </select>
            </div>

            @if($search || $statusFilter || $projectIdFilter || $monthlyFilter !== 'all' || $datePeriod !== 'all')
                <x-reset-filter-button 
                    wire:click="$set('search', ''); $set('statusFilter', ''); $set('projectIdFilter', ''); $set('monthlyFilter', 'all'); $set('datePeriod', 'all'); $set('startDate', ''); $set('endDate', '');" 
                />
            @endif
        </div>
    </div>

    <!-- Installment Table -->
    <x-table :headers="['Unit & Proyek', 'Nama Konsumen / Pembeli', 'Harga Kesepakatan', 'Uang Muka (DP)', 'Progres & Terbayar', 'Sisa Piutang', 'Status', ['label' => 'Aksi', 'class' => 'p-3.5 text-center']]" loadingTarget="search, statusFilter, projectIdFilter, monthlyFilter, datePeriod, startDate, endDate, page">
        @forelse($installments as $inst)
            @php
                $currentMonth = now()->month;
                $currentYear = now()->year;
                $paidThisMonth = $inst->payments->contains(function ($p) use ($currentMonth, $currentYear) {
                    return $p->payment_date && $p->payment_date->month == $currentMonth && $p->payment_date->year == $currentYear;
                });
                $buyerName = $inst->officialDocument->buyer_name ?? ($inst->unit->activeBooking->buyer_name ?? ($inst->unit->bookings->first()->buyer_name ?? 'Pembeli Kavling'));
            @endphp
            <tr class="hover:bg-slate-50/80 transition duration-150">
                <td class="p-3.5">
                    <a href="{{ route('units.show', $inst->unit_id) }}" class="font-bold text-slate-900 text-sm hover:text-emerald-600 block">
                        {{ $inst->unit->code ?? '-' }}
                    </a>
                    <span class="text-[10px] text-slate-500 font-medium">{{ $inst->unit->project->name ?? '-' }}</span>
                </td>
                <td class="p-3.5">
                    <p class="font-bold text-slate-800 text-xs">{{ $buyerName }}</p>
                    <div class="flex items-center gap-1 mt-0.5">
                        @if($inst->status === 'berjalan')
                            @if($paidThisMonth)
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">Bulan ini: Terbayar</span>
                            @else
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200">Bulan ini: Belum Bayar</span>
                            @endif
                        @endif
                    </div>
                </td>
                <td class="p-3.5 font-mono font-bold text-slate-900 text-xs">
                    Rp {{ number_format($inst->total_price, 0, ',', '.') }}
                </td>
                <td class="p-3.5 font-mono font-bold text-slate-700 text-xs">
                    Rp {{ number_format($inst->down_payment, 0, ',', '.') }}
                </td>
                <td class="p-3.5">
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-[10px] font-bold font-mono">
                            <span class="text-emerald-700">Rp {{ number_format($inst->total_paid, 0, ',', '.') }}</span>
                            <span class="text-slate-500">{{ $inst->progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full transition-all duration-300" style="width: {{ $inst->progress_percentage }}%"></div>
                        </div>
                        <p class="text-[9px] text-slate-400 font-mono">{{ $inst->payments->count() }}x setoran tercatat</p>
                    </div>
                </td>
                <td class="p-3.5 font-mono font-bold text-xs {{ $inst->remaining_balance > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                    Rp {{ number_format($inst->remaining_balance, 0, ',', '.') }}
                </td>
                <td class="p-3.5">
                    @if($inst->status === 'lunas')
                        <x-status-badge status="lunas" label="LUNAS" />
                    @elseif($inst->status === 'konversi_cash')
                        <x-status-badge status="lunas" label="CASH" />
                    @else
                        <x-status-badge status="berjalan" label="BERJALAN" />
                    @endif
                </td>
                <td class="p-3.5 text-center whitespace-nowrap">
                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                        @if($inst->status === 'berjalan' && (auth()->user()->isFounder() || auth()->user()->isFinance()))
                            <x-button variant="payment" icon="payment" size="xs" wire:click="openPaymentModal({{ $inst->id }})" title="Catat Pembayaran Setoran Cicilan">
                                <span>Setor</span>
                            </x-button>
                        @else
                            <x-button variant="outline" icon="detail" size="xs" wire:click="openDetailModal({{ $inst->id }})" title="Lihat Histori & Rincian Setoran">
                                <span>Detail</span>
                            </x-button>
                        @endif

                        <x-action-dropdown title="Menu Opsi Cicilan" size="xs">
                            <div class="py-1">
                                <x-dropdown-item icon="detail" wire:click="openDetailModal({{ $inst->id }})">
                                    Detail Rincian
                                </x-dropdown-item>

                                <x-dropdown-item icon="pdf" wire:click="openViewerModal('pdf', '{{ route('installments.unit-statement-pdf', $inst->id) }}', 'Pratinjau Rekapitulasi Cicilan Unit {{ $inst->unit->code }} - {{ $inst->buyer_name }}')">
                                    Cetak Rekap PDF
                                </x-dropdown-item>

                                @if(auth()->user()->isAdminOrFounder())
                                    <x-dropdown-item icon="edit" wire:click="openSetupModal({{ $inst->id }})">
                                        Edit Skema
                                    </x-dropdown-item>
                                @endif
                            </div>

                            @if(auth()->user()->isSuperAdmin())
                                <div class="py-1">
                                    <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                        title: 'Hapus Skema Cicilan',
                                        message: 'Yakin ingin menghapus seluruh skema cicilan Unit {{ $inst->unit->code ?? '' }} dan seluruh riwayat setorannya?',
                                        confirmText: 'Ya, Hapus Skema',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteInstallment({{ $inst->id }})
                                    })">
                                        Hapus Skema
                                    </x-dropdown-item>
                                </div>
                            @endif
                        </x-action-dropdown>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="p-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="font-bold text-slate-600">Belum Ada Skema Cicilan Pembeli</p>
                    <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Setup Skema Cicilan Baru" untuk mendaftarkan unit terjual.</p>
                </td>
            </tr>
        @endforelse
    </x-table>
    <div>{{ $installments->links() }}</div>

</div>
