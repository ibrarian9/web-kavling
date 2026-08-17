<!-- Filters Toolbar -->
<div class="card-clean p-4 border border-slate-200/80 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-3">
    <div class="flex items-center gap-2.5 w-full md:w-auto flex-wrap">
        <select wire:model.live="project_id" class="select-clean text-xs font-bold w-full md:w-56">
            <option value="">Semua Proyek Kavling</option>
            @foreach ($projects as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="unit_id" class="select-clean text-xs font-bold w-full md:w-48">
            <option value="">Semua Unit</option>
            @foreach ($availableUnits as $u)
                <option value="{{ $u->id }}">Unit: {{ $u->code }}</option>
            @endforeach
        </select>

        <select wire:model.live="category_filter" class="select-clean text-xs font-bold w-full md:w-48">
            <option value="all">Semua Tipe Transaksi</option>
            <option value="salary">Gaji Worker Saja</option>
            <option value="material">Belanja Material Saja</option>
        </select>
    </div>

    <x-search-input placeholder="Cari unit, proyek, barang, atau worker..." containerClass="w-full md:w-72" />
</div>

<!-- Table Expense List -->
<x-table :headers="['Tgl Transaksi', 'Kategori / Tipe', 'Proyek & Unit', 'Nama Rincian Barang / Worker', 'Jumlah Qty & Harga Unit', ['label' => 'Total Biaya', 'class' => 'p-3.5 text-right'], ['label' => 'Bukti Resi', 'class' => 'p-3.5 text-center'], ['label' => 'Aksi', 'class' => 'p-3.5 text-right']]" loadingTarget="project_id, unit_id, category_filter, search">
    @forelse ($expenses as $item)
        <tr class="hover:bg-slate-50/60 transition-colors">
            <td class="p-3.5 font-mono font-medium text-slate-600 text-xs whitespace-nowrap">
                {{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}
            </td>
            <td class="p-3.5 whitespace-nowrap">
                @if ($item['type'] === 'salary')
                    <span class="bg-amber-50 text-amber-800 border border-amber-200 font-bold px-2.5 py-0.5 rounded-full text-[10px] inline-flex items-center gap-1">
                        <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Gaji Worker
                    </span>
                @else
                    <span class="bg-sky-50 text-sky-800 border border-sky-200 font-bold px-2.5 py-0.5 rounded-full text-[10px] inline-flex items-center gap-1">
                        <svg class="w-3 h-3 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Belanja Barang
                    </span>
                @endif
            </td>
            <td class="p-3.5">
                <div class="font-bold text-slate-800 text-xs">{{ $item['project_name'] }}</div>
                <div class="text-[11px] font-semibold text-emerald-700 mt-0.5">Unit: {{ $item['unit_code'] }}</div>
            </td>
            <td class="p-3.5 font-bold text-slate-900 text-xs">
                {{ $item['title'] }}
            </td>
            <td class="p-3.5 font-mono text-slate-700 text-xs">
                <span class="bg-slate-100 px-2 py-0.5 rounded font-semibold text-slate-800">{{ $item['quantity_label'] }}</span>
            </td>
            <td class="p-3.5 text-right font-mono font-extrabold text-slate-900 text-xs whitespace-nowrap">
                Rp {{ number_format($item['total_price'], 0, ',', '.') }}
            </td>
            <td class="p-3.5 text-center whitespace-nowrap">
                <div class="inline-flex items-center justify-center gap-1.5 flex-wrap">
                    @if (!empty($item['receipt_photo']))
                        <x-button variant="amber" size="xs" wire:click="openViewer('Pratinjau Foto Struk Nota Belanja', 'image', '{{ $item['receipt_photo'] }}')" title="Pratinjau Foto Struk">
                            Struk
                        </x-button>
                    @endif

                    @if (!empty($item['pdf_url']))
                        <x-button variant="outline" size="xs" wire:click="openViewer('Pratinjau Resi Gaji PDF', 'pdf', '{{ $item['pdf_url'] }}')" title="Pratinjau PDF Resi">
                            PDF
                        </x-button>
                    @endif

                    @if (empty($item['receipt_photo']) && empty($item['pdf_url']))
                        <span class="text-slate-300 text-[10px] italic">Tanpa Lampiran</span>
                    @endif
                </div>
            </td>
            <td class="p-3.5 text-right whitespace-nowrap">
                <div class="inline-flex items-center justify-end gap-1.5 whitespace-nowrap">
                    @if (auth()->user()->isAdminOrFounder() || auth()->user()->isSupervisor() || auth()->user()->isFinance())
                        <x-action-dropdown title="Menu Opsi Biaya" size="xs">
                            <div class="py-1">
                                <x-dropdown-item icon="edit" wire:click="editExpense('{{ $item['type'] }}', {{ $item['id'] }})">
                                    Edit Data Biaya
                                </x-dropdown-item>
                            </div>
                            <div class="py-1">
                                <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                    title: 'Hapus Biaya Lapangan',
                                    message: 'Yakin ingin menghapus catatan biaya ini beserta transaksi arus kas terkait?',
                                    confirmText: 'Hapus Biaya',
                                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                    onConfirm: () => $wire.deleteExpense('{{ $item['type'] }}', {{ $item['id'] }})
                                })">
                                    Hapus Biaya
                                </x-dropdown-item>
                            </div>
                        </x-action-dropdown>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="p-12 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="font-semibold text-slate-600">Belum Ada Catatan Pengeluaran Lapangan</p>
                <p class="text-xs text-slate-400 mt-1">Data upah tukang & belanja material proyek akan dicantumkan di sini.</p>
            </td>
        </tr>
    @endforelse
</x-table>
