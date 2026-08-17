@props([
    'variant' => 'primary', // primary, emerald, rose, purple, blue, amber, secondary, outline, edit, delete, detail, payment, convert, assign, pdf, qr, unit
    'size' => 'md', // xs, sm, md, lg
    'type' => 'button',
    'href' => null,
    'icon' => null, // plus, trash/delete, check, pdf, filter, back, edit, eye/detail, money/payment, convert, assign, qr, unit
    'loadingTarget' => null,
    'full' => false,
])

@php
    // Auto-derive icon if specific action variant is given without explicit icon
    $computedIcon = $icon ?? match($variant) {
        'edit', 'action-edit' => 'edit',
        'delete', 'action-delete' => 'trash',
        'detail', 'action-detail' => 'eye',
        'pdf', 'action-pdf' => 'pdf',
        'qr', 'action-qr' => 'qr',
        'unit', 'action-unit' => 'unit',
        default => null,
    };

    // Size mapping (standard finger-friendly touch targets & crisp font)
    $sizeClasses = match($size) {
        'xs' => 'px-3 py-1.5 min-h-[32px] text-xs gap-1.5 rounded-xl font-bold',
        'sm' => 'px-3.5 py-2 min-h-[36px] text-xs gap-1.5 rounded-xl font-bold',
        'lg' => 'px-5 py-2.5 min-h-[44px] text-sm gap-2 rounded-2xl font-bold',
        default => 'px-4 py-2 min-h-[38px] text-xs gap-2 rounded-xl font-bold',
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
        
        // Soft Action Variants (Standardized for Table rows & Action toolbars)
        'edit', 'action-edit' => 'bg-amber-50 text-amber-800 hover:bg-amber-100 border border-amber-200 shadow-2xs',
        'delete', 'action-delete' => 'bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 shadow-2xs',
        'detail', 'action-detail' => 'bg-teal-50 text-teal-800 hover:bg-teal-100 border border-teal-200 shadow-2xs',
        'payment', 'action-payment' => 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 shadow-2xs',
        'convert', 'action-convert' => 'bg-purple-50 text-purple-800 hover:bg-purple-100 border border-purple-200 shadow-2xs',
        'assign', 'action-assign' => 'bg-blue-50 text-blue-800 hover:bg-blue-100 border border-blue-200 shadow-2xs',
        'pdf', 'action-pdf' => 'bg-sky-50 text-sky-800 hover:bg-sky-100 border border-sky-200 shadow-2xs',
        'qr', 'action-qr' => 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100 border border-emerald-200 shadow-2xs',
        'unit', 'action-unit' => 'bg-slate-100 text-slate-800 hover:bg-slate-200 border border-slate-200 shadow-2xs',
        
        default => 'bg-teal-600 hover:bg-teal-700 text-white shadow-xs',
    };

    $widthClass = $full ? 'w-full justify-center' : 'inline-flex';
    $baseClasses = "{$widthClass} items-center justify-center transition-all duration-150 active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none whitespace-nowrap shrink-0 leading-none {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        @if($loadingTarget)
            <svg wire:loading wire:target="{{ $loadingTarget }}" class="w-3.5 h-3.5 animate-spin -ml-0.5 shrink-0" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif

        @if($computedIcon)
            @if($computedIcon === 'plus')
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            @elseif($computedIcon === 'trash' || $computedIcon === 'delete')
                <svg class="w-3.5 h-3.5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            @elseif($computedIcon === 'edit')
                <svg class="w-3.5 h-3.5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            @elseif($computedIcon === 'eye' || $computedIcon === 'detail')
                <svg class="w-3.5 h-3.5 shrink-0 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            @elseif($computedIcon === 'check')
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            @elseif($computedIcon === 'pdf')
                <svg class="w-3.5 h-3.5 shrink-0 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            @elseif($computedIcon === 'filter')
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            @elseif($computedIcon === 'back')
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            @elseif($computedIcon === 'unit')
                <svg class="w-3.5 h-3.5 shrink-0 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            @elseif($computedIcon === 'qr')
                <svg class="w-3.5 h-3.5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            @endif
        @endif

        {{ $slot }}
    </a>
@else
    <button 
        type="{{ $type }}" 
        {{ $attributes->merge(['class' => $baseClasses]) }}
        @if($loadingTarget) wire:loading.attr="disabled" @endif
    >
        @if($loadingTarget)
            <svg wire:loading wire:target="{{ $loadingTarget }}" class="w-3.5 h-3.5 animate-spin -ml-0.5 shrink-0" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif

        @if($computedIcon)
            @if($computedIcon === 'plus')
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            @elseif($computedIcon === 'trash' || $computedIcon === 'delete')
                <svg class="w-3.5 h-3.5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            @elseif($computedIcon === 'edit')
                <svg class="w-3.5 h-3.5 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            @elseif($computedIcon === 'eye' || $computedIcon === 'detail')
                <svg class="w-3.5 h-3.5 shrink-0 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            @elseif($computedIcon === 'check')
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            @elseif($computedIcon === 'pdf')
                <svg class="w-3.5 h-3.5 shrink-0 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            @elseif($computedIcon === 'filter')
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            @elseif($computedIcon === 'back')
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            @elseif($computedIcon === 'unit')
                <svg class="w-3.5 h-3.5 shrink-0 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            @elseif($computedIcon === 'qr')
                <svg class="w-3.5 h-3.5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            @endif
        @endif

        {{ $slot }}
    </button>
@endif
