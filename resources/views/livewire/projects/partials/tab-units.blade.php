<!-- TAB 1: Penjualan & Profit Per Unit -->
<div class="space-y-4">
    <x-card padding="p-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2 w-full sm:w-auto flex-nowrap">
                <h3 class="font-bold text-slate-900 text-sm whitespace-nowrap">{{ (auth()->user()->isPengawasProject() || auth()->user()->isMarketing()) ? 'Daftar Status Unit Proyek' : 'Dashboard Penjualan & Profit Per Unit' }}</h3>
                <span class="inline-flex items-center bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-emerald-200 whitespace-nowrap">
                    {{ count($unitsList) }} Unit
                </span>
            </div>

            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                @if(!auth()->user()->isMarketing())
                    @if(count($unitsList) > 0)
                        <x-button variant="outline" size="sm" href="{{ route('projects.sales-profit-pdf', $project->id) }}" target="_blank" icon="pdf">
                            <span>Lihat PDF Rekap</span>
                        </x-button>
                    @else
                        <x-button variant="outline" size="sm" disabled icon="pdf" title="Belum ada data unit untuk digenerate PDF" class="opacity-50 cursor-not-allowed">
                            <span>PDF Rekap (Kosong)</span>
                        </x-button>
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
    </x-card>

    <!-- Table: Unit Sales, Costs, and Profit Performance -->
    <x-card padding="p-0" class="overflow-hidden">

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
                            <x-status-badge :status="$u->status" />
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            @if((auth()->user()->isAdminOrFounder() || auth()->user()->isFinance()) && $u->status === 'tersedia' && $u->category !== 'infrastruktur')
                                <x-button variant="emerald" size="xs" href="{{ route('units.show', $u->id) }}" wire:navigate.hover title="Pembelian Cash Direct">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Pembelian Cash</span>
                                </x-button>
                            @endif
                            <x-button variant="detail" size="xs" href="{{ route('units.show', $u->id) }}">
                                <span>Detail</span>
                            </x-button>
                            @if(auth()->user()->isSuperAdmin())
                                <x-button variant="delete" size="xs" @click="confirmModalAction({
                                    title: 'Hapus Unit Kavling/Rumah',
                                    message: 'Yakin ingin menghapus unit {{ $u->code }} dari proyek ini beserta seluruh histori terikatnya?',
                                    confirmText: 'Hapus Unit',
                                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                    onConfirm: () => $wire.deleteUnit({{ $u->id }})
                                })" title="Hapus Unit">
                                    <span>Hapus</span>
                                </x-button>
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
                            <td class="px-3 py-3.5 whitespace-nowrap">
                                <div class="inline-flex items-center gap-2 whitespace-nowrap flex-nowrap">
                                    <span class="font-extrabold text-slate-900 text-sm font-mono text-emerald-700 whitespace-nowrap">{{ $u->code }}</span>
                                    <x-status-badge :status="$u->status" />
                                </div>
                            </td>
                            <td class="px-3 py-3.5 whitespace-nowrap">
                                <span class="font-semibold text-slate-800 capitalize">{{ $u->category ?? $u->type }}</span>
                                <span class="text-[11px] text-slate-500 font-mono block">{{ number_format($u->land_area, 0, ',', '.') }} m²</span>
                            </td>

                            @if(!auth()->user()->isPengawasProject())
                                <td class="px-3 py-3.5 font-bold text-slate-800 whitespace-nowrap">
                                    {{ $perf['buyer_name'] }}
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono font-extrabold text-emerald-700 whitespace-nowrap">
                                    @if($perf['selling_price'] > 0)
                                        Rp {{ number_format($perf['selling_price'], 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-400 font-normal italic">-</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono font-extrabold text-sky-700 whitespace-nowrap">
                                    @if($perf['paid_amount'] > 0)
                                        Rp {{ number_format($perf['paid_amount'], 0, ',', '.') }}
                                    @else
                                        <span class="text-slate-400 font-normal italic">Rp 0</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 text-right font-mono font-extrabold text-amber-700 whitespace-nowrap">
                                    @if($perf['is_sold'] && $perf['remaining_amount'] > 0)
                                        Rp {{ number_format($perf['remaining_amount'], 0, ',', '.') }}
                                    @elseif($perf['is_sold'] && $perf['remaining_amount'] == 0)
                                        <span class="text-emerald-600 font-bold">LUNAS</span>
                                    @else
                                        <span class="text-slate-400 font-normal italic">-</span>
                                    @endif
                                </td>
                                @if(auth()->user()->canViewHpp())
                                    <td class="px-3 py-3.5 text-right font-mono text-slate-600 whitespace-nowrap">
                                        <div>HPP: Rp {{ number_format($perf['hpp'], 0, ',', '.') }}</div>
                                        @if($perf['unit_costs'] > 0)
                                            <div class="text-[10px] text-rose-600 font-semibold">+ Biaya: Rp {{ number_format($perf['unit_costs'], 0, ',', '.') }}</div>
                                        @endif
                                    </td>
                                @endif
                                @if(!auth()->user()->isMarketing())
                                    <td class="px-3 py-3.5 text-right font-mono font-extrabold whitespace-nowrap">
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
                                <td class="px-3 py-3.5 whitespace-nowrap">
                                    @php
                                        $uAssignment = $u->assignments->where('status', 'active')->first();
                                    @endphp
                                    @if($uAssignment && $uAssignment->worker)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-blue-50 text-blue-800 border border-blue-200 text-xs font-semibold whitespace-nowrap">
                                            {{ $uAssignment->worker->name }} ({{ ucfirst($uAssignment->worker->type) }})
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs italic whitespace-nowrap">Penugasan mengikuti proyek</span>
                                    @endif
                                </td>
                            @endif

                            <td class="px-3 py-3.5 text-center whitespace-nowrap">
                                <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                                    <x-button variant="detail" size="xs" href="{{ route('units.show', $u->id) }}" wire:navigate.hover>
                                        Detail
                                    </x-button>

                                    @if(((auth()->user()->isAdminOrFounder() || auth()->user()->isFinance()) && $u->status === 'tersedia' && $u->category !== 'infrastruktur') || auth()->user()->isFounder())
                                        <x-action-dropdown title="Menu Opsi Unit" size="xs">
                                            @if((auth()->user()->isAdminOrFounder() || auth()->user()->isFinance()) && $u->status === 'tersedia' && $u->category !== 'infrastruktur')
                                                <div class="py-1">
                                                    <x-dropdown-item icon="plus" variant="success" href="{{ route('units.show', $u->id) }}" wire:navigate.hover>
                                                        Pembelian Cash
                                                    </x-dropdown-item>
                                                </div>
                                            @endif

                                            @if(auth()->user()->isSuperAdmin())
                                                <div class="py-1">
                                                    <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                                        title: 'Hapus Unit Kavling/Rumah',
                                                        message: 'Yakin ingin menghapus unit {{ $u->code }} dari proyek ini beserta seluruh histori terikatnya?',
                                                        confirmText: 'Hapus Unit',
                                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                        onConfirm: () => $wire.deleteUnit({{ $u->id }})
                                                    })">
                                                        Hapus Unit
                                                    </x-dropdown-item>
                                                </div>
                                            @endif
                                        </x-action-dropdown>
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
    </x-card>
</div>
