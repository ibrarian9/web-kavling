<x-dashboard-feed-card
    subtitle="Ringkasan prospek terbaru & akses cepat WhatsApp klien"
    :actionHref="route('daily-activity-reports.index')"
    actionLabel="Lihat Semua"
    :spanClass="$spanClass ?? ''"
>
    <x-slot:title>Daily Activity Report Terbaru</x-slot:title>
    <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
        <tr>
            <th class="py-3 px-3 whitespace-nowrap">{{ $user->isMarketing() ? 'Tanggal' : 'Tanggal / Sales' }}</th>
            <th class="py-3 px-3">Klien & Kontak</th>
            <th class="py-3 px-3">Proyek / Unit</th>
            <th class="py-3 px-3 text-center">Tahap Prospek</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
        @forelse($reports as $rep)
            <tr class="hover:bg-slate-50/60 transition">
                <td data-label="{{ $user->isMarketing() ? 'Tanggal' : 'Tanggal / Sales' }}" class="py-3.5 px-3 whitespace-nowrap align-top">
                    <div class="flex flex-col items-end sm:items-start gap-1">
                        <span class="font-mono font-bold text-slate-800 text-[11px]">
                            {{ $rep->report_date ? format_id_date($rep->report_date) : '-' }}
                        </span>
                        @if(!$user->isMarketing())
                            <span class="text-[10px] text-teal-700 bg-teal-50 border border-teal-200 px-1.5 py-0.5 rounded font-bold inline-block">
                                {{ \Illuminate\Support\Str::limit($rep->user?->name ?? 'Sales', 18) }}
                            </span>
                        @endif
                    </div>
                </td>
                <td data-label="Klien & Kontak" class="py-3.5 px-3 align-top">
                    <div class="flex flex-col items-end sm:items-start gap-1.5 min-w-0">
                        <span class="font-extrabold text-slate-900 text-right sm:text-left break-words">{{ $rep->client_name }}</span>
                        @if($rep->whatsapp_url)
                            <a href="{{ $rep->whatsapp_url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               title="Chat WhatsApp {{ $rep->client_name }}"
                               class="text-sm sm:text-xs font-mono font-extrabold text-emerald-700 hover:text-emerald-900 bg-emerald-50 hover:bg-emerald-100 active:scale-[0.98] border border-emerald-200 px-3 py-2 sm:px-2 sm:py-1.5 min-h-[44px] sm:min-h-[36px] rounded-xl inline-flex items-center gap-1.5 transition shadow-2xs whitespace-nowrap">
                                <svg class="w-4 h-4 sm:w-3.5 sm:h-3.5 text-emerald-600 shrink-0" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-1.156 4.22 4.269-1.121z"/></svg>
                                <span>{{ $rep->client_phone }}</span>
                            </a>
                        @else
                            <span class="text-xs font-mono text-slate-600 font-semibold">{{ $rep->client_phone ?: '-' }}</span>
                        @endif
                    </div>
                </td>
                <td data-label="Proyek / Unit" class="py-3.5 px-3 align-top">
                    <div class="flex flex-col items-end sm:items-start gap-1 min-w-0">
                        @if($rep->project)
                            <span class="font-bold text-slate-800 text-right sm:text-left break-words">{{ $rep->project->name }}</span>
                        @else
                            <span class="text-slate-400 italic text-[11px]">- Belum Pilih Proyek -</span>
                        @endif
                        @if($rep->unit)
                            <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-1.5 py-0.5 rounded font-mono inline-block">
                                Unit {{ $rep->unit->code }}
                            </span>
                        @endif
                    </div>
                </td>
                <td data-label="Tahap Prospek" class="py-3.5 px-3 text-center whitespace-nowrap align-top">
                    <span class="px-2.5 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider inline-flex items-center {{ $rep->stage_badge_class }}">
                        {{ $rep->lead_stage_label }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="py-8 px-4 text-center text-slate-400 italic">Belum ada laporan aktivitas harian tercatat.</td>
            </tr>
        @endforelse
    </tbody>
</x-dashboard-feed-card>
