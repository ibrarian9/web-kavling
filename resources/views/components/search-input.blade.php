@props([
    'model' => 'search',
    'placeholder' => 'Cari data...',
    'containerClass' => 'w-full sm:w-80',
    'inputClass' => 'w-full bg-slate-50/80 hover:bg-white focus:bg-white text-xs text-slate-800 placeholder-slate-400 font-medium rounded-2xl border border-slate-200 hover:border-slate-300 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none pl-9 pr-8 py-2.5 transition shadow-2xs',
])

@php
    $propName = is_string($model) && !str_contains($model, 'wire:') ? $model : 'search';
    $wireAttribute = str_contains($model, 'wire:') ? $model : "wire:model.live.debounce.300ms=\"{$model}\"";
@endphp

<div {{ $attributes->merge(['class' => "relative group {$containerClass}"]) }}>
    <input 
        type="text" 
        {!! $wireAttribute !!}
        placeholder="{{ $placeholder }}" 
        class="{{ $inputClass }}"
    >
    <svg class="w-4 h-4 text-slate-400 group-focus-within:text-teal-600 absolute left-3 top-1/2 -translate-y-1/2 transition-colors pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
    </svg>
    @if(isset($this) && !empty($this->{$propName}))
        <button 
            type="button" 
            wire:click="$set('{{ $propName }}', '')"
            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 p-1 rounded-full transition flex items-center justify-center"
            title="Bersihkan pencarian"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    @endif
</div>
