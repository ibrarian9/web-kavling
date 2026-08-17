<!-- Filters Toolbar -->
<div class="card-clean p-4 border border-slate-200/80 rounded-3xl flex flex-col md:flex-row items-center justify-between gap-3">
    <div class="flex items-center gap-2.5 w-full md:w-auto flex-wrap">
        <select wire:model.live="projectIdFilter" class="select-clean text-xs font-bold w-full md:w-56">
            <option value="">Semua Perumahan / Proyek</option>
            @foreach ($projects as $proj)
                <option value="{{ $proj->id }}">{{ $proj->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="statusFilter" class="select-clean text-xs font-bold w-full md:w-48">
            <option value="all">Semua Status Approval</option>
            <option value="menunggu">Menunggu ACC</option>
            <option value="disetujui">Disetujui (ACC)</option>
            <option value="ditolak">Ditolak</option>
        </select>
    </div>

    <x-search-input placeholder="Cari kode unit, nama proyek, atau marketing..." containerClass="w-full md:w-72" />
</div>

<!-- Table Card -->
@php
    $propHeaders = ['Unit & Proyek', 'Pengaju (Marketing)'];
    if(auth()->user()->canViewHpp()) {
        $propHeaders[] = 'Harga HPP';
    }
    $propHeaders[] = 'Harga Usulan Jual';
    $propHeaders[] = ['label' => 'Approval Founder', 'class' => 'p-3.5 text-center'];
    $propHeaders[] = ['label' => 'Approval Supervisor', 'class' => 'p-3.5 text-center'];
    $propHeaders[] = ['label' => 'Status Final', 'class' => 'p-3.5 text-center'];
    $propHeaders[] = ['label' => 'Dokumen SPP', 'class' => 'p-3.5 text-center'];
    $propHeaders[] = ['label' => 'Aksi', 'class' => 'p-3.5 text-right'];
@endphp

<x-table :headers="$propHeaders" loadingTarget="projectIdFilter, statusFilter, search, gotoPage, nextPage, previousPage">
    @forelse($proposals as $prop)
        @php
            $founderApp = $prop->approvals->where('approver_role', 'founder')->first();
            $superApp = $prop->approvals->where('approver_role', 'supervisor')->first();
            $isBelowHpp = $prop->proposed_price < $prop->hpp_price;
        @endphp
        <tr class="hover:bg-slate-50/60 transition duration-150">
            <td class="p-3.5">
                <p class="font-bold text-slate-900 font-mono text-sm">{{ $prop->unit->code }}</p>
                <p class="text-slate-400 text-[11px] font-medium">{{ $prop->unit->project->name }}</p>
            </td>
            <td class="p-3.5">
                <p class="font-bold text-slate-800 text-xs">{{ $prop->proposer->name }}</p>
                <p class="text-slate-400 text-[10px] font-mono">{{ $prop->created_at->format('d/m/Y H:i') }}</p>
            </td>
            @if(auth()->user()->canViewHpp())
                <td class="p-3.5 font-mono font-medium text-slate-600 text-xs">Rp {{ number_format($prop->hpp_price, 0, ',', '.') }}</td>
            @endif
            <td class="p-3.5 font-mono font-bold text-emerald-700 text-xs">
                Rp {{ number_format($prop->proposed_price, 0, ',', '.') }}
                @if($isBelowHpp)
                    <span class="block text-[9px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200 mt-0.5 w-fit">
                        Penawaran &lt; HPP
                    </span>
                @endif
            </td>

            <!-- Founder Approval Status Pill -->
            <td class="p-3.5 text-center whitespace-nowrap">
                @if($founderApp)
                    @if($founderApp->decision === 'disetujui')
                        <x-status-badge status="disetujui" label="Founder: ACC" />
                    @else
                        <x-status-badge status="ditolak" label="Founder: Ditolak" />
                    @endif
                @else
                    <x-status-badge status="pending" label="Founder: Pending" />
                @endif
            </td>

            <!-- Supervisor Approval Status Pill -->
            <td class="p-3.5 text-center whitespace-nowrap">
                @if($superApp)
                    @if($superApp->decision === 'disetujui')
                        <x-status-badge status="disetujui" label="Supervisor: ACC" />
                    @else
                        <x-status-badge status="ditolak" label="Supervisor: Ditolak" />
                    @endif
                @else
                    <x-status-badge status="pending" label="Supervisor: Pending" />
                @endif
            </td>

            <!-- Final Status -->
            <td class="p-3.5 text-center whitespace-nowrap">
                @if($prop->status === 'disetujui')
                    <x-status-badge status="disetujui" label="Disetujui" />
                @elseif($prop->status === 'ditolak')
                    <x-status-badge status="ditolak" label="Ditolak" />
                @else
                    <x-status-badge status="pending" label="Menunggu" />
                @endif
            </td>

            <!-- PDF Viewer & Download Trigger Button -->
            <td class="p-3.5 text-center whitespace-nowrap">
                @if($prop->status === 'disetujui')
                    @if($prop->officialDocument)
                        <x-button variant="outline" size="xs" wire:click="openViewerModal('pdf', '{{ route('documents.stream', $prop->officialDocument->id) }}', 'Surat Pemesanan Properti (SPP) - Unit {{ $prop->unit->code }}')" title="Pratinjau Dokumen SPP">
                            Lihat SPP
                        </x-button>
                    @else
                        <x-button variant="emerald" size="xs" wire:click="openDocModal({{ $prop->id }})" title="Terbitkan Dokumen SPP Resmi">
                            Terbitkan SPP
                        </x-button>
                    @endif
                @else
                    <span class="text-slate-300 text-[10px] italic">Menunggu ACC</span>
                @endif
            </td>

            <!-- Aksi Buttons -->
            <td class="p-3.5 text-right whitespace-nowrap">
                <div class="inline-flex items-center justify-end gap-1.5 whitespace-nowrap">
                    @if($prop->status === 'menunggu')
                        @php
                            $userRole = auth()->user()->role;
                            $hasApproved = $prop->approvals->where('user_id', auth()->id())->first();
                        @endphp

                        @if(!$hasApproved && ($userRole === 'founder' || ($userRole === 'supervisor' && !$founderApp)))
                            <x-button variant="emerald" size="xs" wire:click="openApprovalModal({{ $prop->id }})" title="Setujui atau Tolak Pengajuan Ini">
                                Review & ACC
                            </x-button>
                        @endif
                    @endif

                    @if(auth()->user()->isFounder())
                        <x-action-dropdown title="Menu Opsi SPP" size="xs">
                            <div class="py-1">
                                <x-dropdown-item icon="delete" variant="danger" @click="confirmModalAction({
                                    title: 'Hapus Pengajuan SPP',
                                    message: 'Yakin ingin menghapus pengajuan harga unit {{ $prop->unit->code }} ini?',
                                    confirmText: 'Hapus SPP',
                                    btnClass: 'px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center gap-1.5',
                                    onConfirm: () => $wire.deleteProposal({{ $prop->id }})
                                })">
                                    Hapus Pengajuan
                                </x-dropdown-item>
                            </div>
                        </x-action-dropdown>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="{{ count($propHeaders) }}" class="p-12 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="font-semibold text-slate-600">Belum Ada Pengajuan Harga</p>
                <p class="text-xs text-slate-400 mt-1">Data SPP / usulan harga akan muncul di sini.</p>
            </td>
        </tr>
    @endforelse
</x-table>

<div>{{ $proposals->links() }}</div>
