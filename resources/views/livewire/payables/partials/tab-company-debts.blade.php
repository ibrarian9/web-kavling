<!-- TAB 4: UTANG SUB-KON, VENDOR & LUAR PROYEK -->
@if($activeTab === 'company_debts')
    <div class="space-y-3">
        <div class="flex items-center justify-between flex-wrap gap-2.5">
            <div>
                <h3 class="font-extrabold text-slate-800 text-sm flex items-center gap-2">
                    <span>Catatan Utang Sub-kon, Vendor & Non-Proyek</span>
                    <span class="text-xs text-slate-500 font-normal">({{ $companyDebts->total() }} Catatan)</span>
                </h3>
                <p class="text-[11px] text-slate-400">Pencatatan kewajiban utang ke sub-kontraktor, vendor, talangan modal, maupun operasional di luar proyek.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <div class="text-xs font-mono font-bold text-rose-700 bg-rose-50 border border-rose-200 px-3 py-1.5 rounded-xl">
                    Sisa Utang Belum Lunas: Rp {{ number_format($totalUnpaidCompanyDebts, 0, ',', '.') }}
                </div>
                @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                    <x-button variant="rose" icon="plus" wire:click="openCreateDebtModal">Catat Utang Baru</x-button>
                @endif
            </div>
        </div>

        <!-- Unified Table with CSS Table-to-Card Transformation -->
        <x-table 
            class="min-w-[1050px]"
            :headers="[
                ['label' => 'Tanggal & Tempo', 'class' => 'p-3 whitespace-nowrap min-w-[125px]'],
                ['label' => 'Kategori & Kreditur', 'class' => 'p-3 min-w-[200px] md:min-w-[220px]'],
                ['label' => 'Keperluan & Proyek', 'class' => 'p-3 min-w-[260px] md:min-w-[320px]'],
                ['label' => 'Total Utang', 'class' => 'p-3 whitespace-nowrap min-w-[120px]'],
                ['label' => 'Sudah Dibayar', 'class' => 'p-3 whitespace-nowrap min-w-[110px]'],
                ['label' => 'Sisa Utang', 'class' => 'p-3 whitespace-nowrap min-w-[120px]'],
                ['label' => 'Status', 'class' => 'p-3 whitespace-nowrap min-w-[100px]'],
                ['label' => 'Aksi', 'class' => 'p-3 text-center whitespace-nowrap min-w-[130px]']
            ]" 
            loadingTarget="setTab, filter_project_id, filter_status, search, debt_page"
        >
            @forelse($companyDebts as $d)
                @php $sisaDebt = max(0, (float)$d->amount - (float)$d->paid_amount); @endphp
                <tr class="hover:bg-slate-50/80 transition">
                    <td data-label="Tanggal & Tempo" class="p-3 font-mono font-bold text-slate-700 whitespace-nowrap min-w-[125px]">
                        <div>{{ format_id_date($d->debt_date) }}</div>
                        @if($d->due_date)
                            <div class="text-[10px] text-amber-600 font-normal flex items-center gap-1 mt-0.5">
                                <span>Tempo:</span>
                                <span>{{ format_id_date($d->due_date) }}</span>
                            </div>
                        @endif
                    </td>
                    <td data-label="Kategori & Kreditur" class="p-3 min-w-[200px] md:min-w-[220px]">
                        <div class="flex items-center gap-1.5 mb-1">
                            @if($d->creditor_type === 'subkon')
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">Sub-kon</span>
                            @elseif($d->creditor_type === 'vendor')
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-indigo-100 text-indigo-800 border border-indigo-200">Vendor</span>
                            @elseif($d->creditor_type === 'operasional_luar')
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200">Operasional Luar</span>
                            @elseif($d->creditor_type === 'talangan_modal')
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-purple-100 text-purple-800 border border-purple-200">Talangan Modal</span>
                            @else
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200">Lainnya</span>
                            @endif
                        </div>
                        <div class="font-bold text-slate-900 text-xs">{{ $d->creditor_name }}</div>
                        @if($d->creditor_phone)
                            <span class="block text-[10px] text-slate-500 font-mono mt-0.5">WA/HP: {{ $d->creditor_phone }}</span>
                        @endif
                    </td>
                    <td data-label="Keperluan & Proyek" class="p-3 min-w-[260px] md:min-w-[320px]">
                        <div class="font-bold text-slate-800 text-xs leading-relaxed">{{ $d->title }}</div>
                        <div class="mt-1 flex items-center gap-1.5 flex-wrap">
                            @if($d->project)
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h5m-5 0v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span>{{ $d->project->name }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-slate-600 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">
                                    <span>Di Luar Proyek</span>
                                </span>
                            @endif

                            @if($d->attachment_path)
                                <button type="button" wire:click="openViewerModal('auto', '{{ $d->attachment_url }}', 'Dokumen SPK/Perjanjian: {{ $d->title }}')" class="inline-flex items-center gap-1 text-[10px] font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 px-1.5 py-0.5 rounded-md transition" title="Buka Dokumen/SPK">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span>SPK / Berkas</span>
                                </button>
                            @endif
                        </div>
                        @if($d->notes)
                            <span class="block text-[10px] text-slate-500 font-normal mt-1 italic">{{ $d->notes }}</span>
                        @endif
                    </td>
                    <td data-label="Total Utang" class="p-3 font-mono font-bold text-slate-800 whitespace-nowrap">Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
                    <td data-label="Sudah Dibayar" class="p-3 font-mono font-bold text-emerald-600 whitespace-nowrap">Rp {{ number_format($d->paid_amount, 0, ',', '.') }}</td>
                    <td data-label="Sisa Utang" class="p-3 font-mono font-bold text-rose-700 text-sm whitespace-nowrap">Rp {{ number_format($sisaDebt, 0, ',', '.') }}</td>
                    <td data-label="Status" class="p-3">
                        <x-status-badge :status="$d->status" />
                    </td>
                    <td data-card-action class="p-3 text-center">
                        <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                            @if($sisaDebt > 0)
                                @if(auth()->user()->isFounder() || auth()->user()->isFinance())
                                    <x-button variant="payment" size="xs" wire:click="openPayDebtModal({{ $d->id }})">
                                        <span>Bayar Cicilan</span>
                                    </x-button>
                                @endif
                            @else
                                <span class="text-[11px] text-emerald-600 font-semibold whitespace-nowrap">Lunas</span>
                            @endif

                            @if(auth()->user()->isSuperAdmin())
                                <x-button variant="delete" size="xs" @click="confirmModalAction({ title: 'Hapus Catatan Utang', message: 'Apakah Anda yakin ingin menghapus catatan utang ini secara permanen? Seluruh riwayat pembayaran terkait juga akan dihapus.', confirmText: 'Ya, Hapus Utang', btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5', onConfirm: () => $wire.deleteDebt({{ $d->id }}) })" title="Hapus Utang">
                                    <span>Hapus</span>
                                </x-button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <x-table-empty colspan="8" title="Tidak Ada Data Utang" message="Tidak ada catatan utang sub-kon, vendor, atau utang di luar proyek." />
            @endforelse
        </x-table>
        <div>{{ $companyDebts->links() }}</div>
    </div>
@endif
