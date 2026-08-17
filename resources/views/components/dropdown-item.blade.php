@props([
    'type' => 'button',
    'href' => null,
    'icon' => null, // edit, delete/trash, detail/eye, plus, convert, pdf, toggle, check
    'variant' => 'default', // default, danger / rose / red, warning / amber, success / emerald, info / teal, purple
])

@php
    $colorClasses = match($variant) {
        'danger', 'rose', 'red' => 'text-rose-600 hover:bg-rose-50',
        'warning', 'amber' => 'text-amber-700 hover:bg-amber-50',
        'success', 'emerald' => 'text-emerald-700 hover:bg-emerald-50',
        'info', 'teal', 'blue' => 'text-teal-700 hover:bg-teal-50',
        'purple' => 'text-purple-700 hover:bg-purple-50',
        default => 'text-slate-700 hover:bg-slate-50',
    };

    $iconColorClasses = match($icon) {
        'delete', 'trash' => 'text-rose-600',
        'edit' => 'text-amber-600',
        'detail', 'eye' => 'text-teal-600',
        'check', 'success', 'payment' => 'text-emerald-600',
        'convert' => 'text-purple-600',
        'pdf' => 'text-sky-600',
        'toggle' => match($variant) {
            'success', 'emerald' => 'text-emerald-600',
            'warning', 'amber' => 'text-amber-600',
            'danger', 'rose' => 'text-rose-600',
            default => 'text-amber-600',
        },
        default => 'text-slate-500',
    };

    $baseClasses = "w-full text-left px-4 py-2.5 flex items-center gap-2.5 text-xs font-semibold transition cursor-pointer {$colorClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        @if($icon)
            @if($icon === 'edit')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            @elseif($icon === 'delete' || $icon === 'trash')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            @elseif($icon === 'detail' || $icon === 'eye')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            @elseif($icon === 'plus')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            @elseif($icon === 'convert')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            @elseif($icon === 'pdf')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            @elseif($icon === 'toggle')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            @elseif($icon === 'check')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            @endif
        @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        @if($icon)
            @if($icon === 'edit')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            @elseif($icon === 'delete' || $icon === 'trash')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            @elseif($icon === 'detail' || $icon === 'eye')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            @elseif($icon === 'plus')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            @elseif($icon === 'convert')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            @elseif($icon === 'pdf')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            @elseif($icon === 'toggle')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            @elseif($icon === 'check')
                <svg class="w-4 h-4 shrink-0 {{ $iconColorClasses }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            @endif
        @endif
        <span>{{ $slot }}</span>
    </button>
@endif
