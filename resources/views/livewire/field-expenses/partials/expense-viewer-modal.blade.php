<!-- Floating Jendela Melayang Viewer Modal (Image & PDF Struk/Resi) -->
@if ($showViewerModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-3xl w-full shadow-2xl overflow-hidden border border-slate-200">
            <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                    <h3 class="font-bold text-sm text-slate-100 tracking-tight">{{ $viewerTitle }}</h3>
                </div>
                <button wire:click="closeViewer" class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">✕</button>
            </div>
            <div class="p-6 bg-slate-50 flex items-center justify-center min-h-[400px] max-h-[75vh] overflow-y-auto">
                @if ($viewerType === 'image')
                    <img src="{{ $viewerUrl }}" alt="Struk Pembelian / Pembayaran" class="max-w-full max-h-[65vh] object-contain rounded-2xl shadow-lg border border-slate-200">
                @elseif ($viewerType === 'pdf')
                    <iframe src="{{ $viewerUrl }}" class="w-full h-[65vh] rounded-2xl border border-slate-200"></iframe>
                @endif
            </div>
            <div class="p-4 bg-white border-t border-slate-100 flex justify-end">
                <button wire:click="closeViewer" class="btn-secondary">Tutup Jendela</button>
            </div>
        </div>
    </div>
@endif
