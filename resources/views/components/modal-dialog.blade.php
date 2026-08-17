@props([
    'show' => false,
    'title' => '',
    'subTitle' => null,
    'maxWidth' => 'max-w-lg',
    'icon' => null,
    'iconBg' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    'closeAction' => null,
])

@php
    $wireProperty = is_string($show) ? $show : null;
    $isVisible = is_bool($show) ? $show : ($wireProperty ? (bool) ($this->{$wireProperty} ?? false) : (bool)$show);
    $closeClick = $closeAction ? "{$closeAction}()" : ($wireProperty ? "\$set('{$wireProperty}', false)" : null);
@endphp

@if($isVisible)
    <div 
        class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4 md:p-6"
    >
        <div class="bg-white border border-slate-200 rounded-2xl sm:rounded-3xl {{ $maxWidth }} w-full p-4 sm:p-6 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto custom-scrollbar">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
                <div class="flex items-center gap-2.5">
                    @if($icon)
                        <div class="p-2 rounded-xl {{ $iconBg }} border shrink-0">
                            {{ $icon }}
                        </div>
                    @endif
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">{{ $title }}</h3>
                        @if($subTitle)
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $subTitle }}</p>
                        @endif
                    </div>
                </div>
                @if($closeClick)
                    <button 
                        type="button" 
                        wire:click="{{ $closeClick }}" 
                        class="text-slate-400 hover:text-slate-600 font-bold p-1 rounded-lg hover:bg-slate-100 transition"
                        title="Tutup Modal"
                    >
                        ✕
                    </button>
                @endif
            </div>

            <!-- Modal Content / Body -->
            {{ $slot }}
        </div>
    </div>
@endif

