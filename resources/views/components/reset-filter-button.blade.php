@props([
    'label' => 'Reset Filter',
    'title' => 'Reset Semua Filter & Pencarian',
    'size' => 'xs', // xs, sm, md
    'icon' => 'refresh', // refresh, x, none
])

@php
    $sizeClasses = match($size) {
        'sm' => 'px-3.5 py-2 text-xs gap-2 rounded-xl min-h-[36px]',
        'md' => 'px-4 py-2.5 text-xs gap-2 rounded-2xl min-h-[40px]',
        default => 'px-3 py-1.5 text-xs gap-1.5 rounded-xl min-h-[32px]', // xs
    };
@endphp

<button 
    type="button" 
    title="{{ $title }}"
    {{ $attributes->merge([
        'class' => "group inline-flex items-center justify-center font-bold transition-all duration-150 active:scale-[0.97] bg-slate-100/90 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 border border-slate-200/90 text-slate-600 shadow-2xs shrink-0 cursor-pointer {$sizeClasses}"
    ]) }}
>
    @if($icon !== 'none' && $icon !== false)
        @if($icon === 'x' || $icon === 'close')
            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-rose-500 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        @else
            <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-rose-500 transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
        @endif
    @endif
    <span>{{ $slot->isEmpty() ? $label : $slot }}</span>
</button>
