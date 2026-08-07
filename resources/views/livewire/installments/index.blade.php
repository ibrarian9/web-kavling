<div class="space-y-6">

    <!-- Header Section & Action -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <span>Pengelolaan Cicilan & Piutang Pembeli</span>
                <span class="px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800 text-[11px] font-extrabold border border-purple-200">Live Tracker</span>
            </h2>
            <p class="text-slate-500 text-xs mt-0.5">Pantau persentase pelunasan piutang, skema pembayaran berkala, sisa saldo, dan histori setoran konsumen.</p>
        </div>

        @if(auth()->user()->isFounder() || auth()->user()->isFinance())
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
                <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 border border-purple-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">{{ $installments->total() }} Skema</p>
            <p class="text-[11px] text-slate-400 mt-1">Skema kredit & cicilan terdaftar</p>
        </div>

        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Terbayar Pembeli</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
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
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-700 font-mono mt-2">
                Rp {{ number_format(\App\Models\UnitInstallment::all()->sum(fn($i) => $i->remaining_balance), 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Sisa tagihan pembeli belum lunas</p>
        </div>
    </div>

    <!-- Search & Filter Controls Bar -->
    <div class="card-clean p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="relative w-full sm:w-80">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Cari kode unit (A-01), pembeli, atau proyek..." 
                   class="w-full pl-9 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-medium text-slate-800 placeholder-slate-400" />
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
            <label for="statusFilter" class="text-xs text-slate-500 font-medium whitespace-nowrap">Status:</label>
            <select id="statusFilter" wire:model.live="statusFilter" class="py-2 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-emerald-500 text-slate-700 font-semibold">
                <option value="">Semua Status</option>
                <option value="berjalan">Berjalan</option>
                <option value="lunas">LUNAS</option>
                <option value="konversi_cash">LUNAS CASH</option>
                <option value="menunggak">Menunggak</option>
            </select>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-clean overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Unit & Pembeli</th>
                        <th class="px-5 py-3.5">Total Deal</th>
                        <th class="px-5 py-3.5">Progress Pelunasan</th>
                        <th class="px-5 py-3.5">Total Terbayar</th>
                        <th class="px-5 py-3.5">Sisa Piutang</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi & Setoran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($installments as $inst)
                        @php
                            $pctPaid = $inst->total_price > 0 ? min(100, round(($inst->total_paid / $inst->total_price) * 100, 1)) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition duration-150">
                            <!-- Unit & Buyer Info -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 font-extrabold text-xs flex items-center justify-center shrink-0">
                                        {{ $inst->unit->code }}
                                    </div>
                                    <div>
                                        <p class="font-extrabold text-slate-900 font-mono text-sm">Unit {{ $inst->unit->code }}</p>
                                        <p class="text-slate-600 text-[11px] font-semibold flex items-center gap-1">
                                            <span>👤 {{ $inst->officialDocument->buyer_name ?? 'Konsumen Pembeli' }}</span>
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-mono">{{ $inst->unit->project->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Total Deal Price -->
                            <td class="px-5 py-4 font-mono">
                                <span class="font-extrabold text-slate-800 text-xs block">Rp {{ number_format($inst->total_price, 0, ',', '.') }}</span>
                                <span class="text-[10px] text-emerald-700 font-bold block">DP: Rp {{ number_format($inst->down_payment, 0, ',', '.') }}</span>
                            </td>

                            <!-- Visual Progress Bar Column -->
                            <td class="px-5 py-4 w-48">
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between text-[11px] font-mono">
                                        <span class="font-bold text-slate-700">{{ $pctPaid }}%</span>
                                        <span class="text-slate-400 text-[10px]">{{ $inst->installment_count }}x Termin</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200/50">
                                        <div class="h-2 rounded-full transition-all duration-500 {{ $pctPaid >= 100 ? 'bg-emerald-500' : ($pctPaid >= 50 ? 'bg-teal-500' : 'bg-amber-500') }}" 
                                             style="width: {{ $pctPaid }}%"></div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-mono">Rp {{ number_format($inst->installment_amount, 0, ',', '.') }} / bln</p>
                                </div>
                            </td>

                            <!-- Total Paid -->
                            <td class="px-5 py-4 font-mono font-bold text-emerald-700 text-xs">
                                Rp {{ number_format($inst->total_paid, 0, ',', '.') }}
                            </td>

                            <!-- Remaining Balance -->
                            <td class="px-5 py-4 font-mono font-extrabold text-rose-600 text-xs">
                                Rp {{ number_format($inst->remaining_balance, 0, ',', '.') }}
                            </td>

                            <!-- Status Badge -->
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

                            <!-- Action Buttons Column (100% Pixel-Perfect Standardized Size & Dropdown Layout) -->
                            <td class="px-4 py-3.5 text-right w-36 align-middle">
                                <div x-data="{ open: false }" class="relative inline-flex items-center justify-end gap-1">
                                    
                                    <!-- Primary Action Button (Identical Height h-8 & Width w-20) -->
                                    @if(!in_array($inst->status, ['lunas', 'konversi_cash']) && (auth()->user()->isFounder() || auth()->user()->isFinance()))
                                        <button wire:click="openPaymentModal({{ $inst->id }})" 
                                                class="h-8 w-20 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-2xs flex items-center justify-center gap-1 shrink-0"
                                                title="Catat Setoran Cicilan Pembeli">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>Setoran</span>
                                        </button>
                                    @else
                                        <button wire:click="openDetailModal({{ $inst->id }})" 
                                                class="h-8 w-20 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200/80 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 shrink-0"
                                                title="Lihat Detail Skema & Setoran">
                                            <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Detail</span>
                                        </button>
                                    @endif

                                    <!-- Dropdown Trigger Button (Identical Height h-8 & Width w-8) -->
                                    <button @click="open = !open" 
                                            @click.outside="open = false" 
                                            class="h-8 w-8 flex items-center justify-center text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 rounded-xl transition focus:outline-none shrink-0" 
                                            title="Menu Opsi Lainnya">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>

                                    <!-- Dropdown Popover Menu -->
                                    <div x-show="open" 
                                        x-transition:enter="transition ease-out duration-100" 
                                        x-transition:enter-start="opacity-0 scale-95" 
                                        x-transition:enter-end="opacity-100 scale-100" 
                                        x-transition:leave="transition ease-in duration-75" 
                                        x-transition:leave-start="opacity-100 scale-100" 
                                        x-transition:leave-end="opacity-0 scale-95" 
                                        x-cloak 
                                        class="absolute right-0 top-full z-30 mt-1.5 w-52 rounded-2xl bg-white shadow-xl ring-1 ring-slate-900/10 p-1 text-xs text-left divide-y divide-slate-100">
                                        
                                        <!-- Tombol 1: Detail Skema -->
                                        <button @click="open = false; $wire.openDetailModal({{ $inst->id }})" 
                                                class="w-full text-left h-9.5 px-3 text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold rounded-xl transition flex items-center gap-2.5 group">
                                            <svg class="w-4 h-4 text-teal-600 group-hover:scale-110 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Lihat Detail Skema</span>
                                        </button>

                                        <!-- Tombol 2: Detail Unit -->
                                        <a href="{{ route('units.show', $inst->unit_id) }}" 
                                        wire:navigate.hover 
                                        class="w-full text-left h-9.5 px-3 text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold rounded-xl transition flex items-center gap-2.5 group">
                                            <svg class="w-4 h-4 text-blue-600 group-hover:scale-110 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                            <span>Halaman Detail Unit</span>
                                        </a>

                                        <!-- Tombol 3: Ganti Cash (Conditional) -->
                                        @if(!in_array($inst->status, ['lunas', 'konversi_cash']) && (auth()->user()->isFounder() || auth()->user()->isFinance()))
                                            <button @click="open = false; $wire.openConvertToCashModal({{ $inst->id }})" 
                                                    class="w-full text-left h-9.5 px-3 text-purple-700 hover:bg-purple-50 font-semibold rounded-xl transition flex items-center gap-2.5 group">
                                                <svg class="w-4 h-4 text-purple-600 group-hover:scale-110 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                                <span>Batalkan & Ganti Cash</span>
                                            </button>
                                        @endif

                                        <!-- Tombol 4: Hapus Skema (Conditional) -->
                                        @if(auth()->user()->isFounder())
                                            <button type="button" 
                                                    @click="open = false; confirmModalAction({
                                                        title: 'Hapus Skema Cicilan',
                                                        message: 'Yakin ingin menghapus skema cicilan unit {{ $inst->unit->code }} beserta seluruh histori terikatnya?',
                                                        confirmText: 'Hapus Skema',
                                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                        onConfirm: () => $wire.deleteInstallment({{ $inst->id }})
                                                    })" 
                                                    class="w-full text-left h-9.5 px-3 text-rose-600 hover:bg-rose-50 font-semibold rounded-xl transition flex items-center gap-2.5 group">
                                                <svg class="w-4 h-4 text-rose-600 group-hover:scale-110 shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Hapus Skema Cicilan</span>
                                            </button>
                                        @endif

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <p class="font-semibold text-slate-600">Belum Ada Skema Cicilan / Piutang Ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau buat skema cicilan baru.</p>
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
