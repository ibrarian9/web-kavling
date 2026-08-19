<!-- Modal Detail Activity Report Prospek -->
@if($showDetailModal && $selectedReport)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg w-full p-5 sm:p-6 shadow-2xl space-y-4 my-auto">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-2xl bg-teal-600 text-white font-black flex items-center justify-center text-xs shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2 2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Detail Aktivitas Prospek Harian</h3>
                        @php
                            $detailDateFormatted = '-';
                            if ($selectedReport->report_date) {
                                if ($selectedReport->report_date->isToday()) {
                                    $detailDateFormatted = 'Hari ini, ' . format_id_date($selectedReport->report_date);
                                } elseif ($selectedReport->report_date->isYesterday()) {
                                    $detailDateFormatted = 'Kemarin, ' . format_id_date($selectedReport->report_date);
                                } else {
                                    $detailDateFormatted = format_id_date($selectedReport->report_date);
                                }
                            }
                        @endphp
                        <p class="text-[11px] text-slate-400">Tanggal Laporan: <strong class="text-slate-700 font-mono">{{ $detailDateFormatted }}</strong></p>
                    </div>
                </div>
                <button wire:click="closeDetailModal" class="text-slate-400 hover:text-slate-600 font-bold text-lg p-1">&times;</button>
            </div>

            <div class="space-y-3 text-xs">
                <!-- Sales & Client Info -->
                <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-2xl space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Sales Marketing:</span>
                        </span>
                        <span class="font-bold text-teal-800 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded">{{ $selectedReport->user->name ?? '-' }} ({{ ucfirst($selectedReport->user->role ?? 'sales') }})</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider">Nama Klien / Prospek:</span>
                        <span class="font-extrabold text-slate-900 text-sm">{{ $selectedReport->client_name }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider">No WhatsApp / Telp:</span>
                        @php
                            $cleanP = preg_replace('/[^0-9]/', '', $selectedReport->client_phone);
                            if (str_starts_with($cleanP, '0')) $cleanP = '62' . substr($cleanP, 1);
                        @endphp
                        <a href="https://wa.me/{{ $cleanP }}" target="_blank" class="font-bold font-mono text-emerald-700 hover:text-emerald-900 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded inline-flex items-center gap-1">
                            <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-1.156 4.22 4.269-1.121z"/></svg>
                            <span>{{ $selectedReport->client_phone }}</span>
                        </a>
                    </div>
                </div>

                <!-- Project & Unit Info -->
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="p-2.5 bg-slate-50 border border-slate-200/80 rounded-xl">
                        <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block mb-0.5">Proyek Minat:</span>
                        <span class="font-bold text-slate-800 block">{{ $selectedReport->project->name ?? 'Belum Pilih Proyek' }}</span>
                    </div>
                    <div class="p-2.5 bg-slate-50 border border-slate-200/80 rounded-xl">
                        <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block mb-0.5">Unit Spesifik:</span>
                        <span class="font-bold text-blue-700 font-mono block">{{ $selectedReport->unit ? 'Unit ' . $selectedReport->unit->code : 'Belum Pilih Unit' }}</span>
                    </div>
                </div>

                <!-- Interaction & Stage Info -->
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="p-2.5 bg-slate-50 border border-slate-200/80 rounded-xl">
                        <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block mb-0.5">Sumber Lead:</span>
                        <span class="font-bold text-slate-800 block">{{ $selectedReport->lead_source_label }}</span>
                        <span class="text-[10px] text-slate-500 block font-medium">Interaksi: {{ $selectedReport->interaction_type_label }}</span>
                    </div>
                    <div class="p-2.5 bg-slate-50 border border-slate-200/80 rounded-xl">
                        <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block mb-0.5">Tahap Status:</span>
                        <span class="px-2 py-0.5 rounded text-[10px] uppercase font-extrabold inline-block mt-0.5 {{ $selectedReport->stage_badge_class }}">
                            {{ $selectedReport->lead_stage_label }}
                        </span>
                    </div>
                </div>

                <!-- Deal Amount -->
                <div class="p-3 bg-emerald-50/80 border border-emerald-200/80 rounded-2xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-emerald-800 uppercase font-bold tracking-wider block">Nominal Closing / Deal:</span>
                        <span class="text-slate-600 text-[11px] font-semibold">{{ str_replace('_', ' ', $selectedReport->payment_type) }}</span>
                    </div>
                    <span class="font-black text-emerald-800 font-mono text-sm sm:text-base">Rp {{ number_format($selectedReport->deal_amount, 0, ',', '.') }}</span>
                </div>

                <!-- Full Notes -->
                <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-2xl space-y-1">
                    <span class="text-slate-500 text-[10px] uppercase font-bold tracking-wider block">Catatan Pembicaraan Lengkap:</span>
                    <p class="text-slate-700 italic text-xs leading-relaxed">"{{ $selectedReport->notes ?: 'Tidak ada catatan khusus.' }}"</p>
                </div>

                @if($selectedReport->follow_up_date)
                    <div class="p-2.5 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 flex items-center justify-between">
                        <span class="font-bold text-[11px] flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Tanggal Janji Follow Up:</span>
                        </span>
                        <span class="font-mono font-bold">{{ format_id_date($selectedReport->follow_up_date) }}</span>
                    </div>
                @endif
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                <x-button variant="secondary" size="md" type="button" wire:click="closeDetailModal">Tutup</x-button>
            </div>
        </div>
    </div>
@endif
