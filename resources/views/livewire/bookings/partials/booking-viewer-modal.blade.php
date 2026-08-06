<!-- PDF Viewer Modal (Resi Booking PDF) -->
@if($showViewerModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white border border-slate-200 rounded-3xl max-w-4xl w-full p-6 shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    {{ $viewerTitle }}
                </h3>
                <button wire:click="closeViewerModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
            </div>
            <div class="flex-1 overflow-hidden min-h-[500px]">
                <iframe src="{{ $viewerUrl }}" class="w-full h-full rounded-2xl border border-slate-200 min-h-[500px]"></iframe>
            </div>
            <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                <a href="{{ $viewerUrl }}" target="_blank" class="btn-secondary text-xs px-4 py-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Buka di Tab Baru / Cetak Direct</span>
                </a>
                <button wire:click="closeViewerModal" class="btn-primary bg-slate-800 hover:bg-slate-900 text-xs px-5 py-2">Tutup Pratinjau</button>
            </div>
        </div>
    </div>
@endif

<!-- Foto Struk Resi Viewer Modal -->
@if($showImageModal ?? false)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white border border-slate-200 rounded-3xl max-w-2xl w-full p-6 shadow-2xl space-y-4 max-h-[92vh] flex flex-col">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-extrabold text-slate-900 text-base flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $imageModalTitle }}
                </h3>
                <button wire:click="closeImageModal" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">✕</button>
            </div>
            <div class="flex-1 overflow-auto flex items-center justify-center p-2 bg-slate-50 rounded-2xl border border-slate-200 min-h-[300px]">
                <img src="{{ $imageModalUrl }}" alt="Foto Struk Resi" class="max-h-[600px] w-auto max-w-full object-contain rounded-xl shadow-sm">
            </div>
            <div class="flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                <a href="{{ $imageModalUrl }}" target="_blank" class="btn-secondary text-xs px-4 py-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Buka Gambar Asli</span>
                </a>
                <button wire:click="closeImageModal" class="btn-primary bg-slate-800 hover:bg-slate-900 text-xs px-5 py-2">Tutup</button>
            </div>
        </div>
    </div>
@endif
