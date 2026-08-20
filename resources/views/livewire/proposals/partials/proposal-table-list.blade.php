<!-- Filters Toolbar (Top Search Bar, Bottom Filter Controls) -->
<div class="card-clean p-4 border border-slate-200/80 rounded-3xl space-y-3 shadow-2xs">
    <!-- Baris 1 (Atas): Full-Width Search Input -->
    <div>
        <x-search-input placeholder="Cari kode unit kavling, nama perumahan/proyek, atau nama marketing pengaju..." containerClass="w-full" />
    </div>

    <!-- Baris 2 (Bawah): Filter Kontrol (Filter Waktu/Periode, Proyek, Status Approval) -->
    <div class="flex items-center gap-2.5 flex-wrap justify-between pt-1 border-t border-slate-100/80">
        <div class="flex items-center gap-2.5 flex-wrap flex-1">
            <!-- Filter Periode Waktu Tanggal Pengajuan -->
            <x-date-period-filter periodModel="datePeriod" startModel="startDate" endModel="endDate" :periodValue="$datePeriod" />

            <!-- Filter Proyek Kavling -->
            <select wire:model.live="projectIdFilter" class="select-clean text-xs font-bold w-full sm:w-auto min-w-[180px]">
                <option value="">Semua Perumahan / Proyek</option>
                @foreach ($projects as $proj)
                    <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                @endforeach
            </select>

            <!-- Filter Status Approval -->
            <select wire:model.live="statusFilter" class="select-clean text-xs font-bold w-full sm:w-auto min-w-[170px]">
                <option value="all">Semua Status Approval</option>
                <option value="menunggu">Menunggu ACC</option>
                <option value="disetujui">Disetujui (ACC)</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>

        <!-- Tombol Reset Filter -->
        @if($search || $projectIdFilter || $statusFilter !== 'all' || $datePeriod !== 'all' || $startDate || $endDate)
            <x-reset-filter-button 
                wire:click="$set('search', ''); $set('projectIdFilter', ''); $set('statusFilter', 'all'); $set('datePeriod', 'all'); $set('startDate', ''); $set('endDate', '');" 
            />
        @endif
    </div>
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

<!--     <!-- Unified Table of Proposals with CSS Table-to-Card Transformation -->
    <x-table :headers="$propHeaders" loadingTarget="projectIdFilter, statusFilter, search, datePeriod, startDate, endDate, gotoPage, nextPage, previousPage">
        @forelse($proposals as $prop)
            @php
                $founderApp = $prop->approvals->where('approver_role', 'founder')->first();
                $superApp = $prop->approvals->where('approver_role', 'supervisor')->first();
                $isBelowHpp = $prop->proposed_price < $prop->hpp_price;
            @endphp
            <tr class="hover:bg-slate-50/60 transition duration-150">
                <td data-label="Unit & Proyek" class="p-3.5">
                    <p class="font-bold text-slate-900 font-mono text-sm">{{ $prop->unit->code }}</p>
                    <p class="text-slate-400 text-[11px] font-medium">{{ $prop->unit->project->name }}</p>
                </td>
                <td data-label="Marketing Pengaju" class="p-3.5">
                    <p class="font-bold text-slate-800 text-xs">{{ $prop->proposer->name }}</p>
                    <p class="text-slate-400 text-[10px] font-mono">{{ format_id_datetime($prop->created_at, false) }}</p>
                </td>
                @if(auth()->user()->canViewHpp())
                    <td data-label="Harga HPP" class="p-3.5 font-mono font-medium text-slate-600 text-xs">Rp {{ number_format($prop->hpp_price, 0, ',', '.') }}</td>
                @endif
                <td data-label="Harga Usulan Jual" class="p-3.5 font-mono font-bold text-emerald-700 text-xs">
                    Rp {{ number_format($prop->proposed_price, 0, ',', '.') }}
                    @if($isBelowHpp)
                        <span class="block text-[9px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200 mt-0.5 w-fit">
                            Penawaran &lt; HPP
                        </span>
                    @endif
                </td>

                <!-- Founder Approval Status Pill -->
                <td data-label="Approval Founder" class="p-3.5 text-center whitespace-nowrap">
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
                <td data-label="Approval Supervisor" class="p-3.5 text-center whitespace-nowrap">
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
                <td data-label="Status Final" class="p-3.5 text-center whitespace-nowrap">
                    @if($prop->status === 'disetujui')
                        <x-status-badge status="disetujui" label="Disetujui" />
                    @elseif($prop->status === 'ditolak')
                        <x-status-badge status="ditolak" label="Ditolak" />
                    @else
                        <x-status-badge status="pending" label="Menunggu" />
                    @endif
                </td>

                <!-- PDF Viewer & Download Trigger Button -->
                <td data-label="Dokumen SPP" class="p-3.5 text-center whitespace-nowrap">
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
                <td data-card-action class="p-3.5 text-right whitespace-nowrap">
                    <div class="inline-flex items-center justify-end gap-1.5 whitespace-nowrap">
                        @if($prop->status === 'menunggu')
                            @php
                                $userRole = auth()->user()->role;
                                $hasApproved = $prop->approvals->where('user_id', auth()->id())->first();
                            @endphp

                            @if(!$hasApproved && ($userRole === 'founder' || $userRole === 'supervisor' || $userRole === 'finance' || $userRole === 'admin'))
                                <x-button variant="emerald" size="xs" wire:click="openApprovalModal({{ $prop->id }})" title="Setujui atau Tolak Pengajuan Ini">
                                    Review & ACC
                                </x-button>
                            @endif
                        @endif

                        @if(auth()->user()->isSuperAdmin())
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
            <x-table-empty :colspan="count($propHeaders)" title="Belum Ada Pengajuan Harga" message="Data SPP / usulan harga akan muncul di sini." />
        @endforelse
    </x-table>

<div>{{ $proposals->links() }}</div>
