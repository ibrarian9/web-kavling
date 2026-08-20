<!-- Filters Toolbar -->
<div class="card-clean p-4 border border-slate-200/80 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-3">
    <div class="flex items-center gap-2.5 w-full md:w-auto flex-wrap flex-1">
        <!-- Filter Periode Waktu Tanggal -->
        <x-date-period-filter periodModel="datePeriod" startModel="startDate" endModel="endDate" :periodValue="$datePeriod" />

        <select wire:model.live="projectFilter" class="select-clean text-xs font-bold w-full sm:w-auto min-w-[180px]">
            <option value="">Semua Perumahan / Proyek</option>
            @foreach ($projects as $proj)
                <option value="{{ $proj->id }}">{{ $proj->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="typeFilter" class="select-clean text-xs font-bold w-full sm:w-auto min-w-[150px]">
            <option value="">Semua Tingkat Booking</option>
            <option value="unit">Per Unit Spesifik</option>
            <option value="project">Per Proyek Perumahan</option>
        </select>

        <select wire:model.live="statusFilter" class="select-clean text-xs font-bold w-full sm:w-auto min-w-[140px]">
            <option value="">Semua Status</option>
            <option value="active">Aktif (Booked)</option>
            <option value="converted">DP ACC / Terjual</option>
            <option value="cancelled">Batal</option>
        </select>

        <x-search-input placeholder="Cari nama pembeli, telp, unit..." containerClass="w-full sm:w-60" />
    </div>

    @if($search || $projectFilter || $typeFilter || $statusFilter || $datePeriod !== 'all' || $startDate || $endDate)
        <x-reset-filter-button 
            wire:click="$set('search', ''); $set('projectFilter', null); $set('typeFilter', ''); $set('statusFilter', ''); $set('datePeriod', 'all'); $set('startDate', ''); $set('endDate', '');" 
        />
    @endif
</div>

    <!-- Unified Table of Bookings with CSS Table-to-Card Transformation -->
    <x-table :headers="['Tgl Pemesanan', 'Nama Pemesan', 'Perumahan & Unit', 'Tingkat Booking', ['label' => 'Nominal Tanda Jadi', 'class' => 'p-3.5 text-right'], ['label' => 'Status', 'class' => 'p-3.5 text-center'], ['label' => 'Foto Resi & Invoice', 'class' => 'p-3.5 text-center'], ['label' => 'Aksi', 'class' => 'p-3.5 text-center']]" loadingTarget="search, projectFilter, typeFilter, statusFilter, datePeriod, startDate, endDate, gotoPage, nextPage, previousPage">
        @forelse ($bookings as $b)
            <tr class="hover:bg-slate-50/60 transition-colors">
                <td data-label="Tgl Pemesanan" class="p-3.5 font-mono font-medium text-slate-700 text-xs">
                    <span class="font-bold text-slate-800">{{ format_id_date($b->booking_date) }}</span>
                    @if ($b->expiry_date)
                        <span class="block text-[10px] text-slate-400">s/d {{ format_id_date($b->expiry_date) }}</span>
                    @endif
                </td>
                <td data-label="Nama Pemesan" class="p-3.5 font-bold text-slate-900 text-xs">
                    {{ $b->buyer_name }}
                    <span class="block text-[10px] font-normal text-slate-400 font-mono">{{ $b->buyer_phone }}</span>
                </td>
                <td data-label="Perumahan & Unit" class="p-3.5 font-medium text-slate-800 text-xs">
                    {{ $b->project->name }}
                    @if ($b->unit)
                        <span class="block text-emerald-600 font-bold font-mono">Unit: {{ $b->unit->code }} ({{ ucfirst($b->unit->category) }})</span>
                    @else
                        <span class="block text-slate-400 italic">Per Proyek Lahan</span>
                    @endif
                </td>
                <td data-label="Tingkat Booking" class="p-3.5">
                    <span class="capitalize text-[10px] font-bold px-2.5 py-0.5 rounded-md border {{ $b->booking_type === 'unit' ? 'bg-teal-50 text-teal-800 border-teal-200' : 'bg-indigo-50 text-indigo-800 border-indigo-200' }}">
                        {{ $b->booking_type === 'unit' ? 'Per Unit' : 'Per Proyek' }}
                    </span>
                </td>
                <td data-label="Nominal Tanda Jadi" class="p-3.5 text-right font-mono font-extrabold text-teal-700 text-sm">
                    Rp {{ number_format($b->booking_amount, 0, ',', '.') }}
                </td>
                <td data-label="Status" class="p-3.5 text-center">
                    @if ($b->status === 'active')
                        <x-status-badge status="pending" label="ACTIVE / MENUNGGU ACC" />
                    @elseif ($b->status === 'converted')
                        <x-status-badge status="disetujui" label="DP ACC" />
                    @elseif ($b->status === 'refunded')
                        <x-status-badge status="ditolak" label="REFUND / BATAL" />
                    @else
                        <x-status-badge status="ditolak" label="BATAL" />
                    @endif
                </td>
                <td data-label="Resi & Invoice" class="p-3.5 text-center whitespace-nowrap">
                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap flex-nowrap">
                        @if ($b->receipt_photo_path)
                            <x-button variant="amber" size="xs" wire:click="openViewerModal('image', '{{ $b->receipt_photo_url }}', 'Foto Struk Resi Booking - {{ $b->buyer_name }}')" title="Buka Foto Struk Bukti Transfer / DP">
                                Struk
                            </x-button>
                        @endif
                        <x-button variant="outline" size="xs" wire:click="openViewerModal('pdf', '{{ route('bookings.receipt', $b->id) }}', 'Pratinjau Invoice Booking - {{ $b->buyer_name }}')" title="Lihat PDF Invoice Booking">
                            PDF
                        </x-button>
                    </div>
                </td>
                <td data-card-action class="p-3.5 text-center whitespace-nowrap">
                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                        @if ($b->status === 'active')
                            @if (auth()->user()->isFounder() || auth()->user()->isMarketing() || auth()->user()->isSupervisor() || auth()->user()->isFinance())
                                <x-button variant="emerald" size="xs" href="{{ route('proposals.index', ['booking_id' => $b->id, 'unit_id' => $b->unit_id]) }}" wire:navigate.hover title="Ajukan Proposal SPP" icon="plus">
                                    <span>Proposal SPP</span>
                                </x-button>
                            @endif

                            @if (auth()->user()->isAdminOrFounder() || auth()->user()->isMarketing() || auth()->user()->isSupervisor() || auth()->user()->isFinance())
                                <x-action-dropdown title="Menu Opsi Booking" size="xs">
                                    <div class="py-1">
                                        <x-dropdown-item icon="edit" wire:click="editBooking({{ $b->id }})">
                                            Edit Data
                                        </x-dropdown-item>
                                    </div>
                                    @if (auth()->user()->isSuperAdmin())
                                        <div class="py-1">
                                            <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                                title: 'Batalkan Booking',
                                                message: 'Batalkan pemesanan {{ $b->buyer_name }} ini? Status unit akan kembali tersedia.',
                                                confirmText: 'Batalkan Booking',
                                                btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                                onConfirm: () => $wire.deleteBooking({{ $b->id }})
                                            })">
                                                Batalkan Booking
                                            </x-dropdown-item>
                                        </div>
                                    @endif
                                </x-action-dropdown>
                            @endif
                        @else
                            <span class="text-slate-300 text-xs italic">-</span>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <x-table-empty colspan="8" title="Belum Ada Riwayat Booking" message="Data pemesanan / tanda jadi akan ditampilkan di sini." />
        @endforelse
    </x-table>

<div>{{ $bookings->links() }}</div>
