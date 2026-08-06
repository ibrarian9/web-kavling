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
                        <button type="submit" wire:loading.attr="disabled" class="btn-primary flex items-center justify-center gap-2">
                            <svg wire:loading wire:target="submitApproval" class="w-4 h-4 animate-spin text-white shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span wire:loading.remove wire:target="submitApproval">Simpan Keputusan</span>
                            <span wire:loading wire:target="submitApproval">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- Modal Terbitkan Surat Resmi (SPP) -->
@if($showDocModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
        <div class="bg-white border border-slate-200/80 rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-900 text-base">Terbitkan Surat Pemesanan Properti (SPP)</h3>
                <button wire:click="$set('showDocModal', false)" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form wire:submit.prevent="issueDocument" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Nama Lengkap Pembeli *</label>
                    <input type="text" wire:model="buyer_name" required placeholder="Bapak / Ibu Pembeli" class="input-clean w-full font-bold">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">No. Telepon / WhatsApp Pembeli *</label>
                    <input type="text" wire:model="buyer_contact" required placeholder="081234567890" class="input-clean w-full font-mono">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1 uppercase tracking-wider">Alamat Lengkap Pembeli</label>
                    <textarea wire:model="buyer_address" rows="2" placeholder="Jl. Monjali No. 12..." class="input-clean w-full"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" wire:click="$set('showDocModal', false)" class="btn-secondary">Batal</button>
                    <button type="submit" wire:loading.attr="disabled" class="btn-primary flex items-center justify-center gap-2">
                        <svg wire:loading wire:target="issueDocument" class="w-4 h-4 animate-spin text-white shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span wire:loading.remove wire:target="issueDocument">Terbitkan Surat PDF</span>
                        <span wire:loading wire:target="issueDocument">Menerbitkan...</span>
                    </button>
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
