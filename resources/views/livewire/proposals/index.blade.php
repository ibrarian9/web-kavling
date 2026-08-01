<div class="space-y-6">

    <!-- Header Section -->
    <div class="card-clean p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Pengajuan & Persetujuan Harga Jual</h2>
            <p class="text-slate-500 text-xs mt-0.5">Alur approval berjenjang & paralel (Founder & Supervisor) sebelum penerbitan Surat Resmi</p>
        </div>

        @if(auth()->user()->isMarketing() || auth()->user()->isFounder())
            <button wire:click="openCreateModal" class="btn-primary whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Buat Pengajuan Harga</span>
            </button>
        @endif
    </div>

    <!-- Summary KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="kpi-card-blue">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Proposal Usulan</span>
                <div class="p-2.5 rounded-xl bg-purple-50 text-purple-600 border border-purple-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-slate-900 font-mono mt-2">{{ $proposals->total() }} Proposal</p>
            <p class="text-[11px] text-slate-400 mt-1">Usulan penawaran harga dari marketing</p>
        </div>

        <div class="kpi-card-amber">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Menunggu Approval</span>
                <div class="p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-amber-600 font-mono mt-2">
                {{ \App\Models\PriceProposal::where('status', 'menunggu')->count() }} Menunggu
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Memerlukan keputusannya Founder & Supervisor</p>
        </div>

        <div class="kpi-card-emerald">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Proposal Disetujui (ACC)</span>
                <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-2xl font-extrabold text-emerald-700 font-mono mt-2">
                {{ \App\Models\PriceProposal::where('status', 'disetujui')->count() }} Disetujui
            </p>
            <p class="text-[11px] text-slate-400 mt-1">Harga disetujui & siap cetak SPP</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-clean overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
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
                                    @if((auth()->user()->isFounder() || auth()->user()->isSupervisor()) && $prop->status === 'menunggu')
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
                                        <button wire:click="deleteProposal({{ $prop->id }})" wire:confirm="Yakin ingin MENGHAPUS pengajuan harga unit {{ $prop->unit->code }} ini?" class="btn-action-delete" title="Hapus Pengajuan Harga">
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

    <!-- Modal Buat Pengajuan (Marketing) -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">{{ $editingProposalId ? 'Edit Pengajuan Harga Unit' : 'Buat Pengajuan Harga Jual Unit' }}</h3>
                    <button wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
                </div>

                <form wire:submit.prevent="submitProposal" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Pilih Unit Kavling / Rumah</label>
                        <select wire:change="selectUnitForProposal($event.target.value)" wire:model="unit_id" class="input-clean w-full font-bold">
                            <option value="">-- Pilih Unit Tersedia --</option>
                            @foreach($availableUnits as $u)
                                <option value="{{ $u->id }}">{{ $u->code }} - {{ $u->project->name }} @if(auth()->user()->canViewHpp())(HPP: Rp {{ number_format($u->hpp, 0, ',', '.') }})@endif</option>
                            @endforeach
                        </select>
                        @error('unit_id') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Highlight Box HPP -->
                    @if(auth()->user()->canViewHpp())
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-slate-500">Harga Pokok (HPP):</span>
                                <span class="font-mono font-bold text-slate-800">Rp {{ number_format($hpp_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Usulan Harga Jual / Penawaran (Rp)</label>
                        <x-currency-input model="proposed_price" class="input-clean w-full font-bold text-base font-mono text-emerald-700" />
                        <p class="text-[10px] text-slate-400 mt-1 font-medium">*Dapat diajukan di bawah HPP untuk kebutuhan penawaran khusus/diskon.</p>
                        @error('proposed_price') <span class="text-rose-500 text-[10px] block mt-1 font-bold">{{ $message }}</span> @enderror
                    </div>


                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Catatan Pengajuan (Opsional)</label>
                        <textarea wire:model="proposal_notes" rows="2" placeholder="Catatan penawaran atau nego pembeli..." class="input-clean w-full"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">{{ $editingProposalId ? 'Simpan Perubahan' : 'Kirim Pengajuan' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal Approval Sejajar (Founder & Supervisor) -->
    @if($showApprovalModal && $approvalProposal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Modal Approval Harga Jual</h3>
                        <p class="text-slate-500 text-[11px]">Review keputusan oleh: {{ strtoupper(auth()->user()->role) }}</p>
                    </div>
                    <button wire:click="$set('showApprovalModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <div class="space-y-4 text-xs">
                    <!-- Detail Unit Card -->
                    <div class="bg-slate-900 text-white rounded-xl p-4 space-y-2 shadow-inner">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-emerald-400 font-mono">{{ $approvalProposal->unit->code }}</span>
                            <span class="text-xs text-slate-400 font-medium">{{ $approvalProposal->unit->project->name }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-800 text-slate-300">
                            @if(auth()->user()->canViewHpp())
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-semibold">Harga Pokok (HPP)</p>
                                    <p class="font-mono font-bold text-white text-sm">Rp {{ number_format($approvalProposal->hpp_price, 0, ',', '.') }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-semibold">Usulan Jual (Marketing)</p>
                                <p class="font-mono font-bold text-emerald-400 text-sm">Rp {{ number_format($approvalProposal->proposed_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Approval Lainnya -->
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-1.5">
                        <p class="font-bold text-slate-700 text-[11px] uppercase tracking-wider">Status Approval Paralel Saat Ini:</p>
                        @foreach(['founder', 'supervisor'] as $r)
                            @php $app = $approvalProposal->approvals->where('approver_role', $r)->first(); @endphp
                            <div class="flex justify-between items-center text-xs">
                                <span class="capitalize font-semibold text-slate-700">{{ $r }}:</span>
                                @if($app)
                                    <span class="{{ $app->decision === 'disetujui' ? 'text-emerald-700 font-bold' : 'text-rose-700 font-bold' }}">
                                        {{ ucfirst($app->decision) }} ({{ $app->approver->name }})
                                    </span>
                                @else
                                    <span class="text-amber-600 font-medium italic">Belum Diputuskan</span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <form wire:submit.prevent="submitApproval" class="space-y-3">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Keputusan Anda ({{ strtoupper(auth()->user()->role) }})</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border border-emerald-200 bg-emerald-50 cursor-pointer hover:bg-emerald-100 transition">
                                    <input type="radio" wire:model="approval_decision" value="disetujui" class="text-emerald-600 focus:ring-emerald-500">
                                    <span class="font-bold text-emerald-800">Setujui Harga</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border border-rose-200 bg-rose-50 cursor-pointer hover:bg-rose-100 transition">
                                    <input type="radio" wire:model="approval_decision" value="ditolak" class="text-rose-600 focus:ring-rose-500">
                                    <span class="font-bold text-rose-800">Tolak Pengajuan</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Catatan Alasan Keputusan</label>
                            <textarea wire:model="approval_notes" rows="2" placeholder="Catatan persetujuan / alasan penolakan..." class="input-clean w-full"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                            <button type="button" wire:click="$set('showApprovalModal', false)" class="btn-secondary">Batal</button>
                            <button type="submit" class="btn-primary">Simpan Keputusan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Terbitkan Surat Resmi -->
    @if($showDocModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
            <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-900 text-base">Terbitkan Surat Pemesanan Properti (SPP)</h3>
                    <button wire:click="$set('showDocModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="issueDocument" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nama Lengkap Pembeli</label>
                        <input type="text" wire:model="buyer_name" required placeholder="Bapak / Ibu Pembeli" class="input-clean w-full font-bold">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">No. Telepon / WhatsApp Pembeli</label>
                        <input type="text" wire:model="buyer_contact" required placeholder="081234567890" class="input-clean w-full font-mono">
                    </div>

                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Alamat Lengkap Pembeli</label>
                        <textarea wire:model="buyer_address" rows="2" placeholder="Jl. Monjali No. 12..." class="input-clean w-full"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showDocModal', false)" class="btn-secondary">Batal</button>
                        <button type="submit" class="btn-primary">Terbitkan Surat PDF</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- PDF Viewer Modal (Dokumen SPP PDF) -->
    @if($showViewerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white border border-slate-200 rounded-3xl max-w-4xl w-full p-6 shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        {{ $viewerTitle }}
                    </h3>
                    <button wire:click="closeViewerModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
                </div>
                <div class="flex-1 overflow-hidden min-h-[500px]">
                    <iframe src="{{ $viewerUrl }}" class="w-full h-full rounded-2xl border border-slate-200 min-h-[500px]"></iframe>
                </div>
                <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                    <a href="{{ $viewerUrl }}" target="_blank" class="btn-secondary text-xs px-4 py-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>Buka di Tab Baru / Cetak Direct</span>
                    </a>
                    <button wire:click="closeViewerModal" class="btn-primary bg-slate-800 hover:bg-slate-900 text-xs px-5 py-2">Tutup Pratinjau</button>
                </div>
            </div>
        </div>
    @endif
</div>
