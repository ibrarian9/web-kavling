<!-- Modal Detail Alur Keuangan & Audit Trail -->
@if(!empty($showDetailModal) && !empty($selectedTransaction))
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs p-3 sm:p-6 md:p-10 flex items-center justify-center min-h-screen">
        <div class="bg-white border border-slate-200/80 rounded-2xl sm:rounded-3xl max-w-lg sm:max-w-xl w-full p-4 sm:p-6 shadow-2xl space-y-4 my-auto sm:my-8 max-h-[88vh] sm:max-h-[85vh] flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 rounded-xl bg-teal-50 text-teal-700 border border-teal-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">Detail Alur Keuangan & Audit Trail</h3>
                        <p class="text-[11px] text-slate-500">Nomor Mutasi: <strong class="font-mono text-slate-800">#TRX-{{ $selectedTransaction->id }}</strong></p>
                    </div>
                </div>
                <button wire:click="closeDetailModal" class="text-slate-400 hover:text-slate-600 text-sm font-bold p-1">✕</button>
            </div>

            <!-- Scrollable Body Content -->
            <div class="space-y-4 text-xs sm:text-sm flex-1 overflow-y-auto pr-1">
                <!-- Source Menu Banner -->
                <div class="p-3 bg-amber-50/90 border border-amber-200/80 rounded-xl text-amber-950 text-xs font-semibold flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 shadow-2xs">
                    <span class="text-slate-500 font-bold uppercase tracking-wider text-[10px]">Di-input Dari Menu:</span>
                    <span class="font-bold text-xs bg-amber-100 text-amber-900 px-2.5 py-1 rounded-lg border border-amber-300 flex items-center gap-1 shrink-0">
                        {{ $auditTrailInfo['source_menu'] }}
                    </span>
                </div>

                <!-- Financial Highlight Box -->
                <div class="p-4 rounded-xl {{ $selectedTransaction->type === 'masuk' ? 'bg-emerald-50 border border-emerald-200/80 text-emerald-900' : 'bg-rose-50 border border-rose-200/80 text-rose-900' }} flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider block opacity-75">Nominal Mutasi Kas</span>
                        <strong class="text-lg sm:text-xl font-mono font-extrabold">
                            {{ $selectedTransaction->type === 'masuk' ? '+' : '-' }} Rp {{ number_format($selectedTransaction->amount, 0, ',', '.') }}
                        </strong>
                    </div>
                    <div class="text-right">
                        <span class="status-{{ $selectedTransaction->type === 'masuk' ? 'tersedia' : 'ditolak' }} text-xs font-extrabold uppercase px-3 py-1">
                            {{ $selectedTransaction->type === 'masuk' ? 'Kas Masuk' : 'Kas Keluar' }}
                        </span>
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
                                <span class="text-[10px] text-slate-400 font-mono">{{ $auditTrailInfo['inputted_by']['time'] }}</span>
                            </div>
                            <p class="font-semibold text-blue-800">{{ $auditTrailInfo['inputted_by']['name'] }} <span class="text-slate-500 font-normal">({{ ucfirst($auditTrailInfo['inputted_by']['role']) }})</span></p>
                            <p class="text-[11px] text-slate-600 mt-0.5 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Waktu System: {{ $auditTrailInfo['created_at_full'] }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Step 2: Approver -->
                    <div class="flex items-start gap-3 relative pl-1">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                            2
                        </div>
                        <div class="space-y-0.5 flex-1">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 sm:gap-0">
                                <span class="font-bold text-slate-900">Diverifikasi & Tanggal Mutasi</span>
                                <span class="text-[10px] text-emerald-700 font-bold bg-emerald-100 px-2 py-0.5 rounded border border-emerald-200 self-start sm:self-auto">{{ $auditTrailInfo['approved_by']['status'] }}</span>
                            </div>
                            <p class="font-semibold text-emerald-800">{{ $auditTrailInfo['approved_by']['name'] }} <span class="text-slate-500 font-normal">({{ $auditTrailInfo['approved_by']['role'] }})</span></p>
                            <p class="text-[11px] text-slate-600 mt-0.5 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Tanggal Mutasi: {{ $auditTrailInfo['transaction_date_full'] }}</span>
                            </p>
                            <p class="text-[11px] text-slate-600 italic mt-0.5">"{{ $auditTrailInfo['approved_by']['notes'] }}"</p>
                        </div>
                    </div>
                </div>

                <!-- Scope & Object Detail -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 text-xs sm:text-sm space-y-2">
                    <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[10px]">Cakupan Objek Transaksi</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px] sm:text-xs">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Proyek Properti:</span>
                            <strong class="text-slate-800 font-semibold">{{ $selectedTransaction->project->name ?? 'Global' }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Kategori Mutasi:</span>
                            <strong class="text-slate-800 font-semibold capitalize">{{ str_replace('_', ' ', $selectedTransaction->category) }}</strong>
                        </div>
                    </div>

                    @if($auditTrailInfo['reference_detail'])
                        <div class="pt-2 border-t border-slate-200/80 text-[11px] sm:text-xs space-y-1">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Tipe Referensi:</span>
                                <span class="font-bold text-teal-700">{{ $auditTrailInfo['reference_detail']['type'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Nomor Referensi:</span>
                                <span class="font-mono font-bold text-slate-800">{{ $auditTrailInfo['reference_detail']['number'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Penerima / Klien:</span>
                                <span class="font-bold text-slate-800">{{ $auditTrailInfo['reference_detail']['recipient'] }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="pt-2 border-t border-slate-200/80 text-[11px] sm:text-xs">
                        <span class="text-slate-400 block text-[10px]">Keterangan Transaksi:</span>
                        <p class="text-slate-700 italic font-medium">{{ $selectedTransaction->description }}</p>
                    </div>

                    @if ($selectedTransaction->receipt_photo_url)
                        <div class="pt-2 border-t border-slate-200/80 text-[11px] sm:text-xs space-y-1">
                            <span class="text-slate-500 font-bold block text-[10px] uppercase tracking-wider">Foto Struk / Bukti Transfer:</span>
                            <div class="relative max-h-52 overflow-hidden rounded-xl border border-slate-200 bg-slate-900 flex items-center justify-center p-1 mt-1">
                                <img src="{{ $selectedTransaction->receipt_photo_url }}" alt="Foto Struk Resi Kas" class="max-h-48 w-auto max-w-full object-contain rounded-lg shadow-sm">
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end pt-3 border-t border-slate-100 shrink-0">
                <button type="button" wire:click="closeDetailModal" class="btn-secondary">Tutup Detail</button>
            </div>
        </div>
    </div>
@endif
