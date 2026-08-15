<!-- TAB 5: RIWAYAT LUNAS GLOBAL (BARU) -->
@if($activeTab === 'settled_history')
    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                <span>Riwayat Lunas Hutang & Piutang Perusahaan (Global)</span>
                <span class="text-xs text-slate-500 font-normal">({{ $settledHistory->total() }} Transaksi Terbayar)</span>
            </h3>
            <div class="text-xs font-mono font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-xl">
                Total Riwayat Terbayar: {{ $settledHistory->total() }} Transaksi
            </div>
        </div>

        <x-table :headers="['Tgl Lunas / Setoran', 'Kategori Navigasi', 'Rincian / Penjual / Supplier / Pekerja', 'Proyek & Unit / Metode', 'Nominal Terbayar', 'Status']" loadingTarget="setTab, search, his_page">
            @forelse($settledHistory as $sh)
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="p-3 font-mono font-medium whitespace-nowrap">
                        {{ $sh->date ? (is_string($sh->date) ? \Carbon\Carbon::parse($sh->date)->format('d/m/Y') : $sh->date->format('d/m/Y')) : '-' }}
                    </td>
                    <td class="p-3">
                        <span class="px-2.5 py-1 rounded-full font-bold text-[10px] border whitespace-nowrap inline-flex items-center shrink-0 {{ $sh->badge_class }}">
                            {{ $sh->category_name }}
                        </span>
                    </td>
                    <td class="p-3 font-bold text-slate-900">
                        {{ $sh->title }}
                        @if($sh->notes)
                            <span class="block text-[10px] text-slate-500 font-normal italic">{{ $sh->notes }}</span>
                        @endif
                    </td>
                    <td class="p-3 text-slate-700 font-medium">{{ $sh->sub_info }}</td>
                    <td class="p-3 font-mono font-bold text-emerald-700 text-sm whitespace-nowrap">Rp {{ number_format($sh->amount, 0, ',', '.') }}</td>
                    <td class="p-3">
                        <x-status-badge status="lunas" :label="$sh->status_label" />
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-400">Belum ada riwayat lunas hutang & piutang.</td>
                </tr>
            @endforelse
        </x-table>
        <div>{{ $settledHistory->links() }}</div>
    </div>
@endif
