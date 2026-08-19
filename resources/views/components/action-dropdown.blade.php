@props([
    'align' => 'right', // right, left
    'dropup' => false,
    'width' => 'w-52',
    'title' => 'Menu Tindakan',
    'label' => null,
    'variant' => 'outline', // outline, secondary, primary, amber, emerald, etc.
    'size' => 'sm', // xs, sm, md
    'icon' => 'dots', // dots, gear, chevron, none
])

@php
    $sizeClasses = match ($size) {
        'xs' => $label ? 'px-3 py-1.5 min-h-[32px] text-xs font-bold rounded-xl gap-1.5' : 'p-1.5 min-h-[32px] min-w-[32px] text-xs rounded-xl justify-center',
        'sm' => $label ? 'px-3.5 py-2 min-h-[36px] text-xs font-bold rounded-xl gap-2' : 'p-2 min-h-[36px] min-w-[36px] text-xs rounded-xl justify-center',
        'md' => 'px-4 py-2.5 min-h-[40px] min-w-[40px] text-xs font-bold rounded-2xl gap-2',
        default => $label ? 'px-3.5 py-2 min-h-[36px] text-xs font-bold rounded-xl gap-2' : 'p-2 min-h-[36px] min-w-[36px] text-xs rounded-xl justify-center',
    };

    $variantClasses = match ($variant) {
        'primary', 'teal' => 'bg-teal-600 hover:bg-teal-700 text-white shadow-xs focus:ring-2 focus:ring-teal-500/20',
        'emerald' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs focus:ring-2 focus:ring-emerald-500/20',
        'amber' => 'bg-amber-500 hover:bg-amber-600 text-white shadow-xs focus:ring-2 focus:ring-amber-500/20',
        'amber-soft' => 'bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200/80 shadow-2xs',
        'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200/80',
        default => 'bg-white hover:bg-slate-50 text-slate-700 border border-slate-200/90 shadow-2xs',
    };
@endphp

<div x-data="{ 
         open: false, 
         isDropup: {{ $dropup ? 'true' : 'false' }},
         toggle() {
             this.open = !this.open;
             if (this.open && !{{ $dropup ? 'true' : 'false' }}) {
                 this.$nextTick(() => {
                     let rect = this.$el.getBoundingClientRect();
                     let menuHeight = 160;
                     let spaceBelow = window.innerHeight - rect.bottom;
                     this.isDropup = spaceBelow < menuHeight && rect.top > menuHeight;
                 });
             }
         }
     }" 
     @click.outside="open = false" 
     @keydown.escape.window="open = false" 
     class="relative inline-block text-left shrink-0">
    <button @click="toggle()" 
            type="button" 
            title="{{ $title }}"
            {{ $attributes->merge(['class' => "inline-flex items-center transition-all duration-150 active:scale-[0.97] focus:outline-none whitespace-nowrap shrink-0 {$sizeClasses} {$variantClasses}"]) }}>
        
        @if($icon === 'gear')
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        @elseif($icon === 'chevron')
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        @elseif($icon === 'dots')
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
        @endif

        @if($label)
            <span class="whitespace-nowrap">{{ $label }}</span>
            <svg class="w-3.5 h-3.5 transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        @endif
    </button>

    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute {{ $align === 'left' ? 'left-0' : 'right-0' }} {{ $width }} rounded-2xl bg-white shadow-xl border border-slate-200/90 py-1.5 z-50 divide-y divide-slate-100 text-xs font-semibold"
         :class="isDropup ? 'bottom-full mb-1.5 origin-bottom-right' : 'top-full mt-1.5 origin-top-right'"
         style="display: none;">
        {{ $slot }}
    </div>
</div>
