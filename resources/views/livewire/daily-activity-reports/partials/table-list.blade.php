<!-- Daily Activity Table Section -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
    <div class="p-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span>Daftar Laporan Aktivitas Harian ({{ $reports->total() }})</span>
        </h3>
    </div>

    <!-- Unified Table of Daily Activity Reports with CSS Table-to-Card Transformation -->
    <x-table :headers="['Tanggal / Sales', 'Klien & Kontak', 'Proyek & Unit', 'Sumber & Interaksi', ['label' => 'Tahap Prospek', 'class' => 'p-3.5 text-center'], ['label' => 'Nominal Closing', 'class' => 'p-3.5 text-right'], 'Catatan / Next Follow Up', ['label' => 'Aksi', 'class' => 'p-3.5 text-center']]">
        @forelse($reports as $rep)
            @php
                $reportDateFormatted = '-';
                if ($rep->report_date) {
                    if ($rep->report_date->isToday()) {
                        $reportDateFormatted = 'Hari ini, ' . format_id_date($rep->report_date);
                    } elseif ($rep->report_date->isYesterday()) {
                        $reportDateFormatted = 'Kemarin, ' . format_id_date($rep->report_date);
                    } else {
                        $reportDateFormatted = format_id_date($rep->report_date);
                    }
                }
            @endphp
            <tr class="hover:bg-slate-50/80 transition-colors">
                <td data-label="Tanggal / Sales" class="p-3.5 font-medium whitespace-nowrap">
                    <span class="font-bold text-slate-900 block font-mono text-[11px]">{{ $reportDateFormatted }}</span>
                    <span class="text-[10px] text-teal-700 bg-teal-50 border border-teal-200 px-1.5 py-0.5 rounded font-extrabold inline-flex items-center gap-1 mt-0.5" title="{{ $rep->user->name ?? 'Sales' }}">
                        <svg class="w-3 h-3 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>{{ Str::limit($rep->user->name ?? 'Sales', 14) }}</span>
                    </span>
                </td>

                <td data-label="Klien & Kontak" class="p-3.5">
                    <span class="font-extrabold text-slate-900 block">{{ $rep->client_name }}</span>
                    <span class="text-[11px] font-mono text-slate-600 inline-flex items-center gap-1 mt-0.5 font-bold">
                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-1.156 4.22 4.269-1.121z"/></svg>
                        <span>{{ $rep->client_phone }}</span>
                    </span>
                </td>

                <td data-label="Proyek & Unit" class="p-3.5">
                    @if($rep->project)
                        <span class="font-bold text-slate-800 block text-xs">{{ $rep->project->name }}</span>
                    @else
                        <span class="text-slate-400 italic text-[11px] block">- Belum Pilih Proyek -</span>
                    @endif
                    @if($rep->unit)
                        <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded font-mono inline-block mt-0.5">
                            Unit {{ $rep->unit->code }}
                        </span>
                    @endif
                </td>

                <td data-label="Sumber & Interaksi" class="p-3.5">
                    <span class="font-bold text-slate-800 block">{{ $rep->lead_source_label }}</span>
                    <span class="text-[10px] text-slate-500 block font-medium">{{ $rep->interaction_type_label }}</span>
                </td>

                <td data-label="Tahap Prospek" class="p-3.5 text-center">
                    <span class="px-2 py-0.5 rounded text-[10px] uppercase font-extrabold tracking-wider inline-block {{ $rep->stage_badge_class }}">
                        {{ $rep->lead_stage_label }}
                    </span>
                </td>

                <td data-label="Nominal Closing" class="p-3.5 text-right font-mono">
                    @if((float)$rep->deal_amount > 0)
                        <span class="font-extrabold text-emerald-700 block text-xs">Rp {{ number_format($rep->deal_amount, 0, ',', '.') }}</span>
                        <span class="text-[10px] text-slate-400 uppercase font-bold">{{ str_replace('_', ' ', $rep->payment_type) }}</span>
                    @else
                        <span class="text-slate-400 text-[11px] italic">-</span>
                    @endif
                </td>

                <td data-label="Catatan & Follow Up" class="p-3.5 max-w-xs">
                    <p class="text-slate-600 italic text-[11px]" title="{{ $rep->notes }}">"{{ Str::limit($rep->notes ?: '-', 22) }}"</p>
                    @if($rep->follow_up_date)
                        <span class="text-[10px] text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded font-semibold inline-flex items-center gap-1 mt-0.5">
                            <svg class="w-3 h-3 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Follow Up: {{ format_id_date($rep->follow_up_date) }}</span>
                        </span>
                    @endif
                </td>

                <td data-card-action class="p-3.5 text-center whitespace-nowrap">
                    <div class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">
                        <x-button variant="detail" size="xs" wire:click="showReportDetail({{ $rep->id }})" title="Lihat Rincian Laporan">
                            <span>Detail</span>
                        </x-button>

                        @if(auth()->user()->isFounder() || auth()->user()->isSupervisor() || $rep->user_id === auth()->id())
                            <x-action-dropdown title="Menu Opsi Laporan" size="xs">
                                <div class="py-1">
                                    <x-dropdown-item icon="edit" wire:click="editReport({{ $rep->id }})">
                                        Edit Laporan
                                    </x-dropdown-item>
                                </div>
                                <div class="py-1">
                                    <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                        title: 'Hapus Laporan Aktivitas Harian',
                                        message: 'Yakin ingin menghapus laporan aktivitas prospek {{ $rep->client_name }}?',
                                        confirmText: 'Hapus Laporan',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteReport({{ $rep->id }})
                                    })">
                                        Hapus Laporan
                                    </x-dropdown-item>
                                </div>
                            </x-action-dropdown>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <x-table-empty colspan="8" title="Belum Ada Laporan Aktivitas Harian" message="Belum ada laporan aktivitas harian yang sesuai filter. Klik '+ Catat Laporan Harian' untuk meng-input prospek baru." />
        @endforelse
    </x-table>

    @if($reports->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $reports->links() }}
        </div>
    @endif
</div>
