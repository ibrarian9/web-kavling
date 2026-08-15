<!-- Modal Detail Alur Keuangan & Audit Trail -->
@if(!empty($showDetailModal) && !empty($selectedTransaction))
    <x-modal-dialog show="showDetailModal" 
                    title="Detail Alur Keuangan & Audit Trail" 
                    subTitle="Nomor Mutasi: #TRX-{{ $selectedTransaction->id }}" 
                    maxWidth="max-w-xl">
        <div class="space-y-4 text-xs sm:text-sm">
            <!-- Source Menu Banner -->
            <div class="p-3 bg-amber-50/90 border border-amber-200/80 rounded-2xl text-amber-950 text-xs font-semibold flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 shadow-2xs">
                <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">Di-input Dari Menu:</span>
                <span class="font-bold text-xs bg-amber-100 text-amber-900 px-2.5 py-1 rounded-lg border border-amber-300 flex items-center gap-1 shrink-0">
                    {{ $auditTrailInfo['source_menu'] }}
                </span>
            </div>

            <!-- Financial Highlight Box -->
            <div class="p-4 rounded-2xl {{ $selectedTransaction->type === 'masuk' ? 'bg-emerald-50 border border-emerald-200/80 text-emerald-900' : 'bg-rose-50 border border-rose-200/80 text-rose-900' }} flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider block opacity-75">Nominal Mutasi Kas</span>
                    <strong class="text-lg sm:text-xl font-mono font-extrabold">
                        {{ $selectedTransaction->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($selectedTransaction->amount, 0, ',', '.') }}
                    </strong>
                </div>
                <div class="text-right">
                    <x-status-badge :status="$selectedTransaction->type === 'masuk' ? 'disetujui' : 'ditolak'" :label="$selectedTransaction->type === 'masuk' ? 'Kas Masuk' : 'Kas Keluar'" />
                </div>
            </div>

            <!-- Audit Timeline Steps -->
            <div class="space-y-4 pt-1 text-xs sm:text-sm">
                <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[11px] border-b border-slate-100 pb-1.5">Alur Pencatatan, Waktu & Otorisasi</h4>

                <!-- Step 1: Inputter -->
                <div class="flex items-start gap-3 relative pl-1">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        1
                    </div>
                    <div class="space-y-0.5 flex-1">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                            <span class="font-bold text-slate-900">Diinput Oleh</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $auditTrailInfo['inputted_by']['time'] ?? '-' }}</span>
                        </div>
                        <p class="font-semibold text-blue-800">{{ $auditTrailInfo['inputted_by']['name'] ?? 'Sistem' }} <span class="text-slate-500 font-normal">({{ ucfirst($auditTrailInfo['inputted_by']['role'] ?? 'Sistem') }})</span></p>
                        <p class="text-[11px] text-slate-600 mt-0.5 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Waktu Entri: {{ $auditTrailInfo['inputted_by']['created_at'] ?? ($auditTrailInfo['inputted_by']['date'] ?? '-') }}</span>
                        </p>
                    </div>
                </div>

                <!-- Step 2: System Processing / Context -->
                <div class="flex items-start gap-3 relative pl-1">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        2
                    </div>
                    <div class="space-y-0.5 flex-1">
                        <span class="font-bold text-slate-900 block">Konteks & Keterangan Transaksi</span>
                        <p class="text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 leading-relaxed font-medium">
                            {{ $selectedTransaction->description }}
                        </p>
                        @if($selectedTransaction->reference_type)
                            <div class="pt-1 flex items-center gap-2 flex-wrap">
                                <span class="text-[10px] text-slate-400 uppercase font-bold">Relasi Data:</span>
                                <span class="font-mono text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-200 text-[10px] font-bold">
                                    {{ class_basename($selectedTransaction->reference_type) }} #{{ $selectedTransaction->reference_id }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Step 3: Verification / Approval -->
                <div class="flex items-start gap-3 relative pl-1">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full {{ ($auditTrailInfo['approved_by']['status'] ?? '') === 'Terverifikasi Otomatis' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                        3
                    </div>
                    <div class="space-y-0.5 flex-1">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                            <span class="font-bold text-slate-900">Status Validasi Arus Kas</span>
                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full {{ ($auditTrailInfo['approved_by']['status'] ?? '') === 'Terverifikasi Otomatis' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ $auditTrailInfo['approved_by']['status'] ?? 'Terverifikasi' }}
                            </span>
                        </div>
                        <p class="text-slate-600 font-medium">{{ $auditTrailInfo['approved_by']['notes'] ?? 'Tercatat dalam laporan mutasi arus kas resmi' }}</p>
                        <p class="text-[10px] text-slate-400 font-mono pt-0.5">Waktu System: {{ $auditTrailInfo['approved_by']['approved_at'] ?? '-' }} ({{ $auditTrailInfo['approved_by']['day'] ?? '-' }})</p>
                        <p class="text-[10px] text-slate-400 font-mono pt-0.5">Pemeriksa Terdaftar: {{ $auditTrailInfo['approved_by']['name'] ?? 'Tim Finance / Founder' }}</p>
                    </div>
                </div>
            </div>

            <!-- Receipt Photo Thumbnail if available -->
            @if($selectedTransaction->receipt_photo_url)
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl space-y-2">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Lampiran Bukti Transaksi:</span>
                    <div class="flex items-center gap-3">
                        <img src="{{ $selectedTransaction->receipt_photo_url }}" alt="Thumbnail Bukti" class="w-14 h-14 object-cover rounded-xl border border-slate-200 shadow-2xs">
                        <div class="space-y-1">
                            <p class="font-bold text-slate-800 text-xs truncate max-w-[200px] sm:max-w-xs">{{ basename($selectedTransaction->receipt_photo_path) }}</p>
                            <a href="{{ $selectedTransaction->receipt_photo_url }}" target="_blank" class="text-teal-600 hover:text-teal-700 font-bold text-xs flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                <span>Lihat Ukuran Penuh</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Modal Footer -->
            <div class="flex items-center justify-end pt-3 border-t border-slate-100 shrink-0">
                <x-button variant="outline" size="sm" wire:click="closeDetailModal">Tutup Detail</x-button>
            </div>
        </div>
    </x-modal-dialog>
@endif
