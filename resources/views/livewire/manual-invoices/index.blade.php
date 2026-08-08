<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                <div class="p-2.5 rounded-2xl bg-teal-500/10 text-teal-600 border border-teal-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span>Invoice Manual & Arus Keuangan</span>
            </h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">Kelola pembuatan invoice manual khusus yang langsung terhubung & tersinkronisasi otomatis ke Arus Kas</p>
        </div>

        <button wire:click="openCreateModal" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs sm:text-sm shadow-md shadow-teal-600/20 transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>+ Buat Invoice Manual</span>
        </button>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Invoice Pemasukan</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-emerald-700 mt-2">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
            <span class="text-[11px] text-slate-400 mt-1 block">Tercatat di Kas Masuk Arus Keuangan</span>
        </div>

        <div class="kpi-card-rose">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Invoice Pengeluaran</span>
                <div class="p-2.5 rounded-xl bg-rose-50 text-rose-600 border border-rose-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-rose-700 mt-2">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
            <span class="text-[11px] text-slate-400 mt-1 block">Tercatat di Kas Keluar Arus Keuangan</span>
        </div>

        <div class="kpi-card-amber">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Tagihan Pending</span>
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-amber-700 mt-2">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
            <span class="text-[11px] text-slate-400 mt-1 block">Invoice terbit (Menunggu Lunas)</span>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="card-clean p-4 space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div class="sm:col-span-1">
                <label class="block font-semibold text-slate-600 mb-1 text-[11px] uppercase">Cari Invoice</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="No. Inv, Penerima, Keterangan..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-teal-500 outline-none">
            </div>

            <div>
                <label class="block font-semibold text-slate-600 mb-1 text-[11px] uppercase">Filter Status</label>
                <select wire:model.live="statusFilter" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-teal-500 outline-none">
                    <option value="">Semua Status</option>
                    <option value="lunas">Lunas</option>
                    <option value="pending">Pending</option>
                    <option value="draf">Draf</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-600 mb-1 text-[11px] uppercase">Filter Tipe Mutasi</label>
                <select wire:model.live="typeFilter" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-teal-500 outline-none">
                    <option value="">Semua Tipe</option>
                    <option value="masuk">Kas Masuk (Pemasukan)</option>
                    <option value="keluar">Kas Keluar (Pengeluaran)</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-600 mb-1 text-[11px] uppercase">Filter Proyek</label>
                <select wire:model.live="projectFilter" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 focus:ring-2 focus:ring-teal-500 outline-none">
                    <option value="">Semua Proyek / Global</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Invoice Table -->
    <div class="card-clean overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100/90 text-slate-700 uppercase text-[10px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3.5">No</th>
                        <th class="px-4 py-3.5">No. Invoice & Tanggal</th>
                        <th class="px-4 py-3.5">Penerima / Klien</th>
                        <th class="px-4 py-3.5">Proyek & Unit</th>
                        <th class="px-4 py-3.5">Tipe & Kategori</th>
                        <th class="px-4 py-3.5 text-right">Nominal (Rp)</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Arus Kas</th>
                        <th class="px-4 py-3.5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($invoices as $index => $inv)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-4 py-3.5 font-mono text-slate-500 font-semibold">{{ $invoices->firstItem() + $index }}</td>
                            <td class="px-4 py-3.5 font-mono">
                                <strong class="text-slate-900 block">{{ $inv->invoice_number }}</strong>
                                <span class="text-slate-500 text-[11px]">{{ $inv->invoice_date ? $inv->invoice_date->format('d/m/Y') : '-' }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-slate-800 block">{{ $inv->recipient_name }}</span>
                                @if($inv->recipient_phone)
                                    <span class="text-slate-400 text-[10px]">Telp: {{ $inv->recipient_phone }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-slate-700">
                                <span class="font-semibold block">{{ $inv->project->name ?? 'Global' }}</span>
                                @if($inv->unit)
                                    <span class="text-teal-700 font-mono text-[11px]">Unit {{ $inv->unit->code }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                @if($inv->type === 'masuk')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">KAS MASUK</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-200">KAS KELUAR</span>
                                @endif
                                <span class="text-slate-500 block text-[10px] capitalize mt-0.5">{{ str_replace('_', ' ', $inv->category) }}</span>
                            </td>
                            <td class="px-4 py-3.5 text-right font-mono font-extrabold text-sm {{ $inv->type === 'masuk' ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $inv->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($inv->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @if($inv->status === 'lunas')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500 text-white shadow-xs">LUNAS</span>
                                @elseif($inv->status === 'pending')
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500 text-white shadow-xs">PENDING</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-400 text-white shadow-xs">DRAF</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                @if($inv->record_cashflow && $inv->status === 'lunas')
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full" title="Tersinkronisasi ke Mutasi Arus Kas">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Tersinkron
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[10px] italic">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button wire:click="openPdfPreview('{{ $inv->uuid }}')" class="btn-action-pdf" title="Pratinjau Invoice PDF">
                                        <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        <span>PDF</span>
                                    </button>

                                    <button wire:click="editInvoice({{ $inv->id }})" class="btn-action-edit" title="Edit Invoice">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </button>

                                    <button type="button" @click="confirmModalAction({
                                         title: 'Hapus Invoice Manual',
                                         message: 'Yakin ingin menghapus invoice manual ini beserta mutasi arus kas terkait?',
                                         confirmText: 'Hapus Invoice',
                                         btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                         onConfirm: () => $wire.deleteInvoice({{ $inv->id }})
                                     })" class="btn-action-delete" title="Hapus Invoice">
                                         <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                         <span>Hapus</span>
                                     </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="font-semibold text-slate-600">Belum Ada Invoice Manual Dicatat</p>
                                <p class="text-xs text-slate-400 mt-1">Klik tombol "+ Buat Invoice Manual" untuk membuat invoice khusus yang langsung terhubung ke Arus Kas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $invoices->links() }}
        </div>
    </div>

    <!-- Modal Form Create & Edit Manual Invoice -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
            <div class="bg-white border border-slate-200 rounded-2xl sm:rounded-3xl max-w-lg sm:max-w-xl w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] flex flex-col">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                    <h3 class="font-extrabold text-slate-900 text-base sm:text-lg tracking-tight flex items-center gap-2">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>{{ $editingInvoiceId ? 'Edit Invoice Manual' : 'Buat Invoice Manual Baru' }}</span>
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-slate-600 text-sm">✕</button>
                </div>

                <form wire:submit.prevent="saveInvoice" class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                    @if(!$editingInvoiceId)
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Nomor Invoice (Opsional / Otomatis System)</label>
                            <input type="text" wire:model="invoice_number" placeholder="Contoh: INV-MANUAL-2026-001 (Kosongkan utk auto)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-mono focus:ring-2 focus:ring-teal-500 outline-none">
                        </div>
                    @endif

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Nama Penerima / Klien / Pembeli *</label>
                        <input type="text" wire:model="recipient_name" placeholder="Contoh: Bapak Ahmad Fauzi / PT Karya Mandiri" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-bold focus:ring-2 focus:ring-teal-500 outline-none">
                        @error('recipient_name') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">No. HP / Telepon (Opsional)</label>
                            <input type="text" wire:model="recipient_phone" placeholder="08123456789" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 focus:ring-2 focus:ring-teal-500 outline-none">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Tipe Mutasi Keuangan *</label>
                            <select wire:model.live="type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-bold focus:ring-2 focus:ring-teal-500 outline-none">
                                <option value="masuk">Kas Masuk (Pemasukan)</option>
                                <option value="keluar">Kas Keluar (Pengeluaran)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Kategori Transaksi *</label>
                            <select wire:model="category" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 focus:ring-2 focus:ring-teal-500 outline-none">
                                @if($type === 'masuk')
                                    <option value="penjualan_unit">Penjualan Unit / Addendum</option>
                                    <option value="biaya_legalitas">Biaya Legalitas / Notaris</option>
                                    <option value="pendapatan_sewa">Pendapatan Sewa / Jasa</option>
                                    <option value="lain_lain">Lain-lain (Kas Masuk)</option>
                                @else
                                    <option value="operasional">Biaya Operasional Khusus</option>
                                    <option value="pembelian_lahan">Pembayaran Vendor / Penjual</option>
                                    <option value="gaji">Pengeluaran Jasa / Gaji</option>
                                    <option value="lain_lain">Lain-lain (Kas Keluar)</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Nominal Invoice (Rp) *</label>
                            <x-currency-input model="amount" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-mono font-bold focus:ring-2 focus:ring-teal-500 outline-none" placeholder="10.000.000" />
                            @error('amount') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Cakupan Proyek (Opsional)</label>
                            <select wire:model.live="project_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 focus:ring-2 focus:ring-teal-500 outline-none">
                                <option value="">Konsolidasi Global (Tanpa Proyek)</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Unit Terkait (Opsional)</label>
                            <select wire:model="unit_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 focus:ring-2 focus:ring-teal-500 outline-none" {{ !$project_id ? 'disabled' : '' }}>
                                <option value="">Tanpa Unit Spesifik</option>
                                @foreach($availableUnits as $u)
                                    <option value="{{ $u->id }}">Unit {{ $u->code }} ({{ $u->type }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Tanggal Invoice *</label>
                            <input type="date" wire:model="invoice_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-mono focus:ring-2 focus:ring-teal-500 outline-none">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Jatuh Tempo (Opsional)</label>
                            <input type="date" wire:model="due_date" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-mono focus:ring-2 focus:ring-teal-500 outline-none">
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Status Payment *</label>
                            <select wire:model="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 font-bold focus:ring-2 focus:ring-teal-500 outline-none">
                                <option value="lunas">Lunas (Masuk Kas)</option>
                                <option value="pending">Pending (Menunggu)</option>
                                <option value="draf">Draf</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Metode Pembayaran</label>
                        <select wire:model="payment_method" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 focus:ring-2 focus:ring-teal-500 outline-none">
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Tunai / Cash">Tunai / Cash</option>
                            <option value="Cek / Giro">Cek / Giro</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider text-[11px]">Keterangan / Rincian Tagihan (Opsional)</label>
                        <textarea wire:model="description" rows="2" placeholder="Pembayaran biaya sertifikat balik nama kavling A1..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-900 focus:ring-2 focus:ring-teal-500 outline-none"></textarea>
                    </div>

                    <div class="bg-teal-50 border border-teal-200 p-3 rounded-xl flex items-center justify-between">
                        <div>
                            <span class="font-bold text-teal-900 text-xs block">Sinkronisasi Otomatis Arus Kas</span>
                            <span class="text-[11px] text-teal-700">Masuk secara otomatis ke laporan mutasi kas jika status Lunas</span>
                        </div>
                        <input type="checkbox" wire:model="record_cashflow" class="w-4 h-4 text-teal-600 rounded focus:ring-teal-500">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-xl font-semibold hover:bg-teal-500 shadow-md transition">Simpan Invoice & Arus Kas</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Floating Viewer (Pratinjau PDF di dalam aplikasi) -->
    @if($showViewerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-md">
            <div class="bg-white rounded-3xl max-w-4xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden border border-slate-200">
                <!-- Modal Header -->
                <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <span class="p-2 rounded-xl bg-teal-500/20 text-teal-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-bold text-base tracking-tight text-white">{{ $viewerTitle }}</h3>
                            <p class="text-[11px] text-slate-400">Pratinjau langsung di dalam aplikasi</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ $viewerUrl }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold flex items-center gap-1 transition">
                            <span>Buka Tab Baru ↗</span>
                        </a>
                        <button wire:click="closeViewerModal" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition">
                            ✕
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="flex-1 bg-slate-950 p-4 overflow-auto flex items-center justify-center min-h-[60vh]">
                    <iframe src="{{ $viewerUrl }}" class="w-full h-[75vh] rounded-2xl bg-white border-0 shadow-lg"></iframe>
                </div>
            </div>
        </div>
    @endif
</div>
