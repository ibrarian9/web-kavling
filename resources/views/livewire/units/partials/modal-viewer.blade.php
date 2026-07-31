<!-- Modal Jendela Melayang (Viewer Modal: Foto Struk / PDF Resi / QR Verifikasi) -->
@if($showViewerModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-md">
        <div class="bg-white rounded-3xl max-w-4xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden border border-slate-200">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    @if($viewerType === 'image')
                        <span class="p-2 rounded-xl bg-amber-500/20 text-amber-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                    @elseif($viewerType === 'pdf')
                        <span class="p-2 rounded-xl bg-sky-500/20 text-sky-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </span>
                    @else
                        <span class="p-2 rounded-xl bg-emerald-500/20 text-emerald-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </span>
                    @endif
                    <div>
                        <h3 class="font-bold text-base tracking-tight text-white">{{ $viewerTitle }}</h3>
                        <p class="text-[11px] text-slate-400">Pratinjau langsung di dalam aplikasi</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ $viewerUrl }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold flex items-center gap-1 transition">
                        <span>Buka Tab Baru ↗</span>
                    </a>
                    <button wire:click="closeViewerModal" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white transition">
                        ✕
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 bg-slate-950 p-4 overflow-auto flex items-center justify-center min-h-[60vh]">
                @if($viewerType === 'image')
                    <img src="{{ $viewerUrl }}" class="max-h-[75vh] w-auto max-w-full object-contain rounded-2xl shadow-2xl border border-slate-800" alt="Foto Struk / Resi">
                @elseif($viewerType === 'pdf')
                    <iframe src="{{ $viewerUrl }}" class="w-full h-[75vh] rounded-2xl bg-white border-0 shadow-lg"></iframe>
                @elseif($viewerType === 'qr')
                    <iframe src="{{ $viewerUrl }}" class="w-full h-[75vh] rounded-2xl bg-white border-0 shadow-lg"></iframe>
                @endif
            </div>
        </div>
    </div>
@endif
