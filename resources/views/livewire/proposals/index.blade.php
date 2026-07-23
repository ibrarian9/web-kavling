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

    <!-- Table Card -->
    <div class="card-clean overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-slate-500 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-5 py-3.5">Unit & Proyek</th>
                        <th class="px-5 py-3.5">Pengaju (Marketing)</th>
                        <th class="px-5 py-3.5">Harga HPP</th>
                        <th class="px-5 py-3.5">Harga Usulan Jual</th>
                        <th class="px-5 py-3.5">Approval Founder</th>
                        <th class="px-5 py-3.5">Approval Supervisor</th>
                        <th class="px-5 py-3.5">Status Final</th>
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
                            <td class="px-5 py-4 font-mono font-medium text-slate-600">Rp {{ number_format($prop->hpp_price, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 font-mono font-bold text-emerald-700">
                                Rp {{ number_format($prop->proposed_price, 0, ',', '.') }}
                                @if($isBelowHpp)
                                    <span class="block text-[9px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200 mt-0.5 w-fit">
                                        Penawaran &lt; HPP
                                    </span>
                                @endif
                            </td>

                            <!-- Founder Approval Status Pill -->
                            <td class="px-5 py-4">
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
                            <td class="px-5 py-4">
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
                            <td class="px-5 py-4">
                                @if($prop->status === 'menunggu')
                                    <span class="status-menunggu">Menunggu ACC</span>
                                @elseif($prop->status === 'disetujui')
                                    <span class="status-disetujui">Disetujui Penuh</span>
                                @else
                                    <span class="status-ditolak">Ditolak</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-right space-x-1">
                                @if((auth()->user()->isFounder() || auth()->user()->isSupervisor()) && $prop->status === 'menunggu')
                                    <button wire:click="openApprovalModal({{ $prop->id }})" class="btn-primary text-[11px] px-2.5 py-1">
                                        Review / ACC
                                    </button>
                                @endif

                                @if($prop->status === 'disetujui' || $prop->unit->officialDocument)
                                    @php $officialDoc = $prop->unit->officialDocument ?? \App\Models\OfficialDocument::where('price_proposal_id', $prop->id)->first(); @endphp
                                    @if($officialDoc)
                                        <a href="{{ route('documents.stream', $officialDoc->id) }}" target="_blank" class="px-2.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold shadow inline-flex items-center gap-1 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span>Unduh Surat Persetujuan PDF</span>
                                        </a>
                                    @else
                                        <button wire:click="openDocModal({{ $prop->id }})" class="btn-secondary text-[11px] px-2.5 py-1">
                                            Cetak Surat PDF
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
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
                    <h3 class="font-bold text-slate-900 text-base">Buat Pengajuan Harga Jual Unit</h3>
                    <button wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
                </div>

                <form wire:submit.prevent="submitProposal" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Pilih Unit Kavling / Rumah</label>
                        <select wire:change="selectUnitForProposal($event.target.value)" wire:model="unit_id" class="input-clean w-full font-bold">
                            <option value="">-- Pilih Unit Tersedia --</option>
                            @foreach($availableUnits as $u)
                                <option value="{{ $u->id }}">{{ $u->code }} - {{ $u->project->name }} (HPP: Rp {{ number_format($u->hpp, 0, ',', '.') }})</option>
                            @endforeach
                        </select>
                        @error('unit_id') <span class="text-rose-500 text-[10px] mt-1 block font-bold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Highlight Box HPP -->
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 space-y-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-500">Harga Pokok (HPP):</span>
                            <span class="font-mono font-bold text-slate-800">Rp {{ number_format($hpp_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

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
                        <button type="submit" class="btn-primary">Kirim Pengajuan</button>
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
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-semibold">Harga Pokok (HPP)</p>
                                <p class="font-mono font-bold text-white text-sm">Rp {{ number_format($approvalProposal->hpp_price, 0, ',', '.') }}</p>
                            </div>
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

</div>
