<!-- Filters Toolbar -->
<div class="card-clean p-4 flex flex-col md:flex-row gap-3">
    <div class="w-full md:w-56">
        <select wire:model.live="projectIdFilter" class="input-clean w-full">
            <option value="">Semua Perumahan / Proyek</option>
            @foreach ($projects as $proj)
                <option value="{{ $proj->id }}">{{ $proj->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="w-full md:w-48">
        <select wire:model.live="statusFilter" class="input-clean w-full">
            <option value="all">Semua Status Approval</option>
            <option value="menunggu">Menunggu ACC</option>
            <option value="disetujui">Disetujui (ACC)</option>
            <option value="ditolak">Ditolak</option>
        </select>
    </div>

    <div class="flex-1">
        <x-search-input placeholder="Cari kode unit, nama proyek, atau nama marketing pengaju..." containerClass="w-full" />
    </div>
</div>

<!-- Table Card -->
<div class="card-clean overflow-hidden">
    <div class="overflow-x-auto relative min-h-[260px]">
        <!-- Reusable System Centered Table Loading Component -->
        <x-table-loading target="projectIdFilter, statusFilter, search, gotoPage, nextPage, previousPage" text="Memuat & Menyaring Data Pengajuan Harga..." subtext="Mohon tunggu sebentar, sistem sedang memproses data proposal & approval." />

        <table class="w-full text-left text-xs text-slate-600" wire:loading.class="opacity-30 pointer-events-none transition-opacity duration-300" wire:target="projectIdFilter, statusFilter, search, gotoPage, nextPage, previousPage">
            <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3.5">Unit & Proyek</th>
                    <th class="px-5 py-3.5">Pengaju (Marketing)</th>
                    @if(auth()->user()->canViewHpp())
                        <th class="px-5 py-3.5">Harga HPP</th>
                    @endif
                    <th class="px-5 py-3.5">Harga Usulan Jual</th>
                    <th class="px-5 py-3.5 text-center">Approval Founder</th>
                    <th class="px-5 py-3.5 text-center">Approval Supervisor</th>
                    <th class="px-5 py-3.5 text-center">Status Final</th>
                    <th class="px-5 py-3.5 text-center">Dokumen SPP</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($proposals as $prop)
                    @php
                        $founderApp = $prop->approvals->where('approver_role', 'founder')->first();
                        $superApp = $prop->approvals->where('approver_role', 'supervisor')->first();
                        $isBelowHpp = $prop->proposed_price < $prop->hpp_price;
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition duration-150">
                        <td class="px-5 py-4">
                            <p class="font-bold text-slate-900 font-mono text-sm">{{ $prop->unit->code }}</p>
                            <p class="text-slate-400 text-[11px] font-medium">{{ $prop->unit->project->name }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-bold text-slate-800">{{ $prop->proposer->name }}</p>
                            <p class="text-slate-400 text-[10px] font-mono">{{ $prop->created_at->format('d/m/Y H:i') }}</p>
                        </td>
                        @if(auth()->user()->canViewHpp())
                            <td class="px-5 py-4 font-mono font-medium text-slate-600">Rp {{ number_format($prop->hpp_price, 0, ',', '.') }}</td>
                        @endif
                        <td class="px-5 py-4 font-mono font-bold text-emerald-700">
                            Rp {{ number_format($prop->proposed_price, 0, ',', '.') }}
                            @if($isBelowHpp)
                                <span class="block text-[9px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200 mt-0.5 w-fit">
                                    Penawaran &lt; HPP
                                </span>
                            @endif
                        </td>

                        <!-- Founder Approval Status Pill -->
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            @if($founderApp)
                                @if($founderApp->decision === 'disetujui')
                                    <span class="status-disetujui">Founder: ACC</span>
                                @else
                                    <span class="status-ditolak">Founder: Ditolak</span>
                                @endif
                            @else
                                <span class="status-menunggu">Founder: Pending</span>
                            @endif
                        </td>

                        <!-- Supervisor Approval Status Pill -->
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            @if($superApp)
                                @if($superApp->decision === 'disetujui')
                                    <span class="status-disetujui">Supervisor: ACC</span>
                                @else
                                    <span class="status-ditolak">Supervisor: Ditolak</span>
                                @endif
                            @else
                                <span class="status-menunggu">Supervisor: Pending</span>
                            @endif
                        </td>

                        <!-- Final Status -->
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            @if($prop->status === 'menunggu')
                                <span class="status-menunggu">Menunggu ACC</span>
                            @elseif($prop->status === 'disetujui')
                                <span class="status-disetujui">Disetujui Penuh</span>
                            @else
                                <span class="status-ditolak">Ditolak</span>
                            @endif
                        </td>

                        <!-- Dokumen SPP PDF -->
                        <td class="px-5 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                @if($prop->status === 'disetujui' || $prop->unit->officialDocument)
                                    @php $officialDoc = $prop->unit->officialDocument ?? \App\Models\OfficialDocument::where('price_proposal_id', $prop->id)->first(); @endphp
                                    @if($officialDoc)
                                        <button wire:click="openViewerModal('pdf', '{{ route('documents.stream', $officialDoc->id) }}', 'Pratinjau Surat Pemesanan Properti (SPP) PDF')" class="btn-action-pdf" title="Lihat SPP PDF">
                                            <svg class="w-3.5 h-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            <span>SPP PDF</span>
                                        </button>
                                    @else
                                        <button wire:click="openDocModal({{ $prop->id }})" class="btn-action-detail" title="Terbitkan Surat Pemesanan Properti">
                                            <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            <span>Terbitkan SPP</span>
                                        </button>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic text-[11px]">-</span>
                                @endif
                            </div>
                        </td>

                        <!-- Aksi Review & Kelola Founder -->
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                @if((auth()->user()->isAdminOrFounder() || auth()->user()->isSupervisor()) && $prop->status === 'menunggu')
                                    <button wire:click="openApprovalModal({{ $prop->id }})" class="btn-action-edit" title="Review Keputusan Approval">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Review</span>
                                    </button>
                                @endif

                                @if(auth()->user()->isFounder())
                                    <button wire:click="editProposal({{ $prop->id }})" class="btn-action-edit" title="Edit Pengajuan Harga">
                                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Edit</span>
                                    </button>
                                    <button type="button" @click="confirmModalAction({
                                        title: 'Hapus Pengajuan Harga',
                                        message: 'Yakin ingin MENGHAPUS pengajuan harga unit {{ $prop->unit->code }} ini?',
                                        confirmText: 'Hapus Proposal',
                                        btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                        onConfirm: () => $wire.deleteProposal({{ $prop->id }})
                                    })" class="btn-action-delete" title="Hapus Pengajuan Harga">
                                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus</span>
                                    </button>
                                @endif

                                @if(!auth()->user()->isFounder() && !((auth()->user()->isFounder() || auth()->user()->isSupervisor()) && $prop->status === 'menunggu'))
                                    <span class="text-slate-400 italic text-[11px]">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->canViewHpp() ? 9 : 8 }}" class="px-6 py-12 text-center text-slate-400">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="font-semibold text-slate-600">Belum Ada Pengajuan Harga Jual</p>
                            <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Buat Pengajuan Harga" untuk membuat usulan penawaran baru.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3.5 border-t border-slate-100">
        {{ $proposals->links() }}
    </div>
</div>
