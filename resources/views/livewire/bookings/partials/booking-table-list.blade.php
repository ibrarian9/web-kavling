<!-- Filters Toolbar -->
<div class="card-clean p-4 border border-slate-200/80 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-3">
    <div class="flex items-center gap-2.5 w-full md:w-auto flex-wrap">
        <select wire:model.live="projectFilter" class="select-clean text-xs font-bold w-full md:w-60">
            <option value="">Semua Perumahan / Proyek</option>
            @foreach ($projects as $proj)
                <option value="{{ $proj->id }}">{{ $proj->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="typeFilter" class="select-clean text-xs font-bold w-full md:w-48">
            <option value="">Semua Tingkat Booking</option>
            <option value="unit">Per Unit Spesifik</option>
            <option value="project">Per Proyek Perumahan</option>
        </select>

        <select wire:model.live="statusFilter" class="select-clean text-xs font-bold w-full md:w-48">
            <option value="">Semua Status</option>
            <option value="active">Aktif (Booked)</option>
            <option value="converted">DP ACC / Terjual</option>
            <option value="cancelled">Batal</option>
        </select>
    </div>
</div>

<!-- Table Card -->
<x-table :headers="['Tgl Pemesanan', 'Nama Pemesan', 'Perumahan & Unit', 'Tingkat Booking', ['label' => 'Nominal Tanda Jadi', 'class' => 'p-3.5 text-right'], ['label' => 'Status', 'class' => 'p-3.5 text-center'], ['label' => 'Foto Resi & Invoice', 'class' => 'p-3.5 text-center'], ['label' => 'Aksi', 'class' => 'p-3.5 text-center']]" loadingTarget="projectFilter, typeFilter, statusFilter, gotoPage, nextPage, previousPage">
    @forelse ($bookings as $b)
        <tr class="hover:bg-slate-50/60 transition-colors">
            <td class="p-3.5 font-mono font-medium text-slate-700 text-xs">
                {{ $b->booking_date ? $b->booking_date->format('d/m/Y') : '-' }}
                @if ($b->expiry_date)
                    <span class="block text-[10px] text-slate-400">s/d {{ $b->expiry_date->format('d/m/Y') }}</span>
                @endif
            </td>
            <td class="p-3.5 font-bold text-slate-900 text-xs">
                {{ $b->buyer_name }}
                <span class="block text-[10px] font-normal text-slate-400 font-mono">{{ $b->buyer_phone }}</span>
            </td>
            <td class="p-3.5 font-medium text-slate-800 text-xs">
                {{ $b->project->name }}
                @if ($b->unit)
                    <span class="block text-emerald-600 font-bold font-mono">Unit: {{ $b->unit->code }} ({{ ucfirst($b->unit->category) }})</span>
                @else
                    <span class="block text-slate-400 italic">Per Proyek Lahan</span>
                @endif
            </td>
            <td class="p-3.5">
                <span class="capitalize text-[10px] font-bold px-2.5 py-0.5 rounded-md border {{ $b->booking_type === 'unit' ? 'bg-teal-50 text-teal-800 border-teal-200' : 'bg-indigo-50 text-indigo-800 border-indigo-200' }}">
                    {{ $b->booking_type === 'unit' ? 'Per Unit' : 'Per Proyek' }}
                </span>
            </td>
            <td class="p-3.5 text-right font-mono font-extrabold text-teal-700 text-sm">
                Rp {{ number_format($b->booking_amount, 0, ',', '.') }}
            </td>
            <td class="p-3.5 text-center">
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
            <td class="p-3.5 text-center whitespace-nowrap">
                <div class="inline-flex items-center justify-center gap-1.5 flex-wrap">
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
            <td class="p-3.5 text-center whitespace-nowrap">
                <div class="inline-flex items-center justify-center gap-1.5">
                    @if ($b->status === 'active')
                        @if (auth()->user()->isFounder() || auth()->user()->isMarketing() || auth()->user()->isSupervisor() || auth()->user()->isFinance())
                            <a href="{{ route('proposals.index', ['booking_id' => $b->id, 'unit_id' => $b->unit_id]) }}" wire:navigate.hover>
                                <x-button variant="emerald" size="xs" title="Ajukan Proposal SPP">
                                    + SPP
                                </x-button>
                            </a>
                        @endif

                        @if (auth()->user()->isAdminOrFounder() || auth()->user()->isMarketing() || auth()->user()->isSupervisor() || auth()->user()->isFinance())
                            <x-button variant="amber" size="xs" wire:click="openEditModal({{ $b->id }})" title="Edit Data Booking">
                                Edit
                            </x-button>

                            <button type="button" @click="confirmModalAction({
                                title: 'Batalkan Booking',
                                message: 'Batalkan pemesanan {{ $b->buyer_name }} ini? Status unit akan kembali tersedia.',
                                confirmText: 'Batalkan Booking',
                                btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                onConfirm: () => $wire.cancelBooking({{ $b->id }})
                            })" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition" title="Batalkan Booking">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @endif
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="p-12 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="font-semibold text-slate-600">Belum Ada Riwayat Booking</p>
                <p class="text-xs text-slate-400 mt-1">Data pemesanan / tanda jadi akan ditampilkan di sini.</p>
            </td>
        </tr>
    @endforelse
</x-table>

<div>{{ $bookings->links() }}</div>
