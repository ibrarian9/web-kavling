<!-- Filters Toolbar -->
<div class="card-clean p-4 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-56">
        <select wire:model.live="project_id" class="input-clean w-full">
            <option value="">Semua Proyek Kavling</option>
            @foreach ($projects as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="w-full md:w-48">
        <select wire:model.live="unit_id" class="input-clean w-full font-semibold">
            <option value="">Semua Unit</option>
            @foreach ($availableUnits as $u)
                <option value="{{ $u->id }}">Unit Kode: {{ $u->code }}</option>
            @endforeach
        </select>
    </div>

    <div class="w-full md:w-48">
        <select wire:model.live="category_filter" class="input-clean w-full">
            <option value="all">Semua Tipe Transaksi</option>
            <option value="salary">Gaji Worker Saja</option>
            <option value="material">Belanja Material Saja</option>
        </select>
    </div>

    <div class="flex-1">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kode unit, nama proyek, barang, atau worker..." class="input-clean w-full">
    </div>
</div>

<!-- Table Expense List -->
<div class="card-clean overflow-hidden">
    <div class="overflow-x-auto relative min-h-[260px]">
        <!-- Reusable System Centered Table Loading Component -->
        <x-table-loading target="project_id, unit_id, category_filter, search" text="Memuat & Menyaring Laporan Pengeluaran..." subtext="Mohon tunggu sebentar, sistem sedang memproses data belanja & gaji." />

        <table class="w-full text-left text-xs text-slate-600" wire:loading.class="opacity-30 pointer-events-none transition-opacity duration-300" wire:target="project_id, unit_id, category_filter, search">
            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3.5">Tgl Transaksi</th>
                    <th class="px-5 py-3.5">Kategori / Tipe</th>
                    <th class="px-5 py-3.5">Proyek & Unit</th>
                    <th class="px-5 py-3.5">Nama Rincian Barang / Worker</th>
                    <th class="px-5 py-3.5">Jumlah Qty & Harga Unit</th>
                    <th class="px-5 py-3.5 text-right">Total Biaya</th>
                    <th class="px-5 py-3.5 text-center">Bukti Resi</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($expenses as $item)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="px-5 py-4 font-mono font-medium text-slate-600 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item['date'])->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
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
                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-800">{{ $item['project_name'] }}</div>
                            <div class="text-[11px] font-semibold text-emerald-700 mt-0.5">Unit: {{ $item['unit_code'] }}</div>
                        </td>
                        <td class="px-5 py-4 font-bold text-slate-900">
                            {{ $item['title'] }}
                        </td>
                        <td class="px-5 py-4 font-mono text-slate-700">
                            <span class="bg-slate-100 px-2 py-0.5 rounded font-semibold text-slate-800">{{ $item['quantity_label'] }}</span>
                        </td>
                        <td class="px-5 py-4 text-right font-mono font-extrabold text-slate-900 text-sm whitespace-nowrap">
                            Rp {{ number_format($item['total_price'], 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                @if (!empty($item['receipt_photo']))
                                    <button wire:click="openViewer('Pratinjau Foto Struk Nota Belanja', 'image', '{{ $item['receipt_photo'] }}')" title="Pratinjau Foto Struk" class="btn-action-edit">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span>Struk</span>
                                    </button>
                                @endif

                                @if (!empty($item['pdf_url']))
                                    <button wire:click="openViewer('Pratinjau Resi Gaji PDF', 'pdf', '{{ $item['pdf_url'] }}')" title="Pratinjau PDF Resi" class="btn-action-pdf">
                                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <span>PDF</span>
                                    </button>
                                @endif

                                @if (!empty($item['qr_url']))
                                    <a href="{{ $item['qr_url'] }}" target="_blank" title="Verifikasi Resi Gaji Publik (QR Code)" class="btn-action-qr">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                        <span>QR</span>
                                    </a>
                                @endif

                                @if (empty($item['receipt_photo']) && empty($item['pdf_url']) && empty($item['qr_url']))
                                    <span class="text-slate-400 italic text-[11px]">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                @if(isset($item['id']))
                                    <button wire:click="openEditModal('{{ $item['type'] }}', {{ $item['id'] }})" class="btn-action-edit" title="Edit Transaksi Operasional">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </button>

                                    <button type="button" @click="confirmModalAction({
                                        title: 'Hapus Transaksi Operasional',
                                        message: 'Yakin ingin menghapus catatan transaksi pengeluaran lapangan ini?',
                                        confirmText: 'Hapus Transaksi',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteExpense('{{ $item['type'] }}', {{ $item['id'] }})
                                    })" class="btn-action-delete" title="Hapus Transaksi Operasional">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus</span>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="font-bold text-slate-600">Belum Ada Transaksi Pengeluaran Lapangan</p>
                            <p class="text-xs text-slate-400 mt-1">Pembayaran gaji dan belanja material unit akan muncul di tabel laporan ini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
