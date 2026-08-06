@props([
    'target' => 'search, project_filter, category_filter, gotoPage, nextPage, previousPage',
    'text' => 'Memuat & Menyaring Data Tabel...',
    'subtext' => 'Mohon tunggu sebentar, sistem sedang memproses data.'
])

<div wire:loading {{ $attributes->merge(['wire:target' => $target]) }} class="absolute inset-0 z-20 flex items-center justify-center bg-white/80 backdrop-blur-[2px] transition-all duration-300">
    <div class="px-6 py-4 rounded-xl bg-white border border-slate-200/90 text-slate-900 flex flex-col items-center justify-center gap-2.5">
        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-200/80 shadow-xs">
            <svg class="w-5 h-5 animate-spin text-emerald-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <div class="text-center space-y-0.5">
            <p class="font-extrabold text-slate-900 text-xs sm:text-sm tracking-tight">{{ $text }}</p>
            <p class="text-[11px] text-slate-500 font-medium">{{ $subtext }}</p>
        </div>
    </div>
</div>
