@props([
    'show' => false,
    'type' => 'auto',
    'url' => '',
    'title' => 'Pratinjau Dokumen & Media',
    'subtitle' => 'Pratinjau langsung di dalam aplikasi',
    'downloadUrl' => null,
    'fileName' => null,
    'closeAction' => 'closeViewerModal',
    'maxWidth' => 'max-w-4xl',
])

@php
    $effectiveUrl = (string) $url;
    $effectiveDownloadUrl = $downloadUrl ?: $effectiveUrl;
    
    // Auto-detect type if 'auto'
    $detectedType = $type;
    if ($detectedType === 'auto') {
        $urlLower = strtolower($effectiveUrl);
        if (str_contains($urlLower, '.pdf') || str_contains($urlLower, 'pdf') || str_contains($urlLower, 'stream') || str_contains($urlLower, 'export')) {
            $detectedType = 'pdf';
        } elseif (str_contains($urlLower, 'qr') || str_contains($urlLower, 'verify')) {
            $detectedType = 'qr';
        } else {
            $detectedType = 'image';
        }
    }

    $suggestedFileName = $fileName ?: basename(parse_url($effectiveUrl, PHP_URL_PATH) ?: 'dokumen');
    if (!str_contains($suggestedFileName, '.') && $detectedType === 'pdf') {
        $suggestedFileName .= '.pdf';
    }
@endphp

