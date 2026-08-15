<div class="space-y-6">
    <!-- Header Page -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <span>Invoice Manual & Arus Keuangan</span>
                <span class="px-2.5 py-0.5 rounded-full bg-teal-100 text-teal-800 text-[11px] font-extrabold border border-teal-200">Billing Sync</span>
            </h1>
            <p class="text-slate-500 text-xs mt-0.5">Kelola pembuatan invoice manual khusus yang langsung terhubung & tersinkronisasi otomatis ke Arus Kas</p>
        </div>

        <x-button variant="emerald" size="sm" wire:click="openCreateModal" icon="plus">
            Buat Invoice Manual
        </x-button>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="kpi-card-emerald bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Invoice Pemasukan</span>
                <div class="p-2.5 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-emerald-700 mt-2">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</p>
            <span class="text-[11px] text-slate-400 mt-1 block">Tercatat di Kas Masuk Arus Keuangan</span>
        </div>

        <div class="kpi-card-rose bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Invoice Pengeluaran</span>
                <div class="p-2.5 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-rose-700 mt-2">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</p>
            <span class="text-[11px] text-slate-400 mt-1 block">Tercatat di Kas Keluar Arus Keuangan</span>
        </div>

        <div class="kpi-card-amber bg-white border border-slate-200/80 rounded-3xl p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Tagihan Pending</span>
                <div class="p-2.5 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold font-mono text-amber-700 mt-2">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
            <span class="text-[11px] text-slate-400 mt-1 block">Invoice terbit (Menunggu Lunas)</span>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="card-clean p-4 border border-slate-200/80 rounded-3xl flex flex-col sm:flex-row items-center justify-between gap-3">
        <x-search-input placeholder="No. Inv, Penerima, Keterangan..." containerClass="w-full sm:w-72" />

        <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end flex-wrap">
            <select wire:model.live="statusFilter" class="select-clean text-xs font-bold">
                <option value="">Semua Status</option>
                <option value="lunas">Lunas</option>
                <option value="pending">Pending</option>
                <option value="draf">Draf</option>
            </select>

            <select wire:model.live="typeFilter" class="select-clean text-xs font-bold">
                <option value="">Semua Tipe</option>
                <option value="masuk">Kas Masuk (Pemasukan)</option>
                <option value="keluar">Kas Keluar (Pengeluaran)</option>
            </select>

            <select wire:model.live="projectFilter" class="select-clean text-xs font-bold">
                <option value="">Semua Proyek / Global</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Invoice Table -->
    <x-table :headers="['No', 'No. Invoice & Tanggal', 'Penerima / Klien', 'Proyek & Unit', 'Tipe & Kategori', ['label' => 'Nominal (Rp)', 'class' => 'p-3.5 text-right'], ['label' => 'Status', 'class' => 'p-3.5 text-center'], ['label' => 'Arus Kas', 'class' => 'p-3.5 text-center'], ['label' => 'Aksi', 'class' => 'p-3.5 text-center']]" loadingTarget="search, statusFilter, typeFilter, projectFilter, page">
        @forelse($invoices as $index => $inv)
            <tr class="hover:bg-slate-50/80 transition">
                <td class="p-3.5 font-mono text-slate-500 font-semibold">{{ $invoices->firstItem() + $index }}</td>
                <td class="p-3.5 font-mono">
                    <strong class="text-slate-900 block text-xs">{{ $inv->invoice_number }}</strong>
                    <span class="text-slate-500 text-[11px]">{{ $inv->invoice_date ? $inv->invoice_date->format('d/m/Y') : '-' }}</span>
                </td>
                <td class="p-3.5">
                    <span class="font-bold text-slate-800 block text-xs">{{ $inv->recipient_name }}</span>
                    @if($inv->recipient_phone)
                        <span class="text-slate-400 text-[10px]">Telp: {{ $inv->recipient_phone }}</span>
                    @endif
                </td>
                <td class="p-3.5 text-slate-700 text-xs">
                    <span class="font-semibold block">{{ $inv->project->name ?? 'Global' }}</span>
                    @if($inv->unit)
                        <span class="text-teal-700 font-mono text-[11px]">Unit {{ $inv->unit->code }}</span>
                    @endif
                </td>
                <td class="p-3.5">
                    @if($inv->type === 'masuk')
                        <x-status-badge status="disetujui" label="KAS MASUK" />
                    @else
                        <x-status-badge status="ditolak" label="KAS KELUAR" />
                    @endif
                    <span class="text-slate-500 block text-[10px] capitalize mt-0.5">{{ str_replace('_', ' ', $inv->category) }}</span>
                </td>
                <td class="p-3.5 text-right font-mono font-extrabold text-xs {{ $inv->type === 'masuk' ? 'text-emerald-700' : 'text-rose-700' }}">
                    {{ $inv->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($inv->amount, 0, ',', '.') }}
                </td>
                <td class="p-3.5 text-center">
                    @if($inv->status === 'lunas')
                        <x-status-badge status="lunas" label="LUNAS" />
                    @elseif($inv->status === 'pending')
                        <x-status-badge status="menunggu" label="PENDING" />
                    @else
                        <x-status-badge status="draf" label="DRAF" />
                    @endif
                </td>
                <td class="p-3.5 text-center">
                    @if($inv->record_cashflow && $inv->status === 'lunas')
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full" title="Tersinkronisasi ke Mutasi Arus Kas">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Tersinkron
                        </span>
                    @else
                        <span class="text-slate-400 text-[10px] italic">-</span>
                    @endif
                </td>
                <td class="p-3.5 text-center whitespace-nowrap">
                    <div class="inline-flex items-center justify-center gap-1.5">
                        <x-button variant="outline" size="xs" wire:click="openPdfPreview('{{ $inv->uuid }}')" title="Pratinjau Invoice PDF">
                            PDF
                        </x-button>

                        <x-button variant="amber" size="xs" wire:click="editInvoice({{ $inv->id }})" title="Edit Invoice">
                            Edit
                        </x-button>

                        <button type="button" @click="confirmModalAction({
                             title: 'Hapus Invoice Manual',
                             message: 'Yakin ingin menghapus invoice manual ini beserta mutasi arus kas terkait?',
                             confirmText: 'Hapus Invoice',
                             btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                             onConfirm: () => $wire.deleteInvoice({{ $inv->id }})
                         })" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Hapus Invoice">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                         </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="p-12 text-center text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="font-semibold text-slate-600">Belum Ada Invoice Manual Dicatat</p>
                    <p class="text-xs text-slate-400 mt-1">Klik tombol "+ Buat Invoice Manual" untuk membuat invoice khusus yang langsung terhubung ke Arus Kas.</p>
                </td>
            </tr>
        @endforelse
    </x-table>

    <div>{{ $invoices->links() }}</div>

    <!-- Modal Form Create & Edit Manual Invoice -->
    @include('livewire.manual-invoices.partials.modal-form')

    <!-- Modal Floating Viewer (Pratinjau PDF di dalam aplikasi) -->
    @include('livewire.manual-invoices.partials.modal-pdf-viewer')
</div>
