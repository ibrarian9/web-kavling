@props([
    'variant' => 'primary', // primary (teal), emerald, rose, purple, blue, amber, secondary, outline
    'size' => 'md', // xs, sm, md, lg
    'type' => 'button',
    'icon' => null,
    'loadingTarget' => null,
    'full' => false,
])

@php
    // Size mapping
    $sizeClasses = match($size) {
        'xs' => 'px-2.5 py-1 text-[11px] gap-1 rounded-lg',
        'sm' => 'px-3 py-1.5 text-xs gap-1.5 rounded-xl',
        'lg' => 'px-5 py-2.5 text-sm gap-2 rounded-2xl',
        default => 'px-4 py-2 text-xs gap-1.5 rounded-xl',
    };

    // Variant mapping
    $variantClasses = match($variant) {
        'primary', 'teal' => 'bg-teal-600 hover:bg-teal-700 text-white shadow-xs focus:ring-2 focus:ring-teal-500/30',
        'emerald', 'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs focus:ring-2 focus:ring-emerald-500/30',
        'rose', 'danger', 'red' => 'bg-rose-600 hover:bg-rose-700 text-white shadow-xs focus:ring-2 focus:ring-rose-500/30',
        'purple' => 'bg-purple-600 hover:bg-purple-700 text-white shadow-xs focus:ring-2 focus:ring-purple-500/30',
        'blue' => 'bg-blue-600 hover:bg-blue-700 text-white shadow-xs focus:ring-2 focus:ring-blue-500/30',
        'amber' => 'bg-amber-600 hover:bg-amber-700 text-white shadow-xs focus:ring-2 focus:ring-amber-500/30',
        'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold border border-slate-200 focus:ring-2 focus:ring-slate-400/20',
        'outline' => 'bg-white hover:bg-slate-50 text-slate-700 font-semibold border border-slate-200 shadow-2xs focus:ring-2 focus:ring-slate-400/20',
        default => 'bg-teal-600 hover:bg-teal-700 text-white shadow-xs',
    };

    $widthClass = $full ? 'w-full justify-center' : 'inline-flex';
@endphp

<button 
    type="{{ $type }}" 
    {{ $attributes->merge(['class' => "{$widthClass} items-center font-bold transition-all duration-150 active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none whitespace-nowrap shrink-0 {$sizeClasses} {$variantClasses}"]) }}
    @if($loadingTarget) wire:loading.attr="disabled" @endif
>
    @if($loadingTarget)
        <svg wire:loading wire:target="{{ $loadingTarget }}" class="w-3.5 h-3.5 animate-spin -ml-0.5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif

    @if($icon)
        @if($icon === 'plus')
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        @elseif($icon === 'trash')
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        @elseif($icon === 'check')
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        @elseif($icon === 'pdf')
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        @elseif($icon === 'filter')
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
        @endif
    @endif

    <span>{{ $slot }}</span>
</button>