@if($show)
    <div 
        x-data="{
            zoom: 1,
            isLoading: true,
            detectedType: '{{ $detectedType }}',
            url: '{{ $effectiveUrl }}',
            zoomIn() { this.zoom = Math.min(this.zoom + 0.25, 3); },
            zoomOut() { this.zoom = Math.max(this.zoom - 0.25, 0.5); },
            resetZoom() { this.zoom = 1; },
            printMedia() {
                if (this.detectedType === 'pdf') {
                    let frame = this.$refs.pdfIframe;
                    if (frame && frame.contentWindow) {
                        frame.contentWindow.print();
                    } else {
                        window.open(this.url, '_blank');
                    }
                } else {
                    window.print();
                }
            }
        }"
        x-init="isLoading = true"
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-md p-2 sm:p-4 md:p-6 flex items-center justify-center min-h-screen animate-in fade-in duration-200"
        @keydown.escape.window="$wire.call('{{ $closeAction }}')"
        @click.self="$wire.call('{{ $closeAction }}')"
    >
        <div class="bg-white border border-slate-200/90 rounded-3xl {{ $maxWidth }} w-full max-h-[94vh] flex flex-col shadow-2xl overflow-hidden my-auto">
            
            <!-- Modal Header Toolbar -->
            <div class="px-4 sm:px-6 py-3.5 bg-slate-900 text-white flex items-center justify-between shrink-0 gap-3 border-b border-slate-800">
                <div class="flex items-center gap-3 min-w-0">
                    @if($detectedType === 'image')
                        <span class="p-2 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                    @elseif($detectedType === 'pdf')
                        <span class="p-2 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-500/30 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </span>
                    @else
                        <span class="p-2 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </span>
                    @endif

                    <div class="min-w-0">
                        <h3 class="font-extrabold text-sm sm:text-base tracking-tight text-white truncate max-w-xs sm:max-w-md md:max-w-lg">{{ $title }}</h3>
                        <p class="text-[10px] sm:text-[11px] text-slate-400 truncate">{{ $subtitle }}</p>
                    </div>
                </div>

                <!-- Action Controls in Header -->
                <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                    @if($detectedType === 'image')
                        <div class="hidden sm:flex items-center bg-slate-800 rounded-xl p-0.5 border border-slate-700">
                            <button type="button" @click="zoomOut()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-slate-700 transition" title="Perkecil Gambar (Zoom Out)">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/></svg>
                            </button>
                            <button type="button" @click="resetZoom()" class="px-2 py-1 text-slate-300 hover:text-white rounded-lg hover:bg-slate-700 transition text-[11px] font-mono" title="Reset Ukuran (100%)">
                                <span x-text="Math.round(zoom * 100) + '%'">100%</span>
                            </button>
                            <button type="button" @click="zoomIn()" class="p-1.5 text-slate-300 hover:text-white rounded-lg hover:bg-slate-700 transition" title="Perbesar Gambar (Zoom In)">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                            </button>
                        </div>
                    @endif

                    @if($effectiveDownloadUrl)
                        <a 
                            href="{{ $effectiveDownloadUrl }}" 
                            download="{{ $suggestedFileName }}"
                            class="px-2.5 sm:px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm"
                            title="Unduh File Langsung"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span class="hidden xs:inline">Unduh</span>
                        </a>
                    @endif

                    @if($effectiveUrl)
                        <a 
                            href="{{ $effectiveUrl }}" 
                            target="_blank" 
                            class="px-2.5 sm:px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-semibold hidden md:flex items-center gap-1 transition border border-slate-700"
                            title="Buka Dokumen di Tab Baru"
                        >
                            <span>Tab Baru</span>
                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @endif

                    <button 
                        type="button" 
                        wire:click="{{ $closeAction }}" 
                        class="p-1.5 sm:p-2 rounded-xl bg-slate-800 hover:bg-rose-900/40 hover:text-rose-400 text-slate-400 hover:border-rose-700/50 border border-slate-700 transition"
                        title="Tutup Pratinjau (ESC)"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content Body -->
            <div class="flex-1 bg-slate-950 p-2 sm:p-4 overflow-auto flex items-center justify-center min-h-[55vh] sm:min-h-[70vh] relative">
                
                <div 
                    x-show="isLoading" 
                    class="absolute inset-0 bg-slate-950/80 flex flex-col items-center justify-center gap-2.5 text-slate-400 z-10"
                >
                    <svg class="w-8 h-8 animate-spin text-emerald-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span class="text-xs font-semibold">Memuat berkas pratinjau...</span>
                </div>

                @if($detectedType === 'image')
                    <div class="w-full h-full flex items-center justify-center overflow-auto p-2">
                        <img 
                            src="{{ $effectiveUrl }}" 
                            alt="{{ $title }}" 
                            x-on:load="isLoading = false"
                            x-on:error="isLoading = false"
                            :style="`transform: scale(${zoom}); transition: transform 0.2s ease-out; transform-origin: center center;`"
                            class="max-h-[72vh] w-auto max-w-full object-contain rounded-2xl shadow-2xl border border-slate-800/80"
                        >
                    </div>
                @elseif($detectedType === 'pdf')
                    <iframe 
                        x-ref="pdfIframe"
                        src="{{ $effectiveUrl }}" 
                        x-on:load="isLoading = false"
                        class="w-full h-[72vh] sm:h-[76vh] rounded-2xl bg-white border-0 shadow-2xl"
                    ></iframe>
                @else
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-2xl flex flex-col items-center text-center space-y-4 max-w-md mx-auto" x-on:load="isLoading = false" x-init="isLoading = false">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <div class="space-y-1">
                            <h4 class="font-extrabold text-slate-900 text-base">QR Code Otentikasi Digital</h4>
                            <p class="text-xs text-slate-500">Scan QR Code menggunakan kamera HP untuk memeriksa keaslian dokumen secara publik.</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-2xl border border-slate-200 inline-block shadow-inner">
                            <img src="{{ $effectiveUrl }}" alt="QR Code Keabsahan" class="w-48 h-48 sm:w-56 sm:h-56 object-contain">
                        </div>
                        <div class="text-[11px] font-mono text-slate-400 bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200 truncate max-w-xs">
                            {{ $effectiveUrl }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Modal Footer Bar -->
            <div class="px-4 sm:px-6 py-3 bg-slate-900 text-slate-400 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-t border-slate-800 shrink-0">
                <div class="flex items-center gap-2 text-[11px]">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-mono text-slate-300">Format: {{ strtoupper($detectedType) }}</span>
                    <span>•</span>
                    <span class="truncate max-w-[200px] sm:max-w-xs">{{ $suggestedFileName }}</span>
                </div>

                <div class="flex items-center gap-2 justify-end">
                    @if($effectiveDownloadUrl)
                        <a 
                            href="{{ $effectiveDownloadUrl }}" 
                            download="{{ $suggestedFileName }}"
                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 transition"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh File</span>
                        </a>
                    @endif
                    <button 
                        type="button" 
                        wire:click="{{ $closeAction }}" 
                        class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 rounded-xl font-bold text-xs transition"
                    >
                        Tutup Pratinjau
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
