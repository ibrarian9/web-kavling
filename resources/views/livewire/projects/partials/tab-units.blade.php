<!-- TAB 1: Penjualan & Profit Per Unit -->
<div class="space-y-4">
    <div class="card-clean p-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <h3 class="font-bold text-slate-900 text-sm whitespace-nowrap">{{ (auth()->user()->isPengawasProject() || auth()->user()->isMarketing()) ? 'Daftar Status Unit Proyek' : 'Dashboard Penjualan & Profit Per Unit' }}</h3>
            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-200">
                {{ count($unitsList) }} Unit
            </span>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            @if(!auth()->user()->isMarketing())
                @if(count($unitsList) > 0)
                    <a href="{{ route('projects.sales-profit-pdf', $project->id) }}" target="_blank" class="btn-header-pdf">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span>Lihat PDF Rekap</span>
                    </a>
                @else
                    <button disabled class="btn-header-pdf-disabled" title="Belum ada data unit untuk digenerate PDF">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <span>PDF Rekap (Belum Ada Data)</span>
                    </button>
                @endif
            @endif

            <x-search-input model="unitSearch" placeholder="Cari kode unit..." containerClass="w-full sm:w-48" />

            <div class="w-full sm:w-36">
                <select wire:model.live="statusFilter" class="select-clean w-full">
                    <option value="">Semua Status Unit</option>
                    <option value="tersedia">Tersedia</option>
                    <option value="menunggu_persetujuan">Menunggu Approval</option>
                    <option value="disetujui">Disetujui / Terjual</option>
                    <option value="booked">Booked</option>
                </select>
            </div>
            <div class="w-full sm:w-32">
                <select wire:model.live="typeFilter" class="select-clean w-full">
                    <option value="">Semua Tipe</option>
                    <option value="kavling">Kavling Tanah</option>
                    <option value="rumah">Rumah Bangunan</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table: Unit Sales, Costs, and Profit Performance -->
    <div class="card-clean overflow-hidden">

        <!-- Mobile Card Layout (visible on small screens) -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($unitsList as $u)
                @php 
                    $perf = $unitPerformances[$u->id] ?? [
                        'selling_price' => 0,
                        'paid_amount' => 0,
                        'remaining_amount' => 0,
                        'hpp' => (float)$u->hpp,
                        'unit_costs' => 0,
                        'profit' => 0,
                        'buyer_name' => '-',
                        'is_sold' => false,
                    ];
                @endphp
                <div class="p-4 space-y-3">
                    <!-- Header: Code + Status + Action -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-sm font-mono text-emerald-700">{{ $u->code }}</span>
                            @if ($u->status === 'tersedia')
                                <span class="status-tersedia">Tersedia</span>
                            @elseif ($u->status === 'disetujui' || $u->status === 'converted' || $u->status === 'terjual')
                                <span class="status-disetujui">Terjual</span>
                            @elseif ($u->status === 'booked')
                                <span class="status-booked">Booked</span>
                            @else
                                <span class="status-menunggu">{{ ucfirst($u->status) }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5">
                            @if((auth()->user()->isAdminOrFounder() || auth()->user()->isFinance()) && $u->status === 'tersedia' && $u->category !== 'infrastruktur')
                                <a href="{{ route('units.show', $u->id) }}" wire:navigate.hover class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold transition shadow-xs" title="Pembelian Cash Direct">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Pembelian Cash</span>
                                </a>
                            @endif
                            <a href="{{ route('units.show', $u->id) }}" wire:navigate.hover class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-teal-50 text-teal-800 hover:bg-teal-100 border border-teal-200 text-[11px] font-bold transition">
                                <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Detail</span>
                            </a>
                            @if(auth()->user()->isFounder())
                                <button type="button" @click="confirmModalAction({
                                    title: 'Hapus Unit Kavling/Rumah',
                                    message: 'Yakin ingin menghapus unit {{ $u->code }} dari proyek ini beserta seluruh histori terikatnya?',
                                    confirmText: 'Hapus Unit',
                                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                    onConfirm: () => $wire.deleteUnit({{ $u->id }})
                                })" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-[11px] font-bold transition" title="Hapus Unit">
                                    <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Hapus</span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Category & Area -->
                    <div class="flex items-center gap-3 text-xs text-slate-600">
                        <span class="font-semibold capitalize">{{ $u->category ?? $u->type }}</span>
                        <span class="text-slate-400">•</span>
                        <span class="font-mono text-slate-500">{{ number_format($u->land_area, 0, ',', '.') }} m²</span>
                    </div>

                    @if(!auth()->user()->isPengawasProject())
                        <!-- Financial Grid -->
                        @if($perf['buyer_name'] !== '-')
                            <p class="text-xs font-bold text-slate-800">Pembeli: {{ $perf['buyer_name'] }}</p>
                        @endif
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="bg-emerald-50/80 rounded-lg p-2 border border-emerald-100">
                                <span class="text-[10px] text-emerald-600 font-semibold block">Harga Deal</span>
                                <span class="font-mono font-bold text-emerald-700">
                                    @if($perf['selling_price'] > 0)
                                        Rp {{ number_format($perf['selling_price'], 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-400 font-normal italic">-</span>
                                    @endif
                                </span>
                            </div>
                            <div class="bg-sky-50/80 rounded-lg p-2 border border-sky-100">
                                <span class="text-[10px] text-sky-600 font-semibold block">Sudah Dibayar</span>
                                <span class="font-mono font-bold text-sky-700">
                                    @if($perf['paid_amount'] > 0)
                                        Rp {{ number_format($perf['paid_amount'], 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-400 font-normal italic">Rp 0</span>
                                    @endif
                                </span>
                            </div>
                            <div class="bg-amber-50/80 rounded-lg p-2 border border-amber-100 {{ auth()->user()->isMarketing() ? 'col-span-2' : '' }}">
                                <span class="text-[10px] text-amber-600 font-semibold block">Sisa Tagihan</span>
                                <span class="font-mono font-bold text-amber-700">
                                    @if($perf['is_sold'] && $perf['remaining_amount'] > 0)
                                        Rp {{ number_format($perf['remaining_amount'], 0, ',', '.') }}
                                    @elseif($perf['is_sold'] && $perf['remaining_amount'] == 0)
                                        <span class="text-emerald-600 font-bold">LUNAS</span>
                                    @else
                                        <span class="text-slate-400 font-normal italic">-</span>
                                    @endif
                                </span>
                            </div>
                            @if(!auth()->user()->isMarketing())
                                <div class="rounded-lg p-2 border {{ $perf['is_sold'] && $perf['profit'] >= 0 ? 'bg-emerald-50/80 border-emerald-100' : ($perf['is_sold'] ? 'bg-rose-50/80 border-rose-100' : 'bg-slate-50 border-slate-100') }}">
                                    <span class="text-[10px] font-semibold block {{ $perf['is_sold'] && $perf['profit'] >= 0 ? 'text-emerald-600' : ($perf['is_sold'] ? 'text-rose-600' : 'text-slate-500') }}">Profit</span>
                                    <span class="font-mono font-bold">
                                        @if($perf['is_sold'])
                                            <span class="{{ $perf['profit'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $perf['profit'] >= 0 ? '+' : '' }} Rp {{ number_format($perf['profit'], 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 font-normal italic text-[11px]">Belum Terjual</span>
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                    @else
                        @php $uAssignment = $u->assignments->where('status', 'active')->first(); @endphp
                        <div class="text-xs">
                            @if($uAssignment && $uAssignment->worker)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-blue-50 text-blue-800 border border-blue-200 font-semibold">
                                    {{ $uAssignment->worker->name }} ({{ ucfirst($uAssignment->worker->type) }})
                                </span>
                            @else
                                <span class="text-slate-400 italic">Penugasan mengikuti proyek</span>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="px-6 py-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                    <p class="font-semibold text-slate-600">Tidak ada unit kavling ditemukan</p>
                    <p class="text-xs text-slate-400 mt-1">Coba ganti filter status atau tambahkan unit baru untuk proyek ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table Layout (hidden on small screens) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-100/90 text-slate-700 uppercase text-[10px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-3 py-3.5">Kode Unit & Status</th>
                        <th class="px-3 py-3.5">Kategori & Luas Tanah</th>
                        @if(!auth()->user()->isPengawasProject())
                            <th class="px-3 py-3.5">Nama Pembeli</th>
                            <th class="px-3 py-3.5 text-right">Harga Deal (Rp)</th>
                            <th class="px-3 py-3.5 text-right">Sudah Dibayar (Rp)</th>
                            <th class="px-3 py-3.5 text-right">Sisa Tagihan (Rp)</th>
                            @if(auth()->user()->canViewHpp())
                                <th class="px-3 py-3.5 text-right">HPP & Biaya (Rp)</th>
                            @endif
                            @if(!auth()->user()->isMarketing())
                                <th class="px-3 py-3.5 text-right">Profit / Margin (Rp)</th>
                            @endif
                        @else
                            <th class="px-3 py-3.5">Mandor / Worker Bertugas</th>
                        @endif
                        <th class="px-3 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($unitsList as $u)
                        @php 
                            $perf = $unitPerformances[$u->id] ?? [
                                'selling_price' => 0,
                                'paid_amount' => 0,
                                'remaining_amount' => 0,
                                'hpp' => (float)$u->hpp,
                                'unit_costs' => 0,
                                'profit' => 0,
                                'buyer_name' => '-',
                                'is_sold' => false,
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-3 py-3.5">
                                <div class="flex items-center gap-2">
                                    <span class="font-extrabold text-slate-900 text-sm font-mono text-emerald-700">{{ $u->code }}</span>
                                    @if ($u->status === 'tersedia')
                                        <span class="status-tersedia">Tersedia</span>
                                    @elseif ($u->status === 'disetujui' || $u->status === 'converted' || $u->status === 'terjual')
                                        <span class="status-disetujui">Terjual</span>
                                    @elseif ($u->status === 'booked')
                                        <span class="status-booked">Booked</span>
                                    @else
                                        <span class="status-menunggu">{{ ucfirst($u->status) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-3.5">
                                <span class="font-semibold text-slate-800 capitalize">{{ $u->category ?? $u->type }}</span>
                                <span class="text-[11px] text-slate-500 font-mono block">{{ number_format($u->land_area, 0, ',', '.') }} m²</span>
                            </td>

                            @if(!auth()->user()->isPengawasProject())
                                <td class="px-3 py-3.5 font-bold text-slate-800">
                                    {{ $perf['buyer_name'] }}
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono font-extrabold text-emerald-700">
                                    @if($perf['selling_price'] > 0)
                                        Rp {{ number_format($perf['selling_price'], 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-400 font-normal italic">-</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono font-extrabold text-sky-700">
                                    @if($perf['paid_amount'] > 0)
                                        Rp {{ number_format($perf['paid_amount'], 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-400 font-normal italic">Rp 0</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono font-extrabold text-amber-700">
                                    @if($perf['is_sold'] && $perf['remaining_amount'] > 0)
                                        Rp {{ number_format($perf['remaining_amount'], 0, ',', '.') }}
                                    @elseif($perf['is_sold'] && $perf['remaining_amount'] == 0)
                                        <span class="text-emerald-600 font-bold">LUNAS</span>
                                    @else
                                        <span class="text-slate-400 font-normal italic">-</span>
                                    @endif
                                </td>
                                @if(auth()->user()->canViewHpp())
                                    <td class="px-3 py-3.5 text-right font-mono text-slate-600">
                                        <div>HPP: Rp {{ number_format($perf['hpp'], 0, ',', '.') }}</div>
                                        @if($perf['unit_costs'] > 0)
                                            <div class="text-[10px] text-rose-600 font-semibold">+ Biaya: Rp {{ number_format($perf['unit_costs'], 0, ',', '.') }}</div>
                                        @endif
                                    </td>
                                @endif
                                @if(!auth()->user()->isMarketing())
                                    <td class="px-3 py-3.5 text-right font-mono font-extrabold">
                                        @if($perf['is_sold'])
                                            <span class="{{ $perf['profit'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $perf['profit'] >= 0 ? '+' : '' }} Rp {{ number_format($perf['profit'], 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 font-normal italic">Belum Terjual</span>
                                        @endif
                                    </td>
                                @endif
                            @else
                                <td class="px-3 py-3.5">
                                    @php
                                        $uAssignment = $u->assignments->where('status', 'active')->first();
                                    @endphp
                                    @if($uAssignment && $uAssignment->worker)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-blue-50 text-blue-800 border border-blue-200 text-xs font-semibold">
                                            {{ $uAssignment->worker->name }} ({{ ucfirst($uAssignment->worker->type) }})
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs italic">Penugasan mengikuti proyek</span>
                                    @endif
                                </td>
                            @endif

                            <td class="px-3 py-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if((auth()->user()->isAdminOrFounder() || auth()->user()->isFinance()) && $u->status === 'tersedia' && $u->category !== 'infrastruktur')
                                        <a href="{{ route('units.show', $u->id) }}" wire:navigate.hover class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold transition shadow-xs" title="Pembelian Cash Direct">
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Pembelian Cash</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('units.show', $u->id) }}" wire:navigate.hover class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-teal-50 text-teal-800 hover:bg-teal-100 border border-teal-200 text-[11px] font-bold transition">
                                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Detail</span>
                                    </a>
                                    @if(auth()->user()->isFounder())
                                         <button type="button" @click="confirmModalAction({
                                             title: 'Hapus Unit Kavling/Rumah',
                                             message: 'Yakin ingin menghapus unit {{ $u->code }} dari proyek ini beserta seluruh histori terikatnya?',
                                             confirmText: 'Hapus Unit',
                                             btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                             onConfirm: () => $wire.deleteUnit({{ $u->id }})
                                         })" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-[11px] font-bold transition" title="Hapus Unit">
                                             <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                             <span>Hapus</span>
                                         </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                                <p class="font-semibold text-slate-600">Tidak ada unit kavling ditemukan</p>
                                <p class="text-xs text-slate-400 mt-1">Coba ganti filter status atau tambahkan unit baru untuk proyek ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
