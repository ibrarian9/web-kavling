<div class="space-y-6">
    <!-- Header Toolbar -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-[10px] uppercase font-extrabold tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Laporan Operasional Pengawas
                </span>
            </div>
            <h1 class="text-xl font-extrabold text-slate-900 tracking-tight mt-1">Laporan Belanja & Gaji Worker</h1>
            <p class="text-xs text-slate-500 mt-0.5">Rekapitulasi pengeluaran pembayaran gaji worker dan belanja material barang unit</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if(count($expenses) > 0)
                <button wire:click="openViewer('Pratinjau Laporan Belanja & Gaji Worker', 'pdf', '{{ route('field-expenses.export-pdf', ['project_id' => $project_id, 'unit_id' => $unit_id, 'category_filter' => $category_filter, 'search' => $search]) }}')" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold inline-flex items-center gap-1.5 transition shadow-sm">
                    <svg class="w-4 h-4 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Lihat PDF Rekap</span>
                </button>
            @else
                <button disabled class="px-3.5 py-2 bg-slate-100 text-slate-400 border border-slate-200 rounded-xl text-xs font-bold inline-flex items-center gap-1.5 cursor-not-allowed opacity-75" title="Belum ada data transaksi pengeluaran/belanja untuk digenerate PDF">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>PDF Rekap (Belum Ada Data)</span>
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-200/80 rounded-2xl text-rose-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Pengeluaran Lapangan</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600 font-mono tracking-tight mt-2">
                Rp {{ number_format($totalExpenses, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Akumulasi gaji worker + belanja material</p>
        </div>

        <div class="kpi-card-amber">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Gaji Worker Dibayar</span>
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-600 font-mono tracking-tight mt-2">
                Rp {{ number_format($totalSalary, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Upah & borongan unit tukang/mandor</p>
        </div>

        <div class="kpi-card-rose">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Belanja Material Barang</span>
                <div class="p-2.5 rounded-xl bg-rose-50 text-rose-600 border border-rose-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-rose-600 font-mono tracking-tight mt-2">
                Rp {{ number_format($totalMaterial, 0, ',', '.') }}
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Pengadaan bahan bangunan unit</p>
        </div>
    </div>

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
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama barang atau nama worker..." class="input-clean w-full">
        </div>
    </div>

    <!-- Table Expense List -->
    <div class="card-clean overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
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

                                        <button wire:click="deleteExpense('{{ $item['type'] }}', {{ $item['id'] }})" wire:confirm="Yakin ingin menghapus catatan transaksi pengeluaran lapangan ini?" class="btn-action-delete" title="Hapus Transaksi Operasional">
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

    <!-- Modal Edit Transaksi Operasional -->
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 my-auto border border-slate-200/80">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Edit Transaksi {{ $editingType === 'material' ? 'Belanja Material' : 'Gaji Worker' }}</span>
                    </h3>
                    <button wire:click="closeEditModal" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="saveEdit" class="space-y-4 text-xs">
                    @if ($editingType === 'material')
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Tanggal Transaksi Belanja *</label>
                            <input type="date" wire:model="edit_purchase_date" required class="input-clean w-full font-mono">
                            @error('edit_purchase_date') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Nama Rincian Barang / Material *</label>
                            <input type="text" wire:model="edit_item_name" required placeholder="Contoh: Semen Padang 50kg" class="input-clean w-full font-semibold">
                            @error('edit_item_name') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1 text-[10px]">Jumlah Qty *</label>
                                <input type="number" step="0.01" wire:model.live="edit_quantity" required class="input-clean w-full font-mono font-bold">
                                @error('edit_quantity') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1 text-[10px]">Satuan Unit *</label>
                                <input type="text" wire:model="edit_unit_measure" required placeholder="sak / m3 / pcs" class="input-clean w-full font-semibold">
                                @error('edit_unit_measure') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase mb-1 text-[10px]">Harga Satuan (Rp) *</label>
                                <x-currency-input model="edit_unit_price" class="input-clean w-full font-mono font-bold" />
                                @error('edit_unit_price') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between font-mono">
                            <span class="text-slate-500 text-xs font-bold uppercase">Estimasi Total Biaya:</span>
                            <strong class="text-slate-900 font-extrabold text-sm">
                                Rp {{ number_format((float)$edit_quantity * (float)$edit_unit_price, 0, ',', '.') }}
                            </strong>
                        </div>
                    @elseif ($editingType === 'salary')
                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Tanggal Pembayaran Gaji *</label>
                            <input type="date" wire:model="edit_payment_date" required class="input-clean w-full font-mono">
                            @error('edit_payment_date') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Nominal Pembayaran Gaji (Rp) *</label>
                            <x-currency-input model="edit_amount_gross" class="input-clean w-full font-mono font-extrabold text-emerald-700 text-sm" />
                            @error('edit_amount_gross') <span class="text-[10px] text-rose-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase mb-1">Metode Pembayaran *</label>
                            <select wire:model="edit_payment_method" class="input-clean w-full font-semibold">
                                <option value="transfer_bank">Transfer Bank</option>
                                <option value="tunai">Tunai / Cash</option>
                            </select>
                        </div>
                    @endif

                    <div>
                        <label class="block font-bold text-slate-700 uppercase mb-1">Catatan / Keterangan Tambahan</label>
                        <textarea wire:model="edit_notes" rows="2" placeholder="Catatan opsional..." class="input-clean w-full text-xs"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="closeEditModal" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Floating Jendela Melayang Viewer Modal (Image & PDF) -->
    @if ($showViewerModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl max-w-3xl w-full shadow-2xl overflow-hidden border border-slate-200">
                <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        <h3 class="font-bold text-sm text-slate-100 tracking-tight">{{ $viewerTitle }}</h3>
                    </div>
                    <button wire:click="closeViewer" class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">✕</button>
                </div>
                <div class="p-6 bg-slate-50 flex items-center justify-center min-h-[400px] max-h-[75vh] overflow-y-auto">
                    @if ($viewerType === 'image')
                        <img src="{{ $viewerUrl }}" alt="Struk Pembelian / Pembayaran" class="max-w-full max-h-[65vh] object-contain rounded-2xl shadow-lg border border-slate-200">
                    @elseif ($viewerType === 'pdf')
                        <iframe src="{{ $viewerUrl }}" class="w-full h-[65vh] rounded-2xl border border-slate-200"></iframe>
                    @endif
                </div>
                <div class="p-4 bg-white border-t border-slate-100 flex justify-end">
                    <button wire:click="closeViewer" class="btn-secondary">Tutup Jendela</button>
                </div>
            </div>
        </div>
    @endif
</div>
